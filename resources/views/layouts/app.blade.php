<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'InvSys - Sistem Inventaris')</title>
    
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png') }}">
    
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    @stack('styles')

    <style>
        /* =========================================================
           MODERN MINIMALIST OVERRIDES UNTUK QUIXLAB (BOOTSTRAP 4)
           ========================================================= */

        /* 1. Global Background & Typography */
        body {
            background-color: #f4f7f6 !important;
            color: #4a5568;
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            color: #2d3748 !important;
            letter-spacing: -0.3px;
        }

        /* 2. Modern Card Styling (Smooth & Rounded) */
        .card {
            border: none !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 24px rgba(149, 157, 165, 0.08) !important;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            background-color: #ffffff;
        }
        .card:hover {
            box-shadow: 0 8px 28px rgba(149, 157, 165, 0.12) !important;
        }

        /* 3. Soft Pastel Accents (Colorful Minimalist) */
        .bg-primary-light { background-color: #eef2ff !important; color: #4f46e5 !important; }
        .bg-success-light { background-color: #ecfdf5 !important; color: #10b981 !important; }
        .bg-danger-light  { background-color: #fef2f2 !important; color: #ef4444 !important; }
        .bg-warning-light { background-color: #fffbeb !important; color: #f59e0b !important; }
        .bg-info-light    { background-color: #f0f9ff !important; color: #0ea5e9 !important; }

        /* 4. Button Styling yang Lebih Segar */
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

        /* 5. Tabel yang Lebih Bersih */
        .table thead th {
            border-bottom: 2px solid #edf2f7 !important;
            border-top: none !important;
            color: #718096 !important;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            background-color: transparent !important;
        }
        .table td {
            border-color: #f7fafc !important;
            vertical-align: middle !important;
        }

        /* 6. Sidebar, Header & Brand Logo Tweaks */
        .nav-header {
            background-color: #4f46e5 !important;
            transition: all 0.3s ease-in-out;
        }
        .nk-sidebar {
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.03);
            border-right: none;
            background-color: #ffffff;
            transition: all 0.3s ease-in-out;
        }
        .nk-sidebar .metismenu a {
            border-radius: 0 20px 20px 0;
            margin-right: 15px;
            transition: all 0.2s ease;
        }
        .nk-sidebar .metismenu a:hover, .nk-sidebar .metismenu a:focus, .nk-sidebar .metismenu a.active {
            background-color: #eef2ff !important;
            color: #4f46e5 !important;
        }
        .nk-sidebar .metismenu a i {
            color: #a0aec0;
            transition: all 0.2s ease;
        }
        .nk-sidebar .metismenu a:hover i, .nk-sidebar .metismenu a.active i {
            color: #4f46e5 !important;
        }
        .header {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03) !important;
            border-bottom: none !important;
            background-color: #ffffff;
            transition: all 0.3s ease-in-out;
        }
        .content-body {
            transition: all 0.3s ease-in-out;
        }
        
        /* 7. Hamburger Icon Override */
        .header .nav-control .hamburger .line {
            background-color: #4a5568 !important; 
            transition: all 0.3s ease-in-out;
            border-radius: 4px;
        }
        .header .nav-control .hamburger:hover .line {
            background-color: #4f46e5 !important; 
        }

        /* 8. User Profile Avatar Dropdown */
        .user-avatar-custom {
            width: 40px; 
            height: 40px; 
            font-size: 18px;
            background: linear-gradient(135deg, #4f46e5, #818cf8);
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
        }

        /* =========================================================
           PERBAIKAN LOGIKA CSS SAAT SIDEBAR DI-MINIMIZE (COLLAPSE)
           ========================================================= */
        @media (min-width: 768px) {
            /* Keadaan ketika class .menu-toggle aktif (Sidebar mengecil) */
            #main-wrapper.menu-toggle .nav-header {
                width: 5rem !important;
            }
            #main-wrapper.menu-toggle .nav-header .brand-title {
                display: none !important;
            }
            #main-wrapper.menu-toggle .nav-header .logo-abbr {
                display: block !important;
                margin: 0 auto;
                text-align: center;
            }
            #main-wrapper.menu-toggle .header {
                width: calc(100% - 5rem) !important;
                margin-left: 5rem !important;
            }
            #main-wrapper.menu-toggle .nk-sidebar {
                width: 5rem !important;
            }
            #main-wrapper.menu-toggle .nk-sidebar .nav-text,
            #main-wrapper.menu-toggle .nk-sidebar .nav-label {
                display: none !important;
            }
            #main-wrapper.menu-toggle .nk-sidebar .metismenu a {
                border-radius: 12px !important;
                margin: 4px 10px !important;
                padding: 12px 0 !important;
                text-align: center;
            }
            #main-wrapper.menu-toggle .nk-sidebar .metismenu a i {
                float: none !important;
                margin: 0 !important;
                font-size: 1.25rem;
            }
            #main-wrapper.menu-toggle .content-body {
                margin-left: 5rem !important;
            }
        }
    </style>
</head>

<body>

    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    
    <div id="main-wrapper">

        <div class="nav-header">
            <div class="brand-logo">
                <a href="{{ route('dashboard') }}">
                    <b class="logo-abbr text-white font-weight-bold" style="font-size: 18px; display: none;">IS</b>
                    <span class="logo-compact text-white font-weight-bold" style="font-size: 18px;">InvSys</span>
                    <span class="brand-title text-white font-weight-bold" style="font-size: 20px; letter-spacing: 1.5px;">
                        InvSys
                    </span>
                </a>
            </div>
        </div>
        
        <div class="header">    
            <div class="header-content clearfix">
                
                <div class="nav-control">
                    <div class="hamburger" style="cursor: pointer;" title="Minimize Sidebar">
                        <span class="line"></span><span class="line"></span><span class="line"></span>
                    </div>
                </div>
                
                <div class="header-right">
                    <ul class="clearfix">
                        <li class="icons dropdown">
                            <div class="user-img c-pointer position-relative" data-toggle="dropdown">
                                <span class="activity active"></span>
                                <div class="rounded-circle text-white d-inline-flex align-items-center justify-content-center font-weight-bold user-avatar-custom">
                                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                                </div>
                            </div>
                            <div class="drop-down dropdown-profile dropdown-menu dropdown-menu-right shadow-sm border-0" style="border-radius: 12px;">
                                <div class="dropdown-content-body">
                                    <ul>
                                        <li class="px-3 py-2 bg-light rounded m-2">
                                            <span class="d-block font-weight-bold text-dark">{{ Auth::user()->name ?? 'Administrator' }}</span>
                                            <small class="text-muted">{{ Auth::user()->email ?? 'admin@invsys.local' }}</small>
                                        </li>
                                        <li>
                                            <a href="#"><i class="icon-user text-primary mr-2"></i> <span>Profil</span></a>
                                        </li>
                                        <li>
                                            <form action="{{ route('logout') }}" method="POST" id="logout-form" class="m-0">
                                                @csrf
                                                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-danger">
                                                    <i class="icon-power text-danger mr-2"></i> <span>Logout</span>
                                                </a>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="nk-sidebar">           
            <div class="nk-nav-scroll">
                <ul class="metismenu" id="menu">
                    
                    <li class="pt-3">
                        <a href="{{ route('dashboard') }}" aria-expanded="false">
                            <i class="icon-speedometer menu-icon"></i><span class="nav-text font-weight-bold">Dashboard</span>
                        </a>
                    </li>
                    
                    <li class="nav-label text-uppercase text-muted font-weight-bold mt-2" style="font-size: 11px; letter-spacing: 1px;">Master Data</li>
                    <li>
                        <a href="{{ route('items.index') }}" aria-expanded="false">
                            <i class="icon-grid menu-icon"></i><span class="nav-text">Data Barang</span>
                        </a>
                    </li>

                    <li class="nav-label text-uppercase text-muted font-weight-bold mt-2" style="font-size: 11px; letter-spacing: 1px;">Operasional</li>
                    
                    <li>
                        <a href="{{ route('inbounds.index') }}" aria-expanded="false">
                            <i class="icon-arrow-down-circle menu-icon"></i><span class="nav-text">Inbound (Masuk)</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('outbounds.index') }}" aria-expanded="false">
                            <i class="icon-arrow-up-circle menu-icon"></i><span class="nav-text">Outbound (Keluar)</span>
                        </a>
                    </li>

                    <li class="nav-label text-uppercase text-muted font-weight-bold mt-2" style="font-size: 11px; letter-spacing: 1px;">Laporan</li>
                    <li>
                        <a href="{{ route('reports.stock') }}" aria-expanded="false">
                            <i class="icon-docs menu-icon"></i><span class="nav-text">Laporan Stok</span>
                        </a>
                    </li>
                    
                </ul>
            </div>
        </div>
        
        <div class="content-body">
            <div class="container-fluid mt-4">
                
                <div class="row">
                    <div class="col-12">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" style="border-radius: 10px;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong><i class="icon-check mr-2"></i> Berhasil!</strong> {{ session('success') }}
                            </div>
                        @endif
                        
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" style="border-radius: 10px;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong><i class="icon-close mr-2"></i> Gagal!</strong> {{ session('error') }}
                            </div>
                        @endif
                    </div>
                </div>

                @yield('content')
                
            </div>
        </div>
        
        <div class="footer">
            <div class="copyright">
                <p class="text-muted">Copyright &copy; {{ date('Y') }} InvSys System. All Rights Reserved.</p>
            </div>
        </div>
        
    </div>

    <script src="{{ asset('plugins/common/common.min.js') }}"></script>
    <script src="{{ asset('js/custom.min.js') }}"></script>
    <script src="{{ asset('js/settings.js') }}"></script>
    <script src="{{ asset('js/gleek.js') }}"></script>
    <script src="{{ asset('js/styleSwitcher.js') }}"></script>

    @stack('scripts')
</body>
</html>