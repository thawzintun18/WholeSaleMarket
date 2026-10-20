@extends('layouts.app')

@section('page-title', 'Add Crop')

@section('breadcrumb',
    'ဈေးနှုန်းများ / ကြိုသုံးငွေယူထားသောတောင်သူစာရင်း /
    ရှိပြီးသားတောင်သူအတွက်ကြိုသုံးငွေထုတ်ပေးခြင်း')

@section('content')

    <div class="d-flex flex-column flex-md-row gap-2 mb-4">

        {{-- တောင်သူအသစ်ထည့်ခြင်း --}}
        <a href="{{ route('FarmerDebt#directPage') }}"
            class="btn {{ request()->routeIs('FarmerDebt#directPage') ? 'btn-dark' : 'btn-outline-dark' }}">
            <i class="bi bi-person-plus me-2"></i>
            တောင်သူအသစ်ထည့်ရန်
        </a>

        {{-- ရှိပြီးသားတောင်သူအတွက် ကြိုသုံးငွေ --}}
        <a href="{{ route('FarmerDebt#directPageSettlement') }}"
            class="btn {{ request()->routeIs('FarmerDebt#directPageSettlement') ? 'btn-dark' : 'btn-outline-dark' }}">
            <i class="bi bi-cash-stack me-2"></i>
            ကြိုသုံးငွေထုတ်ပေးရန်
        </a>

    </div>

    <div class="row align-items-center mb-4 mt-3">

        <div class=" col-md-10">
            <h1 class="page-title mb-1" style="line-height: 38px">
                ရှိပြီးသားတောင်သူအတွက် ကြိုသုံးငွေထုတ်ပေးခြင်း
            </h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                ရှိပြီးသားတောင်သူများကို ရွေးချယ်ပြီး ကြိုသုံးငွေ ထုတ်ပေးနိုင်ပါသည်။ ထုတ်ပေးထားသော ငွေပမာဏနှင့်
                မှတ်ချက်များကို စာရင်းသွင်းကာ နောင်သီးနှံလာရောင်းချချိန်တွင် ပြန်လည်နုတ်ယူရှင်းလင်းနိုင်ပါသည်။
            </p>
        </div>

        <div class=" col-md-2 d-flex justify-content-end mt-md-0 mt-2">
            <a href="{{ route('FarmerDebt#list') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>
    </div>

    <div class="container-fluid py-3">

        {{-- 1. တောင်သူရှာရန် --}}
        <div class="card border shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">၁။ တောင်သူရှာရန်</h6>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('FarmerDebt#directPageSettlement') }}">

                    <div class="input-group">
                        <input type="text" name="search" class="form-control" value="{{ $search }}"
                            placeholder="ကုဒ်၊ အမည်၊ ဖုန်း၊ ရွာအမည်ဖြင့် ရှာပါ">

                        <button type="submit" class="btn btn-dark">
                            <i class="bi bi-search me-1"></i> ရှာရန်
                        </button>
                    </div>

                    <div class="form-text">
                        တောင်သူကုဒ်၊ အမည်၊ ဖုန်းနံပါတ် သို့မဟုတ် ရွာအမည်
                        တစ်ခုခုဖြင့် ရှာနိုင်ပါသည်။
                    </div>
                </form>
            </div>

            @if ($search !== '')
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>တောင်သူကုဒ်</th>
                                <th>တောင်သူအမည်</th>
                                <th>ဖုန်းနံပါတ်</th>
                                <th>ရွာအမည်</th>
                                <th class="text-center">ရွေးချယ်ရန်</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($farmers as $item)
                                <tr>
                                    <td>{{ $item->farmer_code ?? '-' }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->phone ?? '-' }}</td>
                                    <td>{{ $item->village ?? '-' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('FarmerDebt#directPageSettlement', [
                                            'search' => $search,
                                            'farmer_id' => $item->id,
                                        ]) }}"
                                            class="btn btn-sm {{ $farmer?->id === $item->id ? 'btn-dark' : 'btn-outline-dark' }}">
                                            <i class="bi bi-person-check me-1"></i>
                                            ရွေးချယ်မည်
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-secondary">
                                        ရှာဖွေမှုနှင့် ကိုက်ညီသော တောင်သူမရှိပါ။
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($farmers->count() === 20)
                    <div class="card-footer bg-white text-secondary small">
                        ပထမဆုံး ကိုက်ညီသော တောင်သူ ၂၀ ယောက်ကိုသာ ပြထားပါသည်။
                        ပိုမိုတိကျစွာ ရှာဖွေနိုင်ပါသည်။
                    </div>
                @endif
            @endif
        </div>

        {{-- 2. ရွေးချယ်ထားသော တောင်သူ --}}
        @if ($farmer)
            <div class="card border shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">၂။ ရွေးချယ်ထားသော တောင်သူအချက်အလက်</h6>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="text-secondary small">တောင်သူကုဒ်</div>
                            <div class="fw-semibold">{{ $farmer->farmer_code ?? '-' }}</div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="text-secondary small">တောင်သူအမည်</div>
                            <div class="fw-semibold">{{ $farmer->name }}</div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="text-secondary small">ဖုန်းနံပါတ်</div>
                            <div class="fw-semibold">{{ $farmer->phone ?? '-' }}</div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="text-secondary small">ရွာအမည်</div>
                            <div class="fw-semibold">{{ $farmer->village ?? '-' }}</div>
                        </div>

                        <div class="col-12">
                            <div class="text-secondary small">မှတ်ချက်</div>
                            <div>{{ $farmer->notes ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. ငွေထုတ်ပေးရန် --}}
            <div class="card border shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">၃။ ငွေထုတ်ပေးရန်</h6>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('FarmerDebt#createSettlement') }}">
                        @csrf

                        <input type="hidden" name="farmer_id" value="{{ $farmer->id }}">

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="debt_type" class="form-label fw-semibold">
                                    ငွေအမျိုးအစား <span class="text-danger">*</span>
                                </label>

                                <select name="debt_type" id="debt_type" class="form-select" required>
                                    <option value="">အမျိုးအစားရွေးပါ</option>
                                    <option value="PurchaseAdvance" @selected(old('debt_type') === 'PurchaseAdvance')>
                                        ကြိုသုံးငွေ
                                    </option>
                                    <option value="CashLoan" @selected(old('debt_type') === 'CashLoan')>
                                        တောကြွေး
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="amount" class="form-label fw-semibold">
                                    ငွေပမာဏ (ကျပ်)
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="number" name="amount" id="amount" class="form-control"
                                    value="{{ old('amount') }}" min="0.01" max="9999999999999.99" step="0.01"
                                    placeholder="ငွေပမာဏထည့်ပါ" required>
                            </div>

                            <div class="col-12 d-flex justify-content-end gap-2">
                                <div class=" d-md-flex">

                                    <a href="{{ route('FarmerDebt#directPageSettlement') }}"
                                        class="btn btn-outline-danger me-2">
                                        ပယ်ဖျက်ရန်
                                    </a>

                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle me-1"></i>
                                        ငွေထုတ်ပေးမည်
                                    </button>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @elseif (request()->filled('farmer_id'))
            <div class="alert alert-light border text-dark">
                ရွေးချယ်ထားသော တောင်သူကို ရှာမတွေ့ပါ၊ သို့မဟုတ် အသုံးပြုခွင့်မရှိပါ။
            </div>
        @endif

    </div>


@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.getElementById('phone');
            const amountInput = document.getElementById('amount');

            if (!phoneInput) return;
            if (!amountInput) return;

            phoneInput.addEventListener('input', function() {
                const myanmarNumbers = '၀၁၂၃၄၅၆၇၈၉';

                this.value = this.value.replace(/[၀-၉]/g, function(digit) {
                    return myanmarNumbers.indexOf(digit);
                });
            });

            amountInput.addEventListener('input', function() {
                const myanmarNumbers = '၀၁၂၃၄၅၆၇၈၉';

                this.value = this.value.replace(/[၀-၉]/g, function(digit) {
                    return myanmarNumbers.indexOf(digit);
                });
            });
        });
    </script>
@endpush
