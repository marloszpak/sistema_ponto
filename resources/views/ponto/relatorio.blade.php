@extends('layouts.app')
@section('content')
<div class="container mt-4">

  @foreach (['sucesso' => 'success', 'erro' => 'danger', 'info' => 'info'] as $key => $alert)
    @if(session($key))
      <div class="alert alert-{{ $alert }} alert-dismissible fade show" role="alert">
        {{ session($key) }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
      </div>
    @endif
  @endforeach

  <div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white"><i class="bi bi-funnel me-2"></i><strong>Filtrar Relatório</strong></div>
    <div class="card-body">
      <form method="GET" action="{{ route('relatorios.batidas') }}" class="row g-3 align-items-end">
        
        <div class="col-md-4">
          <label class="form-label fw-semibold">Funcionário</label>
          <select name="funcionario_id" class="form-select" required>
            <option value="" disabled {{ empty($funcionarioId) ? 'selected' : '' }}>Selecione o funcionário</option>
            @foreach($funcionarios as $f)
              <option value="{{ $f->id }}" {{ (isset($funcionarioId) && $funcionarioId == $f->id) ? 'selected' : '' }}>
                {{ $f->nome }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Período</label>
          <div class="d-flex gap-2">
            <input type="date" name="periodo_de" value="{{ $periodo_de ?? now()->format('Y-m-d') }}" class="form-control">
            <input type="date" name="periodo_ate" value="{{ $periodo_ate ?? ($periodo_de ?? now()->format('Y-m-d')) }}" class="form-control">
          </div>
        </div>

        <div class="col-md-4 d-flex flex-wrap gap-2">
          <button class="btn btn-primary w-auto">
            <i class="bi bi-search me-1"></i> Gerar
          </button>

          @php
            $query = http_build_query(request()->query());
            $csvUrl = route('relatorios.batidas.exportar.csv') . ($query ? ('?' . $query) : '');
            $pdfUrl = route('relatorios.batidas.exportar.pdf') . ($query ? ('?' . $query) : '');
          @endphp

          <a href="{{ !empty($funcionarioId) ? $csvUrl : '#' }}" class="btn btn-outline-secondary w-auto {{ empty($funcionarioId) ? 'disabled' : '' }}">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i> CSV
          </a>
          <a href="{{ !empty($funcionarioId) ? $pdfUrl : '#' }}" class="btn btn-outline-secondary w-auto {{ empty($funcionarioId) ? 'disabled' : '' }}">
            <i class="bi bi-file-earmark-pdf me-1"></i> PDF
          </a>
        </div>
      </form>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-secondary text-white">
      <strong>Resultados</strong>
      @if(!empty($funcionarioSelecionado))
        <small class="text-light">Funcionário: {{ $funcionarioSelecionado->nome }}</small>
      @endif
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped mb-0 align-middle">
          <thead class="table-light">
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
                <td style="max-width: 600px;">{{ $info['batidas_texto'] }}</td>
                <td>{{ gmdate('H:i', $info['segundos']) }} ({{ gmdate('H:i:s', $info['segundos']) }})</td>
              </tr>
            @empty
              <tr><td colspan="3" class="text-center text-muted py-3">Nenhum registro no período.</td></tr>
            @endforelse
            <tr class="table-secondary">
              <td><strong>Total no período</strong></td>
              <td></td>
              <td><strong>{{ gmdate('H:i', $totalSegundosPeriodo ?? 0) }} ({{ gmdate('H:i:s', $totalSegundosPeriodo ?? 0) }})</strong></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
