@extends('layouts.app')

@section('page-title', 'Add Crop')

@section('breadcrumb', 'ဈေးနှုန်းများ / ကြိုသုံးငွေယူထားသောတောင်သူစာရင်း / အကြွေးယူထားသောငွေကိုဆပ်ခြင်း')

@section('content')

    <div class="row align-items-center mb-4">

        <div class=" col-md-10">
            <h1 class="page-title mb-1" style="line-height: 38px">
                အကြွေးယူထားသောငွေကိုဆပ်ခြင်း
            </h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                တောင်သူများ ယခင်က ကြိုတင်ယူထားသော ငွေများကို ပြန်လည်ပေးဆပ်ခြင်း၊ ပေးဆပ်သည့်ငွေပမာဏကို မှတ်တမ်းတင်ခြင်းနှင့်
                ကျန်ရှိနေသော အကြွေးလက်ကျန်ကို စာရင်းရှင်းလင်းစွာ စီမံနိုင်ပါသည်။
            </p>
        </div>

        <div class=" col-md-2 d-flex justify-content-end mt-md-0 mt-2">
            <a href="{{ route('FarmerDebt#list') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>
    </div>


    <div class="row">

        <div class="offset-lg-2 col-lg-8 col-12">

            <div class="dashboard-card shadow-sm">

                <form id="cropForm" action="{{ route('FarmerDebt#settlementCreate', $farmerDebt->id) }}" method="POST">

                    @csrf

                    <p> <small class=" fw-bold fs-5 mx-2">{{ $farmerDebt->name }}</small> ၏ အကြွေးယူထားသောငွေ
                        <small class=" fw-bold fs-5 mx-2">{{ number_format($farmerDebt->balance_amount, 0) }}</small>
                        အတွက် လာဆပ်သော
                        ငွေပမာဏထည့်ရန်
                    </p>


                    <div class="row g-4">

                        {{-- Amount --}}
                        <div class="col-md-12">
                            <label for="balance_amount" class="form-label fw-semibold">
                                ငွေပမာဏ
                            </label>

                            <input type="text" id="balance_amount" name="balance_amount"
                                value="{{ old('balance_amount', $farmerDebt->balance_amount) }}"
                                class="form-control @error('balance_amount') is-invalid @enderror"
                                placeholder="ငွေပမာဏထည့်ပါ" maxlength="150">

                            @error('balance_amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>


                    {{-- Buttons --}}
                    <div class="d-flex justify-content-end mt-3 gap-2">

                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-lg me-1"></i>
                            ငွေပမာဏထည့်သွင်းမည်
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const balanceAmount = document.getElementById('balance_amount');

            if (!balanceAmount) return;

            balanceAmount.addEventListener('input', function() {
                this.value = this.value.replace(/[၀-၉]/g, function(digit) {
                    return '၀၁၂၃၄၅၆၇၈၉'.indexOf(digit).toString();
                });
            });
        });
    </script>
@endpush
