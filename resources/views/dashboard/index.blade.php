@extends('layouts.app')

@section('page-title', 'ပင်မစာမျက်နှာ (Dashboard)')

@section('breadcrumb', 'ပင်မစာမျက်နှာ')

@section('content')
    <div class="container-fluid p-0">

        {{-- စာရင်းချုပ်ကတ်များ (Summary KPI Cards) --}}
        <div class="row g-3 mb-4">

            {{-- ယနေ့ဝယ်အား --}}
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-primary h-100 p-3">
                    <div class="small text-muted fw-bold mb-1"><i class="bi bi-cart-check me-1"></i> ယနေ့ ဝယ်ယူငွေ (တော)</div>
                    <div class="fs-4 fw-bold text-primary">
                        {{ number_format((float)($todayPurchaseTotal ?? 0), 2) }} <span class="fs-6 text-muted">ကျပ်</span>
                    </div>
                    <div class="small text-muted mt-2">ဘောင်ချာ အရေအတွက်: {{ $todayPurchaseCount ?? 0 }} စောင်</div>
                </div>
            </div>

            {{-- ယနေ့ရောင်းအား --}}
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-success h-100 p-3">
                    <div class="small text-muted fw-bold mb-1"><i class="bi bi-shop me-1"></i> ယနေ့ ရောင်းရငွေ (မြို့)</div>
                    <div class="fs-4 fw-bold text-success">
                        {{ number_format((float)($todaySaleTotal ?? 0), 2) }} <span class="fs-6 text-muted">ကျပ်</span>
                    </div>
                    <div class="small text-muted mt-2">ဘောင်ချာ အရေအတွက်: {{ $todaySaleCount ?? 0 }} စောင်</div>
                </div>
            </div>

            {{-- ပေးရန်ကျန်ငွေ စုစုပေါင်း --}}
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-danger h-100 p-3">
                    <div class="small text-muted fw-bold mb-1"><i class="bi bi-wallet2 me-1"></i> တောင်သူများကို ပေးရန်ကျန်ငွေ</div>
                    <div class="fs-4 fw-bold text-danger">
                        {{ number_format((float)($totalPendingBalance ?? 0), 2) }} <span class="fs-6 text-muted">ကျပ်</span>
                    </div>
                    <div class="small text-muted mt-2">ရှင်းရန်ကျန် ဘောင်ချာများ</div>
                </div>
            </div>

            {{-- ရစရာရှိငွေ / ကြိုသုံးငွေ --}}
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-warning h-100 p-3">
                    <div class="small text-muted fw-bold mb-1"><i class="bi bi-cash-stack me-1"></i> တောင်သူကြိုသုံးငွေ (Advances)</div>
                    <div class="fs-4 fw-bold text-warning-emphasis">
                        {{ number_format((float)($totalAdvanceDebt ?? 0), 2) }} <span class="fs-6 text-muted">ကျပ်</span>
                    </div>
                    <div class="small text-muted mt-2">လက်ကျန် စုစုပေါင်း</div>
                </div>
            </div>

        </div>


        {{-- ၂။ ဇယားဂရပ်များ (Charts Section using Chart.js) --}}
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

            {{-- သီးနှံအမျိုးအစားအလိုက် ဝယ်အား ප්‍රතිခိုင်နှုန်း (Pie Chart) --}}
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


        {{-- ၃။ လတ်တလော ဆောင်ရွက်ထားသော ဘောင်ချာများ (Recent Transactions) --}}
        <div class="row g-3">

            {{-- လတ်တလော တောဘောင်ချာများ --}}
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
                                        <td class="ps-4 small text-muted">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('m-d') }}</td> {{-- h:i A --}}
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

            {{-- လတ်တလော မြို့ဘောင်ချာများ --}}
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
                                            {{-- <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill">ပြီးမြောက်</span> --}}
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

    </div>

    {{-- Chart.js Script (ဂရပ်များပေါ်လာစေရန်) --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ၁။ Weekly Trend Line Chart
        const ctxWeekly = document.getElementById('weeklyTrendChart').getContext('2d');
        const weeklyTrendChart = new Chart(ctxWeekly, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']) !!},
                datasets: [{
                    label: 'ဝယ်ယူငွေ (တော)',
                    data: {!! json_encode($chartPurchaseData ?? [0,0,0,0,0,0,0]) !!},
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.05)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }, {
                    label: 'ရောင်းရငွေ (မြို့)',
                    data: {!! json_encode($chartSaleData ?? [0,0,0,0,0,0,0]) !!},
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
                plugins: {
                    legend: { position: 'bottom' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // ၂။ Crop Share Doughnut Chart
        const ctxCrop = document.getElementById('cropShareChart').getContext('2d');
        const cropShareChart = new Chart(ctxCrop, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($cropLabels ?? ['နှမ်း', 'ပဲတီစိမ်း', 'မြေပဲ', 'နေကြာ']) !!},
                datasets: [{
                    data: {!! json_encode($cropData ?? [40, 25, 20, 15]) !!},
                    backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
    @endpush
@endsection
