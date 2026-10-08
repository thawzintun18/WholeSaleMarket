@extends('wholeSaleMarket.layout.main')

@section('content')
    <div class="container-fluid px-3 px-lg-4 py-4">
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon"><i class="bi bi-award"></i></span>
                <div>
                    <h1 class="h3 mb-1">သီးနှံအရည်အသွေးအဆင့်ထည့်သွင်းရန်</h1>
                    <div class=" d-flex mt-3">
                        <a href="{{ route('WholeSaleMarket#dashboard') }}" class=" mx-2">
                            <small>ပင်မစာမျက်နှာ</small>
                        </a>
                        <i class="bi bi-arrow-right font-weight-bolder"></i>
                        <a href="{{ route('Crop#list') }}" class=" mx-2">
                            <small>သီးနှံစာရင်း</small>
                        </a>
                        <i class="bi bi-arrow-right font-weight-bolder"></i>
                        <p class=" mx-2">သီးနှံအရည်အသွေးအဆင့်ထည့်သွင်းရန်</p>

                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid py-4">

            <div class="crop-form-card">

                <!-- Header -->
                <div class="crop-form-header">

                    <div class=" d-flex justify-content-between">
                        <h5>
                            <span class="page-icon"><i class="bi bi-award"></i></span>
                            " {{ $crop_name }} "သီးနှံအတွက် အရည်အသွေးအဆင့်ထည့်သွင်းရန်
                        </h5>
                        <div class="">
                            <a href="{{ route('Crop#list') }}" class=" btn btn-sm btn-light btn-back">နောက်သို့</a>
                        </div>
                    </div>

                </div>


                <!-- Form -->
                <div class="crop-form-body">

                    <form id="cropForm" action="{{ route('Crop#create') }}" method="POST">

                        @csrf

                        <div class="row g-4">

                            <!-- Crop Name -->
                            <div class="col-12 col-md-6">

                                <label for="crop_name" class="form-label">
                                    သီးနှံအမည်
                                    <span class="required">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="bi bi-flower1"></i>
                                    </span>

                                    <input type="text" name="crop_name" id="crop_name" value="{{ old('crop_name') }}"
                                        class="form-control myanmar-input @error('crop_name') is-invalid @enderror"
                                        placeholder="ဥပမာ - ဆန်၊ ပြောင်း၊ နှမ်း">

                                </div>

                                @error('crop_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <!-- Commission Amount -->
                            <div class="col-12 col-md-6">

                                <label for="commission_amount" class="form-label">
                                    ပွဲခ
                                    <span class="required">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="bi bi-cash-stack"></i>
                                    </span>

                                    <input type="number" name="commission_amount" id="commission_amount"
                                        value="{{ old('commission_amount') }}" min="0"
                                        class="form-control @error('commission_amount') is-invalid @enderror"
                                        placeholder="ဥပမာ - 500">

                                </div>

                                @error('commission_amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <!-- Quantity Per Basket -->
                            <div class="col-12 col-md-6">

                                <label for="quantity_per_basket" class="form-label">
                                    တစ်တင်းပါ အရေအတွက်
                                    <span class="required">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="bi bi-box-seam"></i>
                                    </span>

                                    <input type="number" name="quantity_per_basket" id="quantity_per_basket"
                                        value="{{ old('quantity_per_basket') }}" min="1"
                                        class="form-control @error('quantity_per_basket') is-invalid @enderror"
                                        placeholder="ဥပမာ - 20">

                                </div>

                                @error('quantity_per_basket')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <!-- Unit -->
                            <div class="col-12 col-md-6">

                                <label for="unit" class="form-label">
                                    ယူနစ်
                                    <span class="required">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="bi bi-rulers"></i>
                                    </span>

                                    <select name="unit" id="unit"
                                        class="form-select @error('unit') is-invalid @enderror">

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

                                </div>

                                @error('unit')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                        </div>


                        <!-- Buttons -->
                        <div class="d-flex justify-content-end gap-2 mt-4 button-group">

                            <button type="submit" class="btn-create">
                                <i class="bi bi-plus-lg me-1"></i>
                                သီးနှံထည့်သွင်းမည်
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>
@endsection
