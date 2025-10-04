<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use Illuminate\Http\Request;

class FuncionarioController extends Controller
{
    public function listar(Request $request)
    {
        $q = $request->query('q');

        $query = Funcionario::orderBy('nome');

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('nome', 'like', "%{$q}%")
                    ->orWhere('cpf', 'like', "%{$q}%");
            });
        }

        $funcionarios = $query->get();

        return view('funcionarios.index', compact('funcionarios','q'));
    }

    public function salvar(Request $req)
    {
        $dados = $req->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|size:11|unique:funcionarios,cpf',
            'cargo' => 'nullable|string|max:255',
        ]);

        Funcionario::create($dados);
        return redirect()->back()->with('sucesso','Funcionário criado com sucesso.');
    }

    public function editar(Funcionario $funcionario)
    {
        return view('funcionarios.editar', compact('funcionario'));
    }

    public function atualizar(Request $req, Funcionario $funcionario)
    {
        $dados = $req->validate([
            'nome' => 'required|string|max:255',
            'cpf' => "required|string|size:11|unique:funcionarios,cpf,{$funcionario->id}",
            'cargo' => 'nullable|string|max:255',
        ]);

        $funcionario->update($dados);
        return redirect()->back()->with('sucesso','Funcionário atualizado.');
    }

    public function excluir(Funcionario $funcionario)
    {
        $funcionario->delete();
        return redirect()->back()->with('sucesso','Funcionário removido.');
    }

    public function mostrar(Funcionario $funcionario)
    {
        return response()->json($funcionario->only(['id','nome','cpf','cargo']));
    }

    public function verificarCpf(Request $request)
    {
        $cpf = $request->query('cpf');
        $id = $request->query('id');

        if (!$cpf) {
            return response()->json(['unique' => false, 'mensagem' => 'CPF não informado'], 400);
        }

        $query = Funcionario::where('cpf', $cpf);
        if ($id) {
            $query->where('id', '<>', $id);
        }

        $existe = $query->exists();

        return response()->json(['unique' => !$existe]);
    }
}
