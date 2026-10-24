@extends('layouts.app')

@section('page-title', 'ဝယ်ယူမှု စာရင်းများ')

@section('breadcrumb', 'ပင်မစာမျက်နှာ / ဝယ်ယူမှု / စာရင်းများ')

@section('content')
    <div class="container-fluid p-0">

        <!-- 🌟 Top Header & Action -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">သီးနှံဝယ်ယူမှု ဘောင်ချာများ</h4>
                <p class="text-muted small mb-0">တောင်သူများထံမှ ဝယ်ယူထားသော စာရင်းများနှင့် ငွေကြေးရှင်းတမ်းများကို
                    စီမံခန့်ခွဲရန်။</p>
            </div>
            <a href="{{ route('purchases#create#page') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> ဝယ်ယူမှုအသစ် ပြုလုပ်ရန်
            </a>
        </div>

        <!-- ၁။ Summary KPI Cards (Dynamic based on Date Filter) -->
        <div class="row g-3 mb-4">

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-primary h-100 p-3">
                    <div class="small text-muted fw-bold mb-1">ဘောင်ချာ အရေအတွက်</div>
                    <div class="fs-4 fw-bold text-primary">
                        {{ number_format((int) ($summary->filtered_voucher_count ?? 0)) }} <span
                            class="fs-6 text-muted">စောင်</span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-success h-100 p-3">
                    <div class="small text-muted fw-bold mb-1">ရွေးချယ်ထားသောကာလ ဝယ်ယူငွေ</div>
                    <div class="fs-4 fw-bold text-success">
                        {{ number_format((float) ($summary->filtered_gross_total ?? 0), 2) }} <span
                            class="fs-6 text-muted">ကျပ်</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-info h-100 p-3">
                    <div class="small text-muted fw-bold mb-1">အကြွေးပြန်နှုတ်ရရှိငွေ</div>
                    <div class="fs-4 fw-bold text-info">
                        {{ number_format((float) ($summary->filtered_debt_settled ?? 0), 2) }} <span
                            class="fs-6 text-muted">ကျပ်</span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-danger h-100 p-3">
                    <div class="small text-muted fw-bold mb-1">ပေးရန်ကျန်ငွေ စုစုပေါင်း (Pending)</div>
                    <div class="fs-4 fw-bold text-danger">
                        {{ number_format((float) ($summary->total_pending_balance ?? 0), 2) }} <span
                            class="fs-6 text-muted">ကျပ်</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- ၂။ Advanced Filter, Quick Presets & Search Section -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('purchases.index') }}" id="filterForm">

                    <!-- Quick Date Presets (တစ်ရက်၊ တစ်ပတ်၊ တစ်လ အလွယ်ရွေးရန်) -->
                    <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
                        <span class="small fw-bold text-muted me-2"><i class="bi bi-clock-history"></i>
                            အမြန်ကြည့်ရန်:</span>
                        <button type="submit" name="quick_filter" value="today"
                            class="btn btn-outline-secondary btn-sm rounded-pill px-3 {{ request('quick_filter') === 'today' ? 'active bg-dark text-white' : '' }}">ယနေ့
                            (Today)</button>
                        <button type="submit" name="quick_filter" value="this_week"
                            class="btn btn-outline-secondary btn-sm rounded-pill px-3 {{ request('quick_filter') === 'this_week' ? 'active bg-dark text-white' : '' }}">ဒီအပတ်
                            (This Week)</button>
                        <button type="submit" name="quick_filter" value="this_month"
                            class="btn btn-outline-secondary btn-sm rounded-pill px-3 {{ request('quick_filter') === 'this_month' ? 'active bg-dark text-white' : '' }}">ဒီလ
                            (This Month)</button>
                        <span>
                            <a href="{{ route('purchases.index') }}" class="btn btn-primary btn-sm rounded-pill px-3"
                                title="Reset လုပ်ရန်">
                                <i class="bi bi-arrow-counterclockwise pe-1"></i>Reset
                            </a>
                        </span>

                    </div>

                    <hr class="text-muted opacity-25">

                    <div class="row g-3 align-items-end">
                        <!-- ရှာဖွေရန် -->
                        <div class="col-12 col-md-3">
                            <label class="form-label small fw-semibold text-muted">ရှာဖွေရန် (No / တောင်သူ / ဖုန်း /
                                ကျေးရွာ)</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control border-start-0 bg-light"
                                    placeholder="ဘောင်ချာ No သို့မဟုတ် အမည်..." value="{{ request('search') }}">
                            </div>
                        </div>

                        <!-- သီးနှံအမျိုးအစား Filter -->
                        <div class="col-12 col-md-2">
                            <label class="form-label small fw-semibold text-muted">သီးနှံအမျိုးအစား</label>
                            <select name="crop_id" class="form-select form-select-sm bg-light">
                                <option value="">သီးနှံအားလုံး</option>
                                @foreach ($crops as $crop)
                                    <option value="{{ $crop->id }}"
                                        {{ request('crop_id') == $crop->id ? 'selected' : '' }}>
                                        {{ $crop->crop_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- ငွေရှင်းမှု အခြေအနေ -->
                        <div class="col-12 col-md-2">
                            <label class="form-label small fw-semibold text-muted">ငွေရှင်းမှု အခြေအနေ</label>
                            <select name="status" class="form-select form-select-sm bg-light">
                                <option value="">အားလုံး</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>ပေးရန်ကျန်
                                    (Pending)</option>
                                <option value="complete" {{ request('status') === 'complete' ? 'selected' : '' }}>ရှင်းပြီး
                                    (Complete)</option>
                            </select>
                        </div>

                        <!-- From Date -->
                        <div class="col-6 col-md-2">
                            <label class="form-label small fw-semibold text-muted">မှ ရက်စွဲ (From)</label>
                            <input type="date" name="from_date" class="form-control form-control-sm bg-light"
                                value="{{ request('from_date', $fromDate) }}">
                        </div>

                        <!-- To Date -->
                        <div class="col-6 col-md-2">
                            <label class="form-label small fw-semibold text-muted">ထိ ရက်စွဲ (To)</label>
                            <input type="date" name="to_date" class="form-control form-control-sm bg-light"
                                value="{{ request('to_date', $toDate) }}">
                        </div>

                        <!-- Submit & Reset Buttons -->
                        <div class="col-12 col-md-1 d-flex gap-1">
                            <button type="submit" class="btn btn-primary btn-sm w-100 py-3 shadow-sm" title="ရှာဖွေမည်">
                                <i class="bi bi-search"></i>
                            </button>
                            {{-- <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary btn-sm w-100"
                                title="Reset လုပ်ရန်">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a> --}}
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- ၃. Purchase List Table (Clean & Modern) -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 px-4 border-bottom border-light">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-list-check me-2"></i> ဝယ်ယူမှု ဘောင်ချာများ စာရင်း</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 16.5px;">
                    <thead class="table-light text-uppercase fs-7 text-muted">
                        <tr>
                            {{-- class="ps-4 py-3" class="py-3"  <th class="ps-4 py-3" style="width: 15%;">ဘောင်ချာ No / ရက်စွဲ</th> --}}
                            <th style="width: 7%;">စဉ်</th>
                            <th style="width: 8%;">နေ့စွဲ</th>
                            <th style="width: 15%;">တောင်သူအမည်</th>
                            <th style="width: 25%;">ဝယ်ယူထားသော သီးနှံများ (Quality / Price)</th>
                            <th class="text-end" style="width: 10%;">သင့်ငွေ</th>
                            <th class="text-end" style="width: 10%;">ပေးပြီးငွေ</th>
                            <th class="text-end" style="width: 10%;">ပေးရန်ကျန်</th>
                            <th class="text-center" style="width: 5%;">အခြေအနေ</th>
                            <th class="text-end pe-4" style="width: 10%;">လုပ်ဆောင်ချက်</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $purchase)
                            <tr>
                                <!-- ဘောင်ချာ No / ရက်စွဲ -->
                                {{-- <td class="ps-4">
                                    <span class="fw-bold text-primary">{{ $purchase->invoice_no }}</span>
                                    <div class="small text-muted" style="font-size: 11.5px;">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('Y-m-d h:i A') }}
                                    </div>
                                </td> --}}

                                <td class="ps-4">
                                    {{ $purchases->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <span class="fw-semibold">
                                        {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('Y-m-d') }}
                                    </span>
                                    <div class="small text-muted" style="font-size: 11.5px;">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('h:i A') }}
                                    </div>
                                </td>

                                <!-- တောင်သူအမည် -->
                                <td>
                                    <span class="fw-semibold text-dark">{{ $purchase->farmer_name }}</span>
                                    <div class="small text-muted"><i class="bi bi-telephone me-1"></i>
                                        {{ $purchase->farmer_phone ?? '-' }}</div>
                                    <div class="small fw-semibold text-dark">
                                        {{ $purchase->farmer_village ?? '-' }}</div>
                                </td>

                                <!-- သီးနှံအသေးစိတ်များ -->
                                <td class="py-3">
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($purchase->items as $item)
                                            <li class="mb-1 pb-1 border-bottom border-light last-border-0">
                                                <div class="d-flex justify-content-between">
                                                    <span class="fw-semibold text-dark">{{ $item->crop_name }}</span>
                                                    <span
                                                        class="badge bg-light text-dark border px-2 py-0.5 rounded-pill">{{ $item->grade_name }}</span>
                                                </div>
                                                <div class="small text-muted mt-0.5">
                                                    {{-- ပမာဏ: --}}
                                                    <strong>
                                                        {{ number_format($item->quantity, 2) }}
                                                        {{ strtolower($item->unit) === 'viss' ? 'ပိဿာ' : (strtolower($item->unit) === 'pound' ? 'ပေါင်' : $item->unit) }}
                                                    </strong>
                                                    @if (strtolower($item->unit) === 'pound' && $item->bag_count > 0)
                                                        <span class="text-info fw-semibold">({{ $item->bag_count }}
                                                            အိတ်)</span>
                                                    @endif
                                                    {{-- / ဈေး --}}/
                                                    {{ number_format($item->actual_buy_price, 2) }} ကျပ်
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>

                                <!-- သင့်ငွေ -->
                                <td class="text-end fw-bold text-dark">
                                    {{ number_format((float) $purchase->net_amount, 2) }}
                                </td>

                                <!-- ပေးပြီးငွေ -->
                                <td class="text-end text-success fw-semibold">
                                    {{ number_format((float) $purchase->total_paid, 2) }}
                                </td>

                                <!-- ပေးရန်ကျန်ငွေ -->
                                <td class="text-end fw-bold">
                                    @if ($purchase->balance_amount > 0)
                                        <span class="text-danger bg-danger-subtle px-2 py-1 rounded">
                                            {{ number_format((float) $purchase->balance_amount, 2) }}
                                        </span>
                                    @else
                                        <span class="text-muted">0.00</span>
                                    @endif
                                </td>

                                <!-- အခြေအနေ -->
                                <td class="text-center">
                                    @if ($purchase->balance_amount <= 0)
                                        <span
                                            class="badge bg-success-subtle text-success px-4 py-1 rounded-pill">ရှင်း</span>
                                    @else
                                        <span
                                            class="badge bg-warning-subtle text-warning-emphasis px-3 py-1 rounded-pill">ငွေကျန်</span>
                                    @endif
                                </td>

                                <!-- Action Buttons -->
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex align-items-center gap-2">
                                        {{-- ဘောင်ချာကြည့်ရန် --}}
                                        <a href="{{ route('purchases#voucher', $purchase->id) }}"
                                            class="btn btn-sm btn-outline-secondary rounded-2" title="ဘောင်ချာကြည့်မည်"
                                            aria-label="ဘောင်ချာကြည့်မည်">
                                            <i class="bi bi-receipt"></i>
                                        </a>

                                        {{-- ငွေပေးချေမှု မှတ်တမ်းကြည့်ရန် ခလုတ် --}}
                                        <button type="button" class="btn btn-sm btn-outline-info rounded-2"
                                                data-bs-toggle="modal" data-bs-target="#historyModal{{ $purchase->id }}"
                                                title="ငွေပေးချေမှု မှတ်တမ်း">
                                            <i class="bi bi-clock-history"></i>
                                        </button>

                                        {{-- ကျန်ငွေရှိမှ ငွေရှင်းရန်ပြမည် --}}
                                        @if ($purchase->balance_amount > 0)
                                            <button type="button" class="btn btn-sm btn-outline-success rounded-2"
                                                data-bs-toggle="modal" data-bs-target="#payModal{{ $purchase->id }}">
                                                <i class="bi bi-wallet2 me-1"></i> ငွေရှင်း
                                            </button>
                                        @endif

                                    </div>



                                    <!-- ငွေရှင်းမည် Modal -->
                                    <div class="modal fade" id="payModal{{ $purchase->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow rounded-4">
                                                <div class="modal-header bg-light">
                                                    <h6 class="modal-title fw-bold">
                                                        <i class="bi bi-cash-stack me-1 text-primary"></i>
                                                        ကျန်ငွေ ရှင်းပေးရန် ({{ $purchase->invoice_no }})
                                                    </h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('purchases#payBalance', $purchase->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-body p-4">
                                                        <div class="mb-3">
                                                            <label
                                                                class="form-label small text-muted fw-semibold">တောင်သူအမည်</label>
                                                            <input type="text" class="form-control bg-light"
                                                                value="{{ $purchase->farmer_name }}" readonly>
                                                        </div>

                                                        <div class="row g-2 mb-3">
                                                            <div class="col-6">
                                                                <label
                                                                    class="form-label small text-muted fw-semibold">ပေးရန်ကျန်ငွေ</label>
                                                                <input type="text"
                                                                    class="form-control bg-light text-danger fw-bold"
                                                                    value="{{ number_format((float) $purchase->balance_amount, 2) }}"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="form-label small text-muted fw-semibold">ယခု
                                                                    ပေးချေမည့်ငွေ</label>
                                                                <input type="number" step="0.01" name="pay_amount"
                                                                    class="form-control fw-bold border-primary"
                                                                    max="{{ $purchase->balance_amount }}"
                                                                    value="{{ $purchase->balance_amount }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light border-0">
                                                        <button type="button"
                                                            class="btn btn-outline-secondary btn-sm rounded-pill px-3"
                                                            data-bs-dismiss="modal">မလုပ်တော့ပါ</button>
                                                        <button type="submit"
                                                            class="btn btn-success btn-sm rounded-pill px-3">
                                                            <i class="bi bi-check-circle me-1"></i> ငွေပေးချေမည်
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ငွေပေးချေမှု မှတ်တမ်း (Payment History) Modal -->
                                    <div class="modal fade" id="historyModal{{ $purchase->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content border-0 shadow rounded-4 text-start">
                                                <div class="modal-header bg-light">
                                                    <h6 class="modal-title fw-bold">
                                                        <i class="bi bi-clock-history me-1 text-info"></i>
                                                        ငွေပေးချေမှု မှတ်တမ်း - ဘောင်ချာ No: {{ $purchase->invoice_no }}
                                                    </h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3 d-flex justify-content-between">
                                                        <span class="small text-muted">တောင်သူအမည်: <strong class="text-dark">{{ $purchase->farmer_name }}</strong> ( <strong class="text-dark">{{ $purchase->farmer_village }}</strong> )</span>
                                                        <span class="small text-muted">စုစုပေါင်းသင့်ငွေ: <strong class="text-dark">{{ number_format($purchase->net_amount, 2) }} ကျပ်</strong></span>
                                                    </div>

                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-sm align-middle mb-0" style="font-size: 16px;">
                                                            <thead class="table-light text-center">
                                                                <tr>
                                                                    <th>စဉ်</th>
                                                                    <th>ပေးချေသည့် ရက်စွဲ</th>
                                                                    <th>ပေးချေပုံ အမျိုးအစား</th>
                                                                    <th class="text-end">ပေးချေငွေ ပမာဏ</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    // ဤ purchase_id နှင့် ဆက်စပ်နေသော ပေးချေမှုမှတ်တမ်းများကို Database မှ ထုတ်ယူပြသမည်
                                                                    $payments = DB::table('purchase_payments')
                                                                        ->where('purchase_id', $purchase->id)
                                                                        ->orderBy('payment_date', 'asc')
                                                                        ->get();
                                                                @endphp

                                                                @forelse($payments as $index => $pay)
                                                                    <tr>
                                                                        <td class="text-center">{{ $index + 1 }}</td>
                                                                        <td class="text-center">{{ \Carbon\Carbon::parse($pay->payment_date)->format('Y-m-d h:i A') }}</td>
                                                                        <td class="text-center">
                                                                            @if($pay->payment_type === 'initial')
                                                                                <span class="badge bg-primary-subtle text-primary px-2 py-1">ဘောင်ချာထုတ်ချိန်ရှင်းငွေ (Initial)</span>
                                                                            @else
                                                                                <span class="badge bg-success-subtle text-success px-2 py-1">နောက်ပိုင်းထပ်ရှင်းငွေ (Additional)</span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-end fw-bold text-success">
                                                                            {{ number_format($pay->amount, 2) }} ကျပ်
                                                                        </td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="4" class="text-center py-3 text-muted">
                                                                            ပေးချေမှု မှတ်တမ်း မရှိသေးပါ။
                                                                        </td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light border-0">
                                                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">ပိတ်မည်</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                                    ရှာဖွေထားသော ဝယ်ယူမှု စာရင်းများ မရှိပါ။
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if (method_exists($purchases, 'links'))
                <div class="card-footer bg-white py-3 px-4 border-top border-light">
                    {{ $purchases->links() }}
                </div>
            @endif
        </div>

    </div>





@endsection
