@extends('layouts.app')

@section('page-title', 'Purchase Voucher')

@section('breadcrumb')
    Stock / ကားဘောင်ချာစာရင်း / ကားဘောင်ချာအသစ်ဖန်တီး / ဘောင်ချာ
@endsection
@section('content')

    <div class="container-fluid">

        {{-- Action Buttons --}}
        <div class="d-flex justify-content-between align-items-center gap-2 mb-3 d-print-none">

            <div class="">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-2 rounded-pill shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i>
                    {{-- Purchase အသစ် --}}
                    နောက်သို့
                </a>
            </div>


            <button type="button" class="btn btn-info rounded-pill shadow-sm px-4" onclick="window.print()">
                <i class="bi bi-printer me-1"></i>
                ဘောက်ချာထုတ်မည်
            </button>
        </div>

        {{-- Voucher Card --}}

        <div class="card border shadow-lg bg-white print-card">
            <div class="card-body p-3 p-md-4">

                {{-- 1. ပွဲရုံအချက်အလက် --}}
                <div class="text-center border-bottom pb-3 mb-3">
                    <div class="text-dark mb-1">
                        ဒေါ်ဝင်းမြင့်
                    </div>

                    <div class="text-dark mb-1">
                        ကိုချိုလေး + မစိန်စိန်မြင့်
                    </div>

                    <div class="text-dark fs-3 fw-bold mb-1">
                        မြရတနာ ပွဲရုံနှင့်ဆီစက်
                    </div>

                    <div class="text-dark mb-1">
                        အမှတ်စီ(၆၁+၁၇၂)၊ ရန်ကင်း(၇)လမ်း၊ မကွေးမြို့။
                    </div>

                    <div class="mt-2 fw-bold text-dark">
                        ၀၉-၄၄၂၀၆၄၃၂၉၊ ၀၉-၄၄၅၈၇၆၄၁၁၊ ၀၉-၄၀၁၅၅၉၁၁၉
                    </div>
                </div>

                {{-- 3. သီးနှံစာရင်း --}}
                <div class=" mb-5">
                    <table class="table table-bordered table-sm align-middle mb-0"
                        style="width: 100%; table-layout: fixed; font-size: 14px;">

                        <thead class="table-light text-center">
                            <tr>
                                <th style="width: 5%;">စဉ်</th>
                                <th style="width: 17%;">ရက်စွဲ</th>
                                <th style="width: 17%;">ကားနံပါတ်</th>
                                <th style="width: 25%;">ကားသမားအမည်</th>
                                <th style="width: 25%;">စုစုပေါင်းငွေ</th>

                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($carfee as $item)
                                <tr>
                                    <td class="text-center">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="text-center">
                                        <div class="fw-bold">
                                            {{ $item->payment_date }}
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        {{ $item->car_no }}
                                    </td>

                                    <td class="text-center">
                                        {{ $item->driver_name }}
                                    </td>

                                    <td class="text-center">
                                        {{ number_format($item->total_amount, 0) }}
                                        <small class="small text-secondary">ကျပ်</small>
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
                                    <td>သင့်ငွေ</td>
                                    <td class="text-end fw-semibold">
                                        {{ number_format($item->total_amount, 0) }}
                                        <small class="small text-secondary">ကျပ်</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>


                {{-- 5. အောက်ခြေစာ --}}
                <div class="text-center mt-3 pt-2 border-top small">
                    <small class=" text-muted">
                        "ကုန်ပစ္စည်းများ အချိန်မီ၊ ဘေးကင်းစွာ ပို့ဆောင်ပေးနိုင်ပြီး စီးပွားရေးလုပ်ငန်းများ အစဉ်အမြဲ
                        အောင်မြင်တိုးတက်ကြပါစေ။"
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
