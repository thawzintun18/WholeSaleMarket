<div class="mb-3 d-print-none">
    <ul class="nav gap-4" role="tablist">

        <li class="nav-item" role="presentation">
            <a href="{{ route('reports#daily#purchases') }}"
               class="text-decoration-none pb-2 fw-semibold d-inline-block {{ request()->routeIs('reports#daily#purchases') ? 'text-dark border-bottom border-dark border-2' : 'text-secondary opacity-75' }}">
                <i class="bi bi-cart-plus me-1"></i> တစ်နေ့တာအဝယ်
            </a>
        </li>

        <li class="nav-item" role="presentation">
            <a href="{{ route('reports#daily#sales') }}"
               class="text-decoration-none pb-2 fw-semibold d-inline-block {{ request()->routeIs('reports#daily#sales') ? 'text-dark border-bottom border-dark border-2' : 'text-secondary opacity-75' }}">
                <i class="bi bi-graph-up-arrow me-1"></i> တစ်နေ့တာအရောင်း
            </a>
        </li>

    </ul>
</div>