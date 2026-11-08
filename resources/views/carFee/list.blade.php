@extends('layouts.app')

@section('page-title', 'Crops')
@section('breadcrumb')
    Stock / ကားဘောင်ချာစာရင်း
@endsection


@section('content')

    <div class=" d-md-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="page-title mb-1">ကားဘောင်ချာစာရင်း</h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                ပွဲရုံမှ လက်ခံဝယ်ယူမည့် သီးနှံအမျိုးအစားများနှင့် သက်ဆိုင်သော အချက်အလက်များကို ဤစာရင်းတွင်
                ကြည့်ရှုနိုင်ပါသည်။
            </p>
        </div>

        <div class=" d-flex justify-content-end">
            <a href="{{ route('CarFee#directPage') }}" class="btn btn-success mt-md-0 mt-3">
                <i class="bi bi-plus-lg me-1"></i>
                ကားဘောင်ချာထပ်ထည့်ရန်
            </a>
        </div>

    </div>


    {{-- Search --}}
    <div class="dashboard-card mb-4 shadow-sm">
        <form action="{{ route('CarFee#list') }}" method="GET">
            <div class="row g-3">

                <div class="col-12 col-md-4">

                    <label class="form-label">
                        ကားသမားရှာဖွေရန်
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="text" name="searchData" value="{{ request('searchData') }}" id="cropSearch"
                            class="form-control p-2" placeholder="ကားသမားအမည် ရိုက်ထည့်ပါ...">

                    </div>

                </div>

                <!-- From Date -->
                <div class="col-6 col-md-2">
                    <label class="form-label small fw-semibold text-muted">မှ ရက်စွဲ (From)</label>
                    <input type="date" name="from_date" class="form-control form-control-sm bg-light"
                        value="{{ request('from_date') }}">
                </div>

                <!-- To Date -->
                <div class="col-6 col-md-2">
                    <label class="form-label small fw-semibold text-muted">ထိ ရက်စွဲ (To)</label>
                    <input type="date" name="to_date" class="form-control form-control-sm bg-light"
                        value="{{ request('to_date') }}">
                </div>

                <div class="col-md-auto d-flex align-items-end">

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>
                        ရှာဖွေမည်
                    </button>

                </div>

                <div class="col-md-auto d-flex align-items-end">

                    <a href="{{ route('CarFee#list') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        ပြန်လည်သတ်မှတ်မည်
                    </a>

                </div>

            </div>
        </form>

    </div>


    <div class="dashboard-card shadow-sm">

        <div class="d-md-flex justify-content-between align-items-center mb-3">

            <div>
                <h5 class="mb-1">
                    ယနေ့ ကားခတွက်ချက်ပြီးစာရင်း
                    <span class="float-end mx-2">{{ $today }}</span>
                </h5>
            </div>

            <div class=" d-md-flex justify-content-end mt-md-0 mt-2">
                <div class=" me-2">
                    <span class="badge bg-light text-dark border">
                        {{ $payments->count() }} မှတ်တမ်း
                    </span>
                </div>
            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>
                        <th>စဉ်</th>
                        <th>ကားနံပါတ်</th>
                        <th>ကားသမားအမည်</th>
                        <th>စုစုပေါင်းငွေ</th>
                        <th>ရက်စွဲ</th>
                        <th>လုပ်ဆောင်ချက်</th>
                    </tr>

                </thead>


                <tbody>

                    @if ($payments->count() > 0)

                        @foreach ($payments as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>{{ $item->car_no }}</td>

                                <td>{{ $item->driver_name }}</td>

                                <td>
                                    {{ number_format($item->total_amount, 0) }}
                                    <small class="small text-secondary">ကျပ်</small>
                                </td>

                                <td>{{ $item->payment_date }}</td>

                                <td>
                                    {{-- ဘောင်ချာကြည့်ရန် --}}
                                    <a href="{{ route('CarFee#voucher', $item->id) }}"
                                        class="btn btn-sm btn-outline-secondary rounded-2" title="ဘောင်ချာကြည့်မည်"
                                        aria-label="ဘောင်ချာကြည့်မည်">
                                        <i class="bi bi-receipt"></i>
                                    </a>

                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-2"
                                        data-bs-toggle="modal" data-bs-target="#carFeeDetailModal"
                                        data-url="{{ route('CarFee#detail', $item->id) }}">

                                        <i class="bi bi-list-ul"></i>
                                    </button>

                                    <button type="button" onclick="DeleteData({{ $item->id }})"
                                        class=" btn btn-sm btn-outline-danger " title="ဖျက်ရန်">
                                        ဖျက်ရန်
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="empty-state-cell">
                                <div class="empty-state">

                                    <h5>ကားဘောင်ချာစာရင်း မရှိသေးပါ</h5>

                                    <p>
                                        ယနေ့ ကားခတွက်ချက်ထားသော စာရင်း မရှိသေးပါ။
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

        </div>

        <div class="modal fade" id="carFeeDetailModal" tabindex="-1" aria-labelledby="carFeeDetailModalLabel"
            aria-hidden="true">

            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">

                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title" id="carFeeDetailModalLabel">
                                <i class="bi bi-truck me-2"></i>
                                ကားခအသေးစိတ်စာရင်း
                            </h5>

                            <div class="small text-muted mt-1">
                                ကားနံပါတ် - <span id="detailCarNo">-</span>
                                |
                                ကားသမား - <span id="detailDriverName">-</span>
                            </div>
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <span class="text-muted">ရက်စွဲ - </span>
                            <span id="detailPaymentDate">-</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">စဉ်</th>
                                        <th>ဘောင်ချာအမှတ်</th>
                                        <th>တောင်သူအမည်</th>
                                        <th class="text-end">ကားခ (ကျပ်)</th>
                                    </tr>
                                </thead>

                                <tbody id="carFeeDetailBody">
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            စာရင်းဖွင့်နေပါသည်...
                                        </td>
                                    </tr>
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">
                                            စုစုပေါင်း
                                        </th>
                                        <th class="text-end" id="detailTotalAmount">
                                            0
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            ပိတ်မည်
                        </button>
                    </div>

                </div>
            </div>
        </div>

        {{-- Pagination Example --}}
        <div class="d-md-flex justify-content-between align-items-center mt-4">

            <p class="text-muted">
                မှတ်တမ်း {{ $payments->firstItem() ?? 0 }}
                မှ {{ $payments->lastItem() ?? 0 }}
                အထိ ပြသထားပြီး
                စုစုပေါင်း {{ $payments->total() }} မျိုး ရှိပါသည်။
            </p>

            <nav class=" d-flex justify-content-end">

                <ul class="pagination pagination-sm mb-0">

                    <span> {{ $payments->links() }} </span>

                </ul>

            </nav>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const modal = document.getElementById('carFeeDetailModal');

            modal.addEventListener('show.bs.modal', async function(event) {

                const button = event.relatedTarget;
                const url = button.dataset.url;

                const tbody = document.getElementById('carFeeDetailBody');

                tbody.replaceChildren();

                const loadingRow = tbody.insertRow();
                const loadingCell = loadingRow.insertCell();

                loadingCell.colSpan = 4;
                loadingCell.className = 'text-center py-4';
                loadingCell.textContent = 'စာရင်းဖွင့်နေပါသည်...';

                document.getElementById('detailTotalAmount').textContent = '0';

                try {
                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'စာရင်းရယူ၍ မရပါ။');
                    }

                    document.getElementById('detailCarNo').textContent =
                        data.car_no || '-';

                    document.getElementById('detailDriverName').textContent =
                        data.driver_name || '-';

                    document.getElementById('detailPaymentDate').textContent =
                        data.payment_date || '-';

                    tbody.replaceChildren();

                    let total = 0;

                    if (!data.purchases || data.purchases.length === 0) {
                        const row = tbody.insertRow();
                        const cell = row.insertCell();

                        cell.colSpan = 4;
                        cell.className = 'text-center text-muted py-4';
                        cell.textContent = 'သက်ဆိုင်ရာ ကားခစာရင်း မရှိပါ။';
                    } else {
                        data.purchases.forEach(function(item, index) {

                            const row = tbody.insertRow();

                            const values = [
                                index + 1,
                                item.invoice_no || '-',
                                item.farmer_name || '-',
                            ];

                            values.forEach(function(value) {
                                const cell = row.insertCell();
                                cell.textContent = value;
                            });

                            row.cells[0].className = 'text-center';
                            row.cells[3] = row.insertCell();
                            row.cells[3].className = 'text-end';

                            const amount = Number(item.car_fee_amount || 0);

                            row.cells[3].textContent =
                                amount.toLocaleString('en-US', {
                                    maximumFractionDigits: 2
                                });

                            total += amount;
                        });
                    }

                    document.getElementById('detailTotalAmount').textContent =
                        total.toLocaleString('en-US', {
                            maximumFractionDigits: 2
                        });

                } catch (error) {
                    tbody.replaceChildren();

                    const row = tbody.insertRow();
                    const cell = row.insertCell();

                    cell.colSpan = 4;
                    cell.className = 'text-center text-danger py-4';
                    cell.textContent = error.message;
                }
            });
        });
    </script>
@endpush

@section('DeleteData')
    <script>
        function DeleteData(id) {
            Swal.fire({
                title: "ကားဘောင်ချာကို ဖျက်ရန် သေချာပါသလား?",
                text: "ဖျက်ပြီးပါက ဤကားဘောင်ချာအချက်အလက်ကို ပြန်လည်ရယူ၍ မရတော့ပါ။",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ဖျက်မည်",
                cancelButtonText: "မဖျက်တော့ပါ"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "ဖျက်ပြီးပါပြီ!",
                        text: "ကားဘောင်ချာကို အောင်မြင်စွာ ဖျက်ပြီးပါပြီ။",
                        icon: "success"
                    }).then(() => {
                        location.href = '/whole-sale-market/CarFee/delete/' + id;
                    });
                }
            });
        }
    </script>
@endsection
