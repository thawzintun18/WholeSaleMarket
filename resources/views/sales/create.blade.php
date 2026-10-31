@extends('layouts.app')

@section('page-title', 'ဝယ်ယူမှုအသစ်')

@section('breadcrumb', 'ပင်မစာမျက်နှာ / ဝယ်ယူမှု / အရောင်းဘောင်ချာအသစ်ဖန်တီး')

@section('content')

    <div class="container-fluid p-0">

        @include('partials.purchase-sale-deposit-tabs')

        {{-- Page Header --}}
        <div class="d-md-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1" style="line-height: 35px">သီးနှံအရောင်းဘောင်ချာအသစ် ဖန်တီးမည်</h1>
                <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                    အရောင်းစာရင်းများကို သေချာစွာဖြည့်စွက်ပါ ( ဝယ်ယူမှုစာရင်းကို ဆက်မလုပ်တော့ပဲ အစကနေပြန်စမည်ဆိုလျှင်
                    အသစ်ပြန်စမည်ကိုနှိပ်ပြီး ပြန်စတင်နိုင်ပါသည် )
                </p>
            </div>

            <div class=" d-flex justify-content-end">
                <a href="{{ route('Sale#directPage') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise"></i> အသစ်ပြန်စမည်
                </a>
            </div>
        </div>

        {{-- Farmer Selection --}}
        <div class="card border-1 shadow-lg mb-4">
            <div class="card-header bg-white py-3">
                <div class="d-md-flex justify-content-between align-items-center ">
                    <div class=" ">
                        <h5 class="mb-0 fw-bold my-1">
                            <i class="bi bi-person-check me-2 text-primary"></i>
                            ဝယ်သူရွေးချယ်ခြင်း
                        </h5>
                    </div>

                    <div class=" d-flex justify-content-end mt-md-0 mt-2">
                        <button type="button" class=" btn btn-success my-1" style="height: 40px;"
                            data-bs-toggle="modal" data-bs-target="#newCustomerModal">
                            <i class="bi bi-person-plus me-1"></i>
                            ဝယ်သူအသစ်ထည့်မည်
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <div class="row g-3 align-items-end">
                    <div class="col-lg-12">
                        <label for="customer_search" class="form-label fw-semibold">
                            ဝယ်သူအမည် / ဖုန်းနံပါတ် / ကုဒ်
                            <span class="text-danger">*</span>
                        </label>

                        <div class="position-relative mb-3">
                            <div class="input-group" style="height: 40px;">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search"></i>
                                </span>

                                <input type="text" id="customer_search" class="form-control"
                                    placeholder="ဝယ်သူအချက်အလက် ထည့်ပြီးအလွယ်တကူ ရှာဖွေပြီးရွေးချယ်နိုင်သည် ( မရှိပါက အသစ်ထည့်ပါ )"
                                    autocomplete="off">

                                <button type="button" class="btn btn-outline-secondary" id="clear_customer">
                                    ပယ်ဖျက်မည်
                                </button>
                            </div>

                            {{-- Search Results --}}
                            <div id="customer_results" class="list-group position-absolute w-100 shadow"
                                style="z-index: 1050; display: none; max-height: 300px; overflow-y: auto;">
                            </div>
                        </div>

                        <small class="text-muted">
                            ရှိပြီးသားဝယ်သူကို ရှာဖွေရွေးချယ်ပါ
                            ( မရှိပါက အသစ်ထည့်နိုင်သည် )
                        </small>
                    </div>
                </div>

                {{-- Selected Show Customer --}}
                <div id="selected_customer_box"
                    class="border border-success border-opacity-25 rounded-3 p-3 p-md-4 mt-4 bg-white shadow-sm"
                    style="display: none;">

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                        {{-- Customer Information --}}
                        <div class="d-flex align-items-center gap-3 flex-grow-1">

                            <div class="rounded-circle bg-success bg-opacity-10 text-success
                                    d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width: 60px; height: 60px;">
                                <i class="bi bi-person-fill fs-2"></i>
                            </div>

                            <div class="flex-grow-1">

                                <div class="text-success small fw-semibold mb-1">
                                    <i class="bi bi-check-circle-fill me-1"></i>
                                    ရွေးချယ်ထားသော ဝယ်သူ
                                </div>

                                <h4 class="fw-bold mb-3" id="selected_customer_name"></h4>

                                <div class="d-flex flex-wrap gap-3 gap-md-4">

                                    <div>
                                        <div class="text-secondary small mb-1">
                                            <i class="bi bi-telephone me-1"></i>
                                            ဖုန်းနံပါတ်
                                        </div>
                                        <div class="fw-semibold fs-6" id="selected_customer_phone">
                                        </div>
                                    </div>

                                    <div class="border-start"></div>

                                    <div>
                                        <div class="text-secondary small mb-1">
                                            <i class="bi bi-geo-alt me-1"></i>
                                            လိပ်စာ
                                        </div>
                                        <div class="fw-semibold fs-6" id="selected_customer_address">
                                        </div>
                                    </div>

                                </div>

                                <div class="text-secondary mt-3">
                                    <small>
                                        ၀ယ်သူကုဒ် -
                                        <span id="selected_customer_code"></span>
                                    </small>
                                </div>

                            </div>
                        </div>

                        {{-- Change Farmer --}}
                        <button type="button" id="change_customer" class="btn btn-outline-success flex-shrink-0">
                            <i class="bi bi-arrow-repeat me-1"></i>
                            ပြန်ရွေးမည်
                        </button>

                    </div>
                </div>


                <div id="customer_error" class="alert alert-danger mt-3 mb-0" style="display: none;">
                </div>

            </div>
        </div>

        {{-- Average Price  --}}
        <div class="card border-1 shadow-lg mb-4">

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card">
                <div class="card-body">

                    <h5 class="mb-3">
                        ဒီနေ့ Average Price
                        ({{ \Carbon\Carbon::parse($today)->format('d-m-Y') }})
                    </h5>

                    @if (!$isCalculated)

                        <p>
                            ဒီနေ့အတွက် Average Price မတွက်ရသေးပါ။
                        </p>

                        <form action="{{ route('Sale#calculateTodayAveragePrices') }}" method="POST"
                            onsubmit="return confirm('ဒီနေ့ဝယ်ထားသော သီးနှံအားလုံး၏ Average Price ကို တွက်ချက်မလား?')">
                            @csrf

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-calculator me-1"></i>
                                ဒီနေ့အတွက် သီးနှံအားလုံး၏ Average Price တွက်ရန်
                            </button>
                        </form>
                    @else
                        <p class="text-success">
                            ဒီနေ့အတွက် Average Price တွက်ပြီးပါပြီ။
                        </p>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>စဉ်</th>
                                        <th>သီးနှံအမည်</th>
                                        <th class="text-end">ဝယ်ယူငွေစုစုပေါင်း</th>
                                        <th class="text-end">Stock လက်ကျန်</th>
                                        <th class="text-end">တစ်ယူနစ်ဈေး</th>
                                        <th class="text-end">တစ်တင်းဈေး</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($averagePrices as $price)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>

                                            <td>{{ $price->crop_name }}</td>

                                            <td class="text-end">
                                                {{ number_format($price->total_gross_amount, 0) }}
                                                <small class="small text-secondary">ကျပ်</small>
                                            </td>

                                            <td class="text-end">
                                                {{ number_format($price->quantity_on_hand, 0) }}
                                                @if ($price->unit == 'viss')
                                                    ပိဿာ
                                                @else
                                                    ပေါင်
                                                @endif
                                            </td>

                                            <td class="text-end">
                                                {{ number_format($price->average_cost, 0) }}
                                                <small class="small text-secondary">ကျပ်</small>
                                            </td>

                                            <td class="text-end">
                                                {{ number_format($price->average_basket_price, 0) }}
                                                <small class="small text-secondary">ကျပ်</small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                ဒီနေ့ Average Price စာရင်းမရှိပါ။
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    @endif

                </div>
            </div>

        </div>

        <form id="sale_form" action="{{ route('Sale#create') }}" method="POST">
            @csrf

            {{-- Purchase Items: သီးနှံစာရင်းထည့်သွင်းခြင်း --}}
            <div class="card border-1 shadow-lg mb-4">

                {{-- Header --}}
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <div>
                            <h5 class="mb-1 fw-bold">
                                <i class="bi bi-basket2 me-2 text-primary"></i>
                                သီးနှံအ၀ယ်စာရင်းထည့်သွင်းခြင်း
                            </h5>

                            <small class="text-muted mt-2">
                                ၀ယ်သူဆီကိုရောင်းချမည့် သီးနှံများကို တစ်မျိုးချင်းထည့်သွင်းပါ။
                            </small>
                        </div>

                        <span class="badge bg-primary fs-6" id="item_count">
                            သီးနှံ ၀ မျိုး
                        </span>

                    </div>
                </div>

                <div class="card-body p-4">

                    {{-- သီးနှံအချက်အလက်ထည့်ရန် --}}
                    <div class="border rounded-3 p-3 p-md-4 mb-4">

                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-plus-circle me-1 text-success"></i>
                            သီးနှံအသစ်ထည့်ရန်
                        </h6>

                        <div class="row g-3">

                            {{-- သီးနှံရွေးချယ်ရန် --}}
                            <div class="col-lg-6">
                                <label for="crop_id" class="form-label fw-semibold">
                                    သီးနှံအမည် <span class="text-danger">*</span>
                                </label>

                                <select id="crop_id" class="form-select">
                                    <option value="">သီးနှံရွေးချယ်ပါ</option>

                                    @foreach ($crops as $crop)
                                        <option value="{{ $crop->id }}" data-unit="{{ $crop->unit }}"
                                            data-commission="{{ $crop->commission_amount ?? 0 }}"
                                            data-quantity-per-basket="{{ $crop->quantity_per_basket ?? '' }}">
                                            {{ $crop->crop_name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <!-- quantity_per_basket ကို သိမ်းမယ့် Hidden Input -->
                            <input type="hidden" id="quantity_per_basket" value="">

                            {{-- အလေးချိန် / အရေအတွက် --}}
                            <div class="col-md-6 col-lg-3">
                                <label for="quantity" class="form-label fw-semibold">
                                    အရေအတွက်
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" id="quantity" class="form-control" min="0.001" step="0.001"
                                    placeholder="ဥပမာ - 100">
                            </div>

                            {{-- ယူနစ် --}}
                            <div class="col-md-6 col-lg-3">
                                <label for="unit" class="form-label fw-semibold">
                                    ယူနစ်
                                </label>

                                <input type="text" id="unit" class="form-control" readonly
                                    placeholder="သီးနှံရွေးချယ်ပါ">
                            </div>

                            {{-- ယနေ့ရည်ညွှန်းဈေး --}}
                            <div class="col-md-6 col-lg-3">
                                <label for="reference_price" class="form-label fw-semibold">
                                     ယနေ့ပျမ်းမျှဈေး {{-- ( နောက်ဆုံးသတ်မှတ်ထားသော ပျမ်းမျှ ဈေးကိုပြထားခြင်းဖြစ်သည် ) --}}
                                </label>

                                <input type="text" id="reference_price" class="form-control" min="0"
                                    step="0.01" placeholder="ယနေ့သတ်မှတ်ဈေး" readonly>
                            </div>

                            {{-- အမှန်တကယ်ဝယ်ဈေး --}}
                            <div class="col-md-6 col-lg-3">
                                <label for="actual_sale_price" class="form-label fw-semibold">
                                    တကယ်‌ရောင်းဈေး
                                    {{-- <span class="text-danger">*( ရောင်းနိုင့်မည့်ဈေးကို စိတ်ကြိုက် အတိုး အလျှော့
                                        လုပ်နိုင်သည်
                                        )</span> --}}
                                </label>

                                <input type="text" id="actual_sale_price" class="form-control" min="0"
                                    step="0.01" placeholder="ရောင်းမည့်ဈေး">
                            </div>


                            {{-- မှတ်ချက် --}}
                            <div class="col-lg-6">
                                <label for="item_note" class="form-label fw-semibold">
                                    မှတ်ချက် ( မထည့်လဲရ )
                                </label>

                                <input type="text" id="item_note" class="form-control" maxlength="255"
                                    placeholder="လိုအပ်ပါက မှတ်ချက်ရေးပါ">
                            </div>

                            {{-- အိတ်အရေအတွက် (မြေပဲအတွက်သာ) --}}
                            <div class="col-md-6 col-lg-3 d-none" id="bag_count_wrapper">
                                <label for="bag_count" class="form-label fw-semibold">
                                    အိတ်အရေအတွက်
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="number" id="bag_count" class="form-control" min="1" step="1"
                                    placeholder="အိတ်အရေအတွက် ထည့်ပါ">
                            </div>

                            {{-- သီးနှံထည့်ရန် --}}
                            <div class="col-12 text-center">
                                <button type="button" id="add_crop_item" class="btn btn-success">
                                    <i class="bi bi-plus-lg me-1"></i>
                                    သီးနှံစာရင်းထဲ ထည့်မည်
                                </button>
                            </div>

                        </div>

                    </div>

                    <input type="hidden" name="customer_id" id="customer_id">
                    <div id="purchase_items_inputs"></div>

                    {{-- ထည့်ပြီးသော သီးနှံစာရင်း --}}
                    <div class="mb-3">
                        <h6 class="fw-bold mb-1">
                            <i class="bi bi-list-ul me-2 text-primary"></i>
                            ထည့်သွင်းထားသော သီးနှံစာရင်း
                        </h6>

                        <small class="text-muted">
                            မသိမ်းရသေးသော သီးနှံစာရင်းများကို အောက်တွင် စစ်ဆေးနိုင်ပါသည်။
                        </small>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">စဉ်</th>
                                    <th>သီးနှံ</th>
                                    <th class="text-end">အရေအတွက်</th>
                                    <th class="text-end">တင်း</th>
                                    <th class="text-end">၁ တင်းရောင်းဈေး</th>
                                    <th class="text-end">စုစုပေါင်း</th>
                                    <th class="text-center">လုပ်ဆောင်ချက်</th>
                                </tr>
                            </thead>
                            <tbody id="purchase_items_body">
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                        သီးနှံစာရင်း မထည့်ရသေးပါ။
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- စုစုပေါင်းအကျဉ်းချုပ် --}}
                    <div class="row justify-content-end mt-4">
                        <div class="col-md-6 col-lg-5">
                            <div class="card bg-light border-0">
                                <div class="card-body">

                                    <div class="d-flex justify-content-between mb-2">
                                        <span>သီးနှံစုစုပေါင်းဖိုး</span>
                                        <strong id="items_gross_total">0.00</strong>
                                    </div>

                                    <hr>

                                    <div class="mb-3 d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">ပေးချေရမည့်ငွေ</span>
                                        <strong class="fs-5" id="net_payable_total">0.00</strong>
                                    </div>

                                    <hr>

                                    <div class="mb-3">
                                        <label for="paid_amount" class="form-label fw-semibold">
                                            ပေးချေမည့်ငွေ
                                        </label>
                                        <input type="text" id="paid_amount" name="paid_amount"
                                            class="form-control text-end" value="{{ old('paid_amount', '') }}"
                                            inputmode="decimal" autocomplete="off" placeholder="ပေးချေမည့်ငွေ ဖြည့်ပါ">
                                    </div>

                                    <hr>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">နောက်ဆုံး ပေးရန်ကျန်ငွေ</span>
                                        <strong class="fs-5 text-success" id="final_payable_total">
                                            0.00
                                        </strong>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Purchase သိမ်းဆည်းပြီး Voucher ကြည့်ရန် --}}
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" id="save_purchase_btn" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>
                            သိမ်းပြီး Voucher ထုတ်မည်
                        </button>
                    </div>

                </div>
            </div>
            {{-- သီးနှံစာရင်းအပိုင်း အဆုံး --}}

        </form>

    </div>

    {{-- New Farmer Modal ( farmer အသစ်ထပ်ထည့်တဲ့ UI ) --}}
    <div class="modal fade" id="newCustomerModal" tabindex="-1" aria-labelledby="newCustomerModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="newCustomerModalLabel">
                        <i class="bi bi-person-plus me-2 text-success"></i>
                        ၀ယ်သူအသစ် စာရင်းသွင်းရန်
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="new_customer_form">
                    @csrf

                    <div class="modal-body">

                        <div id="new_customer_error" class="alert alert-danger" style="display: none;"></div>

                        <input type="hidden" name="customer_id" id="customer_id">

                        <div class="mb-3">
                            <label for="new_customer_name" class="form-label">
                                ၀ယ်သူအမည် <span class="text-danger">*</span>
                            </label>

                            <input type="text" name="name" id="new_customer_name" class="form-control"
                                placeholder="၀ယ်သူအမည် ထည့်ပါ" maxlength="150">
                        </div>

                        <div class="mb-3">
                            <label for="new_customer_phone" class="form-label">
                                ဖုန်းနံပါတ်
                            </label>

                            <input type="text" name="phone" id="new_customer_phone" class="form-control"
                                placeholder="၀ယ်သူဖုန်းနံပါတ် ထည့်ပါ" maxlength="30">
                        </div>

                        <div class="mb-3">
                            <label for="new_customer_address" class="form-label">
                                လိပ်စာ
                            </label>

                            <input type="text" name="address" id="new_customer_address" class="form-control"
                                placeholder="၀ယ်သူကျေးရွာအမည် ထည့်ပါ" maxlength="150">
                        </div>

                        <div class="mb-0">
                            <label for="new_customer_notes" class="form-label">
                                မှတ်ချက်
                            </label>

                            <textarea name="notes" id="new_customer_notes" class="form-control" rows="2" placeholder="မှတ်ချက်ရေးပါ ( မရေးလဲရ )"></textarea>
                        </div>

                        <div class="my-2">
                            <span class="text-danger">*</span>
                            <small class=" text-dark fw-bold fs-6">
                                စာရင်းသွင်းပြီးလျှင်၀ယ်သူကိုတစ်ခါတည်း ရွေးထားပြီးသားဖြစ်လိမ့်မည်
                            </small>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            မလုပ်တော့ပါ
                        </button>

                        <button type="submit" id="save_customer_btn" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i>
                            သိမ်းပြီးရွေးမည်
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection

{{-- တောင်သူအပိုင်းအတွက် JS အစ --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const searchInput = document.getElementById('customer_search');
            const resultsBox = document.getElementById('customer_results');
            const customerIdInput = document.getElementById('customer_id');
            const selectedBox = document.getElementById('selected_customer_box');
            const errorBox = document.getElementById('customer_error');

            const modalElement = document.getElementById('newCustomerModal');
            const customerForm = document.getElementById('new_customer_form');

            const searchUrl = @json(route('Customer#search'));
            const storeUrl = @json(route('Customer#create'));
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            let searchTimer;

            // လိုအပ်သော HTML element မရှိပါက အကြောင်းရင်းကို ရှင်းလင်းစွာ ပြမည်
            if (
                !searchInput ||
                !resultsBox ||
                !customerIdInput ||
                !selectedBox ||
                !errorBox ||
                !modalElement ||
                !customerForm
            ) {
                console.error('Customer picker အတွက် လိုအပ်သော HTML element မတွေ့ပါ။');
                return;
            }

            function showError(message) {
                errorBox.textContent = message;
                errorBox.style.display = 'block';
            }

            function hideError() {
                errorBox.textContent = '';
                errorBox.style.display = 'none';
            }

            function escapeHtml(value) {
                return String(value ?? '').replace(/[&<>"']/g, function(char) {
                    return {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    } [char];
                });
            }

            // Customer ရွေးချယ်မည်
            function selectCustomer(customer) {
                if (!customer || !customer.id) {
                    showError('ဝယ်သူအချက်အလက် မမှန်ကန်ပါ။');
                    return;
                }

                customerIdInput.value = customer.id;

                document.getElementById('selected_customer_name').textContent =
                    customer.name || '';

                document.getElementById('selected_customer_code').textContent =
                    customer.customer_code || 'မရှိပါ';

                document.getElementById('selected_customer_phone').textContent =
                    customer.phone || 'မရှိပါ';

                document.getElementById('selected_customer_address').textContent =
                    customer.address || 'မရှိပါ';

                searchInput.value = customer.name || '';

                selectedBox.style.display = 'block';
                resultsBox.style.display = 'none';

                hideError();
            }

            // Customer အသစ်ထည့်ရန် Modal ဖွင့်မည်
            function openNewCustomerModal(query) {
                document.getElementById('new_customer_name').value = query;

                bootstrap.Modal.getOrCreateInstance(modalElement).show();
            }

            // Search results ပြမည်
            function renderResults(customers, query) {
                resultsBox.replaceChildren();

                if (customers.length === 0) {
                    const div = document.createElement('div');
                    div.className =
                        'list-group-item d-flex align-items-center justify-content-between gap-2 p-3';

                    const text = document.createElement('span');
                    text.className = 'text-danger fw-bold';
                    text.textContent = 'ကိုက်ညီသောဝယ်သူ မတွေ့ပါ။';

                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'btn btn-sm btn-success flex-shrink-0';
                    button.textContent = 'ဝယ်သူအသစ်ထည့်မည်';

                    button.addEventListener('click', function() {
                        openNewCustomerModal(query);
                    });

                    div.append(text, button);
                    resultsBox.appendChild(div);
                    resultsBox.style.display = 'block';

                    return;
                }

                customers.forEach(function(customer) {
                    const button = document.createElement('button');

                    button.type = 'button';
                    button.className =
                        'list-group-item list-group-item-action p-3';

                    button.innerHTML = `
                <div class="d-flex align-items-center gap-3">

                    <div class="bg-success bg-opacity-10 text-success rounded-circle
                                d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 44px; height: 44px;">
                        <i class="bi bi-person-fill fs-4"></i>
                    </div>

                    <div class="flex-grow-1 overflow-hidden">

                        <div class="fw-bold text-dark mb-1 fs-5">
                            ${escapeHtml(customer.name || 'အမည်မရှိ')}
                        </div>

                        <div class="d-flex flex-wrap gap-3 text-secondary small">

                            ${customer.customer_code ? `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <span class=' text-info'>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <i class="bi bi-upc-scan me-1"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ${escapeHtml(customer.customer_code)}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ` : ''}

                            ${customer.phone ? `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <span class=' fw-bold text-dark fs-6'>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <i class="bi bi-telephone me-1"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ${escapeHtml(customer.phone)}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ` : ''}

                            ${customer.address ? `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <span class=' fw-bold text-dark fs-6'>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <i class="bi bi-geo-alt me-1"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ${escapeHtml(customer.address)}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ` : ''}

                        </div>
                    </div>

                    <div class="text-success flex-shrink-0">
                        <i class="bi bi-chevron-right"></i>
                    </div>

                </div>
            `;

                    button.addEventListener('click', function() {
                        selectCustomer(customer);
                    });

                    resultsBox.appendChild(button);
                });

                resultsBox.style.display = 'block';
            }

            // Customer ရှာမည်
            searchInput.addEventListener('input', function() {
                const query = searchInput.value.trim();

                clearTimeout(searchTimer);

                customerIdInput.value = '';
                selectedBox.style.display = 'none';
                hideError();

                if (!query) {
                    resultsBox.replaceChildren();
                    resultsBox.style.display = 'none';
                    return;
                }

                searchTimer = setTimeout(async function() {
                    try {
                        const response = await fetch(
                            searchUrl + '?search=' + encodeURIComponent(query), {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            }
                        );

                        if (!response.ok) {
                            throw new Error('ဝယ်သူရှာဖွေမှု မအောင်မြင်ပါ။');
                        }

                        const customers = await response.json();

                        // နောက်ဆုံးရိုက်ထားသော စာသားအတွက်သာ ရလဒ်ပြမည်
                        if (searchInput.value.trim() === query) {
                            renderResults(customers, query);
                        }

                    } catch (error) {
                        showError(error.message);
                    }
                }, 300);
            });

            // ရွေးချယ်မှု ပယ်ဖျက်မည်
            document.getElementById('clear_customer').addEventListener('click', function() {
                clearTimeout(searchTimer);

                searchInput.value = '';
                customerIdInput.value = '';
                selectedBox.style.display = 'none';
                resultsBox.replaceChildren();
                resultsBox.style.display = 'none';

                hideError();
                searchInput.focus();
            });

            // Customer ပြန်ရွေးမည်
            document.getElementById('change_customer').addEventListener('click', function() {
                customerIdInput.value = '';
                selectedBox.style.display = 'none';
                searchInput.focus();
            });

            // Customer အသစ် သိမ်းမည်
            customerForm.addEventListener('submit', async function(event) {
                event.preventDefault();

                const form = event.currentTarget;
                const saveButton = document.getElementById('save_customer_btn');
                const modalError = document.getElementById('new_customer_error');

                const nameInput = document.getElementById('new_customer_name');
                const phoneInput = document.getElementById('new_customer_phone');
                const addressInput = document.getElementById('new_customer_address');

                modalError.textContent = '';
                modalError.style.display = 'none';

                form.querySelectorAll('.is-invalid').forEach(function(input) {
                    input.classList.remove('is-invalid');
                });

                form.querySelectorAll('.validation-error').forEach(function(error) {
                    error.remove();
                });

                let isValid = true;

                function showFieldError(input, message) {
                    input.classList.add('is-invalid');

                    const error = document.createElement('div');
                    error.className = 'invalid-feedback validation-error';
                    error.textContent = message;

                    input.insertAdjacentElement('afterend', error);
                    isValid = false;
                }

                const name = nameInput.value.trim();
                const phone = phoneInput.value.trim();
                const address = addressInput.value.trim();

                if (!name) {
                    showFieldError(nameInput, 'ဝယ်သူအမည် ဖြည့်ပေးပါ။');
                } else if (name.length > 150) {
                    showFieldError(nameInput, 'ဝယ်သူအမည်သည် အများဆုံး 150 လုံး ဖြစ်ရပါမယ်။');
                } else if (!/^[\p{Script=Latin}\p{Script=Myanmar}\p{M}\s]+$/u.test(name)) {
                    showFieldError(nameInput,
                        'အမည်တွင် မြန်မာ သို့မဟုတ် English အက္ခရာများကိုသာ ထည့်ပါ။');
                }

                if (phone && !/^[0-9၀-၉+\-\s()]+$/.test(phone)) {
                    showFieldError(phoneInput, 'ဖုန်းနံပါတ်ပုံစံ မှန်ကန်စွာ ဖြည့်ပေးပါ။');
                } else if (phone.length > 30) {
                    showFieldError(phoneInput, 'ဖုန်းနံပါတ်သည် အများဆုံး 30 လုံး ဖြစ်ရပါမယ်။');
                }

                if (address.length > 150) {
                    showFieldError(addressInput, 'လိပ်စာသည် အများဆုံး 150 လုံး ဖြစ်ရပါမယ်။');
                }

                if (!isValid) {
                    form.querySelector('.is-invalid')?.focus();
                    return;
                }

                saveButton.disabled = true;
                saveButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-1"></span> သိမ်းဆည်းနေသည်...';

                try {
                    const response = await fetch(storeUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || ''
                        },
                        body: new FormData(form)
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        if (data.errors) {
                            Object.entries(data.errors).forEach(function([field, messages]) {
                                const input = form.querySelector(
                                    '[name="' + field + '"]'
                                );

                                if (input) {
                                    showFieldError(input, messages[0]);
                                } else {
                                    modalError.textContent = messages[0];
                                    modalError.style.display = 'block';
                                }
                            });
                        } else {
                            modalError.textContent =
                                data.message || 'ဝယ်သူအသစ် သိမ်း၍မရပါ။';
                            modalError.style.display = 'block';
                        }

                        form.querySelector('.is-invalid')?.focus();
                        return;
                    }

                    // Controller က customer key နဲ့ ပြန်ပေးထားသည်
                    if (!data.customer) {
                        throw new Error('Server မှ ဝယ်သူအချက်အလက် မရရှိပါ။');
                    }

                    bootstrap.Modal.getOrCreateInstance(modalElement).hide();

                    form.reset();

                    selectCustomer(data.customer);

                } catch (error) {
                    modalError.textContent =
                        error.message || 'Server နှင့် ချိတ်ဆက်၍မရပါ။';
                    modalError.style.display = 'block';

                } finally {
                    saveButton.disabled = false;
                    saveButton.innerHTML =
                        '<i class="bi bi-check-circle me-1"></i> သိမ်းပြီးရွေးမည်';
                }
            });

            // Input ပြင်လျှင် သက်ဆိုင်ရာ validation error ကို ဖယ်မည်
            [
                'new_customer_name',
                'new_customer_phone',
                'new_customer_address'
            ].forEach(function(id) {
                const input = document.getElementById(id);

                input.addEventListener('input', function() {
                    input.classList.remove('is-invalid');

                    const next = input.nextElementSibling;

                    if (next?.classList.contains('validation-error')) {
                        next.remove();
                    }
                });
            });

        });
    </script>
@endpush
{{-- တောင်သူအပိုင်းအတွက် JS အဆုံး --}}


{{-- သီးနှံစာရင်းအတွက် သီးနှံဈေး အလိုအလျောက်ပြပေးတဲ့ JS အစ --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cropSelect = document.getElementById('crop_id');
            const qtyPerBasketInput = document.getElementById('quantity_per_basket');
            const unitInput = document.getElementById('unit');
            const referencePriceInput = document.getElementById('reference_price');
            const actualPriceInput = document.getElementById('actual_sale_price');

            const bagCountWrapper = document.getElementById('bag_count_wrapper');
            const bagCountInput = document.getElementById('bag_count');

            cropSelect.addEventListener('change', async function() {
                const cropId = this.value;
                const option = this.options[this.selectedIndex];

                // အရင်တန်ဖိုးများ ရှင်းမည်
                unitInput.value = '';
                qtyPerBasketInput.value = '';
                referencePriceInput.value = '';
                actualPriceInput.value = '';

                bagCountWrapper.classList.add('d-none');
                bagCountInput.value = '';
                bagCountInput.required = false;

                if (!cropId) {
                    return;
                }

                // သီးနှံယူနစ်
                const rawUnit = (option.dataset.unit || '').trim().toLowerCase();

                if (rawUnit === 'viss') {
                    unitInput.value = 'ပိဿာ';
                } else if (rawUnit === 'pound') {
                    unitInput.value = 'ပေါင်';

                    // ပေါင်ယူနစ်ဖြစ်မှ အိတ်အရေအတွက် ပြမည်
                    bagCountWrapper.classList.remove('d-none');
                    bagCountInput.required = true;
                } else {
                    unitInput.value = option.dataset.unit || '';
                }

                qtyPerBasketInput.value =
                    option.dataset.quantityPerBasket || '';

                try {
                    const response = await fetch(
                        `{{ url('/whole-sale-market/Sale/avgPrice') }}/${cropId}`, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        }
                    );

                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        alert(data.message || 'ပျမ်းမျှဈေးနှုန်း ရယူ၍မရပါ။');
                        return;
                    }

                    // တစ်တင်း ပျမ်းမျှဈေး
                    referencePriceInput.value = data.reference_price;

                    // အမှန်တကယ်ရောင်းဈေးကို မူလဈေးအဖြစ် ဖြည့်ပေးမည်
                    actualPriceInput.value = data.reference_price;

                } catch (error) {
                    console.error(error);
                    alert('ဈေးနှုန်းရယူရာတွင် အမှားဖြစ်နေပါသည်။');
                }
            });
        });
    </script>
@endpush
{{-- သီသီးနှံစာရင်းအတွက် သီးနှံဈေး အလိုအလျောက်ပြပေးတဲ့ JS အဆုံး --}}

{{-- သီးနှံစျေးနှုန်း တွက်ချက် --}}

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('sale_form');

            const cropSelect = document.getElementById('crop_id');
            const quantityInput = document.getElementById('quantity');
            const unitInput = document.getElementById('unit');
            const qtyPerBasketInput = document.getElementById('quantity_per_basket');
            const referencePriceInput = document.getElementById('reference_price');
            const actualPriceInput = document.getElementById('actual_sale_price');
            const noteInput = document.getElementById('item_note');

            const bagCountWrapper = document.getElementById('bag_count_wrapper');
            const bagCountInput = document.getElementById('bag_count');

            const addButton = document.getElementById('add_crop_item');
            const tbody = document.getElementById('purchase_items_body');
            const itemsInputs = document.getElementById('purchase_items_inputs');

            const grossTotalElement = document.getElementById('items_gross_total');
            const payableElement = document.getElementById('net_payable_total');
            const finalPayableElement = document.getElementById('final_payable_total');
            const paidInput = document.getElementById('paid_amount');

            const customerIdInput = document.getElementById('customer_id');

            // သီးနှံများကို Browser ထဲတွင်သာ ယာယီသိမ်းမည်။
            // Form Submit လုပ်မှသာ Controller ဆီပို့မည်။
            let items = [];

            // မြန်မာဂဏန်းနှင့် comma ပြောင်းဖတ်ခြင်း
            function parseNumber(value) {
                const myanmarDigits = '၀၁၂၃၄၅၆၇၈၉';

                const englishValue = String(value ?? '')
                    .replace(/[၀-၉]/g, digit => myanmarDigits.indexOf(digit))
                    .replace(/,/g, '')
                    .trim();

                if (englishValue === '') {
                    return NaN;
                }

                return Number(englishValue);
            }

            function money(value) {
                return Number(value || 0).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function formatQuantity(value) {
                return Number(value || 0).toLocaleString('en-US', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 3
                });
            }

            function escapeHtml(value) {
                return String(value ?? '').replace(/[&<>"']/g, char => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                })[char]);
            }

            // Hidden input များကို Form Submit မလုပ်မီ ပြင်ဆင်မည်။
            // Hidden input များကို table ထဲတွင်လည်း renderItems() က ဖန်တီးထားသည်။
            function buildSubmitInputs() {
                itemsInputs.innerHTML = '';

                items.forEach((item, index) => {
                    const fields = {
                        crop_id: item.cropId,
                        quantity: item.quantity,
                        bag_count: item.bagCount,
                        avg_price: item.avgPrice,
                        sale_price: item.salePrice,
                        gross_amount: item.gross,
                        remainder_amount: item.remainderAmount,
                        note: item.note
                    };

                    Object.entries(fields).forEach(([field, value]) => {
                        const input = document.createElement('input');

                        input.type = 'hidden';
                        input.name = `sale_items[${index}][${field}]`;
                        input.value = value ?? '';

                        itemsInputs.appendChild(input);
                    });
                });
            }

            // စုစုပေါင်းတွက်ခြင်း
            function updateTotals() {
                const grossTotal = items.reduce((sum, item) => {
                    return sum + item.gross;
                }, 0);

                const paid = paidInput.value.trim() === '' ?
                    0 :
                    parseNumber(paidInput.value);

                const validPaid = Number.isFinite(paid) && paid >= 0 ?
                    paid :
                    0;

                const remaining = grossTotal - validPaid;

                grossTotalElement.textContent = money(grossTotal);
                payableElement.textContent = money(grossTotal);
                finalPayableElement.textContent = money(remaining);
            }

            // Table ပြသခြင်း
            function renderItems() {
                tbody.innerHTML = '';

                if (items.length === 0) {
                    tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                        သီးနှံစာရင်း မထည့်ရသေးပါ။
                    </td>
                </tr>
            `;

                    updateTotals();
                    return;
                }

                items.forEach((item, index) => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                <td class="text-center">${index + 1}</td>

                <td>
                    ${escapeHtml(item.cropName)}
                </td>

                <td class="text-end">
                    ${formatQuantity(item.quantity)}
                    ${escapeHtml(item.unit)}

                    ${item.unitType === 'pound'
                        ? `<div class="small text-muted">
                                                               အိတ် ${item.bagCount} အိတ်နုတ်ပြီး
                                                           </div>`
                        : ''
                    }
                </td>

                <td class="text-end">
                    ${formatQuantity(item.baskets)}
                </td>

                <td class="text-end">
                    ${money(item.salePrice)}
                </td>

                <td class="text-end">
                    ${money(item.gross)}
                </td>

                <td class="text-center">
                    <button type="button"
                        class="btn btn-sm btn-outline-dark"
                        data-remove-index="${index}">
                        <i class="bi bi-trash"></i> ဖယ်မည်
                    </button>
                </td>
            `;

                    tbody.appendChild(row);
                });

                updateTotals();
            }

            // သီးနှံရွေးချယ်သည့်အခါ
            cropSelect.addEventListener('change', async function() {
                const cropId = this.value;
                const option = this.options[this.selectedIndex];

                unitInput.value = '';
                qtyPerBasketInput.value = '';
                referencePriceInput.value = '';
                actualPriceInput.value = '';

                bagCountInput.value = '';
                bagCountInput.required = false;
                bagCountWrapper.classList.add('d-none');

                if (!cropId) {
                    return;
                }

                const unitType = (option.dataset.unit || '')
                    .trim()
                    .toLowerCase();

                if (unitType === 'viss') {
                    unitInput.value = 'ပိဿာ';
                } else if (unitType === 'pound') {
                    unitInput.value = 'ပေါင်';
                    bagCountWrapper.classList.remove('d-none');
                    bagCountInput.required = true;
                } else {
                    unitInput.value = option.dataset.unit || '';
                }

                qtyPerBasketInput.value =
                    option.dataset.quantityPerBasket || '';

                try {
                    const response = await fetch(
                        `{{ url('/whole-sale-market/Sale/avgPrice') }}/${cropId}`, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        }
                    );

                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        alert(data.message || 'ပျမ်းမျှဈေးနှုန်း ရယူ၍မရပါ။');
                        return;
                    }

                    const averageBasketPrice =
                        Math.floor(Number(data.reference_price || 0) / 1000) * 1000;

                    referencePriceInput.value = averageBasketPrice;
                    actualPriceInput.value = averageBasketPrice;

                } catch (error) {
                    console.error(error);
                    alert('ဈေးနှုန်းရယူရာတွင် အမှားဖြစ်နေပါသည်။');
                }
            });

            // သီးနှံစာရင်းထဲ ထည့်မည်
            addButton.addEventListener('click', function() {
                const option = cropSelect.options[cropSelect.selectedIndex];

                const cropId = cropSelect.value;
                const cropName = option?.textContent.trim() ?? '';
                const unitType = (option?.dataset.unit || '')
                    .trim()
                    .toLowerCase();

                const quantity = parseNumber(quantityInput.value);
                const qtyPerBasket = parseNumber(qtyPerBasketInput.value);
                const salePrice = parseNumber(actualPriceInput.value);
                const referencePrice = parseNumber(referencePriceInput.value);
                const note = noteInput.value.trim();

                let bagCount = 0;

                if (!cropId) {
                    alert('သီးနှံအမည်ကို ရွေးချယ်ပါ။');
                    cropSelect.focus();
                    return;
                }

                if (!Number.isFinite(quantity) || quantity <= 0) {
                    alert('သီးနှံအရေအတွက်ကို မှန်ကန်စွာ ဖြည့်ပါ။');
                    quantityInput.focus();
                    return;
                }

                if (!Number.isFinite(qtyPerBasket) || qtyPerBasket <= 0) {
                    alert('ဒီသီးနှံအတွက် တစ်တင်းရှိ ပမာဏကို သတ်မှတ်ထားခြင်း မရှိပါ။');
                    return;
                }

                if (!Number.isFinite(salePrice) || salePrice <= 0) {
                    alert('ရောင်းဈေးကို သုညထက်ကြီးသော တန်ဖိုးဖြင့် ဖြည့်ပါ။');
                    actualPriceInput.focus();
                    return;
                }

                if (!Number.isFinite(referencePrice) || referencePrice < 0) {
                    alert('ပျမ်းမျှဈေးနှုန်းကို စစ်ဆေးပါ။');
                    return;
                }

                if (unitType === 'pound') {
                    bagCount = parseNumber(bagCountInput.value);

                    if (!Number.isInteger(bagCount) || bagCount < 1) {
                        alert('အိတ်အရေအတွက်ကို အနည်းဆုံး ၁ အိတ် ဖြည့်ပါ။');
                        bagCountInput.focus();
                        return;
                    }

                    if (quantity <= bagCount) {
                        alert('သီးနှံအရေအတွက်သည် အိတ်အရေအတွက်ထက် ကြီးရပါမည်။');
                        quantityInput.focus();
                        return;
                    }
                }

                // ပျမ်းမျှ တစ်တင်းဈေးကို ၁,၀၀၀ အောက်ချမည်
                const averageBasketPrice =
                    Math.floor(referencePrice / 1000) * 1000;

                // လက်ရှိတွက်ချက်မှုအတိုင်း pound ဖြစ်လျှင်
                // quantity ထဲမှ bag_count ကို နုတ်ပြီး တင်းတွက်မည်။
                const netQuantity = unitType === 'pound' ?
                    quantity - bagCount :
                    quantity;

                const baskets = netQuantity / qtyPerBasket;
                const rawGross = baskets * salePrice;

                // စုစုပေါင်းဖိုးကို ၁,၀၀၀ အောက်ချမည်
                const gross = Math.floor(rawGross / 1000) * 1000;

                const remainderAmount =
                    Math.round((rawGross - gross) * 100) / 100;

                // Controller ရှိ avg_price field နှင့် ကိုက်ညီစေရန်
                const avgPrice = averageBasketPrice;

                // သီးနှံတစ်မျိုးကို တစ်ကြိမ်သာထည့်မည်
                if (items.some(item => String(item.cropId) === String(cropId))) {
                    alert('ဒီသီးနှံကို စာရင်းထဲ ထည့်ထားပြီးဖြစ်ပါသည်။ ဖယ်ပြီးမှ ပြန်ထည့်ပါ။');
                    return;
                }

                items.push({
                    cropId,
                    cropName,
                    quantity,
                    netQuantity,
                    unit: unitInput.value,
                    unitType,
                    bagCount,
                    baskets,
                    avgPrice,
                    salePrice,
                    gross,
                    remainderAmount,
                    note
                });

                renderItems();

                // နောက်ထပ် သီးနှံထည့်ရန် Form ကို ရှင်းမည်
                cropSelect.value = '';
                quantityInput.value = '';
                unitInput.value = '';
                qtyPerBasketInput.value = '';
                referencePriceInput.value = '';
                actualPriceInput.value = '';
                noteInput.value = '';
                bagCountInput.value = '';
                bagCountInput.required = false;
                bagCountWrapper.classList.add('d-none');

                cropSelect.focus();
            });

            // Table ထဲက သီးနှံကို ဖယ်မည်
            tbody.addEventListener('click', function(event) {
                const button = event.target.closest('[data-remove-index]');

                if (!button) {
                    return;
                }

                const index = Number(button.dataset.removeIndex);

                if (!Number.isInteger(index) || index < 0 || index >= items.length) {
                    return;
                }

                items.splice(index, 1);
                renderItems();
            });

            // ပေးချေငွေပြောင်းလဲလျှင် လက်ကျန်ငွေ ပြန်တွက်မည်
            paidInput.addEventListener('input', updateTotals);

            // သိမ်းပြီး Voucher ထုတ်မည်
            form.addEventListener('submit', function(event) {
                if (!customerIdInput.value) {
                    event.preventDefault();
                    alert('ဝယ်သူကို အရင်ရွေးချယ်ပါ။');

                    const customerSearch = document.getElementById('customer_search');

                    if (customerSearch) {
                        customerSearch.focus();
                    }

                    return;
                }

                if (items.length === 0) {
                    event.preventDefault();
                    alert('သိမ်းဆည်းရန် သီးနှံစာရင်း အနည်းဆုံးတစ်မျိုး ထည့်ပါ။');
                    cropSelect.focus();
                    return;
                }

                const netPayable = items.reduce((sum, item) => sum + item.gross, 0);

                const paidText = paidInput.value.trim();
                const paidAmount = paidText === '' ? 0 : parseNumber(paidText);

                if (!Number.isFinite(paidAmount) || paidAmount < 0) {
                    event.preventDefault();
                    alert('ပေးချေမည့်ငွေကို မှန်ကန်စွာ ဖြည့်ပါ။');
                    paidInput.focus();
                    paidInput.select();
                    return;
                }

                if (paidAmount > netPayable) {
                    event.preventDefault();
                    alert('ပေးချေမည့်ငွေသည် ပေးချေရမည့်ငွေထက် မများရပါ။');
                    paidInput.focus();
                    paidInput.select();
                    return;
                }

                // Submit လုပ်ချိန်မှ hidden inputs များဖန်တီးမည်။
                // အဲဒီအချိန်မှသာ sale_items[] ကို Server ဆီပို့မည်။
                buildSubmitInputs();
            });

            updateTotals();
        });
    </script>
@endpush


{{-- Phone number Input (Only) တွေမှာ number တွေကို myn to eng ပြောင်းပေးတဲ့ JS  --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const phoneInputs = ['phone', 'new_customer_phone', 'customer_search', 'paid_amount'];

            const myanmarNumbers = '၀၁၂၃၄၅၆၇၈၉';

            phoneInputs.forEach(function(id) {
                const input = document.getElementById(id);

                if (!input) return;

                input.addEventListener('input', function() {
                    this.value = this.value.replace(/[၀-၉]/g, function(digit) {
                        return myanmarNumbers.indexOf(digit);
                    });
                });
            });

        });

        function escapeHtml(value) {
            return String(value).replace(/[&<>"']/g, function(char) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                } [char];
            });
        }
    </script>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const priceInputs = ['quantity', 'reference_price', 'actual_sale_price'];
            const myanmarNumbers = '၀၁၂၃၄၅၆၇၈၉';

            priceInputs.forEach(function(id) {
                const input = document.getElementById(id);
                if (!input) return;

                input.addEventListener('input', function() {
                    let val = this.value;

                    // ၁။ မြန်မာနံပါတ်များကို အင်္ဂလိပ်နံပါတ်သို့ ပြောင်းခြင်း
                    val = val.replace(/[၀-၉]/g, function(digit) {
                        return myanmarNumbers.indexOf(digit);
                    });

                    // ၂။ နံပါတ် (0-9) နှင့် ဒဿသမ (.) မှလွဲ၍ ကျန်စာလုံးများနှင့် ကော်မာများအားလုံးကို ဖြတ်ထုတ်ခြင်း
                    val = val.replace(/[^0-9.]/g, '');

                    this.value = val;
                });
            });
        });
    </script>
@endpush
