@extends('layouts.app')

@section('page-title', 'တစ်နေ့တာအဝယ်နှင့် အပ်နှံစာရင်း')

@section('breadcrumb', 'ပင်မစာမျက်နှာ / တစ်နေ့တာစာရင်းချုပ်')

@section('content')
    <div class="container-fluid p-0">

        @include('partials.report-purchase-sale-tabs')

        <!-- Header -->
        <div class="d-md-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="bi bi-journal-text me-1 text-primary"></i> သီးနှံဝယ်ယူမှုနှင့် အပ်နှံစာရင်းချုပ်
                </h3>
                <p class="text-muted small mb-0">ဝယ်ယူထားသောစာရင်းနှင့် အပ်နှံထားသောစာရင်းများကို သီးသန့်ခွဲခြားပြသခြင်း</p>
            </div>

            <!-- ဤ div အစတွင် d-print-none class ကို ထည့်ပေးခြင်းဖြင့် Print ထုတ်ချိန်တွင် လုံးဝ ပါမလာတော့ပါ -->
            <div class="d-print-none">
                <a href="{{ route('reports#daily#purchases#excel', request()->all()) }}"
                    class="btn btn-success rounded-pill px-4 shadow-sm" onclick="confirmExcelDownload(event, this)">
                    <i class="bi bi-file-earmark-excel me-1"></i> Excel ထုတ်မည်
                </a>
            </div>
        </div>

        <!-- Summary Cards တွင် ယူနစ်အလိုက် ပိဿာနှင့် တင်း တွဲပြရန် -->
        <div class="row g-3 mb-4 d-print-none">
            <!-- အရင်နေ့က လက်ကျန် -->
            <div class="col-md-3">
                <div class="p-3 border rounded-3 bg-white shadow-sm h-100">
                    <div class="text-muted small">အရင်နေ့က လက်ကျန် (Opening)</div>
                    @if (isset($selectedCrop) && strtolower($selectedCrop->unit) === 'viss')
                        <!-- ပိဿာ ဖြစ်ပါက ပိဿာကို ဦးစားပေးပြပြီး တင်းကို တွဲပြမည် -->
                        <h3 class="fw-bold text-dark my-1">
                            {{ number_format($rawOpeningBalance, 2) }} <span class="fs-5 fw-semibold text-dark">ပိဿာ</span>
                        </h3>
                        <div class="badge bg-secondary-subtle text-dark border border-secondary-subtle fw-normal mt-1">
                            ≈ {{ number_format($openingBalance, 2) }} တင်း
                        </div>
                    @else
                        <!-- ပေါင် (သို့မဟုတ်) အခြားယူနစ်ဖြစ်ပါက တင်းကိုသာ ပြမည် -->
                        <h3 class="fw-bold text-dark my-1">
                            {{ number_format($openingBalance, 2) }} <span
                                class="fs-5 fw-semibold text-secondary">တင်း</span>
                        </h3>
                    @endif
                    <div class="small text-muted mt-2">ပျမ်းမျှဈေး <span
                            class="text-dark fw-bold">{{ number_format($openingAverageCost, 2) }} ကျပ်</span></div>
                </div>
            </div>

            <!-- ယနေ့ ဝယ်ယူမှု -->
            <div class="col-md-3">
                <div class="p-3 border rounded-3 bg-white shadow-sm h-100">
                    <div class="text-muted small">ယနေ့ ဝယ်ယူမှု ပမာဏ</div>
                    @if (isset($selectedCrop) && strtolower($selectedCrop->unit) === 'viss')
                        <!-- ပိဿာ ဖြစ်ပါက ပိဿာကို ဦးစားပေးကြီးပြပြီး တင်းကို တွဲပြမည် -->
                        <h3 class="fw-bold text-primary my-1">
                            {{ number_format($totalVissQuantity, 2) }} <span class="fs-5 fw-semibold text-dark">ပိဿာ</span>
                        </h3>
                        <div class="badge bg-primary-subtle text-primary border border-primary-subtle fw-normal mt-1">
                            ≈ {{ number_format($totalPurchaseBasket, 2) }} တင်း
                        </div>
                    @else
                        <!-- ပေါင် (သို့မဟုတ်) အခြားယူနစ်ဖြစ်ပါက တင်းကိုသာ ပြမည် -->
                        <h3 class="fw-bold text-primary my-1">
                            {{ number_format($totalPurchaseBasket, 2) }} <span
                                class="fs-5 fw-semibold text-dark">တင်း</span>
                        </h3>
                    @endif
                </div>
            </div>

            <!-- ယနေ့ အပ်နှံထားဆဲ -->
            <div class="col-md-3">
                <div class="p-3 border rounded-3 bg-white shadow-sm h-100">
                    <div class="text-muted small">ယနေ့ အပ်နှံထားဆဲ ပမာဏ</div>
                    @if (isset($selectedCrop) && strtolower($selectedCrop->unit) === 'viss')
                        <h3 class="fw-bold text-warning my-1">
                            {{ number_format($totalDepositVissQuantity, 2) }} <span
                                class="fs-5 fw-semibold text-dark">ပိဿာ</span>
                        </h3>
                        <div class="badge bg-warning-subtle text-warning border border-warning-subtle fw-normal mt-1">
                            ≈ {{ number_format($totalDepositBasket, 2) }} တင်း
                        </div>
                    @else
                        <h3 class="fw-bold text-warning my-1">
                            {{ number_format($totalDepositBasket, 2) }} <span
                                class="fs-5 fw-semibold text-dark">တင်း</span>
                        </h3>
                    @endif
                </div>
            </div>

            <!-- ပွဲရုံတွင်း စုစုပေါင်းလက်ကျန် -->
            <div class="col-md-3">
                <div class="p-3 border rounded-3 bg-white shadow-sm h-100">
                    <div class="text-muted small">ပွဲရုံတွင်း စုစုပေါင်းလက်ကျန်</div>

                    @if (isset($selectedCrop) && strtolower($selectedCrop->unit) === 'viss')
                        <!-- ပိဿာ ဖြစ်ပါက ပိဿာကို ဦးစားပေးပြပြီး တင်းကို တွဲပြမည် (Blade ထဲမှာ variable နှစ်ခု တိုက်ရိုက်ပေါင်းသည်) -->
                        <h3 class="fw-bold text-success my-1">
                            {{ number_format(($rawOpeningBalance ?? 0) + ($totalVissQuantity ?? 0), 2) }} <span
                                class="fs-5 fw-semibold text-dark">ပိဿာ</span>
                        </h3>
                        <div class="badge bg-success-subtle text-success border border-success-subtle fw-normal mt-1">
                            ≈ {{ number_format($openingBalance + $totalPurchaseBasket, 2) }} တင်း
                        </div>
                    @else
                        <!-- ပေါင် (သို့မဟုတ်) အခြားယူနစ်ဖြစ်ပါက တင်းကိုသာ ပြမည် -->
                        <h3 class="fw-bold text-success my-1">
                            {{ number_format($openingBalance + $totalPurchaseBasket, 2) }} <span
                                class="fs-5 fw-semibold text-dark">တင်း</span>
                        </h3>
                    @endif

                    <div class="small text-muted mt-1">
                        * အပ်နှံစာရင်းမပါသေးပါ
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 d-print-none">
            <div class="card-body p-4">
                <form action="{{ route('reports#daily#purchases') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">ရက်စွဲ (မှ - ထိ)</label>
                        <div class="input-group">
                            <input type="date" name="start_date" class="form-control me-2"
                                value="{{ $startDate ?? now()->toDateString() }}">
                            <input type="date" name="end_date" class="form-control"
                                value="{{ $endDate ?? now()->toDateString() }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">သီးနှံအမျိုးအစား</label>
                        <select name="crop_id" class="form-select" required onchange="this.form.submit()">
                            @foreach ($crops as $crop)
                                <option value="{{ $crop->id }}" {{ $cropId == $crop->id ? 'selected' : '' }}>
                                    {{ $crop->crop_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Grade အလိုက်</label>
                        <select name="grade_id" class="form-select">
                            <option value="">Grade အားလုံး</option>
                            @foreach ($grades as $grade)
                                <option value="{{ $grade->id }}" {{ $gradeId == $grade->id ? 'selected' : '' }}>
                                    {{ $grade->grade_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary shadow-sm">
                            <i class="bi bi-search me-1"></i> ရှာဖွေမည်
                        </button>
                        <a href="{{ route('reports#daily#purchases') }}"
                            class="btn btn-outline-secondary rounded-3 ms-2">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Report Area -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <h4 class="fw-bold text-dark">သီးနှံဝယ်ယူမှုနှင့် အပ်နှံမှုစာရင်းချုပ်</h4>
                    <p class="text-muted small">ရက်စွဲ: {{ date('d-m-Y', strtotime($startDate)) }} မှ
                        {{ date('d-m-Y', strtotime($endDate)) }} အထိ</p>
                </div>


                <!-- ၂။ အပ်နှံထားဆဲ စာရင်း ဇယား (သီးသန့်) -->
                <div class="mb-3">
                    <h5 class="fw-bold text-warning fw-bold mb-3">
                        ပွဲရုံတွင် အပ်နှံထားဆဲ စာရင်းများ
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-1">
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="text-center" style="width: 50px;">စဉ်</th>
                                    <th>တောင်သူအမည်</th>
                                    <th>ကျေးရွာ</th>
                                    <th>အမျိုးအစား</th>
                                    <th class="text-end">အရေအတွက်</th>
                                    <th class="text-end">တင်း</th>
                                    <th class="text-end">မှန်းခြေဈေး</th>
                                    <th class="text-end">သင့်ငွေ</th>
                                    <th>မှတ်ချက်</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($depositItems as $index => $item)
                                    @php
                                        $rawUnit = strtolower($item->unit ?? '');
                                        $displayUnit =
                                            $rawUnit === 'viss'
                                                ? 'ပိဿာ'
                                                : ($rawUnit === 'pound'
                                                    ? 'ပေါင်'
                                                    : $item->unit);
                                        $qty = (float) $item->quantity;
                                        $bagCount = (int) ($item->bag_count ?? 0);
                                        $qtyPerBasket = (float) ($item->quantity_per_basket ?? 0);
                                        $calcQty = $rawUnit === 'pound' ? max(0, $qty - $bagCount * 1) : $qty;
                                        $basketCount = $qtyPerBasket > 0 ? $calcQty / $qtyPerBasket : 0;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="fw-bold">{{ $item->farmer_name }}</td>
                                        <td>{{ $item->farmer_village ?? '-' }}</td>
                                        <td>{{ $item->crop_name }} ({{ $item->grade_name }})</td>
                                        <td class="text-end">
                                            {{ number_format($qty, 2) }} {{ $displayUnit }}
                                            @if ($rawUnit === 'pound' && $bagCount > 0)
                                                <div class="small text-muted">({{ $bagCount }} အိတ်)</div>
                                            @endif
                                        </td>
                                        <td class="text-end fw-semibold">{{ number_format($basketCount, 2) }}</td>
                                        <td class="text-end">{{ number_format($item->actual_buy_price, 2) }}</td>
                                        <td class="text-end text-muted">-</td>
                                        <td class="small text-muted">{{ $item->note ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-3 text-muted">အပ်နှံထားဆဲ စာရင်း မရှိပါ။
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if ($depositItems->isNotEmpty())
                                <tfoot class="table-light fw-bold">
                                    <tr>
                                        <td colspan="4" class="text-end">အပ်နှံမှု စုစုပေါင်း</td>
                                        <td class="text-end text-dark fs-5">
                                            @php
                                                $firstUnit = strtolower($depositItems->first()->unit ?? '');
                                            @endphp
                                            @if ($firstUnit === 'viss')
                                                {{ number_format($totalDepositVissQuantity, 2) }} ပိဿာ
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-end text-warning fw-bold">
                                            {{ number_format($totalDepositBasket, 2) }} တင်း</td>
                                        <td colspan="3" class="text-muted small">ငွေရှင်းရန် ငွေပမာဏ မရှိသေးပါ</td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- ၁။ ဝယ်ယူထားသော စာရင်း ဇယား (သီးသန့်) -->
                <div class="mb-2">
                    <h5 class="fw-bold text-primary mb-3">
                        နေ့စဉ်ဝယ်ယူထားသော စာရင်းများ
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="text-center" style="width: 50px;">စဉ်</th>
                                    <th>တောင်သူအမည်</th>
                                    <th>ကျေးရွာ</th>
                                    <th>အမျိုးအစား</th>
                                    <th class="text-end">အရေအတွက်</th>
                                    <th class="text-end">တင်း</th>
                                    <th class="text-end">ဝယ်ဈေး</th>
                                    <th class="text-end">သင့်ငွေ (ကျပ်)</th>
                                    <th>မှတ်ချက်</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchaseItems as $index => $item)
                                    @php
                                        $rawUnit = strtolower($item->unit ?? '');
                                        $displayUnit =
                                            $rawUnit === 'viss'
                                                ? 'ပိဿာ'
                                                : ($rawUnit === 'pound'
                                                    ? 'ပေါင်'
                                                    : $item->unit);
                                        $qty = (float) $item->quantity;
                                        $bagCount = (int) ($item->bag_count ?? 0);
                                        $qtyPerBasket = (float) ($item->quantity_per_basket ?? 0);
                                        $calcQty = $rawUnit === 'pound' ? max(0, $qty - $bagCount * 1) : $qty;
                                        $basketCount = $qtyPerBasket > 0 ? $calcQty / $qtyPerBasket : 0;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="fw-bold">{{ $item->farmer_name }}</td>
                                        <td>{{ $item->farmer_village ?? '-' }}</td>
                                        <td>{{ $item->crop_name }} ({{ $item->grade_name }})</td>
                                        <td class="text-end">
                                            {{ number_format($qty, 2) }} {{ $displayUnit }}
                                            @if ($rawUnit === 'pound' && $bagCount > 0)
                                                <div class="small text-muted">({{ $bagCount }} အိတ်)</div>
                                            @endif
                                        </td>
                                        <td class="text-end fw-semibold">{{ number_format($basketCount, 2) }}</td>
                                        <td class="text-end">{{ number_format($item->actual_buy_price, 2) }}</td>
                                        <td class="text-end fw-bold">{{ number_format($item->gross_amount, 2) }}</td>
                                        <td class="small text-muted">{{ $item->note ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-3 text-muted">ဝယ်ယူထားသော စာရင်း မရှိပါ။
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if ($purchaseItems->isNotEmpty())
                                <tfoot class="table-light fw-bold">
                                    <tr>
                                        <td colspan="4" class="text-end py-3">အဝယ် စုစုပေါင်း (Total):</td>

                                        {{-- ယူနစ်အလိုက် စုစုပေါင်းပြရန် (Viss ဆိုလျှင် ပိဿာပြမည်၊ Pound ဆိုလျှင် မပြ / တင်းကိုသာပြမည်) --}}
                                        <td class="text-end text-dark fs-5">
                                            @php
                                                $firstUnit = strtolower($purchaseItems->first()->unit ?? '');
                                            @endphp
                                            @if ($firstUnit === 'viss')
                                                {{ number_format($totalVissQuantity, 2) }} ပိဿာ
                                            @else
                                                -
                                            @endif
                                        </td>

                                        {{-- တင်း စုစုပေါင်း (ယူနစ်မရွေး တင်းကို အမြဲပြမည်) --}}
                                        <td class="text-end text-primary fs-5">
                                            {{ number_format($totalPurchaseBasket, 2) }} တင်း
                                        </td>

                                        <td class="text-end">-</td>
                                        <td class="text-end text-success fs-5">{{ number_format($totalAmount, 2) }} ကျပ်
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- Grand Total Summary Box -->
                {{-- <div class="p-3 bg-light rounded-3 border d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark">ပွဲရုံတွင်းရှိ စုစုပေါင်း ကုန်ပမာဏ (ဝယ်ယူ + အပ်နှံ)</h5>
                <h4 class="fw-bold text-success mb-0">{{ number_format($totalPurchaseBasket + $totalDepositBasket, 2) }} တင်း</h4>
            </div> --}}

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function confirmExcelDownload(event, element) {
            event.preventDefault(); // Route သို့ ချက်ချင်းမသွားအောင် တားဆီးခြင်း
            let targetUrl = element.href;

            Swal.fire({
                title: 'Excel ဖိုင် ထုတ်ယူမည်',
                text: "ရွေးချယ်ထားသော စာရင်းအချက်အလက်များကို Excel ဖိုင်ဖြင့် Download ဆွဲမည်မှာ သေချာပါသလား?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754', // Bootstrap success color (green)
                cancelButtonColor: '#6c757d', // Bootstrap secondary color (gray)
                confirmButtonText: 'သေချာပါတယ်',
                cancelButtonText: 'မလုပ်တော့ပါ။'
            }).then((result) => {
                if (result.isConfirmed) {
                    // အတည်ပြုပါက URL သို့ ဆက်သွားပြီး Download ဆွဲမည်
                    window.location.href = targetUrl;
                }
            });
        }
    </script>
@endpush
