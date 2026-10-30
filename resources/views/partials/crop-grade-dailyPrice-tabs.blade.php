
<div class="mb-3">
    <ul class="nav gap-4" role="tablist">

        <li class="nav-item" role="presentation">
            <a href="{{ route('DailyPrice#list') }}"
               class="text-decoration-none pb-2 fw-semibold d-inline-block {{ request()->routeIs('DailyPrice#*') ? 'text-dark border-bottom border-dark border-2' : 'text-secondary opacity-75' }}">
                <i class="bi bi-tags me-1"></i> တစ်နေ့ချင်းဈေး
            </a>
        </li>

        <li class="nav-item" role="presentation">
            <a href="{{ route('Crop#list') }}"
               class="text-decoration-none pb-2 fw-semibold d-inline-block {{ request()->routeIs('Crop#*') ? 'text-dark border-bottom border-dark border-2' : 'text-secondary opacity-75' }}">
                <i class="bi bi-box-seam me-1"></i> သီးနှံများ
            </a>
        </li>

        <li class="nav-item" role="presentation">
            <a href="{{ route('Quality#list') }}"
               class="text-decoration-none pb-2 fw-semibold d-inline-block {{ request()->routeIs('Quality#*') ? 'text-dark border-bottom border-dark border-2' : 'text-secondary opacity-75' }}">
                <i class="bi bi-award me-1"></i> အရည်အသွေး
            </a>
        </li>

    </ul>
</div>
