<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema Ventas Laravel Vue Js- IncanatoIT">
    <meta name="author" content="Incanatoit.com">
    <meta name="keyword" content="Sistema ventas Laravel Vue Js, Sistema compras Laravel Vue Js">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="vendors/img/favicon.png">
    <title>Sistema Ventas - IncanatoIT</title>
    <!-- Icons -->
    <link href="/vendors/css/font-awesome.min.css" rel="stylesheet">
    <link href="/vendors/css/simple-line-icons.min.css" rel="stylesheet">
    <!-- Main styles for this application -->
    <link href="/vendors/css/style.css" rel="stylesheet">
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    <div id="app">
        <header class="app-header navbar">
            <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
            <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="#"></a>
            <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
            <span class="navbar-toggler-icon"></span>
            </button>
            <ul class="nav navbar-nav d-md-down-none">
                <li class="nav-item px-3">
                    <a class="nav-link" href="#">Escritorio</a>
                </li>
                <li class="nav-item px-3">
                    <a class="nav-link" href="#">Configuraciones</a>
                </li>
            </ul>
            <ul class="nav navbar-nav ml-auto">
                <notificacion :dataarray="notificacion"></notificacion>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="vendors/img/avatars/6.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                        <input type="hidden" id="usrid" value="{{ auth::user()->id }}" />
                        <span class="d-md-down-none">{{ auth::user()->username }} </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header text-center">
                            <strong>Cuenta</strong>
                        </div>
                        <a class="dropdown-item" href="#"><i class="fa fa-user"></i> Perfil</a>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="fa fa-lock"></i>
                            Cerrar sesión
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </header>

        <div class="app-body">
            @include('partial.sidebar')

            <!-- Contenido Principal -->
            @yield('contenido')

            <!-- /Fin del contenido principal -->
        </div>
    </div>
    <footer class="app-footer">
        <span><a href="http://www.incanatoit.com/">IncanatoIT</a> &copy; 2017</span>
        <span class="ml-auto">Desarrollado por <a href="http://www.incanatoit.com/">IncanatoIT</a></span>
    </footer>

    <!-- Bootstrap and necessary plugins -->
    <script src="/vendors/js/jquery.min.js"></script>
    <script src="/vendors/js/popper.min.js"></script>
    <script src="/vendors/js/bootstrap.min.js"></script>
    <script src="/vendors/js/pace.min.js"></script>
    <!-- Plugins and scripts required by all views -->
    <script src="/vendors/js/Chart.min.js"></script>
    <script src="/js/app.js"></script>
    <!-- GenesisUI main scripts -->
    <script src="/vendors/js/template.js"></script>
    <script src="/vendors/js/sweetalert2.all.min.js"></script>
    <script href="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>

</body>

</html>
