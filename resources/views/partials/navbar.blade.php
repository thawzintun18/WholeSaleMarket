<nav class="top-navbar d-flex justify-content-between align-items-center">

    {{-- Left --}}
    <div class="d-flex align-items-center">
        <div class="">
            <button type="button" class="btn btn-light d-md-none" id="sidebarToggle">
                <i class="bi bi-list fs-5"></i>
            </button>
        </div>

        <div class="small text-muted ps-2" style="line-height: 30px">
            @yield('breadcrumb', 'Dashboard')
        </div>

    </div>

    {{-- Right --}}
    <div class="dropdown">

        <button class="btn btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">

            @if (auth()->user()->role === 'admin')
                Admin
            @elseif (auth()->user()->role === 'staff')
                စာရင်းကိုင်
            @elseif (auth()->user()->role === 'superadmin')
                Super Admin
            @endif
        </button>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
             <li>
                <h6 class="dropdown-header fw-semibold text-dark">
                    <i class="bi bi-gear me-1"></i>
                    Setting
                </h6>
            </li>

            <li><hr class="dropdown-divider"></li>

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
