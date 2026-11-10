@extends('layouts.app')

@section('page-title', 'Edit Grade')

@section('breadcrumb', 'အခြေခံစာရင်းများ / သီးနှံ အရည်အသွေးစာရင်း / သီးနှံ အရည်အသွေးများကိုပြင်ဆင်ရန်')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">

        <div>
            <h1 class="page-title mb-1 fs-3 fs-md-2">သီးနှံ အရည်အသွေးများကိုပြင်ဆင်ရန်</h1>

            <p class="text-muted mb-0 mt-1" style="line-height: 1.5;">
                သီးနှံအရည်အသွေးအမည်များကို လိုအပ်သလို ပြင်ဆင်နိုင်ပါသည်။
            </p>
        </div>

        <div class="d-flex justify-content-end w-100 w-md-auto">
            <a href="{{ route('Quality#list') }}" class="btn rounded-pill px-4 shadow-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>

    </div>

    <div class="dashboard-card shadow-sm p-3 p-md-4">

        <div class="mb-3">

            <h5 class="mb-1 fw-bold" style="line-height: 1.4;">
                {{ $grade->crop_name }}သီးနှံအတွက် အရည်အသွေးပြင်ဆင်ရန်
            </h5>

            <small class="text-muted d-block mt-1" style="line-height: 1.5;">
                သတ်မှတ်ထားသော သီးနှံအရည်အသွေးအမည်ကို လိုအပ်သလို ပြင်ဆင်၊ ပြောင်းလဲနိုင်ပါသည်။
            </small>

        </div>

        <form action="{{ route('Quality#update', $grade->id) }}" method="POST" id="editQualityForm">
            @csrf
            <input type="hidden" name="crop_id" value="{{ $grade->crop_id }}">
            <div class="row align-items-end g-3">
                {{-- Grade Name --}}
                <div class="col-12 col-md-6 offset-md-1">
                    <label for="grade_name" class="form-label mb-1">
                        အရည်အသွေးအမည် <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="grade_name" id="grade_name"
                        value="{{ old('grade_name', $grade->grade_name) }}"
                        class="form-control @error('grade_name') is-invalid @enderror" placeholder="e.g. A, B, C"
                        maxlength="50">
                    @error('grade_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-12 col-md-4 d-flex justify-content-start">
                    <button type="submit" class="btn rounded-pill btn-success shadow-sm w-100 w-md-auto" id="submitButton">
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
        const form = document.getElementById('editQualityForm');
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
                ပြင်ဆင်နေသည်...
            `;
        });

        document.addEventListener('DOMContentLoaded', function() {
            const grade_name = document.getElementById('grade_name');

            if (!grade_name) return;

            grade_name.addEventListener('input', function() {
                this.value = this.value.replace(/[၀-၉]/g, function(digit) {
                    return '၀၁၂၃၄၅၆၇၈၉'.indexOf(digit).toString();
                });
            });
        });
    </script>
@endpush
