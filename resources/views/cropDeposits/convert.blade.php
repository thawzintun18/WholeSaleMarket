@extends('layouts.app')

@section('page-title', 'CropDeposit')
@section('breadcrumb')
    အခြေခံစာရင်းများ / သီးနှံအပ်နှံ စာရင်း / သီးနှံအပ်နှံမှဘောင်ချာ
@endsection

@section('content')
    <div class="container py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="bi bi-arrow-repeat me-2 text-success"></i>သီးနှံအပ်နှံမှုကို ဝယ်ယူမှုဘောင်ချာသို့ ပြောင်းလဲခြင်း (Convert)
                </h3>
                <p class="text-muted small mb-0">တောင်သူလာရောက်ရောင်းချသည့်နေ့၏ ပေါက်ဈေးနှုန်းအမှန်ဖြင့် ချိန်ညှိပြီး ဘောင်ချာထုတ်ပါ</p>
            </div>
            <a href="{{ route('crop#deposits#index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-1"></i> နောက်သို့
            </a>
        </div>

        @if (session('error'))
            <div class="alert alert-danger shadow-sm rounded-3">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('crop#deposits#convert#purchase', $deposit->id) }}" method="POST" class="convert-form">
            @csrf

            <!-- Hidden Farmer ID -->
            <input type="hidden" id="farmer_id" name="farmer_id" value="{{ $deposit->farmer_id }}">

            <!-- တောင်သူနှင့် အပ်နှံမှု အချက်အလက် Card -->
            <div class="card border-1 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold my-1 text-dark">
                        <i class="bi bi-person-check me-2 text-primary"></i>တောင်သူနှင့် အပ်နှံမှု အချက်အလက်
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <span class="text-primary fw-bold">
                                <i class="bi bi-calendar-event me-1"></i>အပ်နှံရက် :
                                {{ date('d-M-Y h:i A', strtotime($deposit->deposit_date)) }}
                            </span>
                            <h4 class="fw-bold text-dark my-1" id="selected_farmer_name">{{ $deposit->farmer_name }}</h4>
                            <div class="text-muted small">
                                <i class="bi bi-telephone me-1"></i><span id="selected_farmer_phone">{{ $deposit->farmer_phone ?? 'ဖုန်းမရှိ' }}</span> |
                                ကျေးရွာ : <span id="selected_farmer_village">{{ $deposit->farmer_village }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <div class="text-secondary small">သယ်ဆောင်လာသည့် ကားနံပါတ်</div>
                            <div class="fw-bold text-dark fs-5">{{ $deposit->car_no ?? '-' }}</div>
                        </div>
                    </div>

                    <!-- လက်ရှိ အကြွေးစာရင်းများပြရန် -->
                    <div class="mt-4 pt-3 border-top">
                        <div class="text-secondary small fw-semibold mb-2">
                            <i class="bi bi-wallet2 me-1"></i> လက်ရှိအကြွေးစာရင်းများ:
                        </div>
                        <div id="selected_farmer_debts_list" class="d-flex flex-column gap-2">
                            @if(isset($farmerDebts) && $farmerDebts->count() > 0)
                                @foreach($farmerDebts as $debt)
                                    @php
                                        $balance = $debt->balance_amount ?? ($debt->amount - ($debt->settled_amount ?? 0));
                                        $typeText = $debt->debt_type == 'PurchaseAdvance' ? 'ကြိုသုံး' : 'တောကြွေး';
                                    @endphp
                                    <div class="p-2 border rounded bg-light d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-semibold small">{{ $typeText }}</div>
                                            <div class="text-muted" style="font-size: 0.85rem;">ရက်စွဲ: {{ $debt->debt_date }}</div>
                                        </div>
                                        <div class="text-end">
                                            <span class="fw-bold text-danger">{{ number_format($balance, 2) }} ကျပ်</span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-success small fw-semibold">အကြွေးမရှိပါ (ရှင်းလင်းသည်)</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- အပ်နှံထားသော သီးနှံများစာရင်း (ဈေးနှုန်းပြင်ရန်) -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-box-seam me-2 text-primary"></i>သီးနှံစာရင်းနှင့် ဈေးနှုန်းချိန်ညှိရန်
                    </h5>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="py-3">သီးနှံအမည်</th>
                                    <th class="py-3">အရေအတွက်</th>
                                    <th class="py-3">ယူနစ်</th>
                                    <th class="py-3">အပ်တုန်းက ဈေး</th>
                                    <th class="py-3" style="width: 250px;">ယနေ့ ဝယ်မည့်ဈေးအမှန် <span class="text-danger">*</span></th>
                                </tr>
                            </thead>
                            <!-- သီးနှံစာရင်း Table Body ထဲတွင် -->
                            <tbody>
                                @foreach ($items as $index => $item)
                                    <tr class="item-row"
                                        data-quantity="{{ $item->quantity }}"
                                        data-bag-count="{{ $item->bag_count ?? 0 }}"
                                        data-unit="{{ strtolower($item->unit ?? '') }}"
                                        data-commission="{{ $item->commission_amount ?? 0 }}"
                                        data-basket="{{ $item->quantity_per_basket ?? 0 }}">
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->crop_name }}
                                                <span class="badge bg-secondary bg-opacity-10 text-muted small">
                                                    {{ $item->grade_name }}
                                                </span>
                                            </div>
                                            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ number_format($item->quantity, 2) }}</div>
                                            @if (strtolower($item->unit) === 'pound' && $item->bag_count > 0)
                                                <div class="text-dark small">အိတ်ရေ: {{ $item->bag_count }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold">
                                                {{ strtolower($item->unit ?? '') === 'viss' ? 'ပိဿာ' : ($item->unit === 'pound' ? 'ပေါင်' : $item->unit) }}
                                            </div>
                                        </td>
                                        <td>
                                            {{ number_format($item->actual_buy_price, 2) }} ကျပ်
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" name="items[{{ $index }}][actual_buy_price]"
                                                    class="form-control fw-bold text-success price-input"
                                                    value="{{ old("items.{$index}.actual_buy_price", $item->actual_buy_price) }}"
                                                    step="0.01" min="0" required>
                                                <span class="input-group-text">ကျပ်</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- စုစုပေါင်းအကျဉ်းချုပ်နှင့် ငွေပေးချေမှု တွက်ချက်သည့်အပိုင်း -->
                    <div class="row justify-content-end mt-4 pt-3 border-top">
                        <div class="col-md-6 col-lg-5">
                            <div class="card bg-light border-0 shadow-sm">
                                <div class="card-body">

                                    <div class="d-flex justify-content-between mb-2">
                                        <span>သီးနှံစုစုပေါင်းဖိုး</span>
                                        <strong id="items_gross_total">0.00</strong>
                                    </div>

                                    <hr>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold text-danger">
                                            <i class="bi bi-wallet2 me-1"></i> နှုတ်ယူမည့် အကြွေးစာရင်းများ
                                        </label>

                                        <div id="voucher_debts_container" class="d-flex flex-column gap-2">
                                            @if(isset($farmerDebts) && $farmerDebts->count() > 0)
                                                @foreach($farmerDebts as $debt)
                                                    @php
                                                        $balance = $debt->balance_amount ?? ($debt->amount - ($debt->settled_amount ?? 0));
                                                        $typeText = $debt->debt_type == 'PurchaseAdvance' ? 'ကြိုသုံး' : 'တောကြွေး';
                                                    @endphp
                                                    <div class="p-2 border rounded bg-white d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <div class="fw-semibold small text-dark">{{ $typeText }} <span class="text-muted">({{ number_format($balance, 2) }} ကျန်)</span></div>
                                                        </div>
                                                        <div>
                                                            @if($debt->debt_type === 'PurchaseAdvance')
                                                                <div class="input-group input-group-sm" style="width: 150px;">
                                                                    <input type="text" name="debts[{{ $debt->id }}]" class="form-control debt-settle-input text-end fw-bold" value="{{ $balance }}" readonly>
                                                                </div>
                                                            @else
                                                                <div class="input-group input-group-sm" style="width: 150px;">
                                                                    <input type="number" name="debts[{{ $debt->id }}]" class="form-control debt-settle-input text-end fw-bold" value="{{ $balance }}" max="{{ $balance }}" min="0" step="any">
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-muted small">နုတ်ယူရန် အကြွေးမရှိပါ။</div>
                                            @endif
                                        </div>

                                        <div class="d-flex justify-content-between mt-2 text-danger small fw-semibold">
                                            <span>စုစုပေါင်း နုတ်မည့်အကြွေး</span>
                                            <span id="total_debt_deduction">0.00</span>
                                        </div>
                                    </div>

                                    <hr>

                                    <!-- ကားနံပါတ် / ကားခနုတ်ငွေ -->
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="car_no" class="form-label fw-semibold">ကားနံပါတ်</label>
                                            <input type="text" id="car_no" name="car_no" class="form-control"
                                                value="{{ old('car_no', $deposit->car_no) }}" placeholder="ကားနံပါတ်ထည့်ပါ" autocomplete="off">
                                        </div>
                                        <div class="col">
                                            <label for="car_fee_amount" class="form-label fw-semibold">ကားခအတွက် နုတ်ငွေ</label>
                                            <input type="number" id="car_fee_amount" name="car_fee_amount"
                                                class="form-control" value="{{ old('car_fee_amount', $deposit->car_fee_amount ?? 0) }}"
                                                step="0.01" min="0" inputmode="decimal" autocomplete="off">
                                        </div>
                                    </div>

                                    <hr>

                                    <!-- ပေးချေရမည့်ငွေ (Net Payable) -->
                                    <div class="mb-3 d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">ပေးချေရမည့်ငွေ</span>
                                        <strong class="fs-5 text-dark" id="net_payable_total">0.00</strong>
                                    </div>

                                    <hr>

                                    <!-- ပေးချေမည့်ငွေ (Paid Amount) -->
                                    <div class="mb-3">
                                        <label for="paid_amount" class="form-label fw-semibold">ပေးချေမည့်ငွေ</label>
                                        <input type="number" id="paid_amount" name="paid_amount"
                                            class="form-control text-end" value="{{ old('paid_amount', 0) }}"
                                            step="0.01" min="0" inputmode="decimal" autocomplete="off"
                                            placeholder="ပေးချေမည့်ငွေ ဖြည့်ပါ">
                                    </div>

                                    <hr>

                                    <!-- နောက်ဆုံး ပေးရန်ကျန်ငွေ (Final Balance) -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">နောက်ဆုံး ပေးရန်ကျန်ငွေ</span>
                                        <strong class="fs-5 text-success" id="final_payable_total">0.00</strong>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-end mb-5">
                <button type="submit" class="btn btn-success px-5 py-3 rounded-pill shadow">
                    <i class="bi bi-receipt me-1"></i> ဝယ်ယူမှုဘောင်ချာ ထုတ်မည်
                </button>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function calculateTotals() {
                let grossTotal = 0;

                // ၁။ သီးနှံတန်ဖိုး စုစုပေါင်း တွက်ချက်ခြင်း (Backend Logic နှင့် တူညီစေရန်)
                document.querySelectorAll('.item-row').forEach(row => {
                    const priceInput = row.querySelector('.price-input');
                    if (priceInput) {
                        let quantity = parseFloat(row.dataset.quantity) || 0;
                        let bagCount = parseInt(row.dataset.bagCount) || 0;
                        let unit = row.dataset.unit || '';
                        let commission = parseFloat(row.dataset.commission) || 0;
                        let quantityPerBasket = parseFloat(row.dataset.basket) || 0;
                        let actualPrice = parseFloat(priceInput.value) || 0;

                        // Pound ယူနစ်ဖြစ်လျှင် အိတ်ရေ နှုတ်ခြင်း
                        let calculationQty = quantity;
                        if (unit === 'pound') {
                            let deductionPerBag = 1; // လိုအပ်ပါက ချိန်ညှိနိုင်သည်
                            calculationQty = quantity - (bagCount * deductionPerBag);
                            if (calculationQty < 0) calculationQty = 0;
                        }

                        // တောင်း/ခြင်း နှုန်းထားဖြင့် စားခြင်း
                        let basketCount = quantityPerBasket > 0 ? (calculationQty / quantityPerBasket) : calculationQty;

                        // Commission နှုတ်ပြီးသားဈေး
                        let netPricePerUnit = actualPrice - commission;
                        if (netPricePerUnit < 0) netPricePerUnit = 0;

                        // Raw Amount နှင့် Gross Amount (ထောင်တန်ဖိုးအောက် ဖြတ်ခြင်း)
                        let rawAmount = basketCount * netPricePerUnit;
                        let grossAmt = Math.floor(rawAmount / 1000) * 1000;

                        grossTotal += grossAmt;
                    }
                });

                document.getElementById('items_gross_total').textContent = grossTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                // ၂။ စုစုပေါင်း နုတ်မည့်အကြွေး
                let totalDebtDed = 0;
                document.querySelectorAll('.debt-settle-input').forEach(input => {
                    totalDebtDed += parseFloat(input.value) || 0;
                });
                document.getElementById('total_debt_deduction').textContent = totalDebtDed.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                // ၃။ ကားခ
                let carFee = parseFloat(document.getElementById('car_fee_amount').value) || 0;

                // ၄။ ပေးချေရမည့်ငွေ (သီးနှံစုစုပေါင်း - အကြွေး - ကားခ)
                let netPayable = grossTotal - totalDebtDed - carFee;
                if (netPayable < 0) netPayable = 0;
                document.getElementById('net_payable_total').textContent = netPayable.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                // ၅။ ပေးချေမည့်ငွေ နှင့် နောက်ဆုံး ပေးရန်ကျန်ငွေ တွက်ချက်ခြင်း
                let paidAmount = parseFloat(document.getElementById('paid_amount').value) || 0;
                let finalPayable = netPayable - paidAmount;
                if (finalPayable < 0) finalPayable = 0;
                document.getElementById('final_payable_total').textContent = finalPayable.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            }

            // Event Listeners ချိတ်ဆက်ခြင်း
            document.querySelectorAll('.price-input, #car_fee_amount, .debt-settle-input, #paid_amount').forEach(element => {
                element.addEventListener('input', calculateTotals);
                element.addEventListener('change', calculateTotals);
            });

            // Page Load ချိန်တွင် အစဦး တွက်ချက်ပေးရန်
            calculateTotals();
        });

        // Form Submit စဉ် Spinner ပြသခြင်း
        document.querySelectorAll('.convert-form').forEach(form => {
            form.addEventListener('submit', function(event) {
                if (this.dataset.submitted === 'true') {
                    event.preventDefault();
                    return;
                }
                this.dataset.submitted = 'true';
                const button = this.querySelector('[type="submit"]');
                if (button) {
                    button.disabled = true;
                    button.innerHTML = `
                        <span class="spinner-border spinner-border-sm me-1"></span>
                        ဘောင်ချာထုတ်နေသည်...
                    `;
                }
            });
        });
    </script>
@endpush
