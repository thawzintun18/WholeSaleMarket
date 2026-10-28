@extends('layouts.app')

@section('page-title', 'CropDeposit')
@section('breadcrumb')
    အခြေခံစာရင်းများ / သီးနှံအပ်နှံ စာရင်း / သီးနှံအပ်နှံမှဘောင်ချာ
@endsection

@section('content')
    <div class="container py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="bi bi-arrow-repeat me-2 text-success"></i>သီးနှံအပ်နှံမှုကို ဝယ်ယူမှုဘောင်ချာသို့
                    ပြောင်းလဲခြင်း (Convert)
                </h3>
                <p class="text-muted small mb-0">တောင်သူလာရောက်ရောင်းချသည့်နေ့၏ ပေါက်ဈေးနှုန်းအမှန်ဖြင့် ချိန်ညှိပြီး
                    ဘောင်ချာထုတ်ပါ</p>
            </div>
            <a href="{{ route('crop#deposits#index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-1"></i> နောက်သို့
            </a>
        </div>

        @if (session('error'))
            <div class="alert alert-danger shadow-sm rounded-3">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('crop#deposits#convert#purchase', $deposit->id) }}" method="POST">
            @csrf

            <!-- တောင်သူနှင့် အပ်နှံမှု အချက်အလက် Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <span
                                class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold mb-2">
                                ဘောင်ချာနံပါတ်: {{ $deposit->deposit_no }}
                            </span>
                            <h4 class="fw-bold text-dark mb-1">{{ $deposit->farmer_name }}</h4>
                            <div class="text-muted small">
                                <i class="bi bi-telephone me-1"></i>{{ $deposit->farmer_phone ?? 'ဖုန်းမရှိ' }} |
                                <i class="bi bi-calendar-event me-1"></i>အပ်နှံရက်:
                                {{ date('d-m-Y H:i', strtotime($deposit->deposit_date)) }}
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <div class="text-secondary small">သယ်ဆောင်လာသည့် ကားနံပါတ်</div>
                            <div class="fw-bold text-dark fs-5">{{ $deposit->car_no ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- အပ်နှံထားသော သီးနှံများစာရင်း (ဈေးနှုန်းပြင်ရန်) -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-box-seam me-2 text-primary"></i>သီးနှံစာရင်းနှင့် ဈေးနှုန်းချိန်ညှိရန်
                    </h5>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="py-3">သီးနှံအမည်</th>
                                    <th class="py-3">အရေအတွက်</th>
                                    <th class="py-3">ယူနစ်</th>
                                    <th class="py-3">အပ်တုန်းက ဈေး</th>
                                    <th class="py-3" style="width: 250px;">ယနေ့ ဝယ်မည့်ဈေးအမှန် <span
                                            class="text-danger">*</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $index => $item)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->crop_name }}
                                                <span class="badge bg-secondary bg-opacity-10 text-muted small">
                                                    {{ $item->grade_name }}
                                                </span>
                                            </div>

                                            <!-- Hidden ID -->
                                            <input type="hidden" name="items[{{ $index }}][id]"
                                                value="{{ $item->id }}">
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ number_format($item->quantity, 2) }}</div>
                                            @if (strtolower($item->unit) === 'pound' && $item->bag_count > 0)
                                                <div class="text-muted small">အိတ်ရေ: {{ $item->bag_count }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold">
                                                {{ strtolower($item->unit ?? '') === 'viss' ? 'ပိဿာ' : ($rawUnit === 'pound' ? 'ပေါင်' : $item->unit) }}
                                            </div>
                                        </td>
                                        <td class="">
                                            {{ number_format($item->reference_price, 2) }} ကျပ်
                                        </td>
                                        <td>
                                            <!-- ဈေးနှုန်းအသန် ပြင်ဆင်ရိုက်ထည့်မည့် Input -->
                                            <div class="input-group">
                                                <input type="number" name="items[{{ $index }}][actual_buy_price]"
                                                    class="form-control fw-bold text-success"
                                                    value="{{ old("items.{$index}.actual_buy_price", $item->actual_buy_price) }}"
                                                    step="0.01" min="0" required>
                                                <span class="input-group-text">ကျပ်</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- ကားခ အချက်အလက် -->
                    <div class="row mt-4 pt-3 border-top">
                        <div class="col-md-4 ms-auto">
                            <label class="form-label fw-semibold">ကားခ</label>
                            <input type="number" name="car_fee_amount" class="form-control"
                                value="{{ old('car_fee_amount', $deposit->car_fee_amount ?? 0) }}" step="0.01"
                                min="0">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button  onclick="return confirm('သီးနှံများကို ဈေးနှုန်းအမှန်ဖြင့် ဖြတ်တောက်ပြီး ဝယ်ယူမှုဘောင်ချာ အသစ်ထုတ်မည်မှာ သေချာပါသလား?');"-->
            <div class="text-end mb-5">
                <button type="submit" class="btn btn-success px-5 py-3 rounded-pill shadow">
                    <i class="bi bi-check-circle-fill me-2"></i> ဝယ်ယူမှုဘောင်ချာ ထုတ်မည်
                </button>
            </div>
        </form>
    </div>
@endsection
