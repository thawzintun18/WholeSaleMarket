<aside class="sidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <i class="bi bi-shop me-2"></i>
        မြရတနာ ပွဲရုံ
    </div>


    <div class="sidebar-menu">
        @if (in_array(auth()->user()->role, ['admin', 'superadmin']))
            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid"></i>
                <span>ပင်မစာမျက်နှာ</span>
            </a>
        @endif

        {{-- Master --}}
        <div class="menu-title">
            အခြေခံစာရင်းများ
        </div>
        <a href="{{ route('DailyPrice#list') }}"
            class="{{ request()->routeIs('Crop#*', 'Quality#*', 'DailyPrice#*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i>
            <span>သီးနှံနှင့် ဈေးနှုန်းများ</span>
        </a>
        <a href="{{ route('farmers#index') }}"
            class="{{ request()->routeIs('farmers#*', 'Customer#*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span>ရောင်း/ဝယ် သူစာရင်း</span>
        </a>

        {{-- Purchase --}}
        <div class="menu-title">
            အရောင်းအ၀ယ်
        </div>
        <a href="{{ route('purchases#create#page') }}"
            class="{{ request()->routeIs('purchases#create#page', 'Sale#directPage', 'crop#deposits#createPage') ? 'active' : '' }}">
            <i class="bi bi-cart-plus"></i>
            <span>အဝယ်/အရောင်း လုပ်ရန်</span>
        </a>
        <a href="{{ route('purchases#index') }}"
            class="{{ request()->routeIs('purchases#*', 'Sale#*', 'crop#deposits#*') && !request()->routeIs('purchases#create#page', 'Sale#directPage', 'crop#deposits#createPage') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i>
            <span>ဘောင်ချာစာရင်းများ</span>
        </a>
        <a href="{{ route('FarmerDebt#list') }}" class="{{ request()->routeIs('FarmerDebt#*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i>
            <span>ကြိုသုံးငွေစာရင်း</span>
        </a>
        <a href="{{ route('CarFee#list') }}" class="{{ request()->routeIs('CarFee#*') ? 'active' : '' }}">
            <i class="bi-truck"></i>
            <span>ကားဘောင်ချာစာရင်း</span>
        </a>

        @if (in_array(auth()->user()->role, ['admin', 'superadmin']))
            {{-- Farmer Account --}}
            <div class="menu-title">
                Stock
            </div>
            <a href="{{ route('Leave#list') }}" class="{{ request()->routeIs('Leave#list') ? 'active' : '' }}">
                <i class="bi bi-wallet2"></i>
                <span>နေ့စဉ်သီးနှံလက်ကျန်စာရင်း</span>
            </a>

            {{-- Reports --}}
            <div class="menu-title">
                Reports
            </div>
            <a href="{{ route('reports#daily#purchases') }}"
                class="{{ request()->routeIs('reports#*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart"></i>
                <span>တစ်နေ့တာအဝယ်/အရောင်း စာရင်းချုပ်</span>
            </a>
        @endif

        <div class="menu-title">
            Backup
        </div>
        <a href="{{ route('Backup#list') }}" class="{{ request()->routeIs('Backup#list') ? 'active' : '' }}">
            <i class="bi bi-database-check"></i>
            <span>Backup စာရင်း</span>
        </a>
    </div>
</aside>
