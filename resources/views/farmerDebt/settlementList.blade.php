@extends('layouts.app')

@section('page-title', 'Grades')

@section('breadcrumb', 'ဈေးနှုန်းများ / ကြိုသုံးငွေယူထားသော တောင်သူစာရင်း / အကြွေးပြန်ဆပ်မှုစာရင်း')

@section('content')

    <div class="row align-items-center mb-4">

        <div class=" col-md-8">
            <h1 class="page-title mb-1" style="line-height: 38px">
                အကြွေးပြန်ဆပ်မှုစာရင်း
            </h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                တောင်သူများထံမှ ပြန်လည်လက်ခံရရှိထားသော အကြွေးဆပ်ငွေများကို ရက်စွဲ၊ ငွေပမာဏနှင့် တောင်သူအမည်တို့အလိုက်
                စာရင်းကြည့်ရှုနိုင်ပါသည်။
            </p>
        </div>

        <div class="col-md-4 d-flex justify-content-end">
            <a href="{{ route('FarmerDebt#list') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>

    </div>



    {{-- Farmer Debt Detail --}}
    <div class="dashboard-card shadow-sm mb-4">
        <div class="card-header bg-white border-dark rounded-0 py-3">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-person-lines-fill me-2"></i>
                အကြွေးအသေးစိတ်
            </h5>
        </div>

        <div class="card-body">

            {{-- Farmer Information --}}
            <h6 class="fw-bold border-bottom border-dark pb-2 mb-3">
                တောင်သူအချက်အလက်
            </h6>

            <div class="row mb-4">
                <div class="col-md-4 col-4 mb-3">
                    <div class="text-secondary small mb-1">တောင်သူအမည်</div>
                    <div class="fw-bold fs-5">
                        {{ $farmerDebt->name }}
                    </div>
                </div>

                <div class="col-md-4 col-4 mb-3">
                    <div class="text-secondary small mb-1">ဖုန်းနံပါတ်</div>
                    <div class="fw-semibold">
                        {{ $farmerDebt->phone ?: '—' }}
                    </div>
                </div>

                <div class="col-md-4 col-4 mb-3">
                    <div class="text-secondary small mb-1">နေရပ်လိပ်စာ</div>
                    <div class="fw-semibold">
                        {{ $farmerDebt->village ?: '—' }}
                    </div>
                </div>
            </div>

            {{-- Debt Information --}}
            <h6 class="fw-bold border-bottom border-dark pb-2 mb-3">
                အကြွေးအချက်အလက်
            </h6>

            <div class="row mb-4">
                <div class="col-md-4 col-4 mb-3">
                    <div class="text-secondary small mb-1">အကြွေးယူသည့်ရက်</div>
                    <div class="fw-semibold">
                        <div>{{ \Carbon\Carbon::parse($farmerDebt->debt_date)->format('d-m-Y') }}</div>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($farmerDebt->debt_date)->format('h:i A') }}
                        </small>
                    </div>
                </div>

                <div class="col-md-4 col-4 mb-3">
                    <div class="text-secondary small mb-1">အကြွေးအမျိုးအစား</div>
                    <div class="fw-semibold">
                        @if ($farmerDebt->debt_type == 'PurchaseAdvance')
                            ကြိုယူငွေ
                        @elseif($farmerDebt->debt_type == 'CashLoan')
                            တောကြွေး
                        @endif
                    </div>
                </div>

                <div class="col-md-4 col-4 mb-3">
                    <div class="text-secondary small mb-1">အခြေအနေ</div>

                    @if ($farmerDebt->status == 'Open')
                        <span class="badge dashboard-card shadow-sm px-3 py-2 bg-danger text-white">အကြွေးကျန်ရှိ</span>
                    @elseif($farmerDebt->status == 'Partial')
                        <span class="badge dashboard-card shadow-sm px-3 py-2 bg-warning text-white">တစ်စိတ်တစ်ပိုင်း
                            ရှင်းပြီး</span>
                    @elseif($farmerDebt->status == 'Close')
                        <span class="badge dashboard-card shadow-sm px-3 py-2 bg-success text-white">အကြွေးရှင်းပြီး</span>
                    @endif

                </div>
            </div>

            {{-- Amount Summary --}}
            <h6 class="fw-bold border-bottom border-dark pb-2 mb-3">
                ငွေစာရင်းချုပ်
            </h6>

            <div class="row g-3">
                <div class="col-md-4 col-4">
                    <div class="dashboard-card shadow-sm p-3 h-100">
                        <div class="text-secondary small mb-2">
                            မူလအကြွေးငွေ
                        </div>
                        <div class="fs-4 fw-bold">
                            {{ number_format($farmerDebt->amount, 0) }}
                        </div>
                        <div class="small text-secondary">ကျပ်</div>
                    </div>
                </div>

                <div class="col-md-4 col-4">
                    <div class="dashboard-card shadow-sm p-3 h-100">
                        <div class="text-secondary small mb-2">
                            ပြန်ဆပ်ပြီးငွေ
                        </div>
                        <div class="fs-4 fw-bold">
                            {{ number_format($farmerDebt->settled_amount ?? 0, 0) }}
                        </div>
                        <div class="small text-secondary">ကျပ်</div>
                    </div>
                </div>

                <div class="col-md-4 col-4">
                    <div class="dashboard-card shadow-sm p-3 h-100">
                        <div class="text-secondary small mb-2">
                            လက်ကျန်အကြွေး
                        </div>
                        <div class="fs-4 fw-bold">
                            {{ number_format($farmerDebt->balance_amount ?? 0, 0) }}
                        </div>
                        <div class="small text-secondary">ကျပ်</div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    @if ($farmerDebt->debt_type == 'CashLoan')

        {{-- Grade Table --}}
        <div class="dashboard-card shadow-sm">

            <div class="d-md-flex justify-content-between align-items-center mb-3">

                <div>

                    <h5 class="mb-1" style="line-height: 38px">
                        အကြွေးပြန်ဆပ်မှုစာရင်း
                    </h5>

                    <small class="text-muted mt-2">
                        စာရင်းသွင်းထားသော အကြွေးပြန်ဆပ်မှုစာရင်းများ
                    </small>

                </div>


                <div class="d-md-flex justify-content-end mt-md-0 mt-2">
                    <div class="me-2">
                        <span class="badge bg-light text-dark border">
                            {{ $settlement->count() }} မှတ်တမ်း
                        </span>
                    </div>
                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>
                            <th style="width: 60px;">စဉ်</th>
                            <th>အကြွေးဆပ်သည့်နေ့ရက်</th>
                            <th>တောင်သူအမည်</th>
                            <th>လာဆပ်သောငွေ</th>
                        </tr>

                    </thead>


                    <tbody>

                        @if ($settlement->count() > 0)

                            @foreach ($settlement as $item)
                                <tr>

                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        <div>{{ \Carbon\Carbon::parse($item->settlement_date)->format('d-m-Y') }}</div>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($item->settlement_date)->format('h:i A') }}
                                        </small>
                                    </td>

                                    <td>
                                        {{ $item->name }}
                                    </td>

                                    <td class="">

                                        <p>{{ number_format($item->amount, 0) }} <small
                                                class="small text-secondary">ကျပ်</small></p>

                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="10" class="empty-state-cell">
                                    <div class="empty-state">

                                        <h5>အကြွေးပြန်ဆပ်မှုစာရင်း မရှိသေးပါ</h5>

                                        <p>
                                            အကြွေးပြန်ဆပ်မှုစာရင်းကို ထည့်သွင်းထားသော မှတ်တမ်း မရှိသေးပါ။
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

            </div>


            {{-- Pagination Example --}}
            <div class="d-md-flex justify-content-between align-items-center mt-4">

                <p class="text-muted">
                    မှတ်တမ်း {{ $settlement->firstItem() ?? 0 }}
                    မှ {{ $settlement->lastItem() ?? 0 }}
                    အထိ ပြသထားပြီး
                    စုစုပေါင်း {{ $settlement->total() }} မျိုး ရှိပါသည်။
                </p>

                <nav class=" d-flex justify-content-end">

                    <ul class="pagination pagination-sm mb-0">

                        <span> {{ $settlement->links() }} </span>

                    </ul>

                </nav>

            </div>
        </div>

    @endif


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
