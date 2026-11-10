@extends('layouts.app')

@section('page-title', 'တစ်နေ့တာအရောင်း')

@section('breadcrumb', 'ပင်မစာမျက်နှာ / တစ်နေ့တာအရောင်းစာရင်းချုပ်')

@section('content')
<div class="container-fluid p-0">

    @include('partials.report-purchase-sale-tabs')

    <!-- Header (Print လုပ်သည့်အခါ မပေါ်စေရန် d-print-none သုံးထားသည်) -->
    <div class="d-md-flex flex-wrap justify-content-between align-items-center mb-4 gap-3 d-print-none">
        <div>
            <h3 class="fw-bold text-dark mb-1" style="line-height: 35px">
                <i class="bi bi-journal-text me-1 text-primary"></i> နေ့စဉ်သီးနှံအရောင်းစာရင်းချုပ်
            </h3>
            <p class="text-muted small mb-0 mt-2" style="line-height: 30px">သီးနှံအမျိုးအစားနှင့် ဝယ်ယူသူ (Customer) အလိုက် တစ်နေ့တာ ရောင်းချခဲ့သော စာရင်းများ</p>
        </div>
        <div class=" d-flex justify-content-end mt-md-0 mt-3">
            <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-printer me-1"></i> စာရင်းချုပ် Print ထုတ်မည်
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4 d-print-none">
        <!-- ပွဲရုံတွင်း စုစုပေါင်းလက်ကျန် -->
        <div class="col-md-4">
            <div class="p-3 border rounded-3 bg-white shadow-sm h-100">
                <div class="text-muted small">ပွဲရုံတွင်း စုစုပေါင်းလက်ကျန်</div>
                @if(isset($selectedCrop) && strtolower($selectedCrop->unit) === 'viss')
                    <!-- ပိဿာဆိုရင် ပိဿာရော တင်းရောပြမည် -->
                    <h3 class="fw-bold text-success my-1">
                        {{ number_format(($rawOpeningBalance ?? 0) + ($totalVissQuantity ?? 0), 2) }} <span class="fs-5 fw-semibold text-dark">ပိဿာ</span>
                    </h3>
                    <div class="badge bg-success-subtle text-success border border-success-subtle fw-normal mt-1">
                        ≈ {{ number_format($openingBalance + $totalPurchaseBasket , 2) }} တင်း
                    </div>
                @else
                    <!-- ပေါင်ဆိုရင် တင်း တစ်မျိုးတည်းသာပြမည် -->
                    <h3 class="fw-bold text-success my-1">
                        {{ number_format($openingBalance + $totalPurchaseBasket , 2) }} <span class="fs-5 fw-semibold text-dark">တင်း</span>
                    </h3>
                @endif

                <div class="small text-muted mt-1">
                    * အပ်နှံစာရင်းမပါသေးပါ
                </div>
            </div>
        </div>
        <!--ယနေ့ ရောင်းချမှု -->
        <div class="col-md-4">
            <div class="p-3 border rounded-3 bg-white shadow-sm h-100">
                <div class="text-muted small">ယနေ့ ရောင်းချမှု</div>
                @if(isset($selectedCrop) && strtolower($selectedCrop->unit) === 'viss')
                    <h3 class="fw-bold text-danger my-1">
                        {{ number_format($totalSaleVissQuantity ?? 0, 2) }} <span class="fs-5 fw-semibold text-dark">ပိဿာ</span>
                    </h3>
                    <div class="badge bg-danger-subtle text-danger border border-danger-subtle fw-normal mt-1">
                        ≈ {{ number_format($totalSaleBasket, 2) }} တင်း
                    </div>
                @else
                    <h3 class="fw-bold text-danger my-1">
                        {{ number_format($totalSaleBasket, 2) }} <span class="fs-5 fw-semibold text-dark">တင်း</span>
                    </h3>
                @endif
            </div>
        </div>
        <!--ယနေ့ လက်ကျန် (Closing Balance = ရှေ့လက်ကျန် + ဝယ် - ရောင်း) -->
        <div class="col-md-4">
            <div class="p-3 border rounded-3 bg-white shadow-sm bg-light h-100">
                <div class="text-muted small">ယနေ့ လက်ကျန်</div>
                @if(isset($selectedCrop) && strtolower($selectedCrop->unit) === 'viss')
                    <!-- ပိဿာအတွက် လက်ကျန်ကို View ထဲမှာ တိုက်ရိုက်တွက်ပြသည် -->
                    <h3 class="fw-bold text-primary my-1">
                        {{ number_format(($rawOpeningBalance ?? 0) + ($totalVissQuantity ?? 0) - ($totalSaleVissQuantity ?? 0), 2) }} <span class="fs-5 fw-semibold text-dark">ပိဿာ</span>
                    </h3>
                    <div class="badge bg-primary-subtle text-primary border border-primary-subtle fw-normal mt-1">
                        ≈ {{ number_format($closingBalance, 2) }} တင်း
                    </div>
                @else
                    <!-- ပေါင်အတွက် တင်းလက်ကျန်ကိုသာပြသည် -->
                    <h3 class="fw-bold text-primary my-1">
                        {{ number_format($closingBalance, 2) }} <span class="fs-5 fw-semibold text-dark">တင်း</span>
                    </h3>
                @endif
            </div>
        </div>
    </div>

    <!-- Filter Card (d-print-none) -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 d-print-none">
        <div class="card-body p-4">
            <form action="{{ route('reports#daily#sales') }}" method="GET" class="row g-3 align-items-end">
                <!-- ရက်စွဲ -->
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">ရက်စွဲအလိုက် (မှ - ထိ)</label>
                    <div class="input-group">
                        <input type="date" name="start_date" class="form-control me-2" value="{{ $startDate ?? now()->toDateString() }}">
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate ?? now()->toDateString() }}">
                    </div>
                </div>

                <!-- သီးနှံအမျိုးအစား -->
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">သီးနှံအမျိုးအစား</label>
                    <select name="crop_id" class="form-select" required onchange="this.form.submit()">
                        @foreach($crops as $crop)
                            <option value="{{ $crop->id }}" {{ $cropId == $crop->id ? 'selected' : '' }}>
                                {{ $crop->crop_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- ခလုတ်များ -->
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <i class="bi bi-search me-1"></i> ရှာဖွေမည်
                    </button>

                    <!-- Reset ခလုတ် -->
                    <a href="{{ route('reports#daily#sales') }}" class="btn btn-outline-secondary rounded-3 ms-2">
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
                <h3 class="fw-bold text-dark">မြို့ဘောင်ချာ အရောင်းနေ့စဉ်စာရင်းချုပ်</h3>
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
                            {{-- <th class="py-3">ဘောင်ချာနံပါတ်</th> --}}
                            <th class="py-3">ဝယ်ယူသူ</th>
                            <th class="py-3">လိပ်စာ</th>
                            <th class="py-3">သီးနှံအမျိုးအစား</th>
                            <th class="py-3 text-end">အရေအတွက်</th>
                            <th class="py-3 text-end">တင်း</th>
                            <th class="py-3 text-end">ရောင်းဈေး</th>
                            <th class="py-3 text-end">သင့်ငွေ (ကျပ်)</th>
                            <th class="py-3">မှတ်ချက်</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                {{-- <td class="fw-bold text-primary">{{ $item->invoice_no }}</td> --}}
                                <td class="fw-bold text-dark">{{ $item->customer_name }}</td>
                                <td>{{ $item->customer_address ?? '-' }}</td>
                                <td>{{ $item->crop_name }}</td>
                                <td class="text-end fw-semibold">
                                    {{ number_format($item->quantity, 2) }} <span class="small text-muted">{{ $item->display_unit }}</span>
                                    @if(strtolower($item->unit) === 'pound' && $item->bag_count > 0)
                                        <div class="small text-muted">({{ $item->bag_count }} အိတ်)</div>
                                    @endif
                                </td>

                                {{-- တွက်ပြီးသား တင်းအရေအတွက် --}}
                                <td class="text-end fw-semibold text-dark">
                                    {{ $item->basket_count > 0 ? number_format($item->basket_count, 2) : '-' }}
                                </td>

                                <td class="text-end">{{ number_format($item->sale_price, 2) }}</td>
                                <td class="text-end fw-bold text-dark">{{ number_format($item->gross_amount, 2) }}</td>
                                <td class="small text-muted">{{ $item->note ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">ဤရက်စွဲအတွက် အရောင်းစာရင်း မရှိသေးပါ။</td>
                            </tr>
                        @endforelse
                    </tbody>
                    {{-- @if($items->isNotEmpty())
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="5" class="text-end py-3">စုစုပေါင်း (Total):</td>
                                <td class="text-end text-primary fs-5">
                                    {{ number_format($totalSaleBasket, 2) }} တင်း
                                </td>
                                <td class="text-end">-</td>
                                <td class="text-end text-success fs-5">
                                    {{ number_format($totalAmount, 2) }} ကျပ်
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif --}}
                    @if($items->isNotEmpty())
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="4" class="text-end py-3">အရောင်း စုစုပေါင်း (Total):</td>

                                {{-- ယူနစ်အလိုက် စုစုပေါင်းပြရန် (Viss ဆိုလျှင် ပိဿာပြမည်၊ Pound ဆိုလျှင် - ပြမည်) --}}
                                <td class="text-end text-dark fs-5">
                                    @php
                                        $firstUnit = strtolower($items->first()->unit ?? '');
                                    @endphp
                                    @if($firstUnit === 'viss')
                                        {{ number_format($totalVissQuantity, 2) }} ပိဿာ
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- တင်း စုစုပေါင်း --}}
                                <td class="text-end text-primary fs-5">
                                    {{ number_format($totalSaleBasket, 2) }} တင်း
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
            font-size: 12px !important;
        }

        /* Navbar, Sidebar, Filter များနှင့် မလိုအပ်သည်များ ဖျောက်ရန် */
        nav, .sidebar, .top-navbar, footer, header, .d-print-none {
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
