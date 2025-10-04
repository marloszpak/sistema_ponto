<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Relatório de Ponto</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    th, td { border: 1px solid #ccc; padding: 6px; text-align: left; vertical-align: top; }
    th { background: #f5f5f5; }
    .right { text-align: right; }
  </style>
</head>
<body>
  <h3>Relatório de Ponto</h3>
  <p>Período: {{ \Carbon\Carbon::parse($periodo_de)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($periodo_ate)->format('d/m/Y') }}</p>

  @if(!empty($funcionarioSelecionado))
    <p>Funcionário: {{ $funcionarioSelecionado->nome }}</p>
  @elseif(!empty($funcionarioId))
    <p>Funcionário: ID {{ $funcionarioId }}</p>
  @endif

  <table>
    <thead>
      <tr>
        <th>Dia</th>
        <th>Batidas (hh:mm)</th>
        <th>Horas no dia</th>
      </tr>
    </thead>
    <tbody>
      @forelse($dias as $dia => $info)
        <tr>
          <td>{{ \Carbon\Carbon::parse($dia)->format('d/m/Y') }}</td>
          <td>{{ $info['batidas_texto'] }}</td>
          <td class="right">{{ gmdate('H:i:s', $info['segundos']) }}</td>
        </tr>
      @empty
        <tr><td colspan="3">Nenhum registro no período.</td></tr>
      @endforelse
      <tr>
        <td><strong>Total no período</strong></td>
        <td></td>
        <td class="right"><strong>{{ gmdate('H:i:s', $totalSegundosPeriodo) }}</strong></td>
      </tr>
    </tbody>
  </table>

  <small>Gerado em: {{ now()->format('d/m/Y H:i') }}</small>
</body>
</html>
