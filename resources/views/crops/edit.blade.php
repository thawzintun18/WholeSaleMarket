@extends('layouts.app')

@section('page-title', 'Edit Crop')

@section('breadcrumb', 'အခြေခံစာရင်းများ / သီးနှံစာရင်း / သီးနှံအချက်အလက်များကိုပြင်ဆင်ရန်')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">

        <div>
            <h1 class="page-title mb-1 fs-3 fs-md-2">
                သီးနှံအချက်အလက်များကိုပြင်ဆင်ရန်
            </h1>

            <p class="text-muted mb-0 mt-1" style="line-height: 1.5">
                သီးနှံအမည်၊ ပွဲခနှင့် တစ်တင်းပါ အရေအတွက်တို့ကို လိုအပ်သလို ပြင်ဆင်နိုင်ပါသည်။
            </p>
        </div>

        <div class="d-flex justify-content-end w-100 w-md-auto">
            <a href="{{ route('Crop#list') }}" class="btn rounded-pill px-4 shadow-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>

    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-12">
            <div class="dashboard-card shadow-sm p-3 p-md-4">
                <form id="editCropForm" action="{{ route('Crop#update', $crop->id) }}" method="POST">
                    @csrf
                    {{-- Crop Name --}}
                    <div class="mb-3">
                        <label class="form-label mb-1">
                            သီးနှံအမည်
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="crop_name" id="crop_name"
                            class="form-control p-2 @error('crop_name') is-invalid @enderror"
                            value="{{ old('crop_name', $crop->crop_name) }}" placeholder="ဥပမာ - ပြောင်း၊ နှမ်း">
                        @error('crop_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label mb-1">
                            ပွဲခ
                            <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="commission_amount" id="commission_amount"
                            class="form-control p-2 @error('commission_amount') is-invalid @enderror"
                            value="{{ old('commission_amount', $crop->commission_amount) }}" placeholder="ဥပမာ - 2000">
                        @error('commission_amount')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    {{-- Quantity Per Basket --}}
                    <div class="mb-3">
                        <label class="form-label mb-1">
                            တစ်တင်းပါ အရေအတွက်
                            <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="quantity_per_basket" id="quantity_per_basket"
                            class="form-control p-2 @error('quantity_per_basket') is-invalid @enderror"
                            value="{{ old('quantity_per_basket', $crop->quantity_per_basket) }}" min="0"
                            step="0.01" placeholder="ဥပမာ - 20">
                        @error('quantity_per_basket')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text mt-1">
                            တစ်တင်းတွင် ပါဝင်သည့် ပမာဏကို ထည့်သွင်းပါ။
                        </div>
                    </div>


                    {{-- Unit --}}
                    <div class="mb-4">
                        <label class="form-label mb-1">
                            ယူနစ်
                            <span class="text-danger">*</span>
                        </label>
                        <select name="unit" id="unit" class="form-select p-2 @error('unit') is-invalid @enderror">
                            <option value="" disabled {{ old('unit') ? '' : 'selected' }}>
                                ယူနစ်ရွေးချယ်ပါ
                            </option>
                            <option value="viss" {{ old('unit', $crop->unit) == 'viss' ? 'selected' : '' }}>
                                ပိဿာ
                            </option>
                            <option value="pound" {{ old('unit', $crop->unit) == 'pound' ? 'selected' : '' }}>
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
                        <button type="submit" class="btn rounded-pill px-4 shadow-sm btn-success" id="submitButton">
                            <i class="bi bi-check-lg me-1"></i>
                            သီးနှံအချက်အလက်ပြင်ဆင်မည်
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>

        const form = document.getElementById('editCropForm');
        const button = document.getElementById('submitButton');
        let isSubmitting = false;
        form.addEventListener('submit', function(event) {
            if (isSubmitting) {
                event.preventDefault();
                return;
            }
            if (!form.checkValidity()) {
                return;
            }
            isSubmitting = true;
            button.disabled = true;
            button.innerHTML = `
                <span class="spinner-border spinner-border-sm me-1"></span>
                ခဏစောင့်ပါ...
            `;
        });



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
    </script>
@endpush
