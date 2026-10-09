blade
@extends('layouts.app')

@section('page-title', 'Purchases')

@section('breadcrumb')
    Dashboard / Purchases
@endsection

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="page-title mb-1">Purchases</h1>

        <p class="text-muted mb-0">
            Manage farmer purchases, payments and vouchers.
        </p>
    </div>

    <a href="{{ route('purchases.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg me-1"></i>
        New Purchase
    </a>

</div>


{{-- Summary Cards --}}
<div class="row g-3 mb-4">

    <div class="col-md-6 col-xl-3">
        <div class="dashboard-card shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-start">

                <div>
                    <p class="text-muted mb-2">Today's Purchases</p>
                    <h3 class="mb-1">24</h3>
                    <small class="text-muted">Today's vouchers</small>
                </div>

                <div class="purchase-icon bg-primary-subtle text-primary">
                    <i class="bi bi-receipt"></i>
                </div>

            </div>
        </div>
    </div>


    <div class="col-md-6 col-xl-3">
        <div class="dashboard-card shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-start">

                <div>
                    <p class="text-muted mb-2">Today's Purchase Amount</p>
                    <h3 class="mb-1">8,450,000</h3>
                    <small class="text-muted">MMK</small>
                </div>

                <div class="purchase-icon bg-success-subtle text-success">
                    <i class="bi bi-basket"></i>
                </div>

            </div>
        </div>
    </div>


    <div class="col-md-6 col-xl-3">
        <div class="dashboard-card shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-start">

                <div>
                    <p class="text-muted mb-2">Today's Paid Amount</p>
                    <h3 class="mb-1">5,200,000</h3>
                    <small class="text-muted">MMK</small>
                </div>

                <div class="purchase-icon bg-info-subtle text-info">
                    <i class="bi bi-cash-stack"></i>
                </div>

            </div>
        </div>
    </div>


    <div class="col-md-6 col-xl-3">
        <div class="dashboard-card shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-start">

                <div>
                    <p class="text-muted mb-2">Outstanding Balance</p>
                    <h3 class="mb-1 text-danger">3,250,000</h3>
                    <small class="text-muted">MMK remaining</small>
                </div>

                <div class="purchase-icon bg-danger-subtle text-danger">
                    <i class="bi bi-wallet2"></i>
                </div>

            </div>
        </div>
    </div>

</div>


{{-- Search & Filter --}}
<div class="dashboard-card shadow-sm mb-4">

    <form action="{{ route('purchases.index') }}" method="GET">

        <div class="row g-3">

            <div class="col-lg-4 col-md-6">

                <label class="form-label">Search</label>

                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        value="{{ request('search') }}"
                        placeholder="Invoice no, farmer name, phone..."
                    >
                </div>

            </div>


            <div class="col-lg-2 col-md-6">

                <label class="form-label">Payment Status</label>

                <select name="payment_status" class="form-select">

                    <option value="">All Payments</option>

                    <option value="unpaid"
                        @selected(request('payment_status') === 'unpaid')>
                        Unpaid
                    </option>

                    <option value="partial"
                        @selected(request('payment_status') === 'partial')>
                        Partially Paid
                    </option>

                    <option value="paid"
                        @selected(request('payment_status') === 'paid')>
                        Fully Paid
                    </option>

                </select>

            </div>


            <div class="col-lg-2 col-md-6">

                <label class="form-label">Purchase Status</label>

                <select name="status" class="form-select">

                    <option value="">All Statuses</option>

                    <option value="DRAFT"
                        @selected(request('status') === 'DRAFT')>
                        Draft
                    </option>

                    <option value="CONFIRMED"
                        @selected(request('status') === 'CONFIRMED')>
                        Confirmed
                    </option>

                    <option value="COMPLETE"
                        @selected(request('status') === 'COMPLETE')>
                        Complete
                    </option>

                </select>

            </div>


            <div class="col-lg-2 col-md-6">

                <label class="form-label">From Date</label>

                <input
                    type="date"
                    name="date_from"
                    class="form-control"
                    value="{{ request('date_from') }}"
                >

            </div>


            <div class="col-lg-2 col-md-6">

                <label class="form-label">To Date</label>

                <input
                    type="date"
                    name="date_to"
                    class="form-control"
                    value="{{ request('date_to') }}"
                >

            </div>


            <div class="col-12 d-flex gap-2">

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i>
                    Search
                </button>

                <a
                    href="{{ route('purchases.index') }}"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                    Reset
                </a>

            </div>

        </div>

    </form>

</div>


{{-- Purchase List --}}
<div class="dashboard-card shadow-sm">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h5 class="mb-1">Purchase List</h5>

            <small class="text-muted">
                View purchases, payment balances and vouchers.
            </small>
        </div>

        <span class="badge bg-light text-dark border">
            {{-- {{ $purchases->total() }} Purchases --}}
        </span>

    </div>


    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">
                <tr>

                    <th width="55">#</th>

                    <th>Invoice No.</th>

                    <th>Purchase Date</th>

                    <th>Farmer</th>

                    <th class="text-end">Net Amount</th>

                    <th class="text-end">Paid</th>

                    <th class="text-end">Balance</th>

                    <th class="text-center">Status</th>

                    <th class="text-center" width="160">Actions</th>

                </tr>
            </thead>


            <tbody>

                {{-- @forelse ($purchases as $purchase)

                    @php
                        $paymentStatus = $purchase->balance_amount <= 0
                            ? 'paid'
                            : ($purchase->total_paid > 0
                                ? 'partial'
                                : 'unpaid');
                    @endphp

                    <tr>

                        <td>
                            {{ $purchases->firstItem() + $loop->index }}
                        </td>

                        <td>
                            <strong>
                                {{ $purchase->invoice_no }}
                            </strong>
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}

                            <div class="small text-muted">
                                {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('h:i A') }}
                            </div>
                        </td>

                        <td>
                            <div class="fw-semibold">
                                {{ $purchase->farmer->name ?? 'Unknown Farmer' }}
                            </div>

                            <small class="text-muted">
                                {{ $purchase->farmer->phone ?? '-' }}
                            </small>
                        </td>

                        <td class="text-end">
                            {{ number_format($purchase->net_amount, 0) }}
                        </td>

                        <td class="text-end text-success">
                            {{ number_format($purchase->total_paid, 0) }}
                        </td>

                        <td class="text-end
                            {{ $purchase->balance_amount > 0 ? 'text-danger fw-semibold' : 'text-muted' }}">
                            {{ number_format($purchase->balance_amount, 0) }}
                        </td>

                        <td class="text-center">

                            @if ($purchase->status === 'DRAFT')

                                <span class="badge bg-secondary-subtle text-secondary border">
                                    Draft
                                </span>

                            @elseif ($purchase->status === 'CONFIRMED')

                                <span class="badge bg-primary-subtle text-primary border">
                                    Confirmed
                                </span>

                            @elseif ($purchase->status === 'COMPLETE')

                                <span class="badge bg-success-subtle text-success border">
                                    Complete
                                </span>

                            @else

                                <span class="badge bg-light text-dark border">
                                    {{ $purchase->status }}
                                </span>

                            @endif

                            <div class="mt-1">

                                @if ($paymentStatus === 'paid')

                                    <span class="small text-success">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Paid
                                    </span>

                                @elseif ($paymentStatus === 'partial')

                                    <span class="small text-warning">
                                        <i class="bi bi-clock-history me-1"></i>
                                        Partial
                                    </span>

                                @else

                                    <span class="small text-danger">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        Unpaid
                                    </span>

                                @endif

                            </div>

                        </td>

                        <td class="text-center">

                            <div class="d-flex justify-content-center gap-1">

                                <a
                                    href="{{ route('purchases.show', $purchase->id) }}"
                                    class="btn btn-sm btn-outline-secondary"
                                    title="View Purchase"
                                >
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a
                                    href="{{ route('purchases.edit', $purchase->id) }}"
                                    class="btn btn-sm btn-outline-primary"
                                    title="Edit Purchase"
                                >
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a
                                    href="{{ route('purchases.voucher', $purchase->id) }}"
                                    class="btn btn-sm btn-outline-success"
                                    title="Print Voucher"
                                    target="_blank"
                                >
                                    <i class="bi bi-printer"></i>
                                </a>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="9" class="text-center py-5">

                            <i class="bi bi-receipt fs-1 text-muted"></i>

                            <h6 class="mt-3 mb-1">
                                No Purchases Found
                            </h6>

                            <p class="text-muted mb-3">
                                No purchase records match your search.
                            </p>

                            <a
                                href="{{ route('purchases.create') }}"
                                class="btn btn-success btn-sm"
                            >
                                <i class="bi bi-plus-lg me-1"></i>
                                Create Purchase
                            </a>

                        </td>
                    </tr>

                @endforelse --}}

            </tbody>

        </table>

    </div>


    {{-- Pagination --}}
    {{-- @if ($purchases->hasPages())

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4">

            <small class="text-muted">
                Showing {{ $purchases->firstItem() }}
                to {{ $purchases->lastItem() }}
                of {{ $purchases->total() }} purchases
            </small>

            {{ $purchases->links() }}

        </div>

    @endif --}}

</div>


<style>

    .purchase-icon {
        width: 46px;
        height: 46px;
        border-radius: 10px;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 22px;
        flex-shrink: 0;
    }

    .dashboard-card {
        border-radius: 10px;
    }

    .table th {
        white-space: nowrap;
        font-size: 13px;
    }

    .table td {
        font-size: 14px;
    }

    .table td .badge {
        font-weight: 500;
    }

</style>

@endsection

