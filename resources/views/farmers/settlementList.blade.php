@extends('layouts.app')

@section('page-title', 'Grades')

@section('breadcrumb', 'အခြေခံစာရင်းများ / တောင်သူစာရင်း / အကြွေးစာရင်း')

@section('content')

    <div class="row align-items-center mb-4">

        <div class=" col-md-8">
            <h1 class="page-title mb-1" style="line-height: 38px">
                အကြွေးစာရင်း
            </h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                သီးနှံလာမအပ်မီ ကြိုတင်ထုတ်ယူထားသော ငွေများနှင့် အကြွေးယူသွားသောငွေများကို သီးနှံလာအပ်သည့်အခါ
                စာရင်းရှင်းရာတွင် နုတ်ယူရန်
                ကျန်ရှိနေသော ငွေလက်ကျန်များကို ကြည့်ရှုနိုင်သည်။
            </p>
        </div>

        <div class="col-md-4 d-flex justify-content-end">
            <a href="{{ route('farmers#index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>

    </div>


    {{-- တောင်သူအချက်အလက် --}}
    <div class="dashboard-card mb-4">
        <div class="card-header bg-white border-dark py-3">
            <h5 class="mb-0 fw-bold text-dark">
                တောင်သူအချက်အလက်
            </h5>
        </div>

        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-4 col-6">
                    <div class="small text-secondary mb-1">
                        တောင်သူကုဒ်
                    </div>
                    <div class="fw-semibold text-dark">
                        {{ $farmer->farmer_code ?? '-' }}
                    </div>
                </div>

                <div class="col-md-4 col-6">
                    <div class="small text-secondary mb-1">
                        တောင်သူအမည်
                    </div>
                    <div class="fw-semibold text-dark">
                        {{ $farmer->name }}
                    </div>
                </div>

                <div class="col-md-4 col-6">
                    <div class="small text-secondary mb-1">
                        ဖုန်းနံပါတ်
                    </div>
                    <div class="fw-semibold text-dark">
                        {{ $farmer->phone ?? '-' }}
                    </div>
                </div>

                <div class="col-md-4 col-6">
                    <div class="small text-secondary mb-1">
                        ကျေးရွာ
                    </div>
                    <div class="fw-semibold text-dark">
                        {{ $farmer->village ?? '-' }}
                    </div>
                </div>

                <div class="col-md-4 col-6">
                    <div class="small text-secondary mb-1">
                        အကောင့်အခြေအနေ
                    </div>

                    @if ($farmer->is_active)
                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                            အသုံးပြုနေသည်
                        </span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                            ပိတ်ထားသည်
                        </span>
                    @endif
                </div>

                <div class="col-md-12">
                    <div class="small text-secondary mb-1">
                        မှတ်ချက်
                    </div>
                    <div class="text-dark">
                        {{ $farmer->notes ?: '-' }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="dashboard-card shadow-sm">

        <div class="d-md-flex justify-content-between align-items-center mb-3">

            <div>

                <h5 class="mb-1" style="line-height: 38px">
                    အကြွေးစာရင်း
                </h5>

                <small class="text-muted mt-2">
                    စာရင်းသွင်းထားသော အကြွေးများ
                </small>

            </div>


            <div class="d-md-flex justify-content-end mt-md-0 mt-2">
                <div class="me-2">
                    <span class="badge bg-light text-dark border">
                        {{ $settlementList->count() }} မှတ်တမ်း
                    </span>
                </div>
            </div>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>
                        <th style="width: 60px;">စဉ်</th>
                        <th>အကြွေးယူသည့်နေ့ရက်</th>
                        <th>မူလအကြွေးငွေ</th>
                        <th>ပြန်လည်ရှင်းလင်းပြီးငွေ</th>
                        <th>ကျန်ရှိအကြွေးငွေ</th>
                        <th>အကြွေးအမျိုးအစား</th>
                        <th>အကြွေးအခြေအနေ</th>
                    </tr>

                </thead>


                <tbody>

                    @if ($settlementList->count() > 0)

                        @foreach ($settlementList as $item)
                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <div>{{ \Carbon\Carbon::parse($item->debt_date)->format('d-m-Y') }}</div>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($item->debt_date)->format('h:i A') }}
                                    </small>
                                </td>

                                <td class=" text-center">
                                    {{ number_format($item->amount, 0) }} <small class="small text-secondary">ကျပ်</small>

                                </td>

                                <td class=" text-center">
                                    {{ number_format($item->settled_amount, 0) == 0 ? 0 : number_format($item->settled_amount, 0) }}
                                    <small class="small text-secondary">ကျပ်</small>
                                </td>

                                <td class=" text-center">
                                    {{ number_format($item->balance_amount, 0) }} <small
                                        class="small text-secondary">ကျပ်</small>
                                </td>

                                <td class=" text-center">
                                    <small class="">
                                        @if ($item->debt_type == 'PurchaseAdvance')
                                            ကြိုယူငွေ
                                        @elseif($item->debt_type == 'CashLoan')
                                            တောကြွေး
                                        @endif
                                    </small>
                                </td>

                                <td class=" text-center">
                                    <div class="">
                                        @if ($item->status == 'Open')
                                            <small class=" bg-danger text-white rounded py-2 px-2">အကြွေးကျန်ရှိ</small>
                                        @elseif($item->status == 'Partial')
                                            <small class=" bg-warning text-white rounded py-2 px-2">တစ်စိတ်တစ်ပိုင်း
                                                ရှင်းပြီး</small>
                                        @elseif($item->status == 'Close')
                                            <small class=" bg-success text-white rounded py-2 px-2">အကြွေးရှင်းပြီး</small>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10" class="empty-state-cell">
                                <div class="empty-state">

                                    <h5>ကြိုသုံးငွေယူထားသော တောင်သူ မရှိသေးပါ</h5>

                                    <p>
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
        <div class="d-md-flex justify-content-between align-items-center mt-4">

            <p class="text-muted">
                မှတ်တမ်း {{ $settlementList->firstItem() ?? 0 }}
                မှ {{ $settlementList->lastItem() ?? 0 }}
                အထိ ပြသထားပြီး
                စုစုပေါင်း {{ $settlementList->total() }} မျိုး ရှိပါသည်။
            </p>

            <nav class=" d-flex justify-content-end">

                <ul class="pagination pagination-sm mb-0">

                    <span> {{ $settlementList->links() }} </span>

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
