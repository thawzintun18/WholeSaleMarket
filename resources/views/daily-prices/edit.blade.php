@extends('layouts.app')

@section('page-title', 'Edit Daily Price')

@section('breadcrumb', 'ဈေးနှုန်းများ / နေ့စဉ်သီးနှံဈေးနှုန်းများ / နေ့စဉ်သီးနှံဈေးနှုန်းများကိုပြင်ဆင်ရန်')

@section('content')

    <div class="d-md-flex justify-content-between align-items-center mb-4">


        <div>
            <h1 class="page-title mb-1">နေ့စဉ်သီးနှံဈေးနှုန်းများကိုပြင်ဆင်ရန်</h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                သီးနှံနှင့် အရည်အသွေးအဆင့်အလိုက် သတ်မှတ်ထားသော ဝယ်ဈေးနှုန်းကို ပြင်ဆင်နိုင်ပါသည်။
            </p>
        </div>

        <div class="d-flex justify-content-end">
            <a href="{{ route('DailyPrice#list') }}" class="btn btn-outline-secondary mt-md-0 mt-3">
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>
        </div>

    </div>

    <div class="dashboard-card shadow-sm">


        <div class="mb-4">

            <h5 class="mb-1" style="line-height: 35px">
                {{ $dailyPrice->crop_name }}သီးနှံနှင့် {{ $dailyPrice->grade_name }}အရည်အသွေးအဆင့်အလိုက် သတ်မှတ်ထားသော
                ဝယ်ဈေးနှုန်းကို ပြင်ဆင်နိုင်ပါသည်။
            </h5>

            <small class="text-muted" style="line-height: 30px">
                သတ်မှတ်ထားသော ဝယ်ဈေးနှုန်းကို လိုအပ်သလို ပြင်ဆင်၊ ပြောင်းလဲနိုင်ပါသည်။
            </small>

        </div>


        <form action="{{ route('DailyPrice#update', $dailyPrice->id) }}" method="POST">
            @csrf
            <div class="row g-4">

                <input type="hidden" name="crop_id" value="{{ $dailyPrice->crop_id }}">

                <input type="hidden" name="grade_id" value="{{ $dailyPrice->grade_id }}">

                {{-- Reference Buy Price --}}
                <div class="col-md-5 offset-md-2">

                    <label class="form-label">
                        ဝယ်ဈေးနှုန်း
                        <span class="text-danger">*</span>
                    </label>

                    <div class="input-group">

                        <input type="number" name="reference_buy_price" class="form-control text-end"
                            value="{{ old('reference_buy_price', $dailyPrice->reference_buy_price) }}"
                            placeholder="Enter buying price">

                        <span class="input-group-text">
                            ကျပ်
                        </span>

                    </div>

                </div>

                {{-- Actions --}}
                <div class="col-md-3 d-flex justify-content-md-start justify-content-end pt-4">

                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i>
                        ဝယ်ဈေးနှုန်းကိုပြင်ဆင်မည်
                    </button>

                </div>

            </div>
        </form>
    </div>

@endsection
