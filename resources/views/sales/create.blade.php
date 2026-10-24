@extends('layouts.app')

@section('page-title', 'ဝယ်ယူမှုအသစ်')

@section('breadcrumb', 'ပင်မစာမျက်နှာ / ဝယ်ယူမှု / အသစ်ဖန်တီး')

@section('content')

    <div class="container-fluid px-0">

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
                        <button type="button" class=" btn btn-sm btn-success my-1" style="height: 40px;"
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
                                သီးနှံ စုစုပေါင်းအရေအတွက်
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
                                ယနေ့ ပျမ်းမျှ(avg)ဈေး ( နောက်ဆုံးသတ်မှတ်ထားသော ပျမ်းမျှ ဈေးကိုပြထားခြင်းဖြစ်သည် )
                            </label>

                            <input type="text" id="reference_price" class="form-control" min="0"
                                step="0.01" placeholder="ယနေ့သတ်မှတ်ဈေး" readonly>
                        </div>

                        {{-- အမှန်တကယ်ဝယ်ဈေး --}}
                        <div class="col-md-6 col-lg-3">
                            <label for="actual_sale_price" class="form-label fw-semibold">
                                အမှန်တကယ်‌ရောင်းမည့်ဈေး
                                <span class="text-danger">*( ရောင်းနိုင့်မည့်ဈေးကို စိတ်ကြိုက် အတိုး အလျှော့ လုပ်နိုင်သည်
                                    )</span>
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

                        {{-- သီးနှံထည့်ရန် --}}
                        <div class="col-12 text-center">
                            <button type="button" id="add_crop_item" class="btn btn-success">
                                <i class="bi bi-plus-lg me-1"></i>
                                သီးနှံစာရင်းထဲ ထည့်မည်
                            </button>
                        </div>

                    </div>

                </div>

                <input type="hidden" name="farmer_id" id="farmer_id">
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
                                <th>သီးနှံ / အမျိုးအစား</th>
                                <th class="text-end">အရေအတွက်</th>
                                <th class="text-end">တင်း</th>
                                <th class="text-end">
                                    <div>1 တင်းဝယ်ဈေး</div>
                                    <small class=" text-danger">ပွဲခ မနုတ်ရသေး</small>
                                </th>
                                <th class="text-end">ပွဲခ</th>
                                <th class="text-end">ကားခ</th>
                                <th class="text-end">
                                    <div>စုစုပေါင်း</div>
                                    <small class=" text-danger">ပွဲခ နုတ်ပြီး</small>
                                </th>
                                <th class="text-center">လုပ်ဆောင်ချက်</th>
                            </tr>
                        </thead>

                        <tbody id="purchase_items_body">
                            <tr id="empty_items_row">
                                <td colspan="9" class="text-center text-muted py-5">
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

                                <div class="d-flex justify-content-between mb-2">
                                    <span>ပွဲခ + ကားခ စုစုပေါင်း</span>
                                    <strong id="items_deduction_total">0.00</strong>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">အသားတင်ပေးရန်</span>

                                    <strong class="fs-5 text-success" id="items_net_total">
                                        0.00
                                    </strong>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        {{-- သီးနှံစာရင်းအပိုင်း အဆုံး --}}

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

                            <textarea name="notes" id="new_customer_notes" class="form-control" rows="2">မှတ်ချက်ရေးပါ ( မရေးလဲရ )</textarea>
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

            // သီးနှံရွေးလိုက်လျှင် Grade ရှာမည်
            cropSelect.addEventListener('change', async function() {

                const cropId = this.value;
                const option = this.options[this.selectedIndex];

                // unitInput.value = option.dataset.unit || '';
                const rawUnit = option.dataset.unit || '';
                let displayUnit = rawUnit;
                if (rawUnit.toLowerCase() === 'viss') {
                    displayUnit = 'ပိဿာ';
                } else if (rawUnit.toLowerCase() === 'pound') {
                    displayUnit = 'ပေါင်';
                }
                unitInput.value = displayUnit;

                commissionInput.value = option.dataset.commission || 0;
                const qtyPerBasket = option.getAttribute('data-quantity-per-basket') || 0;
                qtyPerBasketInput.value = qtyPerBasket;

                if (!cropId) {
                    return;
                }

                const response = await fetch(
                    `{{ url('/whole-sale-market/purchases/grades') }}/${cropId}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );

                const grades = await response.json();


            });

        });
    </script>
@endpush
{{-- သီသီးနှံစာရင်းအတွက် သီးနှံဈေး အလိုအလျောက်ပြပေးတဲ့ JS အဆုံး --}}
