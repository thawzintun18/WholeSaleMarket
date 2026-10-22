@extends('layouts.app')

@section('page-title', 'Grades')

@section('breadcrumb', 'ဈေးနှုန်းများ / ကြိုသုံးငွေယူထားသော တောင်သူစာရင်း')

@section('content')

    <div class="row align-items-center mb-4">

        <div class=" col-md-8 col-12">
            <h1 class="page-title mb-1" style="line-height: 38px">
                ကြိုသုံးငွေယူထားသော တောင်သူစာရင်း
            </h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                တောင်သူများမှ သီးနှံလာမအပ်မီ ကြိုတင်ထုတ်ယူထားသော ငွေများနှင့် သီးနှံလာအပ်သည့်အခါ စာရင်းရှင်းရာတွင် နုတ်ယူရန်
                ကျန်ရှိနေသော ကြိုသုံးငွေလက်ကျန်များကို တောင်သူအလိုက် ကြည့်ရှုစီမံနိုင်သော စာရင်းဖြစ်သည်။
            </p>
        </div>

        <div class="col-md-4 col-12 d-flex justify-content-end">
            <a href="{{ route('FarmerDebt#directPage') }}" class="btn btn-success mt-md-0 mt-3">
                <i class="bi bi-plus-lg me-1"></i>
                ကြိုသုံးငွေယူမည့် တောင်သူထပ်ထည့်ရန်
            </a>
        </div>

    </div>

    {{-- Search & Filter --}}

    <div class="dashboard-card mb-4 shadow-sm">
        <form action="{{ route('FarmerDebt#list') }}" method="GET">
            <div class="row g-3">

                {{-- သီးနှံအလိုက် စစ်ထုတ်ရန် --}}
                <div class="col-md-4">

                    <label class="form-label">
                        သီးနှံအမည်
                    </label>

                    <form action="{{ route('Quality#list') }}" method="GET">
                        <select name="crop_id" class="form-select" onchange="this.form.submit()">

                            <option value="" {{ request('crop_id') == '' ? 'selected' : '' }}>
                                သီးနှံအားလုံးအတွက် ကြည့်ရန်
                            </option>

                            {{-- @foreach ($crops as $item)
                                <option value="{{ $item->id }}" {{ request('crop_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->crop_name }} သီးနှံအတွက် ကြည့်ရန်
                                </option>
                            @endforeach --}}

                        </select>
                    </form>

                </div>

                {{-- အဆင့်အမည် ရှာဖွေရန် --}}
                <div class="col-md-4">

                    <label class="form-label">
                        အဆင့်အမည် ရှာဖွေရန်
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="text" name="searchData" value="{{ request('searchData') }}" id="search"
                            class="form-control" placeholder="အဆင့်အမည် ရိုက်ထည့်ပါ...">

                    </div>

                </div>

                {{-- ရှာဖွေရန် ခလုတ် --}}
                <div class="col-md-auto d-flex align-items-end">

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>
                        ရှာဖွေမည်
                    </button>

                </div>

                {{-- ပြန်လည်သတ်မှတ်ရန် --}}
                <div class="col-md-auto d-flex align-items-end">

                    <a href="{{ route('FarmerDebt#list') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        ပြန်လည်သတ်မှတ်မည်
                    </a>

                </div>

            </div>
        </form>

    </div>

    {{-- Grade Table --}}
    <div class="dashboard-card shadow-sm">

        <div class="d-md-flex justify-content-between align-items-center mb-3">

            <div>

                <h5 class="mb-1" style="line-height: 38px">
                    ကြိုသုံးငွေယူထားသော တောင်သူစာရင်း
                </h5>

                <small class="text-muted mt-2">
                    စာရင်းသွင်းထားသော ကြိုသုံးငွေယူထားသော တောင်သူများ
                </small>

            </div>


            <div class="d-md-flex justify-content-end mt-md-0 mt-2">
                <div class="me-2">
                    <span class="badge bg-light text-dark border">
                        {{ $farmerDebt->count() }} မှတ်တမ်း
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
                        <th>တောင်သူကုဒ်</th>
                        <th>တောင်သူအမည်</th>
                        <th>မူလအကြွေးငွေ</th>
                        <th>ပြန်လည်ရှင်းလင်းပြီးငွေ</th>
                        <th>ကျန်ရှိအကြွေးငွေ</th>
                        <th>အကြွေးအမျိုးအစား</th>
                        <th>အကြွေးအခြေအနေ</th>
                        <th class="text-center" style="width: 130px;">လုပ်ဆောင်ချက်</th>
                    </tr>

                </thead>


                <tbody>

                    @if ($farmerDebt->count() > 0)

                        @foreach ($farmerDebt as $item)
                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <div>{{ \Carbon\Carbon::parse($item->debt_date)->format('d-m-Y') }}</div>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($item->debt_date)->format('h:i A') }}
                                    </small>
                                </td>

                                <td>
                                    {{ $item->farmer_code }}

                                </td>

                                <td>
                                    {{ $item->name }}
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

                                <td class="text-center">

                                    <div class="d-flex justify-content-center">
                                        {{-- @if ($item->debt_type == 'PurchaseAdvance' || $item->status == 'Close')
                                            - --}}
                                        @if (($item->debt_type == 'CashLoan' && $item->status == 'Open') || $item->status == 'Partial')
                                            <a href="{{ route('FarmerDebt#settlement', $item->id) }}"
                                                class=" btn btn-primary me-2">အကြွေးဆပ်ရန်</a>
                                        @endif

                                        {{-- @if ($item->debt_type == 'PurchaseAdvance')
                                            - --}}
                                        @if ($item->debt_type == 'CashLoan')
                                            <a href="{{ route('FarmerDebt#settlementList', $item->id) }}"
                                                class=" btn btn-primary">အကြွေးပြန်ဆပ်မှုစာရင်း</a>
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
                တောင်သူ {{ $farmerDebt->firstItem() ?? 0 }}
                မှ {{ $farmerDebt->lastItem() ?? 0 }}
                အထိ ပြသထားပြီး
                စုစုပေါင်း {{ $farmerDebt->total() }} ဦး ရှိပါသည်။
            </p>

            <nav class=" d-flex justify-content-end">

                <ul class="pagination pagination-sm mb-0">

                    <span> {{ $farmerDebt->links() }} </span>

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
