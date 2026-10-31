<div class="mb-3">
    <ul class="nav gap-4" role="tablist">

        <li class="nav-item" role="presentation">
            <a href="{{ route('purchases#create#page') }}"
               class="text-decoration-none pb-2 fw-semibold d-inline-block {{ request()->routeIs('purchases#create#page') ? 'text-dark border-bottom border-dark border-2' : 'text-secondary opacity-75' }}">
                <i class="bi bi-cart-plus me-1"></i> အဝယ် (တောဘောင်ချာ)
            </a>
        </li>

        <li class="nav-item" role="presentation">
            <a href="{{ route('Sale#directPage') }}"
               class="text-decoration-none pb-2 fw-semibold d-inline-block {{ request()->routeIs('Sale#directPage') ? 'text-dark border-bottom border-dark border-2' : 'text-secondary opacity-75' }}">
                <i class="bi bi-cart-plus me-1"></i> အရောင်း (မြို့ဘောင်ချာ)
            </a>
        </li>

        <li class="nav-item" role="presentation">
            <a href="{{ route('crop#deposits#createPage') }}"
               class="text-decoration-none pb-2 fw-semibold d-inline-block {{ request()->routeIs('crop#deposits#createPage') ? 'text-dark border-bottom border-dark border-2' : 'text-secondary opacity-75' }}">
                <i class="bi-bag-fill me-1"></i> သီးနှံအပ်ရန်
            </a>
        </li>

    </ul>
</div>