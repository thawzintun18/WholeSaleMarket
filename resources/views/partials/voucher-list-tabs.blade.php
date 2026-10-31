
<div class="mb-3">
    <ul class="nav gap-4" role="tablist">

        <li class="nav-item" role="presentation">
            <a href="{{ route('purchases#index') }}"
               class="text-decoration-none pb-2 fw-semibold d-inline-block {{ request()->routeIs('purchases#*') && !request()->routeIs('purchases#create#page') ? 'text-dark border-bottom border-dark border-2' : 'text-secondary opacity-75' }}">
                <i class="bi bi-tree me-1"></i> တောဘောင်ချာစာရင်း
            </a>
        </li>

        <li class="nav-item" role="presentation">
            <a href="{{ route('Sale#list') }}"
               class="text-decoration-none pb-2 fw-semibold d-inline-block {{ request()->routeIs('Sale#*') && !request()->routeIs('Sale#directPage') ? 'text-dark border-bottom border-dark border-2' : 'text-secondary opacity-75' }}">
                <i class="bi bi-building me-1"></i> မြို့ဘောင်ချာစာရင်း
            </a>
        </li>

        <li class="nav-item" role="presentation">
            <a href="{{ route('crop#deposits#index') }}"
               class="text-decoration-none pb-2 fw-semibold d-inline-block {{ request()->routeIs('crop#deposits#*') ? 'text-dark border-bottom border-dark border-2' : 'text-secondary opacity-75' }}">
                <i class="bi-bag-fill me-1"></i> အပ်နှံစာရင်း
            </a>
        </li>

    </ul>
</div>
