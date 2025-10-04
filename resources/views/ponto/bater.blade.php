@extends('layouts.app')
@section('content')
<div class="container mt-4">

  @if(session('sucesso'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i>{{ session('sucesso') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
      <i class="bi bi-exclamation-triangle-fill me-2"></i> Ocorreram alguns erros:
      <ul class="mb-0 mt-1 ps-3">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
  @endif

  <div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-primary text-white fw-semibold d-flex align-items-center">
      <i class="bi bi-fingerprint me-2"></i> Bater o Ponto
    </div>
    <div class="card-body">
      <form id="formPonto" method="POST" action="{{ route('ponto.registrar') }}">
        @csrf
        <input type="hidden" name="tipo" id="form_tipo" value="">

        <div class="row g-3 align-items-end">
          <div class="col-md-5">
            <label class="form-label fw-semibold">Funcionário</label>
            <select name="funcionario_id" class="form-select form-select-lg shadow-sm" required>
              <option value="">Selecione...</option>
              @foreach($funcionarios as $f)
                <option value="{{ $f->id }}">{{ $f->nome }} ({{ $f->cpf }})</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-5">
            <label class="form-label fw-semibold">Observação <span class="text-muted">(opcional)</span></label>
            <input type="text" name="observacao" class="form-control form-control-lg shadow-sm"
                   maxlength="255" placeholder="Ex: saída para atendimento, almoço...">
          </div>

          <div class="col-md-2 text-center">
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-success btn-lg btn-registrar shadow-sm" data-tipo="entrada">
                <i class="bi bi-box-arrow-in-right me-1"></i> <span class="btn-text">Entrada</span>
              </button>
              <button type="submit" class="btn btn-warning btn-lg mt-2 btn-registrar shadow-sm text-white"
                      data-tipo="saida" style="background-color:#ffc107;border:none;">
                <i class="bi bi-box-arrow-right me-1"></i> <span class="btn-text">Saída</span>
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-header bg-light fw-semibold d-flex align-items-center">
      <i class="bi bi-clock-history me-2 text-primary"></i> Últimas Marcações
    </div>
    <div class="card-body">
      @if($ultimas->isEmpty())
        <p class="text-muted mb-0">Sem marcações registradas ainda.</p>
      @else
        <div class="table-responsive">
          <table class="table table-striped align-middle mb-0">
            <thead class="table-primary">
              <tr>
                <th>Horário</th>
                <th>Tipo</th>
                <th>Funcionário</th>
                <th>Observação</th>
              </tr>
            </thead>
            <tbody>
              @foreach($ultimas as $u)
                <tr>
                  <td class="fw-semibold">{{ $u->registrado_em->format('H:i') }}</td>
                  <td>
                    <span class="badge {{ $u->tipo === 'entrada' ? 'bg-success' : 'bg-warning text-dark' }}">
                      {{ ucfirst($u->tipo) }}
                    </span>
                  </td>
                  <td>{{ $u->funcionario->nome }}</td>
                  <td>{{ $u->observacao ?? '-' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(function(){
  $('select[name=funcionario_id]').focus();
  let btnClicado = null;
  const $form = $('#formPonto');
  const $hiddenTipo = $('#form_tipo');

  $(document).on('click', '.btn-registrar', function(){
    const tipo = $(this).data('tipo');
    $('#form_tipo').val(tipo);
    btnClicado = $(this);
  });

  $form.on('submit', function(e){
    if (!$hiddenTipo.val()) $hiddenTipo.val('entrada');

    if ($form.data('enviando')) {
      e.preventDefault();
      return false;
    }

    $form.data('enviando', true);
    const $botoes = $form.find('button[type=submit]');
    $botoes.prop('disabled', true);

    if (!btnClicado) btnClicado = $botoes.first();
    const $texto = btnClicado.find('.btn-text');
    $texto.data('original-text', $texto.text());
    $texto.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Registrando...');

    return true;
  });
});
</script>
@endpush
