@extends('layouts.app')

@section('page-title', 'Edit Daily Price')

@section('breadcrumb', 'ဈေးနှုန်းများ / နေ့စဉ်သီးနှံဈေးနှုန်းများ / နေ့စဉ်သီးနှံဈေးနှုန်းများကိုပြင်ဆင်ရန်')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
        <div>
            <h1 class="page-title mb-1 fs-3 fs-md-2">နေ့စဉ်သီးနှံဈေးနှုန်းများကိုပြင်ဆင်ရန်</h1>
            <p class="text-muted mb-0 mt-1" style="line-height: 1.5">
                သီးနှံနှင့် အရည်အသွေးအဆင့်အလိုက် သတ်မှတ်ထားသော ဝယ်ဈေးနှုန်းကို ပြင်ဆင်နိုင်ပါသည်။
            </p>
        </div>
        <div class="d-flex justify-content-end w-100 w-md-auto">
            <a href="{{ route('DailyPrice#list') }}" class="btn rounded-pill px-4 shadow-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>
    </div>

    <div class="dashboard-card shadow-sm p-3 p-md-4">
        <div class="mb-3">
            <h5 class="mb-1 fw-bold" style="line-height: 1.4">
                {{ $dailyPrice->crop_name }}သီးနှံနှင့် {{ $dailyPrice->grade_name }}အရည်အသွေးအဆင့်အလိုက် သတ်မှတ်ထားသော
                ဝယ်ဈေးနှုန်းကို ပြင်ဆင်နိုင်ပါသည်။
            </h5>
            <small class="text-muted d-block mt-1" style="line-height: 1.5">
                သတ်မှတ်ထားသော ဝယ်ဈေးနှုန်းကို လိုအပ်သလို ပြင်ဆင်၊ ပြောင်းလဲနိုင်ပါသည်။
            </small>
        </div>

        <form action="{{ route('DailyPrice#update', $dailyPrice->id) }}" method="POST" id="editDailyPriceForm">
            @csrf
            <div class="row align-items-end g-3">
                <input type="hidden" name="crop_id" value="{{ $dailyPrice->crop_id }}">
                <input type="hidden" name="grade_id" value="{{ $dailyPrice->grade_id }}">

                {{-- Reference Buy Price --}}
                <div class="col-12 col-md-6 offset-md-1">
                    <label class="form-label mb-1">
                        ဝယ်ဈေးနှုန်း
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="number" name="reference_buy_price" id="price" class="form-control text-end"
                            value="{{ old('reference_buy_price', $dailyPrice->reference_buy_price) }}"
                            placeholder="Enter buying price">
                        <span class="input-group-text">
                            ကျပ်
                        </span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="col-12 col-md-4 d-flex justify-content-start">
                    <button type="submit" class="btn btn-success w-100 w-md-auto rounded-pill px-4 shadow-sm" id="submitButton">
                        <i class="bi bi-check-lg me-1"></i>
                        ပြင်ဆင်မည်
                    </button>
                </div>

            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const form = document.getElementById('editDailyPriceForm');
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
                ဝယ်ဈေးနှုန်းပြင်ဆင်နေသည်...
            `;
        });

        document.addEventListener('DOMContentLoaded', function() {
            const price = document.getElementById('price');
            if (!price) return;

            price.addEventListener('input', function() {
                this.value = this.value.replace(/[၀-၉]/g, function(digit) {
                    return '၀၁၂၃၄၅၆၇၈၉'.indexOf(digit).toString();
                });
            });
        });
    </script>
@endpush
