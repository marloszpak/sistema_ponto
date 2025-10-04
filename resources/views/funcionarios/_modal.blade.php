<div class="modal fade" id="modalFuncionario" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <form id="formFuncionario" method="POST" action="{{ route('funcionarios.salvar') }}">
        @csrf
        <input type="hidden" name="id" id="form_funcionario_id" value="{{ old('id', '') }}">
        <input type="hidden" id="metodo_form" name="_method" value="POST">

        <div class="modal-header bg-primary text-white rounded-top-4">
          <h5 class="modal-title fw-semibold">
            <i class="bi bi-person-fill-add me-2"></i>Cadastro de Funcionário
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>

        <div class="modal-body p-4">

          <div id="formErrors" class="alert alert-danger d-none rounded-3">
            <ul class="mb-0" id="formErrorsList"></ul>
          </div>

          @if($errors->any())
            <div class="alert alert-danger rounded-3">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="row g-3">
            <div class="col-md-6">
              <label for="form_nome" class="form-label fw-semibold">Nome <span class="text-danger">*</span></label>
              <input name="nome" id="form_nome" class="form-control form-control-lg" required value="{{ old('nome') }}" placeholder="Ex: Ana Souza">
            </div>

            <div class="col-md-6">
              <label for="form_cpf" class="form-label fw-semibold">CPF <span class="text-danger">*</span></label>
              <input name="cpf" id="form_cpf" class="form-control form-control-lg" required pattern="\d{11}" maxlength="11" value="{{ old('cpf') }}" placeholder="Somente números">
              <div class="form-text">Digite apenas os 11 dígitos (sem pontos ou hífen).</div>
            </div>

            <div class="col-12">
              <label for="form_cargo" class="form-label fw-semibold">Cargo/Função</label>
              <input name="cargo" id="form_cargo" class="form-control form-control-lg" value="{{ old('cargo') }}" placeholder="Ex: Analista, Supervisor, etc.">
            </div>
          </div>
        </div>

        <div class="modal-footer bg-light rounded-bottom-4">
          <button class="btn btn-outline-secondary px-4" type="button" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cancelar
          </button>
          <button class="btn btn-primary px-4" type="submit" id="btnSalvarFuncionario">
            <i class="bi bi-check-circle me-1"></i> Salvar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
