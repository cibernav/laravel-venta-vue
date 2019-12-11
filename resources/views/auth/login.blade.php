<!DOCTYPE html>
<html lang="es">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Sistema Ventas Laravel Vue Js">
  <meta name="author" content="">
  <meta name="keyword" content="Sistema ventas Laravel Vue Js, Sistema compras Laravel Vue Js">

  <title>Sistema Ventas</title>

  <!-- Icons -->
  <link href="/vendors/css/font-awesome.min.css" rel="stylesheet">
  <link href="/vendors/css/simple-line-icons.min.css" rel="stylesheet">

  <!-- Main styles for this application -->
  <link href="/vendors/css/style.css" rel="stylesheet">
  <style>
      .alert{
          padding: 0.5rem 0.5rem;
      }
      .form-check-input{
          margin-left: -0.5rem;
      }
  </style>
</head>

<body class="app flex-row align-items-center">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card-group mb-0">
          <div class="card p-4">
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <h1>Acceder</h1>
                    <p class="text-muted">Control de acceso al sistema</p>
                    <div class="input-group mb-3">
                        <span class="input-group-addon"><i class="icon-user"></i></span>
                        <input type="text" name="username" id="usuario" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" value="{{ old('username') }}" placeholder="Usuario">
                    </div>
                    {{-- {!! $errors->first('username', '<label class="invalid-feedback" role="alert">:message</label>') !!} --}}
                    <div class="input-group mb-4">
                        <span class="input-group-addon"><i class="icon-lock"></i></span>
                        <input type="password" name="password" id="clave" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 offset-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    Recordar
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-6">
                        <button type="submit" class="btn btn-primary px-4">Acceder</button>
                        </div>
                        <div class="col-6 text-right">
                        <button type="button" class="btn btn-link px-0">Olvidaste tu password?</button>
                        </div>
                    </div>
                </form>

            </div>
          </div>
          <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
            <div class="card-body text-center">
              <div>
                <h2>Sistema de Ventas</h2>
                <p>Sistema de compras, Ventas desarrollado en PHP utilizando el Framework Laravel y Vue Js, con el gestor de base de datos MariaDB.</p>
                <a href="https://www.udemy.com/user/juan-carlos-arcila-diaz/" target="_blank" class="btn btn-primary active mt-3">Ver el curso!</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap and necessary plugins -->
  <script src="/vendors/js/jquery.min.js"></script>
  <script src="/vendors/js/popper.min.js"></script>
  <script src="/vendors/js/bootstrap.min.js"></script>

</body>
</html>
