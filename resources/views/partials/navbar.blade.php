<nav class="top-navbar">

    {{-- Left --}}
    <div>
        <button type="button" class="btn btn-light d-md-none" id="sidebarToggle">
            <i class="bi bi-list fs-5"></i>
        </button>
        {{-- <div class="fw-semibold text-dark">
            @yield('page-title', 'Dashboard')
        </div> --}}

        <div class="small text-muted">
            @yield('breadcrumb', 'Dashboard')
        </div>

        {{-- @extends('layouts.app')
            @section('title', 'Farmers')
            @section('page-title', 'Farmers')
            @section('breadcrumb')
                Dashboard / Farmers
            @endsection
            @section('content')
                Page content
            @endsection --}}
    </div>

    {{-- Right --}}
    <div class="dropdown">

        <button class="btn btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="bi bi-gear me-1"></i>
            Setting
        </button>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm">

            {{-- <li>
                <a class="dropdown-item" href="#">
                    <i class="bi bi-person me-2"></i>
                    Profile
                </a>
            </li>

            <li>
                <a class="dropdown-item" href="#">
                    <i class="bi bi-gear me-2"></i>
                    Settings
                </a>
            </li>

            <li>
                <hr class="dropdown-divider">
            </li> --}}

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i>
                        Logout
                    </button>
                </form>
            </li>

        </ul>

    </div>

</nav>
