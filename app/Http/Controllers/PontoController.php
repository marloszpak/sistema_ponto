<?php

namespace App\Http\Controllers;

use App\Models\Batida;
use App\Models\Funcionario;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PontoController extends Controller
{
    public function telaBaterPonto()
    {
        $funcionarios = Funcionario::orderBy('nome')->get();
        $ultimas = Batida::with('funcionario')->orderBy('registrado_em', 'desc')->limit(10)->get();

        return view('ponto.bater', compact('funcionarios','ultimas'));
    }

    public function registrar(Request $req)
    {
        $dados = $req->validate([
            'funcionario_id' => 'required|exists:funcionarios,id',
            'tipo' => 'required|in:entrada,saida',
            'observacao' => 'nullable|string|max:255',
        ]);

        Batida::create([
            'funcionario_id' => $dados['funcionario_id'],
            'tipo' => $dados['tipo'],
            'registrado_em' => Carbon::now(),
            'observacao' => $dados['observacao'] ?? null,
        ]);

        return back()->with('sucesso','Ponto Registrado.');
    }

    public function relatorio(Request $req)
    {
        $funcionarios = Funcionario::orderBy('nome')->get();
        $funcionarioId = $req->input('funcionario_id');

        if (!$funcionarioId) {
            $periodo_de = $req->input('periodo_de', now()->format('Y-m-d'));
            $periodo_ate = $req->input('periodo_ate', $periodo_de);

            return view('ponto.relatorio', [
                'dias' => [],
                'totalSegundosPeriodo' => 0,
                'periodo_de' => $periodo_de,
                'periodo_ate' => $periodo_ate,
                'funcionarios' => $funcionarios,
                'funcionarioId' => null,
                'funcionarioSelecionado' => null,
            ])->with('info', 'Selecione um funcionário para gerar o relatório.');
        }

        $data = $this->buildReportData($req);
        $data['funcionarios'] = $funcionarios;
        return view('ponto.relatorio', $data);
    }

    public function exportarCsv(Request $req)
    {
        $data = $this->buildReportData($req);

        $filename = 'relatorio_ponto_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($data) {
            echo "\xEF\xBB\xBF";

            $out = fopen('php://output', 'w');

            fputcsv($out, ['Funcionário', 'Dia','Batidas (hh:mm)','Horas no dia (HH:MM:SS)'], ';');

            $funcionarioNome = 'Todos';
            if (!empty($data['funcionarioSelecionado']) && isset($data['funcionarioSelecionado']->nome)) {
                $funcionarioNome = $data['funcionarioSelecionado']->nome;
            } elseif (!empty($data['funcionarioId'])) {
                $f = \App\Models\Funcionario::find($data['funcionarioId']);
                if ($f) $funcionarioNome = $f->nome;
            }

            foreach ($data['dias'] as $dia => $info) {
                $row = [
                    $funcionarioNome,
                    \Carbon\Carbon::parse($dia)->format('d/m/Y'),
                    $info['batidas_texto'],
                    gmdate('H:i:s', $info['segundos']),
                ];
                fputcsv($out, $row, ';');
            }

            fputcsv($out, [], ';');
            fputcsv($out, ['Total no período', '', '', gmdate('H:i:s', $data['totalSegundosPeriodo'])], ';');

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportarPdf(Request $req)
    {
        $data = $this->buildReportData($req);

        $filename = 'relatorio_ponto_' . now()->format('Ymd_His') . '.pdf';

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class) || class_exists(\Barryvdh\DomPDF\PDF::class)) {
            if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ponto.relatorio_pdf', $data);
                return $pdf->download($filename);
            }

            if (class_exists(\Barryvdh\DomPDF\PDF::class)) {
                $pdf = Pdf::loadView('ponto.relatorio_pdf', $data);

                return $pdf->download($filename);
            }
        }

        return redirect()->back()->with('erro', 'Para exportar em PDF instale o pacote: composer require barryvdh/laravel-dompdf');
    }

    private function buildReportData(Request $req): array
    {
        $funcionarioId = $req->input('funcionario_id');
        $periodo_de = $req->input('periodo_de');
        $periodo_ate = $req->input('periodo_ate');

        if (!$periodo_de && !$periodo_ate) {
            $periodo_de = $periodo_ate = now()->format('Y-m-d');
        }
        if ($periodo_de && !$periodo_ate) $periodo_ate = $periodo_de;
        if (!$periodo_de && $periodo_ate) $periodo_de = $periodo_ate;

        $query = Batida::with('funcionario')
            ->whereDate('registrado_em', '>=', $periodo_de)
            ->whereDate('registrado_em', '<=', $periodo_ate);

        if ($funcionarioId) $query->where('funcionario_id', $funcionarioId);

        $batidas = $query->orderBy('registrado_em')->get();

        $dias = [];
        $totalSegundosPeriodo = 0;

        $agrupadas = $batidas->groupBy(function($b) {
            return $b->registrado_em->format('Y-m-d');
        });

        foreach ($agrupadas as $dia => $itens) {
            $itens = $itens->sortBy('registrado_em')->values();

            $listaBatidas = $itens->map(function($b) {
                return $b->registrado_em->format('H:i') . ' ' . ($b->tipo === 'entrada' ? 'E' : 'S');
            })->implode(', ');

            $segundosDia = 0;
            for ($i=0; $i < $itens->count(); $i++) {
                if ($itens[$i]->tipo === 'entrada' && isset($itens[$i+1]) && $itens[$i+1]->tipo === 'saida') {
                    $segundosDia += $itens[$i]->registrado_em->diffInSeconds($itens[$i+1]->registrado_em);
                    $i++;
                }
            }

            $dias[$dia] = [
                'batidas_texto' => $listaBatidas,
                'segundos' => $segundosDia,
            ];

            $totalSegundosPeriodo += $segundosDia;
        }

        $funcionarios = Funcionario::orderBy('nome')->get();

        $funcionarioSelecionado = null;
        if ($funcionarioId) {
            $funcionarioSelecionado = Funcionario::find($funcionarioId);
        }

        return compact(
            'dias',
            'totalSegundosPeriodo',
            'periodo_de',
            'periodo_ate',
            'funcionarios',
            'funcionarioId',
            'funcionarioSelecionado'
        );
    }
}
