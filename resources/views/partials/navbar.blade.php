<nav class="top-navbar d-md-flex">

    {{-- Left --}}
    <div class=" d-flex pt-3 pt-md-0">
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
    <div class="dropdown d-flex justify-content-end">

        <button class="btn btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="bi bi-gear me-1"></i>
            Setting
        </button>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm">

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
