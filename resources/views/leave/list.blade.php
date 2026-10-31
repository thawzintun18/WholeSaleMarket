@extends('layouts.app')

@section('page-title', 'Crops')
@section('breadcrumb')
    Stock / နေ့စဉ်သီးနှံလက်ကျန်စာရင်း
@endsection


@section('content')

    <div class=" d-md-flex justify-content-start align-items-center mb-4">

        <div>
            <h1 class="page-title mb-1">နေ့စဉ်သီးနှံလက်ကျန်စာရင်း</h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                နေ့စဉ် သီးနှံအဝင်အထွက်များကို တွက်ချက်ပြီး သီးနှံတစ်ခုချင်းစီ၏ လက်ကျန်အရေအတွက်နှင့် စုစုပေါင်းလက်ကျန်များကို
                ကြည့်ရှုစစ်ဆေးရန်။
            </p>
        </div>

    </div>


    {{-- Search --}}
    <div class="dashboard-card mb-4 shadow-sm">
        <form action="{{ route('Leave#list') }}" method="GET">
            <div class="row g-3">

                <div class="col-12 col-md-2">
                    <label class="form-label small fw-semibold text-muted">သီးနှံအမျိုးအစား</label>
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
                <div class="col-6 col-md-2">
                    <label class="form-label small fw-semibold text-muted">မှ ရက်စွဲ (From)</label>
                    <input type="date" name="from_date" class="form-control form-control-sm bg-light"
                        value="{{ request('from_date') }}">
                </div>

                <!-- To Date -->
                <div class="col-6 col-md-2">
                    <label class="form-label small fw-semibold text-muted">ထိ ရက်စွဲ (To)</label>
                    <input type="date" name="to_date" class="form-control form-control-sm bg-light"
                        value="{{ request('to_date') }}">
                </div>

                <div class="col-md-auto d-flex align-items-end">

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>
                        ရှာဖွေမည်
                    </button>

                </div>

                <div class="col-md-auto d-flex align-items-end">

                    <a href="{{ route('Leave#list') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        ပြန်လည်သတ်မှတ်မည်
                    </a>

                </div>

            </div>
        </form>

    </div>

    <div class="dashboard-card shadow-sm">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="mb-1">နေ့စဉ်သီးနှံလက်ကျန်စာရင်း</h5>
                <small class="text-muted">
                    ရက်စွဲ - {{ \Carbon\Carbon::parse($today)->format('d-m-Y') }}
                </small>
            </div>

            @if (!$todayRun)
                <form action="{{ route('Leave#create') }}" method="POST"
                    onsubmit="return confirm('ဒီနေ့အတွက် သီးနှံလက်ကျန်အားလုံး တွက်ချက်မှာ သေချာပါသလား?')">
                    @csrf

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-calculator me-1"></i>
                        လက်ကျန်တွက်ချက်ရန်
                    </button>
                </form>
            @else
                <button type="button" class="btn btn-outline-primary" disabled>
                    <i class="bi bi-check-circle me-1"></i>
                    တွက်ချက်ပြီးပါပြီ
                </button>
            @endif
        </div>

        {{-- Success / Error --}}
        @if (session('success'))
            <div class="alert alert-light border text-dark">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-light border text-dark">
                {{ session('error') }}
            </div>
        @endif

        {{-- Calculation Status --}}
        @if (!$todayRun)
            <div class="alert alert-light border text-dark">
                ဒီနေ့အတွက် လက်ကျန်မတွက်ချက်ရသေးပါ။
                လက်ကျန်တွက်ချက်ရန် Button ကို နှိပ်ပါ။
            </div>
        @else
            <div class="alert alert-light border text-dark">
                <i class="bi bi-check-circle me-1"></i>
                ဒီနေ့အတွက် လက်ကျန်နှင့် ပျမ်းမျှဈေးနှုန်း တွက်ချက်ပြီးပါပြီ။

                <span class="ms-2">
                    တွက်ချက်ချိန် -
                    {{ \Carbon\Carbon::parse($todayRun->calculated_at)->format('h:i A') }}
                </span>
            </div>
        @endif

        {{-- Stock Table --}}
        <div class="card border rounded-0">

            <div class="card-header bg-white">
                <strong>သီးနှံလက်ကျန်စာရင်း</strong>
            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover mb-0 align-middle">

                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 60px;">စဉ်</th>
                                <th>သီးနှံအမည်</th>
                                <th class="text-end">ဒီနေ့လက်ကျန်</th>
                                <th class="text-end">ဒီနေ့လက်ကျန် (တင်း)</th>
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

                                    <td>
                                        {{ $balance->crop_name ?? '-' }}
                                    </td>

                                    <td class="text-end">
                                        {{ number_format($balance->daily_balance, 0) }}
                                        @if ($balance->unit == 'viss')
                                            ပိဿာ
                                        @else
                                            ပေါင်
                                        @endif
                                    </td>

                                    {{-- ဒီနေ့လက်ကျန် --}}
                                    <td class="text-end">
                                        {{ number_format($dailyBasket, 0) }}
                                        တင်း
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
                                        {{ number_format($totalBasket, 0) }}
                                        တင်း
                                    </td>

                                    {{-- ပျမ်းမျှဈေးနှုန်း --}}
                                    <td class="text-end">
                                        {{ number_format($balance->average_cost, 0) }}
                                        <small class="text-muted">ကျပ်</small>
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7" class="text-center py-4">

                                        @if ($todayRun)
                                            ဒီနေ့အတွက် သီးနှံစာရင်း မရှိပါ။
                                        @else
                                            လက်ကျန်တွက်ချက်ပြီးမှ စာရင်းပေါ်လာပါမည်။
                                        @endif

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
