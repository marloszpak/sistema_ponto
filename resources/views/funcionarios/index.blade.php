@extends('layouts.app')

@section('content')
<div class="container mt-5">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0 text-primary">
      <i class="bi bi-people-fill me-2"></i>Funcionários
    </h2>
    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalFuncionario">
      <i class="bi bi-plus-circle me-1"></i> Novo Funcionário
    </button>
  </div>

  <div class="card shadow-sm mb-4 border-0">
    <div class="card-body">
      <form method="GET" action="{{ route('funcionarios.listar') }}" class="row gy-2 gx-3 align-items-center">
        <div class="col-sm-6 col-md-8">
          <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Buscar por nome ou CPF...">
        </div>
        <div class="col-sm-6 col-md-4 text-end">
          <button class="btn btn-outline-primary w-100">
            <i class="bi bi-search me-1"></i> Buscar
          </button>
        </div>
      </form>
    </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width: 50px;">#</th>
              <th>Nome</th>
              <th>CPF</th>
              <th>Cargo/Função</th>
              <th class="text-center" style="width: 200px;">Ações</th>
            </tr>
          </thead>
          <tbody>
            @forelse($funcionarios as $i => $f)
            <tr>
              <td>{{ $i + 1 }}</td>
              <td>{{ $f->nome }}</td>
              <td>{{ $f->cpf }}</td>
              <td>{{ $f->cargo }}</td>
              <td class="text-center">
                <div class="btn-group" role="group">
                  <button class="btn btn-sm btn-outline-secondary btn-editar" data-id="{{ $f->id }}">
                    <i class="bi bi-pencil-square"></i> Editar
                  </button>
                  <form method="POST" action="{{ route('funcionarios.excluir', $f) }}" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir funcionário?')">
                      <i class="bi bi-trash"></i> Excluir
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="text-center py-4 text-muted">
                <i class="bi bi-exclamation-circle me-1"></i> Nenhum funcionário encontrado.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@include('funcionarios._modal')

@push('scripts')
<script>
$(function(){
  $('.btn-editar').click(function(){
    const id = $(this).data('id');
    $.get(`/funcionarios/${id}/json`, function(data){
      $('#formFuncionario').attr('action', `/funcionarios/${id}`);
      $('#metodo_form').val('PUT');
      $('#form_funcionario_id').val(data.id);
      $('#formFuncionario [name=nome]').val(data.nome);
      $('#formFuncionario [name=cpf]').val(data.cpf);
      $('#formFuncionario [name=cargo]').val(data.cargo);
      $('#formErrors').addClass('d-none');
      $('#formErrorsList').empty();
      new bootstrap.Modal(document.getElementById('modalFuncionario')).show();
    }).fail(function(){
      alert('Erro ao buscar dados do funcionário.');
    });
  });

  $('#modalFuncionario').on('hidden.bs.modal', function(){
    $('#formFuncionario').attr('action', '{{ route("funcionarios.salvar") }}');
    $('#metodo_form').val('POST');
    $('#form_funcionario_id').val('');
    $('#formFuncionario')[0].reset();
    $('#formErrors').addClass('d-none');
    $('#formErrorsList').empty();
  });

  $('#formFuncionario').on('submit', function(e){
    e.preventDefault();

    const $form = $(this);
    const nome = $.trim($('#form_nome').val() || '');
    const cpf = $.trim($('#form_cpf').val() || '');
    const cargo = $.trim($('#form_cargo').val() || '');
    const id = $('#form_funcionario_id').val() || '';
    const erros = [];

    if (!nome) erros.push('O campo Nome é obrigatório.');
    else if (nome.length < 2) erros.push('O Nome deve ter ao menos 2 caracteres.');

    if (!cpf) erros.push('O CPF é obrigatório.');
    else if (!/^\d{11}$/.test(cpf)) erros.push('O CPF deve conter exatamente 11 dígitos (apenas números).');

    if (erros.length) { mostrarErros(erros); return false; }

    $.getJSON("{{ route('funcionarios.verificarCpf') }}", { cpf, id })
      .done(function(resp){
        if (resp && resp.unique === true) {
          $form.off('submit');
          $form[0].submit();
        } else {
          mostrarErros([resp?.mensagem || 'CPF informado já foi cadastrado no sistema.']);
        }
      })
      .fail(() => mostrarErros(['Não foi possível verificar o CPF no servidor. Tente novamente.']));
  });

  function mostrarErros(lista) {
    const $box = $('#formErrors');
    const $ul = $('#formErrorsList');
    $ul.empty();
    lista.forEach(m => $ul.append($('<li>').text(m)));
    $box.removeClass('d-none');
  }
});
</script>
@endpush

@endsection
