blade
@extends('layouts.app')

@section('page-title', 'Add Farmer')

@section('breadcrumb')
    မူလစာမျက်နှာ / တောင်သူများ / တောင်သူအသစ်ထည့်
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title mb-1">တောင်သူအသစ်ထည့်မည်</h1>
        <p class="text-muted mb-0">
            တောင်သူအသစ်ထည့်သွင်းရန် အချက်အလက်ဖြည့်ပါ
        </p>
    </div>

    <a href="{{ route('farmers#index') }}"
       class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        နောက်သို့
    </a>
</div>
<div class="dashboard-card shadow-lg p-4">
    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('warning') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif
    <form action="{{ route('farmers#create') }}"
          method="POST">

        @csrf

        <div class="row g-4">

            {{-- Farmer Name --}}
            <div class="col-md-6">
                <label for="name" class="form-label fw-semibold">
                    တောင်သူအမည်
                    <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    class="form-control @error('name') is-invalid @enderror"
                    placeholder="တောင်သူအမည်ထည့်ပါ"
                    maxlength="150"
                    required
                    autofocus
                >

                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Phone --}}
            <div class="col-md-6">
                <label for="phone" class="form-label fw-semibold">
                    ဖုန်းနံပါတ်
                </label>

                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="{{ old('phone') }}"
                    class="form-control @error('phone') is-invalid @enderror"
                    placeholder="ဥပမာ - 09xxxxxxxxx"
                    maxlength="30"
                >

                @error('phone')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Village --}}
            <div class="col-md-6">
                <label for="village" class="form-label fw-semibold">
                    ကျေးရွာအမည်
                </label>

                <input
                    type="text"
                    id="village"
                    name="village"
                    value="{{ old('village') }}"
                    class="form-control @error('village') is-invalid @enderror"
                    placeholder="ကျေးရွာအမည်ထည့်ပါ"
                    maxlength="150"
                >

                @error('village')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Active Status --}}
            <div class="col-md-6">
                <label for="is_active" class="form-label fw-semibold">
                    Status
                    <span class="text-danger">*</span>
                </label>

                <select
                    id="is_active"
                    name="is_active"
                    class="form-select @error('is_active') is-invalid @enderror"
                    required
                >
                    <option value="1"
                        {{ old('is_active', '1') == '1' ? 'selected' : '' }}>
                        အသုံးပြုနေသည် (Active)
                    </option>

                    <option value="0"
                        {{ old('is_active') === '0' ? 'selected' : '' }}>
                        ပိတ်ထားသည် (Inactive)
                    </option>
                </select>

                @error('is_active')
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

                <textarea
                    id="notes"
                    name="notes"
                    rows="4"
                    class="form-control @error('notes') is-invalid @enderror"
                    placeholder="တောင်သူနှင့်ပတ်သက်သော မှတ်ချက်များ ထည့်ပါ"
                >{{ old('notes') }}</textarea>

                @error('notes')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

        </div>

        <hr class="my-4">

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('farmers#index') }}"
               class="btn btn-outline-danger">
                <i class="bi bi-x-lg me-1"></i>
                ပယ်ဖျက်မည်
            </a>

            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-lg me-1"></i>
                သိမ်းဆည်းမည်
            </button>
        </div>

    </form>

</div>

@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const phoneInput = document.getElementById('phone');

        if (!phoneInput) return;

        phoneInput.addEventListener('input', function () {
            const myanmarNumbers = '၀၁၂၃၄၅၆၇၈၉';

            this.value = this.value.replace(/[၀-၉]/g, function (digit) {
                return myanmarNumbers.indexOf(digit);
            });
        });
    });
</script>
@endpush
