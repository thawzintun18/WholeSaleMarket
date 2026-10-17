@extends('layouts.app')

@section('page-title', 'Purchase Voucher')

@section('breadcrumb')
    Dashboard / ဝယ်ယူမှုအသစ် / ဘောင်ချာ
@endsection
@section('content')

    <div class="container-fluid">

        {{-- Action Buttons --}}
        <div class="d-flex justify-content-between align-items-center gap-2 mb-3 d-print-none">
            <a href="{{ route('purchases#create#page') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Purchase အသစ်
            </a>

            <button type="button" class="btn btn-primary" onclick="window.print()">
                <i class="bi bi-printer me-1"></i>
                ဘောက်ချာထုတ်မည်
            </button>
        </div>

        {{-- Voucher Card --}}

        <div class="card border shadow-lg bg-white print-card">
            <div class="card-body p-3 p-md-4">

                {{-- 1. ပွဲရုံအချက်အလက် --}}
                <div class="text-center border-bottom pb-3 mb-3">
                    <h4 class="fw-bold text-dark mb-1">
                        ဒေါ်ဝင်းမြင့် (မြရတနာ) ပွဲရုံ
                    </h4>

                    <div class=" text-dark">
                        သီးနှံနှင့် ပဲမျိုးစုံရောင်းဝယ်ရေး
                    </div>

                    <div class=" text-dark">
                        ဖုန်း - 09773771821 / 09778626912 / 09781782904
                    </div>

                    <div class="mt-2">
                        <span class="border rounded px-3 py-1 fw-bold">
                            အမှတ်(၅၉)၊ (၇)လမ်း၊ ရန်ကင်းရပ်၊ မကွေးမြို့။
                        </span>
                    </div>
                </div>

                {{-- 2. တောင်သူနှင့် ဘောက်ချာအချက်အလက် --}}
                <div class="row g-2 mb-3">

                    <div class="col-6">
                        <div class="border rounded p-2 h-100">
                            {{-- <div class="fw-bold border-bottom pb-1 mb-2">
                                တောင်သူအချက်အလက်
                            </div> --}}

                            <div class="small mb-1">
                                တောင်သူအမည် -
                                <span class="fw-bold">
                                    {{ $farmer->name }}
                                </span>
                            </div>

                            <div class="small">
                                နေရပ်/ဖုန်း -
                                {{ $farmer->village ?: '—' }} / {{ $farmer->phone ?: '—' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="border rounded p-2 h-100">
                            {{-- <div class="fw-bold border-bottom pb-1 mb-2">
                                ဘောက်ချာအချက်အလက်
                            </div> --}}

                            <div class="small mb-1">
                                ဘောက်ချာအမှတ် -
                                <span class="fw-bold">
                                    {{ $purchase->invoice_no }}
                                </span>
                            </div>

                            <div class="small mb-1">
                                နေ့စွဲ -
                                <span class="fw-semibold">
                                    {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d-m-Y') }} {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('h:i A') }}
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- 3. သီးနှံစာရင်း --}}
                <div class=" mb-5">
                    <table class="table table-bordered table-sm align-middle mb-0"
                        style="width: 100%; table-layout: fixed; font-size: 14px;">

                        <thead class="table-light text-center">
                            <tr>
                                <th style="width: 5%;">စဉ်</th>
                                <th style="width: 25%;">သီးနှံ / အမျိုးအစား</th>
                                <th style="width: 17%;">အလေးချိန်</th>
                                <th style="width: 10%;">တင်း</th>
                                <th style="width: 13%;">1တင်းဈေး</th>
                                <th style="width: 12%;">ပွဲခ</th>
                                <th style="width: 18%;">သီးနှံတန်ဖိုး</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($items as $index => $item)
                                @php
                                    $qpb = (float) ($item->quantity_per_basket ?? 0);

                                    $basketCount = $qpb > 0 ? (float) $item->quantity / $qpb : (float) $item->quantity;

                                    $unit = strtolower($item->unit ?? '');

                                    $displayUnit =
                                        $unit === 'viss' ? 'ပိဿာ' : ($unit === 'pound' ? 'ပေါင်' : $item->unit);
                                @endphp

                                <tr>
                                    <td class="text-center">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="text-start">
                                        <div class="fw-bold">
                                            {{ $item->crop_name ?? 'သီးနှံ' }}
                                        </div>

                                        <div>
                                            ({{ $gradeNames[$item->grade_id] ?? 'အမျိုးအစား' }})
                                        </div>

                                        @if ($item->note)
                                            <div class="small">
                                                မှတ်ချက် - {{ $item->note }}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="text-end">
                                        {{ number_format((float) $item->quantity, 2) }}
                                        <span>{{ $displayUnit }}</span>
                                    </td>

                                    <td class="text-end">
                                        {{ number_format($basketCount, 2) }}
                                    </td>

                                    <td class="text-end">
                                        {{ number_format((float) $item->actual_buy_price, 2) }}
                                    </td>

                                    <td class="text-end">
                                        {{ number_format((float) $item->commission_amount, 2) }}
                                    </td>

                                    <td class="text-end fw-bold">
                                        {{ number_format((float) $item->gross_amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- 4. ငွေစာရင်းချုပ် --}}
                <div class="row g-3">

                    {{-- သတိပြုရန် --}}
                    <div class="col-5">
                        <div class="border rounded p-2 small">
                            <div class="mb-2 small text-muted">
                                • အချက်အလက်များကို သေချာစစ်ဆေးပါ။
                            </div>

                            <div class=" small text-muted">
                                • မှားယွင်းမှုရှိပါက စာရင်းသွင်းသူထံ ချက်ချင်းအကြောင်းကြားပါ။
                            </div>
                        </div>
                    </div>

                    {{-- ငွေစာရင်း --}}
                    <div class="col-7">
                        <table class="table table-sm table-borderless mb-0" style="font-size: 15px;">
                            <tbody>
                                <tr>
                                    <td>ပွဲခနှုတ်ပြီး သီးနှံတန်ဖိုး</td>
                                    <td class="text-end fw-semibold">
                                        {{ number_format((float) $purchase->total_amount, 2) }} ကျပ်
                                    </td>
                                </tr>

                                <tr>
                                    <td>ကားခ စုစုပေါင်း</td>
                                    <td class="text-end fw-semibold">
                                        {{ number_format((float) $totalCarFee, 2) }} ကျပ်
                                    </td>
                                </tr>

                                <tr>
                                    <td>ကြိုသုံး / အခြားနုတ်ငွေ</td>
                                    <td class="text-end fw-semibold">
                                        -
                                        {{ number_format((float) ($purchase->advance_deduction + $purchase->other_deduction), 2) }}
                                        ကျပ်
                                    </td>
                                </tr>

                                <tr class="border-top">
                                    <td class="fw-bold pt-2">
                                        အသားတင်ကျသင့်ငွေ
                                    </td>
                                    <td class="text-end fw-bold pt-2">
                                        {{ number_format((float) $purchase->net_amount, 2) }} ကျပ်
                                    </td>
                                </tr>

                                <tr>
                                    <td>ပေးပြီးငွေ</td>
                                    <td class="text-end fw-semibold">
                                        {{ number_format((float) $purchase->total_paid, 2) }} ကျပ်
                                    </td>
                                </tr>

                                <tr class="border-top">
                                    <td class="fw-bold pt-2">
                                        ကျန်ငွေရှင်းရန်
                                    </td>
                                    <td class="text-end fw-bold pt-2">
                                        {{ number_format((float) $purchase->balance_amount, 2) }} ကျပ်
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>


                {{-- 5. အောက်ခြေစာ --}}
                <div class="text-center mt-3 pt-2 border-top small">
                    <small class=" text-muted">
                        "တောင်သူဦးကြီးများ သီးနှံအထွက်တိုး၍ ဈေးကောင်းရရှိကြပါစေ"
                        ဒေါ်ဝင်းမြင့် (မြရတနာ) မှ ဆုမွန်ကောင်းတောင်းအပ်ပါသည်။
                    </small>
                </div>

            </div>
        </div>



    </div>

@endsection
@push('styles')
    <style>
        @media print {
            @page {
                size: A5 landscape;
                margin: 5mm;
            }

            html,
            body {
                width: 100%;
                margin: 0 !important;
                padding: 0 !important;
            }

            body * {
                visibility: hidden !important;
            }

            .print-card,
            .print-card * {
                visibility: visible !important;
            }

            .print-card {
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                max-width: none !important;
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                background: white !important;
            }

            .print-card .card-body {
                padding: 0 !important;
            }

            .d-print-none {
                display: none !important;
            }

            .container,
            .container-fluid {
                width: 100% !important;
                max-width: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .table {
                width: 100% !important;
                font-size: 10px !important;
                margin-bottom: 0 !important;
            }

            .table th,
            .table td {
                padding: 3px !important;
                vertical-align: middle !important;
            }

            .row {
                --bs-gutter-x: 0.5rem;
            }

            .mb-5 {
                margin-bottom: 0.5rem !important;
            }

            .shadow-lg {
                box-shadow: none !important;
            }

            .print-card,
            .print-card table,
            .print-card tr {
                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }
        }
    </style>
@endpush
