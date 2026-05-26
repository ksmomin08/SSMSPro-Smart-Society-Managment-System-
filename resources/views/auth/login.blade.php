<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('admin_assets/') }}/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Login | Smart Society Management System</title>
    
    <meta name="description" content="" />
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('admin_assets/img/favicon/favicon.ico') }}" />
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    
    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('admin_assets/vendor/fonts/boxicons.css') }}" />
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('admin_assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('admin_assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('admin_assets/css/demo.css') }}" />
    
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('admin_assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('admin_assets/vendor/css/pages/page-auth.css') }}" />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('admin_assets/css/custom.css') }}" />
    
    <!-- Helpers -->
    <script src="{{ asset('admin_assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('admin_assets/js/config.js') }}"></script>
</head>

<body class="auth-page">
    <!-- Floating Background Orbs -->
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="bg-orb bg-orb-3"></div>

    <!-- Content -->
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">
                <i class="bx bx-building-house"></i>
            </div>
            <h2 class="fw-bold mb-0">Smart Society</h2>
            <p class="text-muted mb-0">Management System</p>
        </div>
        <div class="auth-body">
            <h4 class="fw-bold mb-1">Welcome Back! 👋</h4>
            <p class="text-muted mb-4">Please sign in to your account to continue.</p>

            <form id="formAuthentication" class="mb-3" action="{{ route('login.check') }}" method="POST">
                @csrf
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="email" class="form-label">Email or Username</label>
                    <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email or username" autofocus required />
                </div>
                
                <div class="mb-3 form-password-toggle">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password">Password</label>
                        <a href="{{ route('forgot-password') }}">
                            <small>Forgot Password?</small>
                        </a>
                    </div>
                    <div class="input-group input-group-merge">
                        <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required />
                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember-me" name="remember" />
                        <label class="form-check-label" for="remember-me"> Remember Me </label>
                    </div>
                </div>
                
                <div class="mb-3">
                    <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                </div>
            </form>

            <p class="text-center">
                <span>New on our platform?</span>
                <a href="{{ route('register') }}">
                    <span>Create an account</span>
                </a>
            </p>
        </div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('admin_assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    
    <script src="{{ asset('admin_assets/vendor/js/menu.js') }}"></script>
    
    <!-- Main JS -->
    <script src="{{ asset('admin_assets/js/main.js') }}"></script>
</body>
</html>
