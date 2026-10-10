@extends('layouts.app')

@section('page-title', 'Farmers')

@section('breadcrumb')
    Dashboard / တောင်သူစာရင်း
@endsection

@section('content')

    {{-- ခေါင်းစဉ်နှင့် အသစ်ထည့်ရန် --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">တောင်သူစာရင်း</h1>
            <p class="text-muted mb-0">
                တောင်သူများ၏ ကိုယ်ရေးအချက်အလက်များကို စီမံခန့်ခွဲရန်
            </p>
        </div>

        <a href="{{ route('farmers#create#page') }}" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i>
            တောင်သူအသစ်ထည့်ရန်
        </a>
    </div>

    {{-- ရှာဖွေရန် --}}
    <div class="dashboard-card mb-4 shadow-sm">
        <form action="{{ route('farmers#index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <small class=" fs-6">တောင်သူအချက်အလက်သိလျှင်အလွယ်တကူ ရှာဖွေနိုင်သည်</small>
                <div class="col-md-4">
                    <label class="form-label">တောင်သူ ရှာဖွေရန်</label>
                    <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}"
                        placeholder="ကုဒ်၊ အမည်၊ ဖုန်းနံပါတ်...">
                </div>

                <div class="col-md-3">
                    <label class="form-label">ကျေးရွာ</label>
                    <input type="text" name="village" class="form-control" value="{{ request('village') }}"
                        placeholder="ကျေးရွာအမည် ရိုက်ထည့်ပါ">
                </div>

                <div class="col-md-2">
                    <label class="form-label">အခြေအနေ</label>
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

                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>
                        ရှာဖွေမည်
                    </button>

                    <a href="{{ route('farmers#index') }}" class="btn btn-outline-secondary">
                        ပြန်လည်သတ်မှတ်
                    </a>
                </div>

            </div>
        </form>
    </div>

    {{-- တောင်သူစာရင်း --}}
    <div class="dashboard-card shadow-lg">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="mb-1">တောင်သူများစာရင်း</h5>
                <small class="text-muted fs-6">
                    အရောင်းအဝယ်လုပ်ထားသောတောင်သူများကို သီးသန့်မှတ်သားဖော်ပြထားခြင်းဖြစ်သည် ( နောက်ဆုံးရောင်းသွားသော
                    တောင်သူကို အပေါ်ဆုံးကနေစပြီးပြထားပေးသည် )
                </small>
            </div>

            <div class=" d-md-flex justify-content-end mt-md-0 mt-2">
                <div class="me-2">
                    <span class="badge bg-light text-dark border">
                        စုစုပေါင်း {{ number_format($farmers->total()) }} ဦး
                    </span>
                </div>

                <div class="d-flex mt-md-0 mt-2">
                    <div class=" me-2">
                        <a href="{{ route('farmers#history') }}" class=" btn btn-primary">ပြင်ဆင်မှုစာရင်း</a>
                    </div>

                    <div class=" me-2">
                        <a href="{{ route('farmers#trashList') }}" class=" btn btn-primary">ဖျက်ထားသောစာရင်း</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

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

                            <td class=" fs-5 fw-bold">
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
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                        ပိတ်ထား
                                    </span>
                                @endif
                            </td>

                            <td class="text-center">
                                <a href="{{ route('farmers#edit#page', $farmer->id) }}"
                                    class="btn btn-sm btn-outline-primary" title="ပြင်ဆင်ရန်">
                                    {{-- <i class="bi bi-pencil"></i> --}}
                                    ပြင်ရန်
                                </a>

                                <button type="button" onclick="DeleteData({{ $farmer->id }})"
                                    class=" btn btn-sm btn-outline-danger " title="ဖျက်ရန်">
                                    ဖျက်ရန်
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                {{-- <i class="bi bi-people fs-1 text-muted"></i> --}}
                                <p class="text-muted mt-2 mb-1">
                                    တောင်သူစာရင်း မတွေ့ရှိပါ။
                                </p>
                                <small class="text-muted">
                                    ရှာဖွေမှုကို ပြန်စစ်ပါ၊ သို့မဟုတ် တောင်သူအသစ် ထည့်ပါ။
                                </small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        @if ($farmers->total() > 0)
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4">

                <small class="text-muted">
                    စာရင်း {{ number_format($farmers->firstItem()) }}
                    မှ {{ number_format($farmers->lastItem()) }} အထိ၊
                    စုစုပေါင်း {{ number_format($farmers->total()) }} ဦး
                </small>

                {{ $farmers->links('pagination::bootstrap-5') }}

            </div>
        @endif

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
