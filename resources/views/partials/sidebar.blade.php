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
        <a href="{{ route('DailyPrice#list') }}"
            class="{{ request()->routeIs('Crop#*', 'Quality#*', 'DailyPrice#*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span>သီးနှံနှင့် ဈေးနှုန်းများ</span>
            </a>

        {{-- <a href="{{ route('Crop#list') }}" class="{{ request()->routeIs('Crop#*') ? 'active' : '' }}">

            <i class="bi bi-box-seam"></i>
            <span>သီနှံများ</span>

        </a>
        <a href="{{ route('Quality#list') }}" class="{{ request()->routeIs('Quality#*') ? 'active' : '' }}">

            <i class="bi bi-award"></i>
            <span>အရည်အသွေး</span>

        </a>
        <a href="{{ route('DailyPrice#list') }}" class="{{ request()->routeIs('DailyPrice#*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i>
            <span>တစ်နေ့ချင်းဈေး</span>
        </a> --}}

        <a href="{{ route('farmers#index') }}" class="{{ request()->routeIs('farmers#*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span>တောင်သူစာရင်း</span>
        </a>


        {{-- Pricing --}}
        <div class="menu-title">
            ဈေးနှုန်းများ
        </div>
        <a href="{{ route('FarmerDebt#list') }}" class="{{ request()->routeIs('FarmerDebt#*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i>
            <span>ကြိုသုံးငွေစာရင်း</span>
        </a>


        {{-- Purchase --}}
        <div class="menu-title">
            Vouchers
        </div>
        <a href="{{ route('purchases#index') }}" class="{{ request()->routeIs('purchases#*', 'Sale#*', 'crop#deposits#*') && !request()->routeIs('purchases#create#page', 'Sale#directPage', 'crop#deposits#createPage') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i>
            <span>ဘောင်ချာစာရင်းများ</span>
        </a>


        {{-- <a href="{{ route('purchases#index') }}" class="{{ request()->routeIs('purchases#*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i>
            <span>တောဘောင်ချာစာရင်း</span>
        </a> --}}
        {{-- <a href="{{ route('Sale#list') }}" class="{{ request()->routeIs('Sale#*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i>
            <span>မြို့ဘောင်ချာစာရင်း</span>
        </a> --}}

        <a href="{{ route('Sale#directPage') }}" class="{{ request()->routeIs('Sale#directPage') ? 'active' : '' }}">
            <i class="bi bi-cart-plus"></i>
            <span>အရောင်း ( မြို့ဘောင်ချာ )</span>
        </a>

        <a href="{{ route('purchases#create#page') }}"
            class="{{ request()->routeIs('purchases#create#page') ? 'active' : '' }}">
            <i class="bi bi-cart-plus"></i>
            <span>အဝယ် ( တောဘောင်ချာ )</span>
        </a>
        <a href="{{ route('crop#deposits#createPage') }}"
            class="{{ request()->routeIs('crop#deposits#createPage') ? 'active' : '' }}">
            <i class="bi bi-cart-plus"></i>
            <span>အပ်နှံ</span>
        </a>

        {{-- Farmer Account --}}
        {{-- <div class="menu-title">
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
        </a> --}}


        {{-- Expenses --}}
        {{-- <div class="menu-title">
            Expenses
        </div>
        <a href="#" class="{{ request()->routeIs('expenses.*') ? 'active' : '' }}">
            <i class="bi bi-wallet"></i>
            <span>Expenses</span>
        </a> --}}


        {{-- Reports --}}
        <div class="menu-title">
            Reports
        </div>
        <a href="{{ route('reports#daily#purchases') }}"
            class="{{ request()->routeIs('reports#*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart"></i>
            <span>တစ်နေ့တာအဝယ်</span>
        </a>

    </div>

</aside>
