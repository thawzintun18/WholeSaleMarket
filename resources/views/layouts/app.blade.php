<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'မြရတနာ ပွဲရုံ')
    </title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >
    <style>
        body {
            background-color: #f5f6f8;
            font-family: Arial, sans-serif;
        }

        .app-wrapper {
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #1f2937;

            position: fixed;
            left: 0;
            top: 0;

            z-index: 1000;

            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Sidebar Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #1f2937;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }

        .sidebar-brand {
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 20px;
            color: #fff;
            font-size: 20px;
            font-weight: 600;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
        }

        .sidebar-menu {
            padding: 20px 12px;
        }

        .menu-title {
            color: #9ca3af;
            font-size: 11px;
            font-weight: 600;
            margin: 18px 10px 8px;
            text-transform: uppercase;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 12px;
            margin-bottom: 4px;
            color: #d1d5db;
            text-decoration: none;
            border-radius: 7px;
            font-size: 14px;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: #374151;
            color: #fff;
        }

        .sidebar-menu i {
            font-size: 17px;
        }

        /* Main */
        .main-wrapper {
            margin-left: 250px;
            height: 100vh;
            overflow-y: auto;
        }

        /* Navbar */
        .top-navbar {
            height: 70px;
            background-color: #fff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
        }

        .page-content {
            padding: 25px;
        }

        .page-title {
            font-size: 22px;
            font-weight: 600;
            color: #111827;
        }

        /* Cards */
        .dashboard-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 20px;
        }

        /* =========================
        Sidebar Responsive
        ========================= */

        .sidebar {
            transition: margin-left 0.25s ease;
        }

        /* Overlay */
        .sidebar-overlay {
            display: none;
        }


        /* Mobile */
        @media (max-width: 768px) {

            .sidebar {
                margin-left: -250px;
                transition: margin-left 0.25s ease;
                z-index: 1050;
            }

            .sidebar.show {
                margin-left: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.35);
                z-index: 1040;
            }

            .sidebar-overlay.show {
                display: block;
            }

            .main-wrapper {
                margin-left: 0;
            }

        }
    </style>

    @stack('styles')
</head>

<body>

    <div class="app-wrapper">

        {{-- Sidebar --}}
        @include('partials.sidebar')
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <div class="main-wrapper">

            {{-- Navbar --}}
            @include('partials.navbar')

            {{-- Page Content --}}
            <main class="page-content">
                @yield('content')
            </main>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
    {{-- ထည့်သုံးမယ်ဆိုရင် push('scripts') --}}
    <script>
        const sidebar = document.querySelector('.sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        sidebarToggle?.addEventListener('click', function () {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
        });

        sidebarOverlay?.addEventListener('click', function () {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });
    </script>
</body>

</html>
