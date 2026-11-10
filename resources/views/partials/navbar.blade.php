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
            {{ auth()->user()->name }}
            {{-- @if (auth()->user()->role === 'admin')
                {{ auth()->user()->name }}
            @elseif (auth()->user()->role === 'staff')
                {{ auth()->user()->name }}
            @elseif (auth()->user()->role === 'superadmin')
                {{ auth()->user()->name }}
            @endif --}}
        </button>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
             <li>
                <h6 class="dropdown-header fw-semibold text-dark">
                    <i class="bi bi-gear me-1"></i>
                    Setting
                </h6>
            </li>

            @if (auth()->user()->role === 'superadmin')
                <li>
                    <a class=" dropdown-item" href="{{ route('users#index') }}" class="{{ request()->routeIs('users#index') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Staff / Admin စာရင်း</span>
                    </a>
                </li>
            @endif

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
