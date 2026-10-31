@extends('layouts.app')

@section('page-title', 'တစ်နေ့တာအဝယ်')

@section('breadcrumb', 'ပင်မစာမျက်နှာ / တစ်နေ့တာအဝယ်စာရင်းချုုပ်')

@section('content')
<div class="container-fluid p-0">
    
    @include('partials.report-purchase-sale-tabs')

    <!-- Header (Print လုပ်သည့်အခါ မပေါ်စေရန် d-print-none သုံးထားသည်) -->
    <div class="d-md-flex flex-wrap justify-content-between align-items-center mb-4 gap-3 d-print-none">
        <div>
            <h3 class="fw-bold text-dark mb-1" style="line-height: 35px">
                <i class="bi bi-journal-text me-2 text-primary"></i>နေ့စဉ်သီးနှံစာရင်းချုပ် (Daily Purchase Report)
            </h3>
            <p class="text-muted small mb-0 mt-2" style="line-height: 30px">သီးနှံအမျိုးအစားနှင့် Grade အလိုက် တစ်နေ့တာ ဝယ်ယူခဲ့သော စာရင်းများ</p>
        </div>
        <div class=" d-flex justify-content-end mt-md-0 mt-3">
            <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-printer me-1"></i> စာရင်းချုပ် Print ထုတ်မည်
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4 d-print-none">
        <div class="col-md-4">
            <div class="p-3 border rounded-3 bg-white shadow-sm">
                <div class="text-muted small">စုစုပေါင်း ဘောင်ချာအရေအတွက်</div>
                <h4 class="fw-bold text-dark mb-0">{{ number_format($totalPurchases) }} ခု</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 border rounded-3 bg-white shadow-sm">
                <div class="text-muted small">စုစုပေါင်း ဝယ်ယူမှုပမာဏ</div>
                <h4 class="fw-bold text-danger mb-0">{{ number_format($totalQuantity, 2) }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 border rounded-3 bg-white shadow-sm">
                <div class="text-muted small">စုစုပေါင်း သင့်ငွေ (ကျပ်)</div>
                <h4 class="fw-bold text-success mb-0">{{ number_format($totalAmount, 2) }} ကျပ်</h4>
            </div>
        </div>
    </div>

    <!-- Filter Card (d-print-none) -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 d-print-none">
        <div class="card-body p-4">
            <form action="{{ route('reports#daily#purchases') }}" method="GET" class="row g-3 align-items-end">
                <!-- ရက်စွဲ -->
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">ရက်စွဲအလိုက် (မှ - ထိ)</label>
                    <div class="input-group">
                        <input type="date" name="start_date" class="form-control me-2" value="{{ $startDate ?? now()->toDateString() }}">
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate ?? now()->toDateString() }}">
                    </div>
                </div>

                <!-- သီးနှံအမျိုးအစား -->
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">သီးနှံအမျိုးအစား</label>
                    <select name="crop_id" class="form-select" required onchange="this.form.submit()">
                        @foreach($crops as $crop)
                            <option value="{{ $crop->id }}" {{ $cropId == $crop->id ? 'selected' : '' }}>
                                {{ $crop->crop_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Grade အလိုက် Filter -->
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Grade အလိုက်</label>
                    <select name="grade_id" class="form-select">
                        <option value="">Grade အားလုံး</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}" {{ $gradeId == $grade->id ? 'selected' : '' }}>
                                {{ $grade->grade_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- ခလုတ်များ -->
                <div class="col-md-3">
                        <button type="submit" class="btn btn-primary shadow-sm">
                            <i class="bi bi-search me-1"></i> ရှာဖွေမည်
                        </button>

                        <!-- Reset ခလုတ်အသစ် ထည့်ရန် -->
                        <a href="{{ route('reports#daily#purchases') }}" class="btn btn-outline-secondary rounded-3 ms-2">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                        </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Printable Report Area -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-body p-4 p-md-5">
            <!-- Print Header (Screen ပေါ်တွင် သေးပြီး Print ထုတ်မှ ပိုပေါ်လွင်စေရန်) -->
            <div class="text-center mb-4">
                <h3 class="fw-bold text-dark">ပွဲရုံသီးနှံဝယ်ယူမှု နေ့စဉ်စာရင်းချုပ်</h3>
                <p class="text-muted mb-1">
                    ရက်စွဲ:
                    <span class="fw-bold text-dark">{{ date('d-m-Y', strtotime($startDate)) }}</span>
                    မှ
                    <span class="fw-bold text-dark">{{ date('d-m-Y', strtotime($endDate)) }}</span>
                    အထိ
                </p>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light text-secondary small text-uppercase">
                        <tr>
                            <th class="py-3 text-center" style="width: 60px;">စဉ်</th>
                            <th class="py-3">တောင်သူအမည်</th>
                            <th class="py-3">ကျေးရွာ</th>
                            <th class="py-3">အမျိုးအစား</th>
                            <th class="py-3 text-end">အရေအတွက်</th>
                            <th class="py-3 text-end">ဝယ်ယူသည့်ဈေး</th>
                            <th class="py-3 text-end">သင့်ငွေ (ကျပ်)</th>
                            <th class="py-3">မှတ်ချက်</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item)
                            @php
                                $rawUnit = strtolower($item->unit ?? '');
                                $displayUnit = $rawUnit === 'viss' ? 'ပိဿာ' : ($rawUnit === 'pound' ? 'ပေါင်' : $item->unit);
                            @endphp
                            <tr>
                                <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                <td class="fw-bold text-dark">{{ $item->farmer_name }}</td>
                                <td>{{ $item->farmer_village ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-dark border">
                                        {{ $item->crop_name }} - {{ $item->grade_name }}
                                    </span>
                                </td>
                                @php
                                    $rawUnit = strtolower($item->unit ?? '');
                                    $displayUnit = $rawUnit === 'viss' ? 'ပိဿာ' : ($rawUnit === 'pound' ? 'ပေါင်' : $item->unit);
                                @endphp

                                <td class="text-end fw-semibold">
                                    {{ number_format($item->quantity, 2) }} <span class="small text-muted">{{ $displayUnit }}</span>

                                    {{-- ယူနစ်က pound ဖြစ်ပြီး အိတ်ရေရှိမှသာ ပေါ်မည် (Viss ဆိုလျှင် လုံးဝမပေါ်ပါ) --}}
                                    @if($rawUnit === 'pound' && $item->bag_count > 0)
                                        <div class="small text-primary">({{ $item->bag_count }} အိတ်)</div>
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format($item->actual_buy_price, 2) }}</td>
                                <td class="text-end fw-bold text-dark">{{ number_format($item->gross_amount, 2) }}</td>
                                <td class="small text-muted">{{ $item->note ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                    ဤရက်စွဲအတွက် ဝယ်ယူထားသော စာရင်း မရှိသေးပါ။
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($items->isNotEmpty())
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="4" class="text-end py-3">စုစုပေါင်း (Total):</td>
                                <td class="text-end text-danger">
                                    {{ number_format($totalQuantity, 2) }}
                                    @if($items->contains('unit', 'pound'))
                                        <div class="small text-secondary mt-1">
                                            အိတ်စုစုပေါင်း:
                                            {{ number_format($totalBagCount) }} အိတ်
                                        </div>
                                    @endif
                                </td>
                                <td class="text-end">-</td>
                                <td class="text-end text-success fs-5">{{ number_format($totalAmount, 2) }} ကျပ်</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
    @media print {
        /* A4 စာရွက်အတိုင်း ချိန်ညှိရန်နှင့် ဘေးအနားသတ်များ သတ်မှတ်ရန် */
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        body {
            background-color: white !important;
            color: black !important;
            font-size: 12px !important; /* စာသားအရွယ်အစားကို စာရွက်ပေါ်မှာ အဆင်ပြေစေရန် */
        }

        /* Navbar, Sidebar, Filter များနှင့် မလိုအပ်သည်များ ဖျောက်ရန် */
        nav, .sidebar, .navbar, footer, header, .d-print-none {
            display: none !important;
        }

        /* Card ပုံစံများကို ဖြုတ်ရန် */
        .card, .card-body {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
            background: transparent !important;
        }

        .container-fluid, .container {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        /* Table ဇယားကွက် စာရွက်ပေါ်တွင် အပြည့်ပေါ်စေရန် */
        .table-responsive {
            overflow: visible !important;
        }

        table {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        th, td {
            padding: 6px 8px !important;
            font-size: 11px !important;
        }
    }
</style>
@endpush
