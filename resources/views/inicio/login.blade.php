<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema Nómina</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header text-center bg-primary text-white">
                    <h4>Iniciar Sesión</h4>
                </div>
                <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" 
                            name="nombre" 
                            class="form-control" 
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" 
                            name="password" 
                            class="form-control" 
                            required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            Entrar
                        </button>
                    </div>
                </form>

                </div>
                <div class="card-footer text-center small text-muted">
                    Sistema de Nómina
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>