@extends('layouts.app')

@section('page-title', 'Add Crop')

@section('breadcrumb', 'အခြေခံစာရင်းများ / သီးနှံစာရင်း / သီးနှံအသစ် ထပ်မံထည့်သွင်းခြင်း')

@section('content')

    <div class="mb-4">

        <h1 class="page-title mb-1">
            သီးနှံအသစ် ထပ်မံထည့်သွင်းခြင်း
        </h1>

        <p class="text-muted mb-0 mt-2" style="line-height: 30px">
            ပွဲရုံတွင် ရောင်းဝယ်အသုံးပြုမည့် သီးနှံအသစ်များကို အောက်ပါအချက်အလက်များ ဖြည့်သွင်း၍ ထပ်မံစာရင်းသွင်းနိုင်ပါသည်။
        </p>

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

                        <a href="{{ route('Crop#list') }}" class="btn btn-light">
                            နောက်သို့
                        </a>

                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-lg me-1"></i>
                            သီးနှံထည့်သွင်းမည်
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection
