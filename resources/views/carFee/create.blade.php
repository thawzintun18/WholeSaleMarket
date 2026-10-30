@extends('layouts.app')

@section('page-title', 'Add Crop')

@section('breadcrumb', 'Stock / ကားဘောင်ချာစာရင်း / ကားဘောင်ချာ ထပ်မံထည့်သွင်းခြင်း')

@section('content')

    <div class="d-md-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="page-title mb-1">
                ကားဘောင်ချာ ထပ်မံထည့်သွင်းခြင်း
            </h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                ကားဘောင်ချာအသစ်များ ထပ်မံထည့်သွင်းပြီး သယ်ယူပို့ဆောင်ခနှင့် သက်ဆိုင်သော အချက်အလက်များကို
                စာရင်းသွင်းနိုင်ပါသည်။
            </p>
        </div>

        <div class=" d-flex justify-content-end mt-md-0 mt-2">
            <a href="{{ route('CarFee#list') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>
    </div>

    <div class="container-fluid">

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-dark">
                {{ session('success') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-light border border-dark text-dark">
                <strong>အချက်အလက် စစ်ဆေးပေးပါ။</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Payment Form --}}
        <div class="dashboard-card shadow-sm mb-4">
            <div class="card-header bg-white text-dark fw-bold">
                ကားခ စုစုပေါင်းတွက်ချက်ခြင်း
            </div>

            <div class="card-body">
                <form action="{{ route('CarFee#create') }}" method="POST" id="paymentForm">
                    @csrf

                    <div class="row g-3">

                        {{-- Car Number --}}
                        <div class="col-12 col-md-4">
                            <label class="form-label">
                                ကားနံပါတ် ရှာဖွေရွေးချယ်ရန်
                            </label>

                            <input type="text" id="car_no" name="car_no" class="form-control" list="carList"
                                value="{{ old('car_no') }}" placeholder="ကားနံပါတ် ရိုက်ထည့်ပါ" autocomplete="off" required>

                            <datalist id="carList"></datalist>

                            <div id="carMessage" class="form-text text-dark"></div>
                        </div>

                        {{-- Driver Name --}}
                        <div class="col-12 col-md-4">
                            <label class="form-label">
                                ကားသမားအမည်
                            </label>

                            <input type="text" name="driver_name" class="form-control" value="{{ old('driver_name') }}"
                                maxlength="150" placeholder="ကားသမားအမည် ဖြည့်ပါ" required>
                        </div>

                        {{-- Total Amount --}}
                        <div class="col-12 col-md-4">
                            <label class="form-label">
                                ကားခပေးရမည့် စုစုပေါင်းငွေ
                            </label>

                            <input type="text" id="total_amount" class="form-control bg-light" value=""
                                placeholder="ကားနံပါတ်ရွေးပြီးမှ ပြပါမည်" readonly>
                        </div>

                        <div class=" d-flex justify-content-end col-12">
                            <div class=" d-flex">
                                <a href="{{ route('CarFee#directPage') }}" class="btn btn-outline-danger me-2">
                                    ပြန်လည်သတ်မှတ်မည်
                                </a>

                                <button type="submit" id="saveButton" class="btn btn-success" disabled>
                                    <i class="bi bi-save me-1"></i>
                                    တွက်ချက်ပြီး သိမ်းမည်
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carInput = document.getElementById('car_no');
            const carList = document.getElementById('carList');
            const totalInput = document.getElementById('total_amount');
            const message = document.getElementById('carMessage');
            const saveButton = document.getElementById('saveButton');

            const searchUrl = @json(route('CarFee#search'));

            let selectedCar = '';
            let requestNumber = 0;

            function resetAmount() {
                totalInput.value = '';
                saveButton.disabled = true;
                selectedCar = '';
            }

            async function searchCars() {
                const currentRequest = ++requestNumber;
                const carNo = carInput.value.trim();

                resetAmount();
                carList.replaceChildren();

                if (!carNo) {
                    message.textContent = '';
                    return;
                }

                message.textContent = 'ရှာဖွေနေပါသည်...';

                try {
                    const response = await fetch(
                        searchUrl + '?search=' + encodeURIComponent(carNo), {
                            headers: {
                                'Accept': 'application/json'
                            }
                        }
                    );

                    const data = await response.json();

                    if (currentRequest !== requestNumber) return;

                    (data.cars || []).forEach(function(car) {
                        const option = document.createElement('option');
                        option.value = car;
                        carList.appendChild(option);
                    });

                    message.textContent = carList.options.length ?
                        'တွေ့ရှိသော ကားနံပါတ်ကို ရွေးချယ်ပါ။' :
                        'ကိုက်ညီသော ကားနံပါတ် မတွေ့ပါ။';

                    // ရိုက်ထည့်ထားသည့်ကားနံပါတ်က စာရင်းနှင့် အတိအကျကိုက်မှ တွက်မယ်
                    if ((data.cars || []).includes(carNo)) {
                        await calculateCarFee(carNo, currentRequest);
                    }
                } catch (error) {
                    if (currentRequest !== requestNumber) return;
                    message.textContent = 'ရှာဖွေရာတွင် အမှားဖြစ်နေပါသည်။';
                }
            }

            async function calculateCarFee(carNo, currentRequest) {
                try {
                    const response = await fetch(
                        searchUrl + '?car_no=' + encodeURIComponent(carNo), {
                            headers: {
                                'Accept': 'application/json'
                            }
                        }
                    );

                    const data = await response.json();

                    if (currentRequest !== requestNumber) return;

                    if (data.already_paid) {
                        message.textContent = data.message;
                        resetAmount();
                        return;
                    }

                    if (!response.ok) {
                        message.textContent = data.message || 'စာရင်း မတွေ့ပါ။';
                        resetAmount();
                        return;
                    }

                    totalInput.value = Number(data.total_amount).toLocaleString(
                        'en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }
                    );

                    selectedCar = carNo;
                    message.textContent =
                        'ကားခပေးရမည့် စုစုပေါင်းငွေတွက်ပြီးပါပြီ';

                    saveButton.disabled = false;

                } catch (error) {
                    if (currentRequest !== requestNumber) return;
                    message.textContent = 'ကားခတွက်ချက်ရာတွင် အမှားဖြစ်နေပါသည်။';
                    resetAmount();
                }
            }

            carInput.addEventListener('input', searchCars);

            carInput.addEventListener('change', function() {
                const carNo = carInput.value.trim();

                if (carNo && carList.options.length) {
                    const found = Array.from(carList.options)
                        .some(option => option.value === carNo);

                    if (!found) {
                        resetAmount();
                        message.textContent = 'စာရင်းထဲရှိ ကားနံပါတ်ကို ရွေးပါ။';
                    }
                }
            });

            document.getElementById('paymentForm')
                .addEventListener('submit', function(event) {
                    if (!selectedCar || selectedCar !== carInput.value.trim()) {
                        event.preventDefault();
                        alert('ကားနံပါတ်ကို ပြန်ရွေးချယ်ပေးပါ။');
                        return;
                    }

                    saveButton.disabled = true;
                    saveButton.textContent = 'သိမ်းဆည်းနေပါသည်...';
                });
        });
    </script>
@endpush
