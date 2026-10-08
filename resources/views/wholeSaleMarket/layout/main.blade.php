<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="adminHMD professional admin dashboard template">
    <title>WholeSaleMarket</title>

    <link rel="stylesheet" href=" {{ asset('wholeSaleMarket/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href=" {{ asset('wholeSaleMarket/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href=" {{ asset('wholeSaleMarket/assets/css/style.css') }} ">
    <link rel="stylesheet" href=" {{ asset('wholeSaleMarket/assets/css/crop.css') }} ">

</head>

<body>
    <div class="admin-shell">
        <div class="sidebar-backdrop" data-sidebar-close></div>

        <aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
            <div class="sidebar-header">
                <a class="brand-mark" href="index.html" aria-label="adminHMD dashboard">
                    <span class="brand-icon"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i></span>
                    <span class="brand-copy">
                        <span class="brand-title">မြရတနာ</span>
                    </span>
                </a>
            </div>

            <nav class="sidebar-nav">
                <a class="nav-link {{ request()->routeIs('WholeSaleMarket#dashboard') ? 'active' : '' }}"
                    href="{{ route('WholeSaleMarket#dashboard') }}" aria-current="page">
                    <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
                    <span class="nav-text">ပင်မစာမျက်နှာ</span>
                </a>
                <a class="nav-link {{ request()->routeIs('Crop#list') ? 'active' : '' }}"
                    href="{{ route('Crop#list') }}">
                    <span class="nav-icon"><i class="bi bi-flower1"></i></span>
                    <span class="nav-text">သီးနှံများ</span>
                </a>
                <a class="nav-link {{ request()->routeIs('Quality#list') ? 'active' : '' }}"
                    href="{{ route('Quality#list') }}">
                    <span class="nav-icon"><i class="bi bi-award"></i></span>
                    <span class="nav-text">သီးနှံအရည်အသွေးအဆင့်</span>
                </a>

            </nav>

        </aside>

        <div class="admin-main">
            <nav class="navbar admin-navbar navbar-expand bg-white">
                <div class="container-fluid px-3 px-lg-4">
                    <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar"
                        aria-expanded="true" aria-label="Toggle sidebar">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>

                    <div class="d-flex justify-content-center w-100">
                        <div class="greeting">မင်္ဂလာပါ</div>
                    </div>

                    <div class="navbar-actions ms-auto">
                        <button class="icon-button theme-toggle" type="button" data-theme-toggle
                            aria-label="Switch color theme" title="Switch color theme">
                            <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
                        </button>

                        <div class="dropdown">

                            <button class="profile-button dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <img class="avatar-img avatar-sm" src="../assets/images/avatar/avatar.jpg"
                                    alt="Admin Hasan">
                                <span class="profile-name d-none d-sm-inline">Admin Hasan</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="profile.html">Profile</a></li>
                                <li><a class="dropdown-item" href="settings.html">Account settings</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="login.html">Sign out</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="dashboard-content">

                @yield('content')

                @include('sweetalert::alert')

            </main>
        </div>
    </div>

    <script src=" {{ asset('wholeSaleMarket/assets/js/bootstrap.bundle.min.js') }} "></script>
    <script src=" {{ asset('wholeSaleMarket/assets/js/main.js') }} "></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        window.cropSyncUrl = "{{ route('api.sync.crops') }}";
    </script>

    <script src="{{ asset('wholeSaleMarket/assets/js/offline-sync.js') }}"></script>



    @yield('OnlineOffline')
    @yield('DeleteData')
</body>

</html>
