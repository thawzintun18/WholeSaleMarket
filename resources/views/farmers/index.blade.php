@extends('layouts.app')

@section('page-title', 'Farmers')

@section('breadcrumb')
    အခြေခံစာရင်းများ / တောင်သူစာရင်း
@endsection

@section('content')
    <div class="container-fluid p-0">

        @include('partials.farmer-customer-tabs')

        {{-- ခေါင်းစဉ်နှင့် အသစ်ထည့်ရန် --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
            <div>
                <h1 class="page-title mb-1 fs-3 fs-md-2">တောင်သူစာရင်း</h1>
                <p class="text-muted mb-0 mt-1" style="line-height: 1.5">
                    တောင်သူများ၏ ကိုယ်ရေးအချက်အလက်များကို စီမံခန့်ခွဲရန်
                </p>
            </div>
        </div>

        {{-- ရှာဖွေရန် --}}
        <div class="dashboard-card mb-3 shadow-sm p-3 p-md-4">
            <form action="{{ route('farmers#index') }}" method="GET">
                <div class="row align-items-end g-2">
                    <div class="col-12 mb-1">
                        <small class="text-muted">တောင်သူအချက်အလက်သိလျှင်အလွယ်တကူ ရှာဖွေနိုင်သည်</small>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label mb-1">တောင်သူ ရှာဖွေရန်</label>
                        <input type="text" name="search" id="search" class="form-control"
                            value="{{ request('search') }}" placeholder="ကုဒ်၊ အမည်၊ ဖုန်းနံပါတ်...">
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label mb-1">ကျေးရွာ</label>
                        <input type="text" name="village" class="form-control" value="{{ request('village') }}"
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

                    <div class="col-12 col-md-auto d-flex flex-wrap gap-2 mt-2 mt-md-1">
                        <button type="submit" class="btn btn-primary flex-fill flex-md-grow-0 rounded-pill shadow-sm">
                            <i class="bi bi-search me-1"></i>
                            ရှာဖွေမည်
                        </button>

                        <a href="{{ route('farmers#index') }}" class="btn btn-outline-secondary text-center flex-fill flex-md-grow-0 rounded-pill shadow-sm">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            ပြန်လည်သတ်မှတ်မည်
                        </a>
                    </div>

                </div>
            </form>
        </div>

        {{-- တောင်သူစာရင်း --}}
        <div class="dashboard-card shadow-lg p-3 p-md-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                <div class="col-12 col-md-7 ps-0">

                    <h5 class="mb-1">တောင်သူများစာရင်း</h5>

                    <small class="text-muted d-block mt-1" style="line-height: 1.5">
                        အရောင်းအဝယ်လုပ်ထားသောတောင်သူများကို သီးသန့်မှတ်သားဖော်ပြထားခြင်းဖြစ်သည် ( နောက်ဆုံးရောင်းသွားသော
                        တောင်သူကို အပေါ်ဆုံးကနေစပြီးပြထားပေးသည် )
                    </small>

                </div>

                <div class="col-12 col-md-5 d-flex flex-wrap align-items-center justify-content-md-end gap-2 pe-0">
                    <div>
                        <span class="badge bg-light text-dark border px-3 py-2">
                            စုစုပေါင်း {{ number_format($farmers->total()) }} ဦး
                        </span>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <div>
                            <a href="{{ route('farmers#history') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">ပြင်ထားသောစာရင်း</a>
                        </div>

                        <div>
                            <a href="{{ route('farmers#trashList') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm">ဖျက်ထားသောစာရင်း</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap">

                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">စဉ်</th>
                            <th>တောင်သူကုဒ်</th>
                            <th>တောင်သူအမည်</th>
                            <th>ဖုန်းနံပါတ်</th>
                            <th>ကျေးရွာ</th>
                            <th>မှတ်ချက်</th>
                            <th>အခြေအနေ</th>
                            <th class="text-center" style="width: 130px;">လုပ်ဆောင်ချက်</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($farmers as $farmer)
                            <tr>
                                <td>
                                    {{ $farmers->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <span class="badge bg-secondary text-white border">
                                        {{ $farmer->farmer_code ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <strong>{{ $farmer->name }}</strong>
                                </td>

                                <td class="fs-6 fw-bold">
                                    {{ $farmer->phone ?: '-' }}
                                </td>

                                <td>
                                    {{ $farmer->village ?: '-' }}
                                </td>

                                <td>
                                    {{ $farmer->notes ?: '-' }}
                                </td>

                                <td>
                                    @if ($farmer->is_active)
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
                                        @if ($farmer->has_outstanding_debt)
                                            <a href="{{ route('farmer#farmerSettlementList', $farmer->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="အကြွေးပြန်ဆပ်မှုစာရင်း">
                                                အကြွေးပြန်ဆပ်မှု
                                            </a>
                                        @endif

                                        <a href="{{ route('farmers#edit#page', $farmer->id) }}"
                                            class="btn btn-sm btn-outline-dark" title="ပြင်ဆင်ရန်">
                                            ပြင်ရန်
                                        </a>

                                        <button type="button" onclick="DeleteData({{ $farmer->id }})"
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
                                        <h5 class="mb-1 text-secondary fw-bold">တောင်သူစာရင်း မရှိသေးပါ</h5>
                                        <p class="text-muted mb-0">
                                            တောင်သူကို ထည့်သွင်းထားသော မှတ်တမ်း မရှိသေးပါ။
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Pagination --}}
            @if ($farmers->total() > 0)
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 gap-3">

                    <p class="text-muted mb-0 text-center text-md-start">
                        စာရင်း {{ number_format($farmers->firstItem()) }}
                        မှ {{ number_format($farmers->lastItem()) }} အထိ၊
                        စုစုပေါင်း {{ number_format($farmers->total()) }} ဦး
                    </p>

                    <nav class="d-flex justify-content-end">
                        <ul class="pagination pagination-sm mb-0">
                            {{ $farmers->links('pagination::bootstrap-5') }}
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
                text: "ဖျက်ပြီးပါက ဤသီးနှံအချက်အလက်ကို ယာယီဖျက်ထားမည်",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ဖျက်မည်",
                cancelButtonText: "မဖျက်တော့ပါ"
            }).then((result) => {
                if (result.isConfirmed) Swal.fire({
                    title: "ဖျက်ပြီးပါပြီ!",
                    text: "သီးနှံအချက်အလက်ကို အောင်မြင်စွာ ဖျက်ပြီးပါပြီ။",
                    icon: "success"
                }).then(() => {
                    location.href = '/whole-sale-market/farmers/delete/' + id;
                });

            });
        }
    </script>
@endsection
