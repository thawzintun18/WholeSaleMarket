@extends('layouts.app')

@section('page-title', 'Farmers')

@section('breadcrumb')
    အခြေခံစာရင်းများ / ၀ယ်သူစာရင်း
@endsection

@section('content')
    <div class="container-fluid p-0">

        @include('partials.farmer-customer-tabs')

        {{-- ခေါင်းစဉ်နှင့် အသစ်ထည့်ရန် --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
            <div>
                <h1 class="page-title mb-1 fs-3 fs-md-2">၀ယ်သူစာရင်း</h1>
                <p class="text-muted mb-0 mt-1" style="line-height: 1.5">
                    ၀ယ်သူများ၏ ကိုယ်ရေးအချက်အလက်များကို စီမံခန့်ခွဲရန်
                </p>
            </div>
        </div>

        {{-- ရှာဖွေရန် --}}
        <div class="dashboard-card mb-3 shadow-sm p-3 p-md-4">
            <form action="{{ route('Customer#list') }}" method="GET">
                <div class="row align-items-end g-2">
                    <div class="col-12 mb-1">
                        <small class="text-muted">၀ယ်သူအချက်အလက်သိလျှင်အလွယ်တကူ ရှာဖွေနိုင်သည်</small>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label mb-1">၀ယ်သူ ရှာဖွေရန်</label>
                        <input type="text" name="search" id="search" class="form-control"
                            value="{{ request('search') }}" placeholder="ကုဒ်၊ အမည်၊ ဖုန်းနံပါတ်...">
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label mb-1">ကျေးရွာ</label>
                        <input type="text" name="address" class="form-control" value="{{ request('village') }}"
                            placeholder="ကျေးရွာအမည် ရိုက်ထည့်ပါ">
                    </div>

                    <div class="col-12 col-md-2">
                        <label class="form-label mb-1">အခြေအနေ</label>
                        <select name="status" class="form-select">
                            <option value="">အားလုံး</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                အသုံးပြုနေ
                            </option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                ပိတ်ထား
                            </option>
                        </select>
                    </div>

                    <div class="col-12 col-md-auto d-flex flex-wrap gap-2 mt-2 mt-md-0">
                        <button type="submit" class="btn btn-primary flex-fill flex-md-grow-0 rounded-pill shadow-sm">
                            <i class="bi bi-search me-1"></i>
                            ရှာဖွေမည်
                        </button>

                        <a href="{{ route('Customer#list') }}" class="btn btn-outline-secondary text-center flex-fill flex-md-grow-0 rounded-pill shadow-sm">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            ပြန်လည်သတ်မှတ်မည်
                        </a>
                    </div>

                </div>
            </form>
        </div>

        {{-- ၀ယ်သူစာရင်း --}}
        <div class="dashboard-card shadow-lg p-3 p-md-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                <div class="col-12 col-md-7 ps-0">

                    <h5 class="mb-1">၀ယ်သူများစာရင်း</h5>

                    <small class="text-muted d-block mt-1" style="line-height: 1.5">
                        အရောင်းအဝယ်လုပ်ထားသော၀ယ်သူများကို သီးသန့်မှတ်သားဖော်ပြထားခြင်းဖြစ်သည် ( နောက်ဆုံးရောင်းသွားသော
                        ၀ယ်သူကို အပေါ်ဆုံးကနေစပြီးပြထားပေးသည် )
                    </small>

                </div>

                <div class="col-12 col-md-5 d-flex flex-wrap align-items-center justify-content-md-end gap-2 pe-0">
                    <div>
                        <span class="badge bg-light text-dark border px-3 py-2">
                            စုစုပေါင်း {{ number_format($customers->total()) }} ဦး
                        </span>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <div>
                            <a href="{{ route('Customer#history') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">ပြင်ထားသောစာရင်း</a>
                        </div>

                        <div>
                            <a href="{{ route('Customer#trashList') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm">ဖျက်ထားသောစာရင်း</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap">

                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">စဉ်</th>
                            <th>၀ယ်သူကုဒ်</th>
                            <th>၀ယ်သူအမည်</th>
                            <th>ဖုန်းနံပါတ်</th>
                            <th>လိပ်စာ</th>
                            <th>မှတ်ချက်</th>
                            <th>အခြေအနေ</th>
                            <th class="text-center" style="width: 130px;">လုပ်ဆောင်ချက်</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td>
                                    {{ $customers->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <span class="badge bg-secondary text-white border">
                                        {{ $customer->customer_code ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <strong>{{ $customer->name }}</strong>
                                </td>

                                <td class="fs-6 fw-bold">
                                    {{ $customer->phone ?: '-' }}
                                </td>

                                <td>
                                    {{ $customer->address ?: '-' }}
                                </td>

                                <td>
                                    {{ $customer->notes ?: '-' }}
                                </td>

                                <td>
                                    @if ($customer->is_active)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            အသုံးပြုနေ
                                        </span>
                                    @else
                                        <span
                                            class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                            ပိတ်ထား
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('Customer#edit', $customer->id) }}"
                                            class="btn btn-sm btn-outline-dark" title="ပြင်ဆင်ရန်">
                                            ပြင်ရန်
                                        </a>

                                        <button type="button" onclick="DeleteData({{ $customer->id }})"
                                            class="btn btn-sm btn-outline-danger" title="ဖျက်ရန်">
                                            ဖျက်ရန်
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <h5 class="mb-1 text-secondary fw-bold">၀ယ်သူစာရင်း မရှိသေးပါ</h5>
                                        <p class="text-muted mb-0">
                                            ၀ယ်သူကို ထည့်သွင်းထားသော မှတ်တမ်း မရှိသေးပါ။
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Pagination --}}
            @if ($customers->total() > 0)
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 gap-3">

                    <p class="text-muted mb-0 text-center text-md-start">
                        စာရင်း {{ number_format($customers->firstItem()) }}
                        မှ {{ number_format($customers->lastItem()) }} အထိ၊
                        စုစုပေါင်း {{ number_format($customers->total()) }} ဦး
                    </p>

                    <nav class="d-flex justify-content-end">
                        <ul class="pagination pagination-sm mb-0">
                            {{ $customers->links('pagination::bootstrap-5') }}
                        </ul>
                    </nav>

                </div>
            @endif

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.getElementById('search');

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
@section('DeleteData')
    <script>
        function DeleteData(id) {
            Swal.fire({
                title: "ဖျက်ရန် သေချာပါသလား?",
                text: "ဖျက်ပြီးပါက ဤ၀ယ်သူအချက်အလက်ကို ယာယီဖျက်ထားမည်",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ဖျက်မည်",
                cancelButtonText: "မဖျက်တော့ပါ"
            }).then((result) => {
                if (result.isConfirmed) Swal.fire({
                    title: "ဖျက်ပြီးပါပြီ!",
                    text: "၀ယ်သူအချက်အလက်ကို အောင်မြင်စွာ ဖျက်ပြီးပါပြီ။",
                    icon: "success"
                }).then(() => {
                    location.href = '/whole-sale-market/Customer/delete/' + id;
                });

            });
        }
    </script>
@endsection
