@extends('layouts.app')

@section('page-title', 'ပင်မစာမျက်နှာ (Dashboard)')
@section('breadcrumb', 'ပင်မစာမျက်နှာ')

@section('content')
    <div class="container-fluid p-0">

        {{-- ၁။ အဓိက စာရင်းချုပ်ကတ်များ (Key Performance Indicators) --}}
        <div class="row g-3 mb-4">
            {{-- ယနေ့ဝယ်ယူငွေ --}}
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-primary h-100 p-3">
                    <div class="small text-muted fw-bold mb-1"><i class="bi bi-cart-check me-1"></i> ယနေ့ ဝယ်ယူငွေ (တော)</div>
                    <div class="fs-4 fw-bold text-primary">
                        {{ number_format((float)($todayPurchaseTotal ?? 0), 2) }} <span class="fs-6 text-muted">ကျပ်</span>
                    </div>
                    <div class="small text-muted mt-2">ဘောင်ချာ အရေအတွက်: {{ $todayPurchaseCount ?? 0 }} စောင်</div>
                </div>
            </div>

            {{-- ယနေ့ရောင်းရငွေ --}}
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-success h-100 p-3">
                    <div class="small text-muted fw-bold mb-1"><i class="bi bi-shop me-1"></i> ယနေ့ ရောင်းရငွေ (မြို့)</div>
                    <div class="fs-4 fw-bold text-success">
                        {{ number_format((float)($todaySaleTotal ?? 0), 2) }} <span class="fs-6 text-muted">ကျပ်</span>
                    </div>
                    <div class="small text-muted mt-2">ဘောင်ချာ အရေအတွက်: {{ $todaySaleCount ?? 0 }} စောင်</div>
                </div>
            </div>

            {{-- တောင်သူများကို ပေးရန်ကျန်ငွေ စုစုပေါင်း --}}
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-danger h-100 p-3">
                    <div class="small text-muted fw-bold mb-1"><i class="bi bi-wallet2 me-1"></i> တောင်သူများကို ပေးရန်ကျန်ငွေ</div>
                    <div class="fs-4 fw-bold text-danger">
                        {{ number_format((float)($totalPendingBalance ?? 0), 2) }} <span class="fs-6 text-muted">ကျပ်</span>
                    </div>
                    <div class="small text-muted mt-2">ရရန်ရန်လက်ကျန် စုစုပေါင်း</div>
                </div>
            </div>

            {{-- အဝယ်ဆီမှရရန် ကျန်ငွေ  --}}
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-warning h-100 p-3">
                    <div class="small text-muted fw-bold mb-1"><i class="bi bi-cash-stack me-1"></i> အဝယ်ဆီမှရရန် ကျန်ငွေ </div>
                    <div class="fs-4 fw-bold text-warning-emphasis">
                        {{ number_format((float)($totalSalePendingBalance ?? 0), 2) }} <span class="fs-6 text-muted">ကျပ်</span>
                    </div>
                    <div class="small text-muted mt-2">ရှင်းရန်လက်ကျန် စုစုပေါင်း</div>
                </div>
            </div>
        </div>

        {{-- ၂။ လုပ်ငန်းသုံး အချက်အလက် ခွဲခြမ်းစိတ်ဖြာမှု ဂရပ်များ (Analytics Charts) --}}
        <div class="row g-3 mb-4">
            {{-- ၇ ရက်စာ အရောင်းအဝယ် နှိုင်းယှဉ်ဂရပ် --}}
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-bar-chart-line me-2 text-primary"></i> ဤသတင်းပတ် အရောင်းအဝယ် နှိုင်းယှဉ်ချက်</h6>
                        <span class="small text-muted">နေ့စဉ် (ကျပ်)</span>
                    </div>
                    <div style="height: 300px;">
                        <canvas id="weeklyTrendChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- သီးနှံအမျိုးအစားအလိုက် ဝယ်အား အချိုးအစား (Top Crops Share) --}}
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-4">
                    <div class="mb-3">
                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-pie-chart me-2 text-success"></i> သီးနှံအလိုက် ဝယ်ယူမှု အချိုးအစား</h6>
                    </div>
                    <div style="height: 300px;" class="d-flex justify-content-center align-items-center">
                        <canvas id="cropShareChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- ၃။ လစဉ် အရောင်းအဝယ်နှင့် ဝင်ငွေ/အမြတ် အခြေအနေ (Monthly Performance) --}}
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-graph-up-arrow me-2 text-warning"></i> ယခုနှစ် လအလိုက် အရောင်းအဝယ် ပမာဏ နှိုင်းယှဉ်ချက်</h6>
                        <span class="small text-muted">လအလိုက် (ကျပ်)</span>
                    </div>
                    <div style="height: 280px;">
                        <canvas id="monthlyPerformanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- ၄။ လတ်တလော ဘောင်ချာများ (Recent Transactions) --}}
        <div class="row g-3 mb-4">
            {{-- လတ်တလော တောဘောင်ချာ --}}
            <div class="col-12 col-xl-6">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                    <div class="card-header bg-white py-3 px-4 border-bottom border-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-receipt me-2 text-primary"></i> လတ်တလော ဝယ်ယူမှုများ (တောဘောင်ချာ)</h6>
                        <a href="{{ route('purchases#index') }}" class="small text-decoration-none fw-semibold">အားလုံးကြည့်ရန် <i class="bi bi-arrow-right"></i></a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-uppercase fs-7 text-muted">
                                <tr>
                                    <th class="ps-4">ရက်စွဲ</th>
                                    <th>တောင်သူအမည်</th>
                                    <th class="text-end">သင့်ငွေ</th>
                                    <th class="text-end pe-4">အခြေအနေ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPurchases ?? [] as $purchase)
                                    <tr>
                                        <td class="ps-4 small text-muted">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('m-d') }}</td>
                                        <td>
                                            <span class="fw-semibold text-dark">{{ $purchase->name }}</span>
                                            <div class="small text-muted">{{ $purchase->village }}</div>
                                        </td>
                                        <td class="text-end fw-bold">{{ number_format($purchase->net_amount, 2) }}</td>
                                        <td class="text-end pe-4">
                                            @if($purchase->balance_amount <= 0)
                                                <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill">ရှင်းပြီး</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger px-3 py-1 rounded-pill">ငွေကျန်</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">အချက်အလက် မရှိသေးပါ။</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- လတ်တလော မြို့ဘောင်ချာ --}}
            <div class="col-12 col-xl-6">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                    <div class="card-header bg-white py-3 px-4 border-bottom border-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-receipt-cutoff me-2 text-success"></i> လတ်တလော အရောင်းများ (မြို့ဘောင်ချာ)</h6>
                        <a href="{{ route('Sale#list') }}" class="small text-decoration-none fw-semibold">အားလုံးကြည့်ရန် <i class="bi bi-arrow-right"></i></a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-uppercase fs-7 text-muted">
                                <tr>
                                    <th class="ps-4">ရက်စွဲ</th>
                                    <th>ဝယ်ယူသူအမည်</th>
                                    <th class="text-end">သင့်ငွေ</th>
                                    <th class="text-end pe-4">အခြေအနေ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSales ?? [] as $sale)
                                    <tr>
                                        <td class="ps-4 small text-muted">{{ \Carbon\Carbon::parse($sale->sale_date ?? $sale->created_at)->format('m-d') }}</td>
                                        <td>
                                            <span class="fw-semibold text-dark">{{ $sale->name ?? 'အထွေထွေဖောက်သည်' }}</span>
                                        </td>
                                        <td class="text-end fw-bold">{{ number_format($sale->net_amount ?? 0, 2) }}</td>
                                        <td class="text-end pe-4">
                                            @if($sale->balance_amount <= 0)
                                                <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill">ရှင်းပြီး</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger px-3 py-1 rounded-pill">ငွေကျန်</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">အချက်အလက် မရှိသေးပါ။</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ဝင်ငွေအကောင်းဆုံး သီးနှံများ (Top Performing Crops Summary) --}}
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-4">

                    {{-- Header & Filter Dropdown --}}
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                        <div>
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-trophy me-2 text-warning"></i> ဝင်ငွေအကောင်းဆုံး သီးနှံစာရင်း (Top Performing Crops)
                            </h6>
                            <span class="small text-muted">ရွေးချယ်ထားသော ကာလအလိုက် ဝယ်ယူမှု ပမာဏ</span>
                        </div>

                        <div>
                            {{-- Filter Form --}}
                            <form action="{{ route('dashboard') }}" method="GET" class="d-flex flex-wrap gap-2">
                                {{-- လ ရွေးချယ်ရန် --}}
                                <select name="crop_month" class="form-select form-select-sm rounded-pill px-3" onchange="this.form.submit()">
                                    <option value="all" {{ request('crop_month') == 'all' ? 'selected' : '' }}>လအားလုံး (All Months)</option>
                                    @php
                                        $months = [
                                            1 => 'ဇန်နဝါရီ (Jan)', 2 => 'ဖေဖော်ဝါရီ (Feb)', 3 => 'မတ် (Mar)',
                                            4 => 'ဧပြီ (Apr)', 5 => 'မေ (May)', 6 => 'ဇွန် (Jun)',
                                            7 => 'ဇူလိုင် (Jul)', 8 => 'ဩဂုတ် (Aug)', 9 => 'စက်တင်ဘာ (Sep)',
                                            10 => 'အောက်တိုဘာ (Oct)', 11 => 'နိုဝင်ဘာ (Nov)', 12 => 'ဒီဇင်ဘာ (Dec)'
                                        ];
                                        $selectedMonth = request('crop_month', date('n'));
                                    @endphp
                                    @foreach($months as $num => $name)
                                        <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>

                                {{-- နှစ် ရွေးချယ်ရန် --}}
                                <select name="crop_year" class="form-select form-select-sm rounded-pill px-3" onchange="this.form.submit()">
                                    @php
                                        $currentYear = date('Y');
                                        $selectedYear = request('crop_year', $currentYear);
                                    @endphp
                                    @for($y = $currentYear; $y >= $currentYear - 4; $y--)
                                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                            {{ $y }} ခုနှစ်
                                        </option>
                                    @endfor
                                </select>
                            </form>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-uppercase fs-7 text-muted">
                                <tr>
                                    <th class="ps-4">စဉ်</th>
                                    <th>သီးနှံအမည်</th>
                                    <th class="text-center">စုစုပေါင်း အရေအတွက်</th>
                                    <th class="text-end pe-4">စုစုပေါင်း တန်ဖိုး (ကျပ်)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topCrops ?? [] as $index => $crop)
                                    <tr>
                                        <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <span class="fw-semibold text-dark">{{ $crop->crop_name }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill">
                                                {{ number_format($crop->total_qty, 2) }}
                                                {{ $crop->unit === 'pound' ? 'ပေါင်' : 'ပိဿာ' }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4 fw-bold text-success">
                                            {{ number_format($crop->total_amount, 2) }} ကျပ်
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">ရွေးချယ်ထားသော ကာလအတွင်း အချက်အလက် မရှိသေးပါ။</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- Chart.js Scripts --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ၁။ Weekly Trend Line Chart
        const ctxWeekly = document.getElementById('weeklyTrendChart').getContext('2d');
        new Chart(ctxWeekly, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels ?? []) !!},
                datasets: [{
                    label: 'ဝယ်ယူငွေ (တော)',
                    data: {!! json_encode($chartPurchaseData ?? []) !!},
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.05)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }, {
                    label: 'ရောင်းရငွေ (မြို့)',
                    data: {!! json_encode($chartSaleData ?? []) !!},
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.05)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // ၂။ Crop Share Doughnut Chart
        const ctxCrop = document.getElementById('cropShareChart').getContext('2d');
        new Chart(ctxCrop, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($cropLabels ?? []) !!},
                datasets: [{
                    data: {!! json_encode($cropData ?? []) !!},
                    backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#0dcaf0', '#6c757d']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // ၃။ Monthly Performance Bar Chart (လစဉ် နှိုင်းယှဉ်ဂရပ်)
        const ctxMonthly = document.getElementById('monthlyPerformanceChart').getContext('2d');
        new Chart(ctxMonthly, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']) !!},
                datasets: [{
                    label: 'လစဉ် ဝယ်ယူမှု (Purchase)',
                    data: {!! json_encode($monthlyPurchaseData ?? []) !!},
                    backgroundColor: 'rgba(13, 110, 253, 0.7)',
                    borderRadius: 6
                }, {
                    label: 'လစဉ် ရောင်းချမှု (Sale)',
                    data: {!! json_encode($monthlySaleData ?? []) !!},
                    backgroundColor: 'rgba(25, 135, 84, 0.7)',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
    @endpush
@endsection
