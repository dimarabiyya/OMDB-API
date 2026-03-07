<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Movie App</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { 
            background-color: #141414; 
            color: #fff; 
            display: flex; 
            align-items: center; 
            height: 100vh; 
            margin: 0;
        }
        .login-container { 
            width: 100%; 
            max-width: 400px; 
            margin: auto; 
            padding: 30px; 
            background: #1f1f1f; 
            border-radius: 8px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.5); 
        }
        .brand-text {
            color: #e50914;
            font-weight: bold;
            font-size: 2rem;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-control {
            background-color: #333;
            border: 1px solid #444;
            color: #fff;
        }
        .form-control:focus {
            background-color: #444;
            color: #fff;
            border-color: #e50914;
            box-shadow: none;
        }
        .btn-primary {
            background-color: #e50914;
            border: none;
            font-weight: bold;
            padding: 10px;
        }
        .btn-primary:hover {
            background-color: #b00610;
        }
        .alert-danger {
            background-color: #e50914;
            color: #fff;
            border: none;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="brand-text">MOVIEWEB</div>
        <h4 class="text-center mb-4">Masuk ke Akun</h4>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $errors->first('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form action="{{ url('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="text-muted small">Username</label>
                <input type="text" name="username" class="form-control" required placeholder="aldmic" autocomplete="off">
            </div>
            <div class="form-group">
                <label class="text-muted small">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="123abc123">
            </div>
            <button type="submit" class="btn btn-primary btn-block mt-4">Login</button>
        </form>

        <p class="text-center mt-4 text-muted small">
            Gunakan user: <b>aldmic</b> / pass: <b>123abc123</b>
        </p>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>