<div class="mb-3">
    <ul class="nav gap-4" role="tablist">

        <li class="nav-item" role="presentation">
            <a href="{{ route('farmers#index') }}"
               class="text-decoration-none pb-2 fw-semibold d-inline-block {{ request()->routeIs('farmers#*') ? 'text-dark border-bottom border-dark border-2' : 'text-secondary opacity-75' }}">
                <i class="bi bi-people me-1"></i> တောင်သူစာရင်း
            </a>
        </li>

        <li class="nav-item" role="presentation">
            <a href="{{ route('Customer#list') }}"
               class="text-decoration-none pb-2 fw-semibold d-inline-block {{ request()->routeIs('Customer#*') ? 'text-dark border-bottom border-dark border-2' : 'text-secondary opacity-75' }}">
                <i class="bi bi-person-badge me-1"></i> ဝယ်သူစာရင်း
            </a>
        </li>

    </ul>
</div>