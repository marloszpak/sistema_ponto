<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema de Ponto</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .navbar {
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      font-weight: 500;
    }

    .navbar-brand {
      font-size: 1.3rem;
      font-weight: 600;
      color: #0d6efd !important;
    }

    .nav-link {
      color: #555 !important;
      transition: color 0.2s ease-in-out;
    }

    .nav-link:hover, .nav-link.active {
      color: #0d6efd !important;
    }

    main.container {
      flex: 1;
      margin-top: 40px;
      margin-bottom: 60px;
    }

    footer {
      background-color: #fff;
      box-shadow: 0 -1px 5px rgba(0,0,0,0.05);
      padding: 15px 0;
      text-align: center;
      font-size: 0.9rem;
      color: #666;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('funcionarios.listar') }}">
        <i class="bi bi-clock-history text-primary fs-4"></i>
        Sistema de Ponto
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarMenu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('funcionarios.listar') ? 'active' : '' }}" href="{{ route('funcionarios.listar') }}">
              <i class="bi bi-people-fill me-1"></i>Funcionários
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('ponto.tela') ? 'active' : '' }}" href="{{ route('ponto.tela') }}">
              <i class="bi bi-fingerprint me-1"></i>Bater Ponto
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('relatorios.batidas') ? 'active' : '' }}" href="{{ route('relatorios.batidas') }}">
              <i class="bi bi-file-earmark-bar-graph me-1"></i>Relatórios
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container">
    @yield('content')
  </main>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
