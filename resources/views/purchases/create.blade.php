@extends('layouts.app')

@section('page-title', 'ဝယ်ယူမှုအသစ်')

@section('breadcrumb', 'ပင်မစာမျက်နှာ / ဝယ်ယူမှု / အသစ်ဖန်တီး')

@section('content')

    <div class="container-fluid px-0">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">သီးနှံဝယ်ယူမှုဘောင်ချာအသစ် ဖန်တီးမည်</h1>
                <p class="text-muted mb-0">
                    ဝယ်ယူမှုစာရင်းများကို သေချာစွာဖြည့်စွက်ပါ ( ဝယ်ယူမှုစာရင်းကို ဆက်မလုပ်တော့ပဲ အစကနေပြန်စမည်ဆိုလျှင်
                    အသစ်ပြန်စမည်ကိုနှိပ်ပြီး ပြန်စတင်နိုင်ပါသည် )
                </p>
            </div>

            <a href="{{ route('purchases#create#page') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-clockwise"></i> အသစ်ပြန်စမည်
            </a>
        </div>

        {{-- Farmer Selection --}}
        <div class="card border-1 shadow-lg mb-4">
            <div class="card-header bg-white py-3">
                <div class="row ">
                    <div class="col-lg-8">
                        <h5 class="mb-0 fw-bold my-1">
                            <i class="bi bi-person-check me-2 text-primary"></i>
                            တောင်သူရွေးချယ်ခြင်း
                        </h5>
                    </div>

                    <div class="col-lg-4">
                        <button type="button" class="btn btn-success w-100 my-1" style="height: 40px;"
                            data-bs-toggle="modal" data-bs-target="#newFarmerModal">
                            <i class="bi bi-person-plus me-1"></i>
                            တောင်သူအသစ်ထည့်မည်
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <div class="row g-3 align-items-end">
                    <div class="col-lg-12">
                        <label for="farmer_search" class="form-label fw-semibold">
                            တောင်သူအမည် / ဖုန်းနံပါတ် / ကုဒ်
                            <span class="text-danger">*</span>
                        </label>

                        <div class="position-relative mb-3">
                            <div class="input-group" style="height: 40px;">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search"></i>
                                </span>

                                <input type="text" id="farmer_search" class="form-control"
                                    placeholder="တောင်သူအချက်အလက် ထည့်ပြီးအလွယ်တကူ ရှာဖွေပြီးရွေးချယ်နိုင်သည် ( မရှိပါက အသစ်ထည့်ပါ )"
                                    autocomplete="off">

                                <button type="button" class="btn btn-outline-secondary" id="clear_farmer">
                                    ပယ်ဖျက်မည်
                                </button>
                            </div>

                            {{-- Search Results --}}
                            <div id="farmer_results" class="list-group position-absolute w-100 shadow"
                                style="z-index: 1050; display: none; max-height: 300px; overflow-y: auto;">
                            </div>
                        </div>

                        <small class="text-muted">
                            ရှိပြီးသားတောင်သူကို ရှာဖွေရွေးချယ်ပါ
                            ( မရှိပါက အသစ်ထည့်နိုင်သည် )
                        </small>
                    </div>
                </div>

                {{-- Selected Show Farmer --}}
                <div id="selected_farmer_box"
                    class="border border-success border-opacity-25 rounded-3 p-3 p-md-4 mt-4 bg-white shadow-sm"
                    style="display: none;">

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                        {{-- Farmer Information --}}
                        <div class="d-flex align-items-center gap-3 flex-grow-1">

                            <div class="rounded-circle bg-success bg-opacity-10 text-success
                                    d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width: 60px; height: 60px;">
                                <i class="bi bi-person-fill fs-2"></i>
                            </div>

                            <div class="flex-grow-1">

                                <div class="text-success small fw-semibold mb-1">
                                    <i class="bi bi-check-circle-fill me-1"></i>
                                    ရွေးချယ်ထားသော တောင်သူ
                                </div>

                                <h4 class="fw-bold mb-3" id="selected_farmer_name"></h4>

                                <div class="d-flex flex-wrap gap-3 gap-md-4">

                                    <div>
                                        <div class="text-secondary small mb-1">
                                            <i class="bi bi-telephone me-1"></i>
                                            ဖုန်းနံပါတ်
                                        </div>
                                        <div class="fw-semibold fs-6" id="selected_farmer_phone">
                                        </div>
                                    </div>

                                    <div class="border-start"></div>

                                    <div>
                                        <div class="text-secondary small mb-1">
                                            <i class="bi bi-geo-alt me-1"></i>
                                            ကျေးရွာအမည်
                                        </div>
                                        <div class="fw-semibold fs-6" id="selected_farmer_village">
                                        </div>
                                    </div>

                                </div>

                                <div class="text-secondary mt-3">
                                    <small>
                                        တောင်သူကုဒ် -
                                        <span id="selected_farmer_code"></span>
                                    </small>
                                </div>

                            </div>
                        </div>

                        {{-- Change Farmer --}}
                        <button type="button" id="change_farmer" class="btn btn-outline-success flex-shrink-0">
                            <i class="bi bi-arrow-repeat me-1"></i>
                            ပြန်ရွေးမည်
                        </button>

                    </div>
                </div>


                <div id="farmer_error" class="alert alert-danger mt-3 mb-0" style="display: none;">
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
                            သီးနှံစာရင်းထည့်သွင်းခြင်း
                        </h5>

                        <small class="text-muted">
                            တောင်သူရောင်းချသော သီးနှံများကို တစ်မျိုးချင်းထည့်သွင်းပါ။
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

                        {{-- အမျိုးအစားရွေးချယ်ရန် --}}
                        <div class="col-lg-6">
                            <label for="grade_id" class="form-label fw-semibold">
                                သီးနှံအမျိုးအစား <span class="text-danger">*(သီးနှံအမည် ရွေးပြီးမှရွေးလို့ရမည်)</span>
                            </label>

                            <select id="grade_id" class="form-select" disabled>
                                <option value="">အရင်ဆုံး သီးနှံရွေးချယ်ပါ</option>
                            </select>
                        </div>

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
                                ယနေ့ ထိပ်ဆဈေး ( နောက်ဆုံးသတ်မှတ်ထားသော ထိပ်ဆ ဈေးကိုပြထားခြင်းဖြစ်သည် )
                            </label>

                            <input type="text" id="reference_price" class="form-control" min="0"
                                step="0.01" placeholder="ယနေ့သတ်မှတ်ဈေး" readonly>
                        </div>

                        {{-- အမှန်တကယ်ဝယ်ဈေး --}}
                        <div class="col-md-6 col-lg-3">
                            <label for="actual_buy_price" class="form-label fw-semibold">
                                အမှန်တကယ်ဝယ်မည့်ဈေး
                                <span class="text-danger">*( ပေးနိုင့်မည့်ဈေးကို စိတ်ကြိုက် အတိုး အလျှော့ လုပ်နိုင်သည်
                                    )</span>
                            </label>

                            <input type="text" id="actual_buy_price" class="form-control" min="0"
                                step="0.01" placeholder="ဝယ်ယူမည့်ဈေး">
                        </div>

                        {{-- ပွဲခ --}}
                        <div class="col-md-6 col-lg-3">
                            <label for="commission_amount" class="form-label fw-semibold">
                                ကော်မရှင်ခ (ပွဲခ)
                            </label>

                            <input type="text" id="commission_amount" class="form-control" min="0"
                                step="0.01" value="0" readonly>
                        </div>

                        {{-- ကားခ --}}
                        <div class="col-md-6 col-lg-3">
                            <label for="car_fee_amount" class="form-label fw-semibold text-danger">
                                ကားခ (မပေးရလျှင် မထည့်လဲရ)
                            </label>

                            <input type="text" id="car_fee_amount" class="form-control" min="0"
                                step="0.01" value="0">
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
    <div class="modal fade" id="newFarmerModal" tabindex="-1" aria-labelledby="newFarmerModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="newFarmerModalLabel">
                        <i class="bi bi-person-plus me-2 text-success"></i>
                        တောင်သူအသစ် စာရင်းသွင်းရန်
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="new_farmer_form">
                    @csrf

                    <div class="modal-body">

                        <div id="new_farmer_error" class="alert alert-danger" style="display: none;"></div>

                        <div class="mb-3">
                            <label for="new_farmer_name" class="form-label">
                                တောင်သူအမည် <span class="text-danger">*</span>
                            </label>

                            <input type="text" name="name" id="new_farmer_name" class="form-control"
                                placeholder="တောင်သူအမည် ထည့်ပါ" maxlength="150">
                        </div>

                        <div class="mb-3">
                            <label for="new_farmer_phone" class="form-label">
                                ဖုန်းနံပါတ်
                            </label>

                            <input type="text" name="phone" id="new_farmer_phone" class="form-control"
                                placeholder="တောင်သူဖုန်းနံပါတ် ထည့်ပါ" maxlength="30">
                        </div>

                        <div class="mb-3">
                            <label for="new_farmer_village" class="form-label">
                                ကျေးရွာ
                            </label>

                            <input type="text" name="village" id="new_farmer_village" class="form-control"
                                placeholder="တောင်သူကျေးရွာအမည် ထည့်ပါ" maxlength="150">
                        </div>

                        <div class="mb-0">
                            <label for="new_farmer_notes" class="form-label">
                                မှတ်ချက်
                            </label>

                            <textarea name="notes" id="new_farmer_notes" class="form-control" rows="2">မှတ်ချက်ရေးပါ ( မရေးလဲရ )</textarea>
                        </div>

                        <div class="my-2">
                            <span class="text-danger">*</span>
                            <small class=" text-dark fw-bold fs-6">
                                စာရင်းသွင်းပြီးလျှင်တောင်သူကိုတစ်ခါတည်း ရွေးထားပြီးသားဖြစ်လိမ့်မည်
                            </small>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            မလုပ်တော့ပါ
                        </button>

                        <button type="submit" id="save_farmer_btn" class="btn btn-success">
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

            const searchInput = document.getElementById('farmer_search');
            const resultsBox = document.getElementById('farmer_results');
            const farmerIdInput = document.getElementById('farmer_id');
            const selectedBox = document.getElementById('selected_farmer_box');
            const errorBox = document.getElementById('farmer_error');

            const searchUrl = @json(route('farmers#search'));
            const storeUrl = @json(route('farmers#store'));
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            let searchTimer;

            // Error ပြမည်
            function showError(message) {
                errorBox.textContent = message;
                errorBox.style.display = 'block';
            }

            // Error ဖျောက်မည်
            function hideError() {
                errorBox.textContent = '';
                errorBox.style.display = 'none';
            }

            // တောင်သူရွေးချယ်မည်
            function selectFarmer(farmer) {
                farmerIdInput.value = farmer.id;

                document.getElementById('selected_farmer_name').textContent = farmer.name || '';
                document.getElementById('selected_farmer_code').textContent =
                    farmer.farmer_code ? 'ကုဒ်: ' + farmer.farmer_code : '';
                document.getElementById('selected_farmer_phone').textContent =
                    farmer.phone ? 'ဖုန်း: ' + farmer.phone : '';
                document.getElementById('selected_farmer_village').textContent =
                    farmer.village ? 'ကျေးရွာ: ' + farmer.village : '';

                searchInput.value = farmer.name || '';

                selectedBox.style.display = 'block';
                resultsBox.style.display = 'none';

                hideError();
            }

            // Search result ပြမည်
            function renderResults(farmers, query) {
                resultsBox.replaceChildren();

                if (farmers.length === 0) {
                    const div = document.createElement('div');
                    div.className = 'list-group-item d-flex align-items-center justify-content-between p-3';

                    const textSpan = document.createElement('span');
                    textSpan.className = 'text-danger fw-bold fs-6';
                    textSpan.textContent =
                        'ရှာဖွေသောအချက်အလက်နှင့် ကိုက်ညီသောတောင်သူမတွေ့ပါ တောင်သူအသစ်ထည့်ရန် ကိုနှိပ်၍ထည့်နိုင်သည်';

                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'btn btn-sm btn-success';
                    button.textContent = 'တောင်သူအသစ်ထည့်မည်';

                    button.onclick = function() {
                        document.getElementById('new_farmer_name').value = query;

                        bootstrap.Modal.getOrCreateInstance(
                            document.getElementById('newFarmerModal')
                        ).show();
                    };

                    div.appendChild(textSpan);
                    div.appendChild(button);
                    resultsBox.appendChild(div);
                    resultsBox.style.display = 'block';
                    return;
                }

                // select box farmer list for search btn
                farmers.forEach(function(farmer) {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'list-group-item list-group-item-action p-3';

                    button.innerHTML = `
                        <div class="d-flex align-items-center gap-3">

                            <div class="bg-success bg-opacity-10 text-success rounded-circle
                                        d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width: 44px; height: 44px;">
                                <i class="bi bi-person-fill fs-4"></i>
                            </div>

                            <div class="flex-grow-1 overflow-hidden">

                                <div class="fw-bold text-dark mb-1 fs-5">
                                    ${escapeHtml(farmer.name || 'အမည်မရှိ')}
                                </div>

                                <div class="d-flex flex-wrap gap-3 text-secondary small">

                                    ${farmer.farmer_code ? `
                                                                <span class=' text-info'>
                                                                    <i class="bi bi-upc-scan me-1"></i>
                                                                    ${escapeHtml(farmer.farmer_code)}
                                                                </span>
                                                            ` : ''}

                                    ${farmer.phone ? `
                                                                <span class=' fw-bold text-dark fs-6'>
                                                                    <i class="bi bi-telephone me-1"></i>
                                                                    ${escapeHtml(farmer.phone)}
                                                                </span>
                                                            ` : ''}

                                    ${farmer.village ? `
                                                                <span class=' fw-bold text-dark fs-6'>
                                                                    <i class="bi bi-geo-alt me-1"></i>
                                                                    ${escapeHtml(farmer.village)}
                                                                </span>
                                                            ` : ''}

                                </div>
                            </div>

                            <div class="text-success flex-shrink-0">
                                <i class="bi bi-chevron-right"></i>
                            </div>

                        </div>
                    `;

                    button.onclick = function() {
                        selectFarmer(farmer);
                    };

                    resultsBox.appendChild(button);
                });

                resultsBox.style.display = 'block';
            }

            // တောင်သူရှာမည်
            searchInput.addEventListener('input', function() {
                const query = searchInput.value.trim();

                clearTimeout(searchTimer);

                farmerIdInput.value = '';
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
                            throw new Error('တောင်သူရှာဖွေမှု မအောင်မြင်ပါ။');
                        }

                        const farmers = await response.json();

                        if (searchInput.value.trim() === query) {
                            renderResults(farmers, query);
                        }

                    } catch (error) {
                        showError(error.message);
                    }
                }, 300);
            });

            // တောင်သူရွေးချယ်မှု ဖျက်မည်
            document.getElementById('clear_farmer').onclick = function() {
                searchInput.value = '';
                farmerIdInput.value = '';
                selectedBox.style.display = 'none';
                resultsBox.style.display = 'none';
                hideError();
                searchInput.focus();
            };

            // တောင်သူပြန်ရွေးမည်
            document.getElementById('change_farmer').onclick = function() {
                farmerIdInput.value = '';
                selectedBox.style.display = 'none';
                searchInput.focus();
            };

            // တောင်သူအသစ် သိမ်းမည်
            document.getElementById('new_farmer_form').onsubmit = async function(event) {
                event.preventDefault();

                const form = event.currentTarget;
                const saveButton = document.getElementById('save_farmer_btn');
                const modalError = document.getElementById('new_farmer_error');

                const name = document.getElementById('new_farmer_name');
                const phone = document.getElementById('new_farmer_phone');
                const village = document.getElementById('new_farmer_village');


                modalError.textContent = '';
                modalError.style.display = 'none';

                // အရင် Validation အမှားများကို ရှင်းမည်
                form.querySelectorAll('.is-invalid').forEach(function(input) {
                    input.classList.remove('is-invalid');
                });
                form.querySelectorAll('.validation-error').forEach(function(error) {
                    error.remove();
                });
                let isValid = true;
                // Validation Error ပြမည့် Function
                function showError(input, message) {
                    input.classList.add('is-invalid');

                    const error = document.createElement('div');
                    error.className = 'invalid-feedback validation-error';
                    error.textContent = message;

                    input.insertAdjacentElement('afterend', error);
                    isValid = false;
                }

                // 1. တောင်သူအမည်
                if (!name.value.trim()) {
                    showError(name, 'တောင်သူအမည် ဖြည့်ပေးပါ။');
                } else if (name.value.trim().length > 150) {
                    showError(name, 'တောင်သူအမည်သည် အများဆုံး 150 လုံး ဖြစ်ရပါမယ်။');
                }

                // 2. ဖုန်းနံပါတ် (မဖြည့်လည်းရ)
                const phoneValue = phone.value.trim();

                if (phoneValue && !/^[0-9၀-၉+\-\s()]+$/.test(phoneValue)) {
                    showError(phone, 'ဖုန်းနံပါတ်ကို မှန်ကန်စွာ ဖြည့်ပေးပါ။');
                } else if (phoneValue.length > 30) {
                    showError(phone, 'ဖုန်းနံပါတ်သည် အများဆုံး 30 လုံး ဖြစ်ရပါမယ်။');
                }

                // 3. ကျေးရွာ (မဖြည့်လည်းရ)
                if (village.value.length > 150) {
                    showError(village, 'ကျေးရွာအမည်သည် အများဆုံး 150 လုံး ဖြစ်ရပါမယ်။');
                }

                // Validation မအောင်မြင်လျှင် Request မပို့ပါ
                if (!isValid) {
                    form.querySelector('.is-invalid').focus();
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
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: new FormData(form)
                    });

                    const data = await response.json();

                    if (!response.ok) {

                        if (data.errors) {
                            Object.entries(data.errors).forEach(function([field, messages]) {
                                const input = form.querySelector('[name="' + field + '"]');

                                if (input) {
                                    showError(input, messages[0]);
                                }
                            });
                        } else {
                            modalError.textContent =
                                data.message || 'တောင်သူအသစ် သိမ်း၍မရပါ။';
                            modalError.style.display = 'block';
                        }

                        const firstInvalid = form.querySelector('.is-invalid');

                        if (firstInvalid) {
                            firstInvalid.focus();
                        }

                        return;
                    }

                    bootstrap.Modal.getOrCreateInstance(
                        document.getElementById('newFarmerModal')
                    ).hide();

                    form.reset();

                    selectFarmer(data.farmer);

                } catch (error) {
                    modalError.textContent = 'Server နှင့် ချိတ်ဆက်၍မရပါ။';
                    modalError.style.display = 'block';

                } finally {
                    saveButton.disabled = false;
                    saveButton.innerHTML =
                        '<i class="bi bi-check-circle me-1"></i> သိမ်းပြီးရွေးမည်';
                }
            };

            // Input ပြင်ဆင်လိုက်လျှင် အမှားစာကို ဖယ်မည်
            ['new_farmer_name', 'new_farmer_phone', 'new_farmer_village']
            .forEach(function(id) {
                const input = document.getElementById(id);

                input.addEventListener('input', function() {
                    input.classList.remove('is-invalid');

                    const next = input.nextElementSibling;

                    if (next && next.classList.contains('validation-error')) {
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
            const gradeSelect = document.getElementById('grade_id');
            const qtyPerBasketInput = document.getElementById('quantity_per_basket');
            const unitInput = document.getElementById('unit');
            const commissionInput = document.getElementById('commission_amount');
            const referencePriceInput = document.getElementById('reference_price');
            const actualPriceInput = document.getElementById('actual_buy_price');

            // သီးနှံရွေးလိုက်လျှင် Grade ရှာမည်
            cropSelect.addEventListener('change', async function() {

                const cropId = this.value;
                const option = this.options[this.selectedIndex];

                gradeSelect.innerHTML =
                    '<option value="">အမျိုးအစားရွေးချယ်ပါ</option>';

                gradeSelect.disabled = true;
                referencePriceInput.value = '';

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

                grades.forEach(function(grade) {

                    const option = document.createElement('option');

                    option.value = grade.id;
                    option.textContent = grade.grade_name;

                    gradeSelect.appendChild(option);
                });

                gradeSelect.disabled = false;
            });

            // Grade ရွေးလိုက်လျှင် ယနေ့ဈေး ရှာမည်
            gradeSelect.addEventListener('change', async function() {

                const cropId = cropSelect.value;
                const gradeId = this.value;

                referencePriceInput.value = '';

                if (!cropId || !gradeId) {
                    return;
                }

                const params = new URLSearchParams({
                    crop_id: cropId,
                    grade_id: gradeId
                });

                const response = await fetch(
                    `{{ route('purchases#reference-price') }}?${params}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );

                const data = await response.json();

                referencePriceInput.value = data.reference_price;
                actualPriceInput.value = data.reference_price;
            });

        });
    </script>
@endpush
{{-- သီသီးနှံစာရင်းအတွက် သီးနှံဈေး အလိုအလျောက်ပြပေးတဲ့ JS အဆုံး --}}


{{-- သီးနှံစာရင်း တွက်တဲ့အပိုင်း JS အစ --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const cropSelect = document.getElementById('crop_id');
            const gradeSelect = document.getElementById('grade_id');
            const quantityInput = document.getElementById('quantity');
            const qtyPerBasketInput = document.getElementById('quantity_per_basket');
            const unitInput = document.getElementById('unit');
            const referencePriceInput = document.getElementById('reference_price');
            const actualPriceInput = document.getElementById('actual_buy_price');
            const commissionInput = document.getElementById('commission_amount');
            const carFeeInput = document.getElementById('car_fee_amount');
            const noteInput = document.getElementById('item_note');
            const addButton = document.getElementById('add_crop_item');
            const tableBody = document.getElementById('purchase_items_body');
            const hiddenInputs = document.getElementById('purchase_items_inputs');

            let items = [];
            let editingIndex = null;

            const gradesUrl = @json(url('/purchases/grades'));

            // ငွေကြေးပုံစံ
            function money(value) {
                return Number(value || 0).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            // Input ထဲက ငွေတန်ဖိုး
            function numberValue(input) {
                return Number(input.value || 0);
            }

            function calculate(item) {
                const qtyPerBasket = Number(item.quantity_per_basket) || 0;
                const totalBaskets = qtyPerBasket > 0 ? (Number(item.quantity) / qtyPerBasket) : Number(item
                    .quantity);

                const actual_buy_price_after_sub_commission_amt = Number(item.actual_buy_price) - Number(item
                    .commission_amount);
                item.gross_amount = Number((totalBaskets * actual_buy_price_after_sub_commission_amt).toFixed(2));

                item.total_deduction = Number(item.car_fee_amount);

                item.net_amount = Number((item.gross_amount - item.total_deduction).toFixed(2));
                // console.table(item);
            }

            // Form ကိုရှင်းမည်
            function resetFields() {
                cropSelect.value = '';

                gradeSelect.innerHTML =
                    '<option value="">အရင်ဆုံး သီးနှံရွေးချယ်ပါ</option>';

                gradeSelect.disabled = true;

                quantityInput.value = '';
                unitInput.value = '';
                referencePriceInput.value = '';
                actualPriceInput.value = '';
                commissionInput.value = 0;
                carFeeInput.value = 0;
                noteInput.value = '';

                // Hidden Input ကိုပါ ရှင်းလင်းပေးရန်
                const qtyPerBasketInput = document.getElementById('quantity_per_basket');
                if (qtyPerBasketInput) {
                    qtyPerBasketInput.value = '';
                }

                editingIndex = null;

                addButton.innerHTML =
                    '<i class="bi bi-plus-lg me-1"></i> သီးနှံစာရင်းထဲ ထည့်မည်';
            }

            // သီးနှံစာရင်းနှင့် စုစုပေါင်းကို ပြမည်
            function renderItems() {
                tableBody.replaceChildren();
                hiddenInputs.replaceChildren();

                if (items.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                သီးနှံစာရင်း မထည့်ရသေးပါ။
                            </td>
                        </tr>
                        `;
                }

                let grossTotal = 0;
                let deductionTotal = 0;
                let netTotal = 0;

                items.forEach(function(item, index) {
                    calculate(item);

                    grossTotal += item.gross_amount;
                    deductionTotal += item.total_deduction;
                    netTotal += item.net_amount;

                    const row = document.createElement('tr');

                    row.innerHTML = `
                            <td class="text-center">${index + 1}</td>

                            <td>
                                <div class="fw-bold">
                                    ${escapeHtml(item.crop_name)}
                                </div>
                                <small class="text-muted">
                                    ${escapeHtml(item.grade_name)}
                                </small>
                            </td>

                            <td class="text-end">
                                ${Number(item.quantity).toLocaleString('en-US')}
                                ${item.unit.toLowerCase() === 'viss' ? 'ပိဿာ' : (item.unit.toLowerCase() === 'pound' ? 'ပေါင်' : escapeHtml(item.unit))}
                            </td>

                            <td class="text-end">
                                ${Number(item.quantity / item.quantity_per_basket).toLocaleString('en-US')}
                            </td>

                            <td class="text-end">
                                ${money(item.actual_buy_price)}
                            </td>

                            <td class="text-end">
                                ${money(item.commission_amount)}
                            </td>

                            <td class="text-end">
                                ${money(item.car_fee_amount)}
                            </td>

                            <td class="text-end fw-bold">
                                ${money(item.net_amount)}
                            </td>

                            <td class="text-center text-nowrap">

                                <button type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    data-action="delete" data-index="${index}">
                                    <i class="bi bi-trash me-2"></i>ဖျက်မည်
                                </button>
                            </td>
                        `;

                    tableBody.appendChild(row);

                    // နောက်ပိုင်း Purchase သိမ်းရန်
                    Object.entries({
                        crop_id: item.crop_id,
                        grade_id: item.grade_id,
                        quantity: item.quantity,
                        unit: item.unit,
                        reference_price: item.reference_price,
                        actual_buy_price: item.actual_buy_price,
                        commission_amount: item.commission_amount,
                        car_fee_amount: item.car_fee_amount,
                        note: item.note
                    }).forEach(function([key, value]) {
                        const input = document.createElement('input');

                        input.type = 'hidden';
                        input.name = `purchase_items[${index}][${key}]`;
                        input.value = value ?? '';

                        hiddenInputs.appendChild(input);
                    });
                });

                document.getElementById('item_count').textContent =
                    `သီးနှံ ${items.length} မျိုး`;

                document.getElementById('items_gross_total').textContent =
                    money(grossTotal);

                document.getElementById('items_deduction_total').textContent =
                    money(deductionTotal);

                document.getElementById('items_net_total').textContent =
                    money(netTotal);
            }

            // သီးနှံစာရင်းထဲ ထည့်မည်
            addButton.addEventListener('click', function() {

                const cropOption =
                    cropSelect.options[cropSelect.selectedIndex];

                const gradeOption =
                    gradeSelect.options[gradeSelect.selectedIndex];

                const quantity = numberValue(quantityInput);
                const actualPrice = numberValue(actualPriceInput);
                const commission = numberValue(commissionInput);
                const carFee = numberValue(carFeeInput);

                if (!cropSelect.value) {
                    alert('သီးနှံရွေးချယ်ပါ။');
                    cropSelect.focus();
                    return;
                }

                if (!gradeSelect.value) {
                    alert('သီးနှံအမျိုးအစား ရွေးချယ်ပါ။');
                    gradeSelect.focus();
                    return;
                }

                if (quantity <= 0 || !Number.isFinite(quantity)) {
                    alert('အရေအတွက်ကို မှန်ကန်စွာ ဖြည့်ပါ။');
                    quantityInput.focus();
                    return;
                }

                if (
                    actualPrice < 0 ||
                    !Number.isFinite(actualPrice) ||
                    actualPriceInput.value.trim() === ''
                ) {
                    alert('အမှန်တကယ်ဝယ်ဈေး ဖြည့်ပါ။');
                    actualPriceInput.focus();
                    return;
                }

                if (
                    commission < 0 || carFee < 0 ||
                    !Number.isFinite(commission) ||
                    !Number.isFinite(carFee)
                ) {
                    alert('ပွဲခနှင့် ကားခကို မှန်ကန်စွာ ဖြည့်ပါ။');
                    return;
                }

                const item = {
                    crop_id: cropSelect.value,
                    crop_name: cropOption.textContent.trim(),

                    grade_id: gradeSelect.value,
                    grade_name: gradeOption.textContent.trim(),

                    quantity: quantity,
                    unit: unitInput.value,
                    quantity_per_basket: numberValue(qtyPerBasketInput) || 0,

                    reference_price: numberValue(referencePriceInput),
                    actual_buy_price: actualPrice,

                    commission_amount: commission,
                    car_fee_amount: carFee,

                    note: noteInput.value.trim()
                };

                if (editingIndex !== null) {
                    items[editingIndex] = item;
                } else {
                    items.push(item);
                }

                renderItems();
                resetFields();
            });

            // ဖျက်ခြင်း
            tableBody.addEventListener('click', async function(event) {

                const button = event.target.closest('button[data-action]');

                if (!button) return;

                const index = Number(button.dataset.index);
                const action = button.dataset.action;
                const item = items[index];

                if (!item) return;

                if (action === 'delete') {
                    if (!confirm('ဒီသီးနှံစာရင်းကို ဖျက်မှာ သေချာပါသလား။')) {
                        return;
                    }

                    items.splice(index, 1);
                    renderItems();

                    if (editingIndex === index) {
                        resetFields();
                    }

                    return;
                }
            });

            renderItems();

        });
    </script>
@endpush
{{-- သီးနှံစာရင်း တွက်တဲ့အပိုင်း JS အဆုံး --}}




{{-- Phone number Input (Only) တွေမှာ number တွေကို myn to eng ပြောင်းပေးတဲ့ JS  --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const phoneInputs = ['phone', 'new_farmer_phone', 'farmer_search'];
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
            const priceInputs = ['quantity', 'reference_price', 'actual_buy_price', 'commission_amount',
                'car_fee_amount'
            ];
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
