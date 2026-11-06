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



    <div class="dashboard-card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-person-lines-fill me-2"></i>
                    အကြွေးအသေးစိတ်
                </h5>

                @if ($farmerDebt->status == 'Open')
                    <span class="badge bg-danger">အကြွေးကျန်ရှိ</span>
                @elseif ($farmerDebt->status == 'Partial')
                    <span class="badge bg-warning text-dark">တစ်စိတ်တစ်ပိုင်း ရှင်းပြီး</span>
                @elseif ($farmerDebt->status == 'Close')
                    <span class="badge bg-success">အကြွေးရှင်းပြီး</span>
                @endif
            </div>
        </div>

        <div class="card-body">

            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <small class="text-muted">တောင်သူအမည်</small>
                    <div class="fw-bold">{{ $farmerDebt->name }}</div>
                </div>

                <div class="col-md-3 col-6">
                    <small class="text-muted">ဖုန်းနံပါတ်</small>
                    <div class="fw-semibold">{{ $farmerDebt->phone ?: '—' }}</div>
                </div>

                <div class="col-md-3 col-6">
                    <small class="text-muted">နေရပ်</small>
                    <div class="fw-semibold">{{ $farmerDebt->village ?: '—' }}</div>
                </div>

                <div class="col-md-3 col-6">
                    <small class="text-muted">အကြွေးအမျိုးအစား</small>
                    <div class="fw-semibold">
                        @switch($farmerDebt->debt_type)
                            @case('PurchaseAdvance')
                                ကြိုသုံး
                            @break

                            @case('CashLoan')
                                တောကြွေး
                            @break

                            @case('BeanPayment')
                                ပဲဆံဖိုး
                            @break

                            @default
                                အခြား
                        @endswitch
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <small class="text-muted">အကြွေးယူသည့်နေ့</small>
                    <div class="fw-semibold">
                        {{ \Carbon\Carbon::parse($farmerDebt->debt_date)->format('d-M-Y') }}
                    </div>
                </div>
            </div>

            <hr>

            <h6 class="fw-bold mb-3">ငွေစာရင်းချုပ်</h6>

            <div class="row g-3">
                <div class="col-md-4 col-4">
                    <div class="border rounded p-3 h-100">
                        <small class="text-muted">မူလအကြွေး</small>
                        <div class="fw-bold fs-5">
                            {{ number_format($farmerDebt->amount, 2) }}
                        </div>
                        <small class="text-muted">ကျပ်</small>
                    </div>
                </div>

                <div class="col-md-4 col-4">
                    <div class="border rounded p-3 h-100">
                        <small class="text-muted">ပြန်ဆပ်ပြီး</small>
                        <div class="fw-bold fs-5 text-success">
                            {{ number_format($farmerDebt->settled_amount ?? 0, 2) }}
                        </div>
                        <small class="text-muted">ကျပ်</small>
                    </div>
                </div>

                <div class="col-md-4 col-4">
                    <div class="border rounded p-3 h-100">
                        <small class="text-muted">လက်ကျန်အကြွေး</small>
                        <div class="fw-bold fs-5 text-danger">
                            {{ number_format($farmerDebt->balance_amount ?? 0, 2) }}
                        </div>
                        <small class="text-muted">ကျပ်</small>
                    </div>
                </div>
            </div>

        </div>
    </div>


    @if ($farmerDebt->debt_type == 'CashLoan' || $farmerDebt->debt_type == 'BeanPayment')

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
                                        <div>{{ \Carbon\Carbon::parse($item->settlement_date)->format('d-M-Y') }}</div>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($item->settlement_date)->format('h:i A') }}
                                        </small>
                                    </td>

                                    <td>
                                        {{ $item->name }}
                                    </td>

                                    <td class="">

                                        <p>{{ number_format($item->amount, 2) }} <small
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
