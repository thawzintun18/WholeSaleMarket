<aside class="sidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <i class="bi bi-shop me-2"></i>
        မြရတနာ ပွဲရုံ
    </div>


    <div class="sidebar-menu">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">

            <i class="bi bi-grid"></i>
            <span>ပင်မစာမျက်နှာ</span>

        </a>


        {{-- Master --}}
        <div class="menu-title">
            အခြေခံစာရင်းများ
        </div>

        <a href="{{ route('Crop#list') }}" class="{{ request()->routeIs('Crop#list') ? 'active' : '' }}">

            <i class="bi bi-box-seam"></i>
            <span>သီနှံများ</span>

        </a>

        <a href="" class="{{ request()->routeIs('grades.*') ? 'active' : '' }}">

            <i class="bi bi-award"></i>
            <span>အရည်အသွေး</span>

        </a>

        <a href="" class="{{ request()->routeIs('farmers.*') ? 'active' : '' }}">

            <i class="bi bi-people"></i>
            <span>တောင်သူစာရင်း</span>

        </a>


        {{-- Pricing --}}
        <div class="menu-title">
            Pricing
        </div>

        <a href="" class="{{ request()->routeIs('daily-prices.*') ? 'active' : '' }}">

            <i class="bi bi-tags"></i>
            <span>တစ်နေ့ချင်းဈေး</span>

        </a>


        {{-- Purchase --}}
        <div class="menu-title">
            Purchase
        </div>

        <a href="#" class="{{ request()->routeIs('purchases.create') ? 'active' : '' }}">

            <i class="bi bi-cart-plus"></i>
            <span>New Purchase</span>

        </a>

        <a href="#" class="{{ request()->routeIs('purchases.index') ? 'active' : '' }}">

            <i class="bi bi-receipt"></i>
            <span>Purchase List</span>

        </a>


        {{-- Farmer Account --}}
        <div class="menu-title">
            Farmer Account
        </div>

        <a href="#" class="{{ request()->routeIs('advances.*') ? 'active' : '' }}">

            <i class="bi bi-wallet2"></i>
            <span>Advances</span>

        </a>

        <a href="#" class="{{ request()->routeIs('payments.*') ? 'active' : '' }}">

            <i class="bi bi-cash-stack"></i>
            <span>Payments</span>

        </a>

        <a href="#" class="{{ request()->routeIs('outstanding.*') ? 'active' : '' }}">

            <i class="bi bi-credit-card"></i>
            <span>Outstanding</span>

        </a>


        {{-- Expenses --}}
        <div class="menu-title">
            Expenses
        </div>

        <a href="#" class="{{ request()->routeIs('expenses.*') ? 'active' : '' }}">

            <i class="bi bi-wallet"></i>
            <span>Expenses</span>

        </a>


        {{-- Reports --}}
        <div class="menu-title">
            Reports
        </div>

        <a href="#" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">

            <i class="bi bi-bar-chart"></i>
            <span>Reports</span>

        </a>

    </div>

</aside>
