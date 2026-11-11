@extends('layouts.app')

@section('page-title', 'Crops')
@section('breadcrumb')
    Stock / နေ့စဉ်သီးနှံလက်ကျန်စာရင်း
@endsection
@section('content')
    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
        <div>
            <h1 class="page-title mb-1 fs-3 fs-md-2" style="line-height: 1.4">
                နေ့စဉ်သီးနှံလက်ကျန်စာရင်း
            </h1>

            <p class="text-muted mb-0 mt-1" style="line-height: 1.5">
                နေ့စဉ် သီးနှံအဝင်အထွက်များကို တွက်ချက်ပြီး သီးနှံတစ်ခုချင်းစီ၏ လက်ကျန်အရေအတွက်နှင့် စုစုပေါင်းလက်ကျန်များကို
                ကြည့်ရှုစစ်ဆေးရန်။
            </p>
        </div>

        <div class="d-flex justify-content-end w-100 w-md-auto">
            @if (!$hasStockBalance && !$hasStockBalanceRun)
                <a href="{{ route('Leave#directPage') }}" class="btn btn-dark px-4 rounded-pill shadow-sm w-md-auto text-nowrap">
                    <i class="bi bi-plus-lg me-1"></i>
                    သီးနှံလက်ကျန်ထည့်ရန်
                </a>
            @endif
        </div>
    </div>


    {{-- Search --}}
    <div class="dashboard-card mb-3 shadow-sm p-3 p-md-4">
        <form action="{{ route('Leave#list') }}" method="GET">
            <div class="row align-items-end g-2">

                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">သီးနှံအမျိုးအစား</label>
                    <select name="crop_id" class="form-select form-select-sm bg-light">
                        <option value="">သီးနှံအားလုံး</option>
                        @foreach ($crops as $crop)
                            <option value="{{ $crop->id }}" {{ request('crop_id') == $crop->id ? 'selected' : '' }}>
                                {{ $crop->crop_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- From Date -->
                <div class="col-6 col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">မှ ရက်စွဲ (From)</label>
                    <input type="date" name="from_date" class="form-control form-control-sm bg-light"
                        value="{{ request('from_date') }}">
                </div>

                <!-- To Date -->
                <div class="col-6 col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">ထိ ရက်စွဲ (To)</label>
                    <input type="date" name="to_date" class="form-control form-control-sm bg-light"
                        value="{{ request('to_date') }}">
                </div>

                <div class="col-12 col-md-auto d-flex flex-wrap gap-2 mt-2 mt-md-0">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill flex-md-grow-0">
                        <i class="bi bi-search me-1"></i>
                        ရှာဖွေမည်
                    </button>

                    <a href="{{ route('Leave#list') }}" class="btn btn-outline-secondary btn-sm text-center flex-fill flex-md-grow-0">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        ပြန်လည်သတ်မှတ်မည်
                    </a>
                </div>

            </div>
        </form>
    </div>

    <div class="dashboard-card shadow-sm p-3 p-md-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
            <div>
                <h5 class="mb-1" style="line-height: 1.4">နေ့စဉ်သီးနှံလက်ကျန်စာရင်း</h5>
                <small class="text-dark d-block mt-1 fw-bold fs-6">
                    ရက်စွဲ - {{ \Carbon\Carbon::parse($today)->format('d-M-Y') }}
                </small>
            </div>

            <div class="d-flex justify-content-end w-100 w-md-auto">
                @if ($canCalculateStock)
                    <form action="{{ route('Leave#create') }}" method="POST" id="calculateStockForm" class="w-100 w-md-auto">
                        @csrf
                        <button type="submit" class="btn btn-warning px-4 rounded-pill shadow-sm w-md-auto">
                            <i class="bi bi-calculator me-1"></i>
                            လက်ကျန်တွက်ချက်ရန်
                        </button>
                    </form>
                @else
                    <button type="button" class="btn btn-outline-warning px-4 rounded-pill shadow-sm w-md-auto" disabled>
                        <i class="bi bi-check-circle me-1"></i>
                        တွက်ချက်ပြီးပါပြီ
                    </button>
                @endif
            </div>
        </div>

        {{-- Success / Error --}}
        @if (session('success'))
            <div class="alert alert-light border text-dark py-2 mb-2">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-light border text-dark py-2 mb-2">
                {{ session('error') }}
            </div>
        @endif

        {{-- Calculation Status --}}
        @if (!$todayRun)
            <div class="alert alert-light border text-dark py-2 mb-3">
                ဒီနေ့အတွက် လက်ကျန်မတွက်ချက်ရသေးပါ။
                လက်ကျန်တွက်ချက်ရန် Button ကို နှိပ်ပါ။
            </div>
        @else
            <div class="alert alert-light border text-dark py-2 mb-3">
                <i class="bi bi-check-circle me-1"></i>
                ဒီနေ့အတွက် လက်ကျန်နှင့် ပျမ်းမျှဈေးနှုန်း တွက်ချက်ပြီးပါပြီ။

                <span class="ms-2">
                    တွက်ချက်ချိန် -
                    {{ \Carbon\Carbon::parse($todayRun->calculated_at)->format('h:i A') }}
                </span>
            </div>
        @endif

        {{-- Stock Table --}}
        <div class="card border rounded-0 mb-0">

            <div class="card-header bg-white py-2">
                <strong>သီးနှံလက်ကျန်စာရင်း</strong>
            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover mb-0 align-middle text-nowrap">

                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 60px;">စဉ်</th>
                                <th>နေ့စွဲ</th>
                                <th>သီးနှံအမည်</th>
                                <th class="text-end">လက်ကျန်စုစုပေါင်း</th>
                                <th class="text-end">လက်ကျန်စုစုပေါင်း (တင်း)</th>
                                <th class="text-end">ပျမ်းမျှဈေးနှုန်း</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($balances as $balance)
                                @php
                                    $qpb = (float) ($balance->quantity_per_basket ?? 0);

                                    // ပိဿာမှ တင်းသို့ ပြောင်းတွက်ခြင်း
                                    $dailyBasket = $qpb > 0 ? $balance->daily_balance / $qpb : 0;

                                    $totalBasket = $qpb > 0 ? $balance->total_balance / $qpb : 0;
                                @endphp

                                <tr>

                                    <td class="text-center">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="fw-bold">{{ \Carbon\Carbon::parse($balance->balance_date)->format('M-d') }}
                                    </td>

                                    <td>
                                        {{ $balance->crop_name ?? '-' }}
                                    </td>

                                    <td class="text-end fw-semibold">
                                        {{ number_format($balance->total_balance, 0) }}
                                        @if ($balance->unit == 'viss')
                                            ပိဿာ
                                        @else
                                            ပေါင်
                                        @endif
                                    </td>

                                    {{-- လက်ကျန်စုစုပေါင်း --}}
                                    <td class="text-end fw-semibold">
                                        {{ number_format($totalBasket, 2) }}
                                        တင်း
                                    </td>

                                    {{-- ပျမ်းမျှဈေးနှုန်း --}}
                                    <td class="text-end">
                                        {{ number_format($balance->average_cost, 2) }}
                                        <small class="text-muted">ကျပ်</small>
                                    </td>

                                </tr>

                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            @if ($todayRun)
                                                <h5 class="mb-1 text-secondary fw-bold">ဒီနေ့အတွက် သီးနှံစာရင်း မရှိပါ။</h5>
                                            @else
                                                <h5 class="mb-1 text-secondary fw-bold">လက်ကျန်တွက်ချက်ပြီးမှ စာရင်းပေါ်လာပါမည်။</h5>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>

                </div>

            </div>
        </div>

    </div>

@endsection
@push('scripts')
    <script>
        document.getElementById('calculateStockForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const form = this;

            Swal.fire({
                title: 'လက်ကျန်တွက်ချက်မလား?',
                text: 'ဒီနေ့အတွက် သီးနှံလက်ကျန်အားလုံးကို တွက်ချက်မှာ သေချာပါသလား?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'တွက်ချက်မည်',
                cancelButtonText: 'မလုပ်တော့ပါ',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
