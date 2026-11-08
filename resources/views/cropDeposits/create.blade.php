@extends('layouts.app')

@section('page-title', 'CropDeposit')
@section('breadcrumb')
    အခြေခံစာရင်းများ / သီးနှံအပ်နှံ စာရင်း / သီးနှံအပ်နှံခြင်း အသစ်
@endsection
@section('content')
    <div class="container-fluid p-0">

        @include('partials.purchase-sale-deposit-tabs')

        <!-- Header Section -->
        <div class="d-md-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="bi bi-box-arrow-in-down me-2 text-primary" style="line-height: 35px"></i>သီးနှံအပ်နှံမှု
                    အသစ်လက်ခံခြင်း
                </h3>
                <p class="text-muted small mb-0 mt-2" style="line-height: 30px">တောင်သူထံမှ သီးနှံများကို
                    အပ်နှံသိမ်းဆည်းရန်အတွက် အချက်အလက်များကို
                    အောက်ပါအတိုင်း ဖြည့်သွင်းပါ</p>
            </div>
            <div class=" d-flex justify-content-end mt-md-0 mt-3">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-1"></i> နောက်သို့
                </a>
            </div>
        </div>

        <form action="{{ route('crop#deposits#create') }}" method="POST" id="depositForm">
            @csrf

            <!-- ၁။ တောင်သူရွေးချယ်ခြင်း -->
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <div class=" d-md-flex justify-content-between align-items-center">
                        <div class="">
                            <h5 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-person-check me-2 text-primary"></i>တောင်သူရွေးချယ်ခြင်း
                            </h5>
                        </div>
                        <div class=" d-flex justify-content-end mt-2 mt-md-0">
                            <button type="button" class="btn btn-success px-3" data-bs-toggle="modal"
                                data-bs-target="#newFarmerModal">
                                <i class="bi bi-person-plus me-1"></i> တောင်သူအသစ်ထည့်မည်
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label fw-semibold">တောင်သူအမည် / ဖုန်းနံပါတ် / ကုဒ်ဖြင့် ရှာဖွေရန် <span
                                    class="text-danger">*</span></label>
                            <div class="position-relative">
                                <div class="input-group input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                                    <input type="text" id="farmer_search" class="form-control"
                                        placeholder="တောင်သူနာမည် (သို့) ဖုန်းနံပါတ် ရိုက်ထည့်ပါ..." autocomplete="off">
                                    <button type="button" class="btn btn-outline-secondary px-4"
                                        id="clear_farmer">ပယ်ဖျက်မည်</button>
                                </div>
                                <!-- Search Results Dropdown -->
                                <div id="farmer_results" class="list-group position-absolute w-100 shadow mt-1"
                                    style="z-index: 1050; display: none; max-height: 300px; overflow-y: auto;"></div>
                            </div>

                            <input type="hidden" name="farmer_id" id="farmer_id" required>
                            @error('farmer_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- ရွေးချယ်ပြီးသော တောင်သူအချက်အလက်ပြရန် Box -->
                    <div id="selected_farmer_box"
                        class="border border-success border-opacity-25 rounded-4 p-4 mt-4 bg-light shadow-sm"
                        style="display: none;">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 55px; height: 55px;">
                                    <i class="bi bi-person-fill fs-2"></i>
                                </div>
                                <div>
                                    <div class="text-success small fw-semibold mb-1">
                                        <i class="bi bi-check-circle-fill me-1"></i> ရွေးချယ်ထားသော တောင်သူ
                                    </div>
                                    <h4 class="fw-bold text-dark mb-1" id="selected_farmer_name"></h4>
                                    <div class="d-flex flex-wrap gap-3 text-secondary small">
                                        <span><i class="bi bi-telephone me-1"></i><strong class="text-dark"
                                                id="selected_farmer_phone"></strong></span>
                                        <span><i class="bi bi-geo-alt me-1"></i><strong class="text-dark"
                                                id="selected_farmer_village"></strong></span>
                                        <span>ကုဒ် - <strong class="text-dark" id="selected_farmer_code"></strong></span>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="change_farmer" class="btn btn-outline-success btn-sm px-3">
                                <i class="bi bi-arrow-repeat me-1"></i> ပြန်ရွေးမည်
                            </button>
                        </div>
                    </div>

                    <div id="farmer_error" class="alert alert-danger mt-3 mb-0" style="display: none;"></div>
                </div>
            </div>

            <!-- ၂. အထွေထွေ အချက်အလက် (ကားနံပါတ်) -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">ကားနံပါတ်</label>
                            <input type="text" name="car_no" class="form-control form-control-lg"
                                value="{{ old('car_no') }}" placeholder="ဥပမာ - 1A/2344">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">ကားခ</label>
                            <input type="text" name="car_fee_amount" class="form-control form-control-lg"
                                value="{{ old('car_fee_amount') }}" placeholder="ကားခထည့်ရန်" id="car_fee_amount">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ၃. သီးနှံအချက်အလက်ထည့်ရန် စာရင်းများ -->
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-box-seam me-2 text-primary"></i>သီးနှံအသေးစိတ် စာရင်းများ
                    </h5>
                    <button type="button" class="btn btn-success btn-sm px-3" id="addRowBtn">
                        <i class="bi bi-plus-lg me-1"></i> သီးနှံ ထပ်ထည့်ရန်
                    </button>
                </div>

                <div class="card-body p-4">
                    <div id="depositItemsContainer">
                        <!-- Item Row တစ်ခုချင်းစီ (Dynamic ဖြင့် ထပ်တိုးမည်) -->
                        <div class="deposit-item-row border rounded-4 p-4 mb-3 bg-light position-relative">
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-3 remove-row-btn"
                                aria-label="Close" style="display: none;"></button>

                            <div class="row g-3">
                                <!-- သီးနှံအမည် -->
                                <div class="col-md-6 col-xl-6">
                                    <label class="form-label fw-semibold">သီးနှံအမည် <span
                                            class="text-danger">*</span></label>
                                    <select name="deposit_items[0][crop_id]" class="form-select crop_id" required>
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

                                <input type="hidden" class="quantity_per_basket" value="">

                                <!-- သီးနှံအမျိုးအစား (Grade) -->
                                <div class="col-md-6 col-xl-6">
                                    <label class="form-label fw-semibold">သီးနှံအမျိုးအစား</label>
                                    <select name="deposit_items[0][grade_id]" class="form-select grade_id" disabled
                                        required>
                                        <option value="">အရင်ဆုံး သီးနှံရွေးချယ်ပါ</option>
                                    </select>
                                </div>

                                <!-- အရေအတွက် နှင့် အိတ်ရေ -->
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <label class="form-label fw-semibold">အရေအတွက် <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="deposit_items[0][quantity]" class="form-control quantity"
                                        min="0.01" step="0.01" placeholder="အရေအတွက်" id="quantity" required>
                                    <div class="d-none mt-2 bag_count_wrapper">
                                        <input type="text" name="deposit_items[0][bag_count]"
                                            class="form-control bag_count" placeholder="အိတ်အရေအတွက် ထည့်ပါ"
                                            id="bag_count" value="0">
                                    </div>
                                </div>

                                <!-- ယူနစ် -->
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <label class="form-label fw-semibold">ယူနစ်</label>
                                    <input type="text" class="form-control unit" readonly
                                        placeholder="သီးနှံရွေးချယ်ပါ">
                                </div>

                                <!-- ယနေ့ အဖွင့်ဈေး -->
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <label class="form-label fw-semibold">ယနေ့ အဖွင့်ဈေး</label>
                                    <input type="number" name="deposit_items[0][reference_price]"
                                        class="form-control reference_price" min="0" step="0.01"
                                        placeholder="ယနေ့သတ်မှတ်ဈေး" id="reference_price" readonly>
                                </div>

                                <!-- အမှန်တကယ်ဝယ်မည့်ဈေး -->
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <label class="form-label fw-semibold">တကယ်ပေးမည့်ဈေး <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="deposit_items[0][actual_buy_price]"
                                        class="form-control actual_buy_price" min="0" step="0.01"
                                        placeholder="ဝယ်ယူမည့်ဈေး" id="actual_buy_price" required>
                                </div>

                                <!-- မှတ်ချက် -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">မှတ်ချက် <span
                                            class="text-muted small">(မထည့်လဲရ)</span></label>
                                    <input type="text" name="deposit_items[0][note]" class="form-control item_note"
                                        maxlength="255" placeholder="လိုအပ်ပါက မှတ်ချက်ရေးပါ">
                                </div>


                            </div>

                        </div>
                        <!-- Submit Button Section -->
                            <div class="text-end mb-5">
                                <button type="submit" class="btn btn-success shadow" id="submitButton">
                                    <i class="bi bi-check-circle-fill me-2"></i> သီးနှံအပ်နှံမှု အားလုံး သိမ်းဆည်းမည်
                                </button>
                            </div>
                    </div>
                </div>
            </div>


        </form>
    </div>

    <!-- တောင်သူအသစ်ထည့်ရန် Modal -->
    <div class="modal fade" id="newFarmerModal" tabindex="-1" aria-labelledby="newFarmerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header bg-light px-4 py-3">
                    <h5 class="modal-title fw-bold" id="newFarmerModalLabel">
                        <i class="bi bi-person-plus me-2 text-success"></i>တောင်သူအသစ် စာရင်းသွင်းရန်
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="new_farmer_form">
                    @csrf
                    <div class="modal-body p-4">
                        <div id="new_farmer_error" class="alert alert-danger" style="display: none;"></div>

                        <div class="mb-3">
                            <label for="new_farmer_name" class="form-label fw-semibold">တောင်သူအမည် <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" id="new_farmer_name"
                                class="form-control form-control-lg" placeholder="အမည်ထည့်ပါ" maxlength="150" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_farmer_phone" class="form-label fw-semibold">ဖုန်းနံပါတ် <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="phone" id="new_farmer_phone"
                                class="form-control form-control-lg" placeholder="ဖုန်းနံပါတ်" maxlength="30" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_farmer_village" class="form-label fw-semibold">ကျေးရွာ <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="village" id="new_farmer_village"
                                class="form-control form-control-lg" placeholder="ကျေးရွာအမည်" maxlength="150" required>
                        </div>

                        <div class="mb-0">
                            <label for="new_farmer_notes" class="form-label fw-semibold">မှတ်ချက်</label>
                            <textarea name="notes" id="new_farmer_notes" class="form-control" rows="2"
                                placeholder="မှတ်ချက် (မရှိလည်းရ)"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer bg-light px-4 py-3">
                        <button type="button" class="btn btn-secondary px-4"
                            data-bs-dismiss="modal">မလုပ်တော့ပါ</button>
                        <button type="submit" id="save_farmer_btn" class="btn btn-success px-4">
                            <i class="bi bi-check-circle me-1"></i> သိမ်းပြီးရွေးမည်
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- 1. Farmer Search & Select Logic ---
            const searchInput = document.getElementById('farmer_search');
            const resultsBox = document.getElementById('farmer_results');
            const farmerIdInput = document.getElementById('farmer_id');
            const selectedBox = document.getElementById('selected_farmer_box');
            const errorBox = document.getElementById('farmer_error');

            const searchUrl = @json(route('farmers#search'));
            const storeUrl = @json(route('farmers#store'));
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            let searchTimer;

            function escapeHtml(str) {
                return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g,
                    '&quot;');
            }

            function selectFarmer(farmer) {
                farmerIdInput.value = farmer.id;
                document.getElementById('selected_farmer_name').textContent = farmer.name || '';
                document.getElementById('selected_farmer_code').textContent = farmer.farmer_code || '';
                document.getElementById('selected_farmer_phone').textContent = farmer.phone || 'မရှိ';
                document.getElementById('selected_farmer_village').textContent = farmer.village || 'မရှိ';

                searchInput.value = farmer.name || '';
                selectedBox.style.display = 'block';
                resultsBox.style.display = 'none';
                errorBox.style.display = 'none';
            }

            function renderResults(farmers, query) {
                resultsBox.replaceChildren();

                if (farmers.length === 0) {
                    const div = document.createElement('div');
                    div.className = 'list-group-item d-flex align-items-center justify-content-between p-3';
                    div.innerHTML =
                        `<span class="text-danger">တောင်သူမတွေ့ပါ။</span>
                                 <button type="button" class="btn btn-sm btn-success px-3" onclick="document.getElementById('new_farmer_name').value='${escapeHtml(query)}'; bootstrap.Modal.getOrCreateInstance(document.getElementById('newFarmerModal')).show();">အသစ်ထည့်ရန်</button>`;
                    resultsBox.appendChild(div);
                    resultsBox.style.display = 'block';
                    return;
                }

                farmers.forEach(farmer => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'list-group-item list-group-item-action p-3';
                    button.innerHTML =
                        `<div class="fw-bold fs-6">${escapeHtml(farmer.name)}</div>
                                    <div class="text-muted small"><i class="bi bi-telephone me-1"></i>${escapeHtml(farmer.phone || 'ဖုန်းမရှိ')} | <i class="bi bi-geo-alt me-1"></i>${escapeHtml(farmer.village || 'ရွာမရှိ')}</div>`;
                    button.onclick = () => selectFarmer(farmer);
                    resultsBox.appendChild(button);
                });
                resultsBox.style.display = 'block';
            }

            searchInput.addEventListener('input', function() {
                const query = searchInput.value.trim();
                clearTimeout(searchTimer);
                farmerIdInput.value = '';
                selectedBox.style.display = 'none';

                if (!query) {
                    resultsBox.style.display = 'none';
                    return;
                }

                searchTimer = setTimeout(async () => {
                    try {
                        const res = await fetch(
                            `${searchUrl}?search=${encodeURIComponent(query)}`, {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            });
                        if (!res.ok) throw new Error('ရှာဖွေမှု အမှားရှိသည်။');
                        const farmers = await res.json();
                        if (searchInput.value.trim() === query) renderResults(farmers, query);
                    } catch (err) {
                        errorBox.textContent = err.message;
                        errorBox.style.display = 'block';
                    }
                }, 300);
            });

            document.getElementById('clear_farmer').onclick = () => {
                searchInput.value = '';
                farmerIdInput.value = '';
                selectedBox.style.display = 'none';
                resultsBox.style.display = 'none';
                searchInput.focus();
            };

            document.getElementById('change_farmer').onclick = () => {
                farmerIdInput.value = '';
                searchInput.value = '';
                selectedBox.style.display = 'none';
                searchInput.focus();
            };

            // --- 2. New Farmer Store via Modal ---
            document.getElementById('new_farmer_form').onsubmit = async function(e) {
                e.preventDefault();
                const form = e.currentTarget;
                const saveBtn = document.getElementById('save_farmer_btn');
                const modalError = document.getElementById('new_farmer_error');

                modalError.style.display = 'none';
                saveBtn.disabled = true;
                saveBtn.innerHTML = 'သိမ်းဆည်းနေသည်...';

                try {
                    const res = await fetch(storeUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: new FormData(form)
                    });
                    const data = await res.json();

                    if (!res.ok) throw new Error(data.message || 'သိမ်းဆည်း၍ မရပါ။');

                    bootstrap.Modal.getOrCreateInstance(document.getElementById('newFarmerModal')).hide();
                    form.reset();
                    selectFarmer(data.farmer);
                } catch (err) {
                    modalError.textContent = err.message;
                    modalError.style.display = 'block';
                } finally {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i> သိမ်းပြီးရွေးမည်';
                }
            };

            // --- 3. Dynamic Deposit Items & Purchase-like JS Logic ---
            let rowIndex = 0;
            const container = document.getElementById('depositItemsContainer');
            const addBtn = document.getElementById('addRowBtn');

            // Event delegation for dynamic rows
            container.addEventListener('change', async function(e) {
                const row = e.target.closest('.deposit-item-row');
                if (!row) return;

                const cropSelect = row.querySelector('.crop_id');
                const gradeSelect = row.querySelector('.grade_id');
                const qtyPerBasketInput = row.querySelector('.quantity_per_basket');
                const unitInput = row.querySelector('.unit');
                const referencePriceInput = row.querySelector('.reference_price');
                const actualPriceInput = row.querySelector('.actual_buy_price');
                const bagCountWrapper = row.querySelector('.bag_count_wrapper');
                const bagCountInput = row.querySelector('.bag_count');

                // သီးနှံရွေးလိုက်လျှင်
                if (e.target.classList.contains('crop_id')) {
                    const cropId = cropSelect.value;
                    const option = cropSelect.options[cropSelect.selectedIndex];

                    gradeSelect.innerHTML = '<option value="">အမျိုးအစားရွေးချယ်ပါ</option>';
                    gradeSelect.disabled = true;
                    referencePriceInput.value = '';

                    const rawUnit = option.dataset.unit || '';
                    let displayUnit = rawUnit;
                    if (rawUnit.toLowerCase() === 'viss') {
                        displayUnit = 'ပိဿာ';
                    } else if (rawUnit.toLowerCase() === 'pound') {
                        displayUnit = 'ပေါင်';
                    }
                    unitInput.value = displayUnit;

                    if (rawUnit.toLowerCase() === 'pound') {
                        bagCountWrapper.classList.remove('d-none');
                    } else {
                        bagCountWrapper.classList.add('d-none');
                        bagCountInput.value = '0';
                    }

                    qtyPerBasketInput.value = option.dataset.quantityPerBasket || 0;

                    if (!cropId) return;

                    try {
                        const response = await fetch(
                            `{{ url('/whole-sale-market/purchases/grades') }}/${cropId}`, {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            });
                        const grades = await response.json();

                        grades.forEach(grade => {
                            const opt = document.createElement('option');
                            opt.value = grade.id;
                            opt.textContent = grade.grade_name;
                            gradeSelect.appendChild(opt);
                        });

                        gradeSelect.disabled = false;
                    } catch (err) {
                        console.error('Grade ဆွဲထုတ်၍ မရပါ။', err);
                    }
                }

                // Grade ရွေးလိုက်လျှင် ဈေးရှာမည်
                if (e.target.classList.contains('grade_id')) {
                    const cropId = cropSelect.value;
                    const gradeId = gradeSelect.value;

                    referencePriceInput.value = '';

                    if (!cropId || !gradeId) return;

                    const params = new URLSearchParams({
                        crop_id: cropId,
                        grade_id: gradeId
                    });

                    try {
                        const response = await fetch(
                            `{{ route('purchases#reference-price') }}?${params}`, {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            });
                        const data = await response.json();

                        referencePriceInput.value = data.reference_price || 0;
                        actualPriceInput.value = data.reference_price || 0;
                    } catch (err) {
                        console.error('ဈေးနှုန်း ဆွဲထုတ်၍ မရပါ။', err);
                    }
                }
            });

            // သီးနှံအသစ်ထည့်ရန် Row အသစ်တိုးခြင်း
            addBtn.addEventListener('click', function() {
                rowIndex++;
                const firstRow = container.querySelector('.deposit-item-row');
                const newRow = firstRow.cloneNode(true);

                newRow.querySelectorAll('input').forEach(input => {
                    if (input.classList.contains('bag_count')) {
                        input.value = '0';
                    } else {
                        input.value = '';
                    }
                });
                newRow.querySelectorAll('select').forEach(select => {
                    select.selectedIndex = 0;
                    if (select.classList.contains('grade_id')) {
                        select.disabled = true;
                        select.innerHTML = '<option value="">အရင်ဆုံး သီးနှံရွေးချယ်ပါ</option>';
                    }
                });
                newRow.querySelector('.bag_count_wrapper').classList.add('d-none');

                // Name attribute တွေကို index အသစ်ဖြင့် ပြောင်းလဲရန်
                newRow.querySelectorAll('input, select').forEach(el => {
                    let name = el.getAttribute('name');
                    if (name) {
                        name = name.replace(/\[\d+\]/, `[${rowIndex}]`);
                        el.setAttribute('name', name);
                    }
                });

                newRow.querySelector('.remove-row-btn').style.display = 'block';
                container.appendChild(newRow);
                updateRemoveButtons();
            });

            // Row ဖျက်ခြင်း
            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-row-btn')) {
                    e.target.closest('.deposit-item-row').remove();
                    updateRemoveButtons();
                }
            });

            function updateRemoveButtons() {
                const rows = container.querySelectorAll('.deposit-item-row');
                rows.forEach(row => {
                    row.querySelector('.remove-row-btn').style.display = rows.length > 1 ? 'block' : 'none';
                });
            }
        });
    </script>
@endpush

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
        (function () {
            const form = document.getElementById('depositForm');
            const button = document.getElementById('submitButton');
            const addRowButton = document.getElementById('addRowBtn');
            if (!form || !button) return;
            let isSubmitting = false;
            form.addEventListener('submit', function (event) {
                if (isSubmitting) {
                    event.preventDefault();
                    return;
                }
                // Validation စစ်ဆေးရန်
                if (!form.checkValidity()) {
                    return;
                }
                isSubmitting = true;
                // Submit Button ပိတ်ရန်
                button.disabled = true;
                // Dynamic Row ထပ်ထည့်ခြင်း ပိတ်ရန်
                if (addRowButton) {
                    addRowButton.disabled = true;
                }
                button.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    အပ်နှံမှုစာရင်း သိမ်းဆည်းနေသည်...
                `;
            });
        })();


        document.addEventListener('DOMContentLoaded', function() {
            const priceInputs = ['quantity', 'reference_price', 'actual_buy_price',
                'bag_count',
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
