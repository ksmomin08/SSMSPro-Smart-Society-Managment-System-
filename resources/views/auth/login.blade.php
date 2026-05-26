<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | Smart Society Management System</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('admin_assets/img/favicon/favicon.ico') }}" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-XLdFZUhX0uKkD0K+7msbZx8e7n40pXIXaWugSnNnQVpoZ0EA16HxWJY5fWCIyBTp" crossorigin="anonymous">

    <style>
        :root {
            --bg-primary: #0f172a;
            --bg-card: rgba(15, 23, 42, 0.90);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent: #6366f1;
            --accent-strong: #4f46e5;
            --shadow: 0 30px 80px rgba(15, 23, 42, 0.35);
            --border: rgba(148, 163, 184, 0.15);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top left, rgba(99, 102, 241, 0.2), transparent 28%),
                        radial-gradient(circle at bottom right, rgba(14, 165, 233, 0.18), transparent 30%),
                        linear-gradient(180deg, #020617 0%, #0f172a 100%);
            color: var(--text-main);
        }

        .page-shell {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 32px;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 24px;
            box-shadow: var(--shadow);
            overflow: hidden;
            position: relative;
        }

        .login-card::before,
        .login-card::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.4;
            pointer-events: none;
        }

        .login-card::before {
            width: 220px;
            height: 220px;
            background: rgba(99, 102, 241, 0.28);
            top: -60px;
            right: -50px;
        }

        .login-card::after {
            width: 180px;
            height: 180px;
            background: rgba(14, 165, 233, 0.22);
            bottom: -40px;
            left: -40px;
        }

        .login-inner {
            position: relative;
            z-index: 1;
            padding: 40px 32px;
        }

        .login-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 28px;
        }

        .login-brand-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, var(--accent), #0ea5e9);
            color: #fff;
            font-size: 1.4rem;
        }

        .login-brand-text h1 {
            margin: 0;
            font-size: 1.4rem;
            letter-spacing: -0.03em;
        }

        .login-brand-text p {
            margin: 6px 0 0;
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .login-title {
            margin: 24px 0 10px;
            font-size: 1.75rem;
            line-height: 1.1;
        }

        .login-subtitle {
            margin-bottom: 24px;
            color: var(--text-muted);
        }

        .form-control {
            background: rgba(15, 23, 42, 0.92);
            border-color: rgba(148, 163, 184, 0.18);
            color: var(--text-main);
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.18);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #0ea5e9);
            border: none;
            box-shadow: 0 20px 35px rgba(99, 102, 241, 0.22);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4f46e5, #0284c7);
        }

        .login-footer {
            margin-top: 16px;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .login-footer a {
            color: #c7d2fe;
            text-decoration: none;
            font-weight: 600;
        }

        .login-footer a:hover {
            color: #fff;
        }

        .alert-custom {
            background: rgba(248, 113, 113, 0.12);
            border-color: rgba(248, 113, 113, 0.28);
            color: #fee2e2;
        }

        .form-check-label {
            color: var(--text-main);
        }

        .form-text {
            color: var(--text-muted);
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <div class="login-card">
            <div class="login-inner">
                <div class="login-brand">
                    <div class="login-brand-icon">SS</div>
                    <div class="login-brand-text">
                        <h1>Smart Society</h1>
                        <p>Management System</p>
                    </div>
                </div>

                <h2 class="login-title">Welcome Back! 👋</h2>
                <p class="login-subtitle">Please sign in to your account to continue.</p>

                <form action="{{ route('login.check') }}" method="POST">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger alert-custom" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="email" class="form-label">Email or Username</label>
                        <input type="text" class="form-control form-control-lg" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email or username" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label d-flex justify-content-between align-items-center">
                            <span>Password</span>
                            <a href="{{ route('forgot-password') }}" class="text-decoration-none">Forgot Password?</a>
                        </label>
                        <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="********" required>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="remember-me" name="remember">
                        <label class="form-check-label" for="remember-me">Keep me signed in</label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">Sign In</button>
                </form>

                <div class="login-footer mt-4">
                    <span>New on our platform?</span>
                    <a href="{{ route('register') }}">Create an account</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>