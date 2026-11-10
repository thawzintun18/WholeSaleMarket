@extends('layouts.app')

@section('page-title', 'Edit Farmer')

@section('breadcrumb')
    အခြေခံစာရင်းများ / ၀ယ်သူစာရင်း / ၀ယ်သူအချက်အလက်ပြင်ဆင်
@endsection

@section('content')

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
        <div>
            <h1 class="page-title mb-1 fs-3 fs-md-2">၀ယ်သူအချက်အလက်ပြင်ဆင်မည်</h1>
            <p class="text-muted mb-0 mt-1" style="line-height: 1.5">
                ၀ယ်သူ၏ အချက်အလက်များကို ပြင်ဆင်နိုင်ပါသည်
            </p>
        </div>

        <div class="d-flex justify-content-end w-100 w-md-auto">
            <a href="{{ route('Customer#list') }}" class="btn rounded-pill px-4 shadow-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>
    </div>

    <div class="dashboard-card shadow-lg p-3 p-md-4">

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show mb-3">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('warning') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('Customer#update', $customer->id) }}" method="POST" id="editCustomerForm">
            @csrf
            <div class="row g-3">
                {{-- customer Code --}}
                <div class="col-12 col-md-6">
                    <label for="customer_code" class="form-label fw-semibold mb-1">
                        ၀ယ်သူကုဒ်
                    </label>

                    <input type="text" id="customer_code" value="{{ $customer->customer_code }}" class="form-control"
                        readonly>
                </div>

                {{-- customer Name --}}
                <div class="col-12 col-md-6">
                    <label for="name" class="form-label fw-semibold mb-1">
                        ၀ယ်သူအမည်
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}"
                        class="form-control @error('name') is-invalid @enderror" placeholder="၀ယ်သူအမည်ထည့်ပါ"
                        maxlength="150" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="col-12 col-md-6">
                    <label for="phone" class="form-label fw-semibold mb-1">
                        ဖုန်းနံပါတ်
                    </label>

                    <input type="text" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}"
                        class="form-control @error('phone') is-invalid @enderror" placeholder="ဥပမာ - 09xxxxxxxxx"
                        maxlength="30">

                    @error('phone')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Village --}}
                <div class="col-12 col-md-6">
                    <label for="address" class="form-label fw-semibold mb-1">
                        လိပ်စာအမည်
                    </label>

                    <input type="text" id="address" name="address" value="{{ old('address', $customer->address) }}"
                        class="form-control @error('address') is-invalid @enderror" placeholder="လိပ်စာအမည်ထည့်ပါ"
                        maxlength="150">

                    @error('address')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Active Status --}}
                <div class="col-12 col-md-6">
                    <label for="is_active" class="form-label fw-semibold mb-1">
                        Status
                        <span class="text-danger">*</span>
                    </label>

                    <select id="is_active" name="is_active" class="form-select @error('is_active') is-invalid @enderror"
                        required>
                        <option value="1"
                            {{ (string) old('is_active', $customer->is_active) === '1' ? 'selected' : '' }}>
                            အသုံးပြုနေသည် (Active)
                        </option>

                        <option value="0"
                            {{ (string) old('is_active', $customer->is_active) === '0' ? 'selected' : '' }}>
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
                    <label for="notes" class="form-label fw-semibold mb-1">
                        မှတ်ချက်
                    </label>

                    <textarea id="notes" name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror"
                        placeholder="၀ယ်သူနှင့်ပတ်သက်သော မှတ်ချက်များ ထည့်ပါ">{{ old('notes', $customer->notes) }}</textarea>

                    @error('notes')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>

            <hr class="my-3">

            <div class="d-flex flex-column flex-md-row justify-content-end gap-2">
                <a href="{{ route('Customer#list') }}" class="btn btn-outline-danger w-100 w-md-auto text-center rounded-pill shadow-sm">
                    <i class="bi bi-x-lg me-1"></i>
                    ပယ်ဖျက်မည်
                </a>

                <button type="submit" class="btn btn-success w-100 w-md-auto rounded-pill shadow-sm" id="submitButton">
                    <i class="bi bi-check-lg me-1"></i>
                    ပြင်ဆင်ချက်သိမ်းမည်
                </button>
            </div>

        </form>

    </div>

@endsection

@push('scripts')
    <script>
        const form = document.getElementById('editCustomerForm');
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
                ပြင်ဆင်ချက်သိမ်းနေသည်...
            `;
        });

        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.getElementById('phone');
            if (!phoneInput) return;
            phoneInput.addEventListener('input', function() {
                const myanmarNumbers = '၀၁၂၃၄၅၆၇၈၉';
                this.value = this.value.replace(/[၀-၉]/g, function(digit) {
                    return myanmarNumbers.indexOf(digit);
                });
            });
        });
    </script>
@endpush
