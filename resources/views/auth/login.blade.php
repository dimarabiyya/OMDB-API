<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — MOVIEWEB</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        :root {
            --red:      #e50914;
            --bg:       #0a0a0c;
            --surface:  #111115;
            --surface2: #18181e;
            --border:   rgba(255,255,255,0.07);
            --text:     #f0f0f0;
            --muted:    #6b6b7a;
            --gold:     #f5c518;
            --font-title: 'Bebas Neue', sans-serif;
            --font-body:  'DM Sans', sans-serif;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--bg);
            font-family: var(--font-body);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* ── Grain ──────────────────────────────────────── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 10;
        }

        /* ── Ambient Glows ──────────────────────────────── */
        .glow-red {
            position: fixed;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(229,9,20,.13) 0%, transparent 70%);
            top: -200px; left: -150px;
            pointer-events: none;
            animation: driftA 12s ease-in-out infinite alternate;
        }
        .glow-dim {
            position: fixed;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(100,30,200,.07) 0%, transparent 70%);
            bottom: -150px; right: -100px;
            pointer-events: none;
            animation: driftB 15s ease-in-out infinite alternate;
        }

        @keyframes driftA {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(60px, 40px) scale(1.1); }
        }
        @keyframes driftB {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(-40px, -30px) scale(1.08); }
        }

        /* ── Floating film strip decoration ────────────── */
        .film-strip {
            position: fixed;
            top: 0; bottom: 0;
            width: 60px;
            display: flex;
            flex-direction: column;
            gap: 0;
            opacity: .035;
            pointer-events: none;
        }
        .film-strip.left  { left: 30px; }
        .film-strip.right { right: 30px; }
        .film-hole {
            width: 100%;
            aspect-ratio: 1;
            border: 3px solid #fff;
            border-radius: 4px;
            flex-shrink: 0;
            margin: 6px 0;
        }

        /* ── Card ───────────────────────────────────────── */
        .login-card {
            position: relative;
            z-index: 20;
            width: 100%;
            max-width: 420px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 48px 44px 44px;
            box-shadow: 0 40px 100px rgba(0,0,0,.7), 0 0 0 1px rgba(255,255,255,.03);
            opacity: 0;
            transform: translateY(28px);
            animation: cardIn .7s cubic-bezier(.22,1,.36,1) .1s forwards;
        }

        @keyframes cardIn {
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Brand ──────────────────────────────────────── */
        .brand {
            text-align: center;
            margin-bottom: 36px;
        }
        .brand-logo {
            font-family: var(--font-title);
            font-size: 3rem;
            letter-spacing: 4px;
            color: var(--red);
            display: block;
            line-height: 1;
            text-shadow: 0 0 40px rgba(229,9,20,.35);
            margin-bottom: 8px;
        }
        .brand-tagline {
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--muted);
            font-weight: 500;
        }

        /* ── Divider ────────────────────────────────────── */
        .card-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 0 0 32px;
        }

        /* ── Heading ────────────────────────────────────── */
        .login-heading {
            font-family: var(--font-title);
            font-size: 1.7rem;
            letter-spacing: 1.5px;
            color: var(--text);
            margin-bottom: 6px;
        }
        .login-sub {
            font-size: 13px;
            color: var(--muted);
            font-weight: 300;
            margin-bottom: 28px;
        }

        /* ── Alert ──────────────────────────────────────── */
        .alert-custom {
            background: rgba(229,9,20,.12);
            border: 1px solid rgba(229,9,20,.3);
            color: #ff8080;
            border-radius: 8px;
            font-size: 13px;
            padding: 12px 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeUp .4s forwards;
        }
        .alert-icon { font-size: 15px; flex-shrink: 0; }

        /* ── Form ───────────────────────────────────────── */
        .field-group {
            margin-bottom: 18px;
        }

        .field-label {
            display: block;
            font-size: 11px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--muted);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .field-wrap {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 15px;
            color: var(--muted);
            pointer-events: none;
            transition: color .2s;
        }

        .field-input {
            width: 100%;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-family: var(--font-body);
            font-size: 14px;
            padding: 12px 14px 12px 40px;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
            -webkit-appearance: none;
        }
        .field-input::placeholder { color: var(--muted); }
        .field-input:focus {
            border-color: rgba(229,9,20,.5);
            box-shadow: 0 0 0 3px rgba(229,9,20,.1);
            background: #1e1e25;
        }
        .field-input:focus + .field-icon,
        .field-wrap:focus-within .field-icon { color: var(--red); }

        /* Toggle password */
        .toggle-pw {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            font-size: 14px;
            padding: 0;
            transition: color .2s;
        }
        .toggle-pw:hover { color: var(--text); }

        /* ── Submit ─────────────────────────────────────── */
        .btn-login {
            width: 100%;
            background: var(--red);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 13px;
            font-family: var(--font-body);
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
            cursor: pointer;
            margin-top: 10px;
            transition: background .2s, transform .15s, box-shadow .2s;
            position: relative;
            overflow: hidden;
        }
        .btn-login::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,.08) 0%, transparent 60%);
        }
        .btn-login:hover {
            background: #ff1a27;
            transform: translateY(-1px);
            box-shadow: 0 8px 28px rgba(229,9,20,.4);
        }
        .btn-login:active { transform: translateY(0); }

        /* Loading state */
        .btn-login.loading { pointer-events: none; opacity: .7; }
        .btn-login .btn-text { transition: opacity .2s; }
        .btn-login .btn-spinner {
            display: none;
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            position: absolute;
            left: 50%; top: 50%;
            transform: translate(-50%, -50%);
        }
        .btn-login.loading .btn-text { opacity: 0; }
        .btn-login.loading .btn-spinner { display: block; }

        @keyframes spin { to { transform: translate(-50%, -50%) rotate(360deg); } }

        /* ── Footer hint ─────────────────────────────────── */
        .login-hint {
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            text-align: center;
        }
        .hint-label {
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 10px;
            font-weight: 500;
        }
        .hint-creds {
            display: inline-flex;
            gap: 16px;
        }
        .hint-pill {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 6px 14px;
            font-size: 12px;
            color: var(--text);
            font-weight: 500;
        }
        .hint-pill span { color: var(--muted); font-weight: 400; margin-right: 4px; }

        /* ── Animations ──────────────────────────────────── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Scrollbar ───────────────────────────────────── */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: #2a2a35; border-radius: 3px; }
    </style>
</head>

<body>
    <!-- Ambient glows -->
    <div class="glow-red"></div>
    <div class="glow-dim"></div>

    <!-- Film strip decorations -->
    <div class="film-strip left">
        @for ($i = 0; $i < 30; $i++)
            <div class="film-hole"></div>
        @endfor
    </div>
    <div class="film-strip right">
        @for ($i = 0; $i < 30; $i++)
            <div class="film-hole"></div>
        @endfor
    </div>

    <!-- ── Login Card ──────────────────────────────────── -->
    <div class="login-card">

        <!-- Brand -->
        <div class="brand">
            <span class="brand-logo">MOVIEWEB</span>
            <span class="brand-tagline">Your cinematic universe</span>
        </div>

        <hr class="card-divider">

        <h2 class="login-heading">SIGN IN</h2>
        <p class="login-sub">Welcome back. Enter your credentials to continue.</p>

        <!-- Error Alert -->
        @if($errors->any())
            <div class="alert-custom">
                <span class="alert-icon">⚠</span>
                <span>{{ $errors->first('message') ?? 'Invalid username or password.' }}</span>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ url('login') }}" method="POST" id="loginForm">
            @csrf

            <div class="field-group">
                <label class="field-label" for="username"><i class="fa fa-user"></i>Username</label>
                <div class="field-wrap">
                    <input
                        id="username"
                        type="text"
                        name="username"
                        class="field-input"
                        placeholder="Enter your username"
                        value="{{ old('username') }}"
                        autocomplete="username"
                        required>
                </div>
            </div>

            <div class="field-group">
                <label class="field-label" for="password"><i class="fa fa-lock"></i>Password</label>
                <div class="field-wrap">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="field-input"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required>
                    <button type="button" class="toggle-pw" id="togglePw" title="Show/hide password"><i class="fa fa-eye"></i></button>
                </div>
            </div>

            <button type="submit" class="btn-login" id="submitBtn">
                <span class="btn-text">LOGIN</span>
                <span class="btn-spinner"></span>
            </button>
        </form>

        <!-- Hint -->
        <div class="login-hint">
            <p class="hint-label">Demo Credentials</p>
            <div class="hint-creds">
                <span class="hint-pill"><span>user</span>aldmic</span>
                <span class="hint-pill"><span>pass</span>123abc123</span>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle show/hide password
        $('#togglePw').on('click', function() {
            let pw = $('#password');
            let isText = pw.attr('type') === 'text';
            pw.attr('type', isText ? 'password' : 'text');
            $(this).html(isText ? '<i class="fa fa-eye"></i>' : '<i class="fa fa-eye-slash"></i>');
        });

        // Loading state on submit
        $('#loginForm').on('submit', function() {
            $('#submitBtn').addClass('loading');
        });
    </script>
</body>
</html>