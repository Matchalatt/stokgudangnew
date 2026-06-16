<!DOCTYPE html>
<html class="h-100" lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login - InvSys Sistem Inventaris</title>
    
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png') }}">
    
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    <style>
        /* =========================================================
           MODERN MINIMALIST OVERRIDES (SINKRON DENGAN APP.BLADE.PHP)
           ========================================================= */
        body {
            background-color: #f4f7f6 !important;
            color: #4a5568;
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        .card {
            border: none !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 24px rgba(149, 157, 165, 0.08) !important;
            background-color: #ffffff;
        }
        .btn {
            border-radius: 8px !important;
            font-weight: 600 !important;
            letter-spacing: 0.3px;
            border: none !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .btn-primary { 
            background-color: #4f46e5 !important;
            color: #fff !important;
        }
        .btn-primary:hover { 
            background-color: #4338ca !important; 
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25) !important; 
            transform: translateY(-1px);
        }
        .form-control {
            border-radius: 8px !important;
            height: 45px;
            box-shadow: none !important;
        }
        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.15) !important;
        }
        .bg-primary-light { 
            background-color: #eef2ff !important; 
            color: #4f46e5 !important; 
        }
        .input-group-text {
            background-color: #f8fafc;
        }
    </style>
</head>

<body class="h-100">
    
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>

    <div class="login-form-bg h-100 d-flex align-items-center">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    
                    <div class="card login-form mb-0">
                        <div class="card-body pt-5 pb-5 px-4 px-md-5">
                            
                            <div class="text-center mb-4">
                                <div class="d-inline-flex align-items-center justify-content-center bg-primary-light rounded-circle mb-3" style="width: 70px; height: 70px;">
                                    <i class="icon-layers" style="font-size: 30px;"></i>
                                </div>
                                <h3 class="font-weight-bold text-dark mb-1">InvSys</h3>
                                <p class="text-muted">Sistem Inventaris Gudang Bulog</p>
                            </div>

                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 10px;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <strong><i class="icon-check mr-2"></i> Berhasil:</strong> {{ session('status') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 10px;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h6 class="text-danger font-weight-bold mb-2">
                                        <i class="icon-info mr-2"></i> Terjadi kesalahan:
                                    </h6>
                                    <ul class="mb-0 pl-4 small">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form class="mt-4 mb-3" action="{{ route('login.post') }}" method="POST">
                                @csrf
                                
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark" style="font-size: 13px;">Alamat Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text border-right-0" style="border-radius: 8px 0 0 8px;">
                                                <i class="icon-envelope text-muted"></i>
                                            </span>
                                        </div>
                                        <input type="email" name="email" class="form-control border-left-0 pl-1" style="border-radius: 0 8px 8px 0;" placeholder="admin@bulog.co.id" value="{{ old('email') }}" required autofocus>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="font-weight-bold text-dark mb-0" style="font-size: 13px;">Password</label>
                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}" class="text-primary small font-weight-bold" style="text-decoration: none;">Lupa Password?</a>
                                        @endif
                                    </div>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text border-right-0" style="border-radius: 8px 0 0 8px;">
                                                <i class="icon-lock text-muted"></i>
                                            </span>
                                        </div>
                                        <input type="password" name="password" class="form-control border-left-0 pl-1" style="border-radius: 0 8px 8px 0;" placeholder="••••••••" required>
                                    </div>
                                </div>

                                <div class="form-group form-check mb-4 mt-2">
                                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember" style="cursor: pointer; margin-top: 0.25rem;">
                                    <label class="form-check-label text-muted small" for="remember_me" style="cursor: pointer;">Ingat saya di perangkat ini</label>
                                </div>

                                <button type="submit" class="btn btn-primary btn-block py-2 mt-4 shadow-sm" style="font-size: 15px;">
                                    <i class="icon-login mr-2"></i> Masuk ke Dashboard
                                </button>
                            </form>
                            
                            <div class="text-center mt-5 pt-4 border-top">
                                <p class="text-muted small mb-0">&copy; {{ date('Y') }} Sistem Inventaris Logistik<br>Gudang BULOG</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('plugins/common/common.min.js') }}"></script>
    <script src="{{ asset('js/custom.min.js') }}"></script>
    <script src="{{ asset('js/settings.js') }}"></script>
    <script src="{{ asset('js/gleek.js') }}"></script>
</body>
</html>