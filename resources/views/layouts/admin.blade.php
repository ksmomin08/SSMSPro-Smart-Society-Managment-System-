@php
    $appSettings = \App\Models\Setting::first() ?? new \App\Models\Setting();
    
    $primaryHex = $appSettings->primary_color ?? '#566a7f';
    $secondaryHex = $appSettings->secondary_color ?? '#697a8d';
    $sidebarHex = $appSettings->sidebar_bg_color ?? '#111c2b';
    
    // Parse primary hex to RGB components
    $hexClean = str_replace('#', '', $primaryHex);
    if(strlen($hexClean) == 3) {
        $r = hexdec(substr($hexClean, 0, 1) . substr($hexClean, 0, 1));
        $g = hexdec(substr($hexClean, 1, 1) . substr($hexClean, 1, 1));
        $b = hexdec(substr($hexClean, 2, 1) . substr($hexClean, 2, 1));
    } else {
        $r = hexdec(substr($hexClean, 0, 2));
        $g = hexdec(substr($hexClean, 2, 2));
        $b = hexdec(substr($hexClean, 4, 2));
    }
    $primaryRgb = "$r, $g, $b";
    
    // Calculate hover color (darken primary by subtracting 20)
    $rDark = max(0, $r - 20);
    $gDark = max(0, $g - 20);
    $bDark = max(0, $b - 20);
    $primaryDarkHex = sprintf("#%02x%02x%02x", $rDark, $gDark, $bDark);
@endphp
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('admin_assets/') }}/">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $appSettings->website_title ?? 'Smart Society Management System')</title>
    <meta name="description" content="Smart Society Management System — Modern residential community management platform" />

    <!-- Favicon -->
    @if(!empty($appSettings->favicon))
        <link rel="icon" href="{{ asset('storage/' . $appSettings->favicon) }}" />
    @else
        <link rel="icon" href="{{ asset('admin_assets/img/favicon/favicon.ico') }}" />
    @endif

    <!-- Fonts: Outfit + Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('admin_assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('admin_assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('admin_assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('admin_assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('admin_assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin_assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Custom Premium CSS -->
    <link rel="stylesheet" href="{{ asset('admin_assets/css/custom.css') }}" />

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />

    <!-- Helpers -->
    <script src="{{ asset('admin_assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('admin_assets/js/config.js') }}"></script>

    <!-- Dynamic Theme Engine Colors -->
    <style>
        :root {
            --ss-primary: {{ $primaryHex }} !important;
            --ss-primary-rgb: {{ $primaryRgb }} !important;
            --ss-primary-dark: {{ $primaryDarkHex }} !important;
            --ss-secondary: {{ $secondaryHex }} !important;
        }
        
        .layout-menu {
            background-color: {{ $sidebarHex }} !important;
        }
        .layout-menu .app-brand {
            background-color: {{ $sidebarHex }} !important;
        }
        .layout-menu .menu-item:not(.active) > .menu-link:hover .menu-icon {
            color: {{ $primaryHex }} !important;
        }
    </style>

    @yield('styles')
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo" style="border-bottom: 1px solid rgba(255, 255, 255, 0.05); background-color: rgba(0, 0, 0, 0.12) !important;">
                    <a href="/" class="app-brand-link">
                        <span class="app-brand-logo demo" style="width:36px;height:36px;background:linear-gradient(135deg,var(--ss-primary),var(--ss-secondary));border-radius:0.625rem;display:inline-flex;align-items:center;justify-content:center;overflow:hidden;">
                            @if(!empty($appSettings->logo))
                                <img src="{{ asset('storage/' . $appSettings->logo) }}" alt="Logo" style="width:100%;height:100%;object-fit:cover;" />
                            @else
                                <i class="bx bx-building-house" style="color:#fff;font-size:1.25rem;"></i>
                            @endif
                        </span>
                        <span class="app-brand-text demo menu-text fw-bolder ms-2" style="font-size:1.125rem;letter-spacing:-0.3px;color:#fff;">{{ $appSettings->website_title ?? 'SmartSociety' }}</span>
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    @if(auth()->check())
                        @if(auth()->user()->isSuperAdmin())
                            <!-- Super Admin Menu -->
                            <li class="menu-header small text-uppercase">
                                <span class="menu-header-text">Super Admin</span>
                            </li>
                            <li class="menu-item {{ request()->is('super-admin/dashboard') ? 'active' : '' }}">
                                <a href="{{ route('super-admin.dashboard') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                                    <div data-i18n="Dashboard">Dashboard</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('super-admin/societies*') ? 'active' : '' }}">
                                <a href="{{ route('super-admin.societies') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-building-house"></i>
                                    <div data-i18n="Societies">Societies</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('super-admin/logs*') ? 'active' : '' }}">
                                <a href="{{ route('super-admin.logs') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-history"></i>
                                    <div data-i18n="Activity Logs">Activity Logs</div>
                                </a>
                            </li>

                        @elseif(auth()->user()->isSocietyAdmin())
                            <!-- Society Admin Menu -->
                            <li class="menu-header small text-uppercase">
                                <span class="menu-header-text">Overview</span>
                            </li>
                            <li class="menu-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                                <a href="{{ route('admin.dashboard') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                                    <div data-i18n="Dashboard">Dashboard</div>
                                </a>
                            </li>

                            <li class="menu-header small text-uppercase">
                                <span class="menu-header-text">Property Management</span>
                            </li>
                            <li class="menu-item {{ request()->is('buildings*') ? 'active' : '' }}">
                                <a href="{{ route('buildings.index') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-building"></i>
                                    <div data-i18n="Buildings">Buildings</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('flats*') ? 'active' : '' }}">
                                <a href="{{ route('flats.index') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-home-alt"></i>
                                    <div data-i18n="Flats">Flats / Units</div>
                                </a>
                            </li>

                            <li class="menu-header small text-uppercase">
                                <span class="menu-header-text">People</span>
                            </li>
                            <li class="menu-item {{ request()->is('residents*') ? 'active' : '' }}">
                                <a href="{{ route('residents.index') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-group"></i>
                                    <div data-i18n="Residents">Residents</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('admin/guards*') ? 'active' : '' }}">
                                <a href="{{ route('admin.guards') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-shield-quarter"></i>
                                    <div data-i18n="Guards">Security Guards</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('visitors*') ? 'active' : '' }}">
                                <a href="{{ route('visitors.index') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-user-voice"></i>
                                    <div data-i18n="Visitors">Visitor Log</div>
                                </a>
                            </li>

                            <li class="menu-header small text-uppercase">
                                <span class="menu-header-text">Operations</span>
                            </li>
                            <li class="menu-item {{ request()->is('complaints*') ? 'active' : '' }}">
                                <a href="{{ route('complaints.index') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-message-square-error"></i>
                                    <div data-i18n="Complaints">Complaints</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('maintenances*') ? 'active' : '' }}">
                                <a href="{{ route('maintenances.index') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-rupee"></i>
                                    <div data-i18n="Maintenance">Maintenance Bills</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('admin/parking*') ? 'active' : '' }}">
                                <a href="{{ route('admin.parking') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-car"></i>
                                    <div data-i18n="Parking">Parking</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('admin/amenit*') ? 'active' : '' }}">
                                <a href="{{ route('admin.amenities') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-spa"></i>
                                    <div data-i18n="Amenities">Amenities</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('notices*') ? 'active' : '' }}">
                                <a href="{{ route('notices.index') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-bell"></i>
                                    <div data-i18n="Notices">Notices</div>
                                </a>
                            </li>

                            <li class="menu-header small text-uppercase">
                                <span class="menu-header-text">System</span>
                            </li>
                            <li class="menu-item {{ request()->is('admin/settings*') ? 'active' : '' }}">
                                <a href="{{ route('admin.settings') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-cog"></i>
                                    <div data-i18n="Site Settings">System Settings</div>
                                </a>
                            </li>

                        @elseif(auth()->user()->isResident())
                            <!-- Resident Menu -->
                            <li class="menu-header small text-uppercase">
                                <span class="menu-header-text">My Home</span>
                            </li>
                            <li class="menu-item {{ request()->is('resident/dashboard') ? 'active' : '' }}">
                                <a href="{{ route('resident.dashboard') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                                    <div data-i18n="Dashboard">My Dashboard</div>
                                </a>
                            </li>

                        @elseif(auth()->user()->isGuard())
                            <!-- Guard Menu -->
                            <li class="menu-header small text-uppercase">
                                <span class="menu-header-text">Security</span>
                            </li>
                            <li class="menu-item {{ request()->is('guard/dashboard') ? 'active' : '' }}">
                                <a href="{{ route('guard.dashboard') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-shield-alt-2"></i>
                                    <div data-i18n="Gate Security">Gate Security</div>
                                </a>
                            </li>
                        @endif
                    @endif
                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!-- Search -->
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item d-flex align-items-center">
                                <i class="bx bx-search fs-4 lh-0"></i>
                                <input type="text" class="form-control border-0 shadow-none" placeholder="Search..." aria-label="Search..." />
                            </div>
                        </div>
                        <!-- /Search -->

                        <ul class="navbar-nav flex-row align-items-center ms-auto gap-2">
                            <!-- Fullscreen Toggle -->
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0);" onclick="toggleFullscreen()" title="Fullscreen">
                                    <i class="bx bx-fullscreen fs-5"></i>
                                </a>
                            </li>

                            <!-- Notifications -->
                            <li class="nav-item dropdown notification-indicator">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <i class="bx bx-bell fs-5"></i>
                                    <span class="badge bg-danger rounded-pill badge-center" id="notifBadge" style="display:none;">0</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" style="min-width:300px;">
                                    <li>
                                        <h6 class="dropdown-header fw-bold">Notifications</h6>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <div class="dropdown-item text-center text-muted py-3" id="notifContent">
                                            <i class="bx bx-check-circle fs-4 text-success d-block mb-1"></i>
                                            <small>No new notifications</small>
                                        </div>
                                    </li>
                                </ul>
                            </li>

                            <!-- User -->
                            @if(auth()->check())
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=696cff&color=fff' }}" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=696cff&color=fff' }}" alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block">{{ auth()->user()->name }}</span>
                                                    <small class="text-muted">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li><div class="dropdown-divider"></div></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.index') }}">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">My Profile</span>
                                        </a>
                                    </li>
                                    <li><div class="dropdown-divider"></div></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="bx bx-power-off me-2"></i>
                                                <span class="align-middle">Log Out</span>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            @endif
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    @if(isset($appSettings) && $appSettings->announcement_status && !empty($appSettings->announcement_text))
                        <div class="bg-warning text-dark py-2 px-3 d-flex align-items-center justify-content-between position-relative shadow-sm" style="z-index: 10; font-size: 0.9rem; font-family: 'Poppins', sans-serif; font-weight: 500; border-bottom: 1px solid rgba(0,0,0,0.05);">
                            <div class="d-flex align-items-center w-100">
                                <span class="badge bg-danger me-2 shadow-xs" style="font-weight: 600; letter-spacing: 0.5px; padding: 0.35em 0.6em;">BROADCAST</span>
                                <marquee scrollamount="5" class="m-0" style="vertical-align: middle;">{{ $appSettings->announcement_text }}</marquee>
                            </div>
                        </div>
                    @endif
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y animate-fadeInUp">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible animate-fadeInUp" role="alert">
                                <i class="bx bx-check-circle me-1"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible animate-fadeInUp" role="alert">
                                <i class="bx bx-error-circle me-1"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible animate-fadeInUp" role="alert">
                                <i class="bx bx-error-circle me-1"></i>
                                {{ $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                © <script>document.write(new Date().getFullYear());</script> <strong>Smart Society</strong> Management System. Crafted with <i class="bx bx-heart text-danger"></i>
                            </div>
                            <div>
                                <span class="text-muted">v2.0</span>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="{{ asset('admin_assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/js/menu.js') }}"></script>

    <!-- Vendors JS -->
    <script src="{{ asset('admin_assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('admin_assets/js/main.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Fullscreen toggle
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        }

        // Notification polling
        function fetchNotifications() {
            $.get('/notification-count', function(res) {
                if (res.count > 0) {
                    $('#notifBadge').text(res.count).show();
                } else {
                    $('#notifBadge').hide();
                }
            });
        }
        $(document).ready(function() {
            fetchNotifications();
            setInterval(fetchNotifications, 30000);
        });

        // Delete confirmation helper
        function confirmDelete(formId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#696cff',
                cancelButtonColor: '#8592a3',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'animate__animated animate__fadeInDown'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert-dismissible').fadeOut(500);
        }, 5000);
    </script>

    @yield('scripts')
</body>
</html>