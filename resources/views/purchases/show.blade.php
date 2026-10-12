blade
@extends('layouts.app')

@section('page-title', 'Purchase Details')

@section('breadcrumb')
    Dashboard / Purchases / Details
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title mb-1">Purchase Details</h1>
        <p class="text-muted mb-0">
            View purchase items, payment history and outstanding balance.
        </p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('purchases.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>

        <a href="{{ route('purchases.edit', $purchase->id) }}"
           class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>

        <a href="{{ route('purchases.voucher', $purchase->id) }}"
           target="_blank" class="btn btn-success">
            <i class="bi bi-printer me-1"></i> Print Voucher
        </a>
    </div>
</div>

{{-- Purchase Information --}}
<div class="dashboard-card shadow-sm mb-4">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h5 class="mb-1">Purchase Information</h5>
            <small class="text-muted">Invoice details</small>
        </div>

        <span class="badge bg-light text-dark border">
            {{ $purchase->status }}
        </span>
    </div>

    <div class="row g-3">
        <div class="col-md-3">
            <small class="text-muted">Invoice No.</small>
            <div class="fw-semibold">{{ $purchase->invoice_no }}</div>
        </div>

        <div class="col-md-3">
            <small class="text-muted">Purchase Date</small>
            <div class="fw-semibold">
                {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y, h:i A') }}
            </div>
        </div>

        <div class="col-md-3">
            <small class="text-muted">Farmer</small>
            <div class="fw-semibold">
                {{ $purchase->farmer->name ?? '-' }}
            </div>
        </div>

        <div class="col-md-3">
            <small class="text-muted">Phone</small>
            <div class="fw-semibold">
                {{ $purchase->farmer->phone ?? '-' }}
            </div>
        </div>

        <div class="col-12">
            <small class="text-muted">Note</small>
            <div>{{ $purchase->note ?: '-' }}</div>
        </div>
    </div>
</div>

{{-- Purchase Items --}}
<div class="dashboard-card shadow-sm mb-4">
    <h5 class="mb-3">Purchase Items</h5>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Crop</th>
                    <th>Grade</th>
                    <th class="text-end">Quantity</th>
                    <th>Unit</th>
                    <th class="text-end">Buy Price</th>
                    <th class="text-end">Gross Amount</th>
                    <th class="text-end">Commission</th>
                    <th class="text-end">Car Fee</th>
                    <th class="text-end">Net Amount</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($purchase->items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            {{ $item->crop->crop_name ?? '-' }}
                        </td>

                        <td>
                            {{ $item->grade->grade_name ?? '-' }}
                        </td>

                        <td class="text-end">
                            {{ number_format($item->quantity, 3) }}
                        </td>

                        <td>{{ $item->unit }}</td>

                        <td class="text-end">
                            {{ number_format($item->actual_buy_price, 2) }}
                        </td>

                        <td class="text-end">
                            {{ number_format($item->gross_amount, 2) }}
                        </td>

                        <td class="text-end">
                            {{ number_format($item->commission_amount, 2) }}
                        </td>

                        <td class="text-end">
                            {{ number_format($item->car_fee_amount, 2) }}
                        </td>

                        <td class="text-end fw-semibold">
                            {{ number_format($item->net_amount, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            No purchase items found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Payment History --}}
<div class="dashboard-card shadow-sm mb-4">
    <h5 class="mb-3">Payment History</h5>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Payment Date</th>
                    <th>Payment Type</th>
                    <th>Method</th>
                    <th>Note</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($purchase->payments as $payment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y, h:i A') }}
                        </td>

                        <td>{{ $payment->payment_type }}</td>

                        <td>{{ $payment->payment_method }}</td>

                        <td>{{ $payment->note ?: '-' }}</td>

                        <td class="text-end text-success fw-semibold">
                            {{ number_format($payment->amount, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            No payments recorded.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Summary --}}
<div class="row justify-content-end mb-4">
    <div class="col-lg-5">
        <div class="dashboard-card shadow-sm">
            <h5 class="mb-3">Purchase Summary</h5>

            <div class="summary-row">
                <span>Total Amount</span>
                <strong>{{ number_format($purchase->total_amount, 2) }} Ks</strong>
            </div>

            <div class="summary-row">
                <span>Total Deduction</span>
                <strong class="text-danger">
                    {{ number_format($purchase->total_deduction, 2) }} Ks
                </strong>
            </div>

            <hr>

            <div class="summary-row fs-5">
                <span>Net Amount</span>
                <strong class="text-success">
                    {{ number_format($purchase->net_amount, 2) }} Ks
                </strong>
            </div>

            <div class="summary-row">
                <span>Total Paid</span>
                <strong>
                    {{ number_format($purchase->total_paid, 2) }} Ks
                </strong>
            </div>

            <div class="summary-row">
                <span>Balance</span>
                <strong class="text-danger">
                    {{ number_format($purchase->balance_amount, 2) }} Ks
                </strong>
            </div>
        </div>
    </div>
</div>

<style>
    .summary-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 9px 0;
    }
</style>

@endsection

