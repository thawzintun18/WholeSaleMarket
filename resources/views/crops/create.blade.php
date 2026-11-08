@extends('layouts.app')

@section('page-title', 'Add Crop')

@section('breadcrumb', 'အခြေခံစာရင်းများ / သီးနှံစာရင်း / သီးနှံအသစ် ထပ်မံထည့်သွင်းခြင်း')

@section('content')

    <div class="d-md-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="page-title mb-1">
                သီးနှံအသစ် ထပ်မံထည့်သွင်းခြင်း
            </h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                ပွဲရုံတွင် ရောင်းဝယ်အသုံးပြုမည့် သီးနှံအသစ်များကို အောက်ပါအချက်အလက်များ ဖြည့်သွင်း၍
                ထပ်မံစာရင်းသွင်းနိုင်ပါသည်။
            </p>
        </div>

        <div class=" d-flex justify-content-end mt-md-0 mt-2">
            <a href="{{ route('Crop#list') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>
    </div>


    <div class="row">

        <div class="offset-lg-2 col-lg-8 col-12">

            <div class="dashboard-card shadow-sm">

                <form id="cropForm" action="{{ route('Crop#create') }}" method="POST">

                    @csrf


                    {{-- Crop Name --}}
                    <div class="mb-3">

                        <label class="form-label">
                            သီးနှံအမည်
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text" name="crop_name" id="crop_name"
                            class="form-control p-2 @error('crop_name') is-invalid @enderror" value="{{ old('crop_name') }}"
                            placeholder="ဥပမာ - ပြောင်း၊ နှမ်း">

                        @error('crop_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            ပွဲခ
                            <span class="text-danger">*</span>
                        </label>

                        <input type="number" name="commission_amount" id="commission_amount"
                            class="form-control p-2 @error('commission_amount') is-invalid @enderror"
                            value="{{ old('commission_amount') }}" placeholder="ဥပမာ - 2000">

                        @error('commission_amount')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Quantity Per Basket --}}
                    <div class="mb-4">

                        <label class="form-label">
                            တစ်တင်းပါ အရေအတွက်
                            <span class="text-danger">*</span>
                        </label>

                        <input type="number" name="quantity_per_basket" id="quantity_per_basket"
                            class="form-control p-2 @error('quantity_per_basket') is-invalid @enderror"
                            value="{{ old('quantity_per_basket') }}" min="0" step="0.01" placeholder="ဥပမာ - 20">

                        @error('quantity_per_basket')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="form-text">
                            တစ်တင်းတွင် ပါဝင်သည့် ပမာဏကို ထည့်သွင်းပါ။
                        </div>

                    </div>


                    {{-- Unit --}}
                    <div class="mb-3">

                        <label class="form-label">
                            ယူနစ်
                            <span class="text-danger">*</span>
                        </label>

                        <select name="unit" id="unit" class="form-select p-2 @error('unit') is-invalid @enderror">

                            <option value="" disabled {{ old('unit') ? '' : 'selected' }}>
                                ယူနစ်ရွေးချယ်ပါ
                            </option>

                            <option value="viss" {{ old('unit') == 'viss' ? 'selected' : '' }}>
                                ပိဿာ
                            </option>

                            <option value="pound" {{ old('unit') == 'pound' ? 'selected' : '' }}>
                                ပေါင်
                            </option>

                        </select>

                        @error('unit')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Buttons --}}
                    <div class="d-flex justify-content-end gap-2">

                        <button type="submit" class="btn btn-success" id="submitBtn">
                            <i class="bi bi-check-lg me-1"></i>
                            သီးနှံထည့်သွင်းမည်
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection
{{-- @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const priceInputs = ['quantity_per_basket', 'commission_amount'];
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
@endpush --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const crop_name = document.getElementById('crop_name');
            const commission_amount = document.getElementById('commission_amount');
            const quantity_per_basket = document.getElementById('quantity_per_basket');


            if (!crop_name) return;
            if (!commission_amount) return;
            if (!quantity_per_basket) return;

            crop_name.addEventListener('input', function() {
                this.value = this.value.replace(/[၀-၉]/g, function(digit) {
                    return '၀၁၂၃၄၅၆၇၈၉'.indexOf(digit).toString();
                });
            });

            commission_amount.addEventListener('input', function() {
                this.value = this.value.replace(/[၀-၉]/g, function(digit) {
                    return '၀၁၂၃၄၅၆၇၈၉'.indexOf(digit).toString();
                });
            });

            quantity_per_basket.addEventListener('input', function() {
                this.value = this.value.replace(/[၀-၉]/g, function(digit) {
                    return '၀၁၂၃၄၅၆၇၈၉'.indexOf(digit).toString();
                });
            });

        });

        //submit button
        document.getElementById('cropForm').addEventListener('submit', function() {

            let submitBtn = document.getElementById('submitBtn');

            submitBtn.disabled = true;

            submitBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-1"></span>
            စာရင်းသွင်းနေပါသည်...
        `;

        });
    </script>
@endpush
