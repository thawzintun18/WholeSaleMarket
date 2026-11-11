@extends('layouts.app')

@section('page-title', 'Add Crop')

@section('breadcrumb', 'ဈေးနှုန်းများ / ကြိုသုံးငွေယူထားသောတောင်သူစာရင်း / ကြိုသုံးငွေယူမည့်တောင်သူထည့်ခြင်း')

@section('content')

    <div class="d-flex flex-column flex-md-row gap-2 mb-4">

        {{-- တောင်သူအသစ်ထည့်ခြင်း --}}
        <a href="{{ route('FarmerDebt#directPage') }}"
            class="btn rounded-pill shadow-sm px-4 {{ request()->routeIs('FarmerDebt#directPage') ? 'btn-dark' : 'btn-outline-dark' }}">
            <i class="bi bi-person-plus me-2"></i>
            တောင်သူအသစ်ထည့်ရန်
        </a>

        {{-- ရှိပြီးသားတောင်သူအတွက် ကြိုသုံးငွေ --}}
        <a href="{{ route('FarmerDebt#directPageSettlement') }}"
            class="btn rounded-pill shadow-sm px-4 {{ request()->routeIs('FarmerDebt#directPageSettlement') ? 'btn-dark' : 'btn-outline-dark' }} ">
            <i class="bi bi-cash-stack me-2"></i>
            ကြိုသုံးငွေထုတ်ပေးရန်
        </a>

    </div>

    <div class="d-md-flex justify-content-between align-items-center mb-4 mt-3">

        <div>
            <h1 class="page-title mb-1" style="line-height: 38px">
                ကြိုသုံးငွေယူမည့် တောင်သူထပ်မံထည့်သွင်းခြင်း
            </h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                ကြိုသုံးငွေယူမည့် တောင်သူအသစ်ကို ရွေးချယ်ထည့်သွင်းပြီး ကြိုတင်ထုတ်ပေးမည့် ငွေပမာဏနှင့်
                သက်ဆိုင်ရာအချက်အလက်များကို မှတ်တမ်းတင်နိုင်ပါသည်။
            </p>
        </div>

        <div class=" d-flex justify-content-end mt-md-0 mt-2">
            <a href="{{ route('FarmerDebt#list') }}" class="btn btn-outline-secondary rounded-pill shadow-sm px-4">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>
    </div>


    <div class="row">

        <div class="offset-lg-2 col-lg-8 col-12">

            <div class="dashboard-card shadow-sm">

                <form id="form" action="{{ route('FarmerDebt#create') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        {{-- Farmer Name --}}
                        <div class="col-md-12">
                            <label for="name" class="form-label fw-semibold">
                                တောင်သူအမည်
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" placeholder="တောင်သူအမည်ထည့်ပါ"
                                maxlength="150" autofocus>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-12">
                            <label for="phone" class="form-label fw-semibold">
                                ဖုန်းနံပါတ်
                            </label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                class="form-control @error('phone') is-invalid @enderror" placeholder="ဥပမာ - 09xxxxxxxxx"
                                maxlength="30">
                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Village --}}
                        <div class="col-md-12">
                            <label for="village" class="form-label fw-semibold">
                                ကျေးရွာအမည်
                            </label>
                            <input type="text" id="village" name="village" value="{{ old('village') }}"
                                class="form-control @error('village') is-invalid @enderror" placeholder="ကျေးရွာအမည်ထည့်ပါ"
                                maxlength="150">
                            @error('village')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Amount --}}
                        <div class="col-md-12">
                            <label for="amount" class="form-label fw-semibold">
                                ငွေပမာဏ
                            </label>
                            <input type="text" id="amount" name="amount" value="{{ old('amount') }}"
                                class="form-control @error('amount') is-invalid @enderror" placeholder="ငွေပမာဏပမာဏထည့်ပါ"
                                maxlength="150" inputmode="decimal">
                            @error('amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Active Status --}}
                        <div class="col-md-12">
                            <label for="debt_type" class="form-label fw-semibold">
                                အကြွေးအမျိုးအစား
                                <span class="text-danger">*</span>
                            </label>
                            <select id="debt_type" name="debt_type"
                                class="form-select @error('debt_type') is-invalid @enderror">
                                <option value="">အကြွေးအမျိုးအစား ရွေးချယ်ပါ</option>
                                <option value="PurchaseAdvance"
                                    {{ old('debt_type') == 'PurchaseAdvance' ? 'selected' : '' }}>
                                    ကြိုသုံးငွေ
                                </option>
                                <option value="CashLoan" {{ old('debt_type') == 'CashLoan' ? 'selected' : '' }}>
                                    တောကြွေး
                                </option>
                                <option value="BeanPayment" {{ old('debt_type') == 'BeanPayment' ? 'selected' : '' }}>
                                    ပဲဆံဖိုး
                                </option>
                            </select>
                            @error('debt_type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Notes --}}
                        <div class="col-12">
                            <label for="notes" class="form-label fw-semibold">
                                မှတ်ချက်
                            </label>
                            <textarea id="notes" name="notes" rows="4" class="form-control @error('notes') is-invalid @enderror"
                                placeholder="တောင်သူနှင့်ပတ်သက်သော မှတ်ချက်များ ထည့်ပါ">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="d-flex justify-content-end mt-3 gap-2">
                        <button type="submit" class="btn btn-success rounded-pill shadow-sm px-4 py-2" id="submitButton">
                            <i class="bi bi-check-lg me-1"></i>
                            ကြိုသုံးငွေယူမည့် တောင်သူထည့်သွင်းမည်
                        </button>
                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection
@push('scripts')
    <script>

        const form = document.getElementById('form');
        const button = document.getElementById('submitButton');
        let isSubmitting = false;
        form.addEventListener('submit', function (event) {
            // Submit လုပ်ပြီးသားဆို ထပ်မလုပ်ရ
            if (isSubmitting) {
                event.preventDefault();
                return;
            }
            // Browser Validation မအောင်မြင်ရင် မပိတ်ရ
            if (!form.checkValidity()) {
                return;
            }
            isSubmitting = true;
            // Button Disable
            button.disabled = true;
            // Loading ပြ
            button.innerHTML = `
                <span class="spinner-border spinner-border-sm me-1"></span>
                ခဏစောင့်ပါ...
            `;
        });


        document.addEventListener('DOMContentLoaded', function() {

            const phoneInputs = ['phone'];
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
            const priceInputs = ['amount'];
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
