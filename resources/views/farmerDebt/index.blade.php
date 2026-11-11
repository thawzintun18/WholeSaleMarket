@extends('layouts.app')

@section('page-title', 'Grades')

@section('breadcrumb', 'ဈေးနှုန်းများ / ကြိုသုံးငွေယူထားသော တောင်သူစာရင်း')

@section('content')

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
        <div>
            <h1 class="page-title mb-1 fs-3 fs-md-2" style="line-height: 1.4">
                ကြိုသုံးငွေယူထားသော တောင်သူစာရင်း
            </h1>

            <p class="text-muted mb-0 mt-1" style="line-height: 1.5">
                တောင်သူများမှ သီးနှံလာမအပ်မီ ကြိုတင်ထုတ်ယူထားသော ငွေများနှင့် သီးနှံလာအပ်သည့်အခါ စာရင်းရှင်းရာတွင် နုတ်ယူရန်
                ကျန်ရှိနေသော ကြိုသုံးငွေလက်ကျန်များကို တောင်သူအလိုက် ကြည့်ရှုစီမံနိုင်သော စာရင်းဖြစ်သည်။
            </p>
        </div>

        <div class="d-flex justify-content-end w-100 w-md-auto">
            <a href="{{ route('FarmerDebt#directPage') }}" class="btn btn-primary px-4 w-md-auto text-nowrap rounded-pill shadow-sm">
                <i class="bi bi-plus-lg me-1"></i>
                ကြိုသုံးငွေသစ်ထပ်ထည့်ရန်
            </a>
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="dashboard-card mb-3 shadow-sm p-3 p-md-4">
        <form action="{{ route('FarmerDebt#list') }}" method="GET">
            <div class="row align-items-end g-2">

                <div class="col-12 col-md-4">
                    <label class="form-label mb-1">
                        တောင်သူ
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="text" name="searchData" value="{{ request('searchData') }}" id="search"
                            class="form-control" placeholder="တောင်သူအမည် ရိုက်ထည့်ပါ...">
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <label class="form-label mb-1">
                        အကြွေးယူသည့်နေ့
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="date" name="searchDate" value="{{ request('searchDate') }}" id="search"
                            class="form-control">
                    </div>
                </div>

                <div class="col-12 col-md-4 d-flex flex-wrap gap-2 mt-2 mt-md-0">
                    <button type="submit" class="btn btn-primary flex-fill flex-md-grow-0">
                        <i class="bi bi-search me-1"></i>
                        ရှာဖွေမည်
                    </button>

                    <a href="{{ route('FarmerDebt#list') }}" class="btn btn-outline-secondary text-center flex-fill flex-md-grow-0">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        ပြန်လည်သတ်မှတ်မည်
                    </a>
                </div>

            </div>
        </form>
    </div>

    {{-- Grade Table --}}
    <div class="dashboard-card shadow-sm p-3 p-md-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
            <div>
                <h5 class="mb-1" style="line-height: 1.4">
                    ကြိုသုံးငွေယူထားသော တောင်သူစာရင်း
                </h5>

                <small class="text-muted d-block mt-1">
                    စာရင်းသွင်းထားသော ကြိုသုံးငွေယူထားသော တောင်သူများ
                </small>
            </div>

            <div class="d-flex justify-content-end">
                <div>
                    <span class="badge bg-light text-dark border px-3 py-2">
                        {{ $farmerDebt->count() }} မှတ်တမ်း
                    </span>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap">

                <thead>
                    <tr>
                        <th style="width: 60px;">စဉ်</th>
                        <th>နေ့စွဲ</th>
                        <th>တောင်သူအမည်</th>
                        <th class="text-center">မူလအကြွေးငွေ</th>
                        <th class="text-center">ပြန်ရှင်းပြီးငွေ</th>
                        <th class="text-center">ကျန်ငွေ</th>
                        <th class="text-center">အမျိုးအစား</th>
                        <th class="text-center">အခြေအနေ</th>
                        <th class="text-center" style="width: 130px;">လုပ်ဆောင်ချက်</th>
                    </tr>
                </thead>

                <tbody>
                    @if ($farmerDebt->count() > 0)
                        @foreach ($farmerDebt as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <div>{{ \Carbon\Carbon::parse($item->debt_date)->format('d-M-Y') }}</div>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($item->debt_date)->format('h:i A') }}
                                    </small>
                                </td>

                                <td>
                                    <strong>{{ $item->name }}</strong>
                                </td>

                                <td class="text-center">
                                    {{ number_format($item->amount, 2) }} <small class="small text-secondary">ကျပ်</small>
                                </td>

                                <td class="text-center">
                                    {{ number_format($item->settled_amount, 2) == 0 ? 0 : number_format($item->settled_amount, 2) }}
                                    <small class="small text-secondary">ကျပ်</small>
                                </td>

                                <td class="text-center">
                                    {{ number_format($item->balance_amount, 2) }} <small
                                        class="small text-secondary">ကျပ်</small>
                                </td>

                                <td class="text-center">
                                    <small class="">
                                        @if ($item->debt_type == 'PurchaseAdvance')
                                            ကြိုယူငွေ
                                        @elseif($item->debt_type == 'CashLoan')
                                            တောကြွေး
                                        @elseif($item->debt_type == 'BeanPayment')
                                            ပဲဆံဖိုး
                                        @endif
                                    </small>
                                </td>

                                <td class="text-center">
                                    <div>
                                        @if ($item->status == 'Open')
                                            <small class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fw-semibold">ကြွေးကျန်</small>
                                        @elseif($item->status == 'Partial')
                                            <small class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill fw-semibold">တစ်စိတ်တစ်ပိုင်း ရှင်းပြီး</small>
                                        @elseif($item->status == 'Close')
                                            <small class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold">ကြွေးရှင်း</small>
                                        @endif
                                    </div>
                                </td>

                                <td class="text-center">
                                    <div class="d-inline-flex gap-1 justify-content-center">
                                        @if (
                                            ($item->debt_type === 'CashLoan' && in_array($item->status, ['Open', 'Partial'])) ||
                                            ($item->debt_type === 'BeanPayment' && in_array($item->status, ['Open', 'Partial']))
                                        )
                                            <a href="{{ route('FarmerDebt#settlement', $item->id) }}"
                                                class="btn btn-success btn-sm rounded-3 px-2 shadow-sm">အကြွေးဆပ်ရန်</a>
                                        @endif

                                        @if ($item->debt_type == 'CashLoan' || $item->debt_type == 'BeanPayment')
                                            <a href="{{ route('FarmerDebt#settlementList', $item->id) }}"
                                                class="btn btn-secondary btn-sm rounded-3 px-2 shadow-sm">အကြွေးပြန်ဆပ်မှုစာရင်း</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <h5 class="mb-1 text-secondary fw-bold">ကြိုသုံးငွေယူထားသော တောင်သူ မရှိသေးပါ</h5>

                                    <p class="text-muted mb-0">
                                        ကြိုသုံးငွေယူထားသော တောင်သူကို ထည့်သွင်းထားသော မှတ်တမ်း မရှိသေးပါ။
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>

            </table>
        </div>

        {{-- Pagination Example --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 gap-3">
            <p class="text-muted mb-0 text-center text-md-start">
                တောင်သူ {{ $farmerDebt->firstItem() ?? 0 }}
                မှ {{ $farmerDebt->lastItem() ?? 0 }}
                အထိ ပြသထားပြီး
                စုစုပေါင်း {{ $farmerDebt->total() }} ဦး ရှိပါသည်။
            </p>

            <nav class="d-flex justify-content-end">
                <ul class="pagination pagination-sm mb-0">
                    {{ $farmerDebt->links() }}
                </ul>
            </nav>
        </div>

    </div>

@endsection

@section('DeleteData')
    <script>
        function DeleteData(id) {
            Swal.fire({
                title: "ဖျက်ရန် သေချာပါသလား?",
                text: "ဖျက်ပြီးပါက ဤသီးနှံအရည်အသွေးအချက်အလက်ကို ပြန်လည်ရယူ၍ မရတော့ပါ။",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ဖျက်မည်",
                cancelButtonText: "မဖျက်တော့ပါ"
            }).then((result) => {
                if (result.isConfirmed) Swal.fire({
                    title: "ဖျက်ပြီးပါပြီ!",
                    text: "သီးနှံအရည်အသွေးအချက်အလက်ကို အောင်မြင်စွာ ဖျက်ပြီးပါပြီ။",
                    icon: "success"
                }).then(() => {
                    location.href = '/whole-sale-market/Quality/delete/' + id;
                });

            });
        }
    </script>
@endsection
