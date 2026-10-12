blade
@extends('layouts.app')

@section('page-title', 'Edit Purchase')

@section('breadcrumb')
    Dashboard / Purchases / Edit
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title mb-1">Edit Purchase</h1>
        <p class="text-muted mb-0">
            Update purchase information and crop items.
        </p>
    </div>

    <a href="{{ route('purchases.show', $purchase->id) }}"
       class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Cancel
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Please check the following errors:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('purchases.update', $purchase->id) }}"
      method="POST" id="editPurchaseForm">

    @csrf
    @method('PUT')

    {{-- Purchase Information --}}
    <div class="dashboard-card shadow-sm mb-4">
        <h5 class="mb-3">Purchase Information</h5>

        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Invoice No.</label>
                <input type="text"
                       class="form-control"
                       value="{{ $purchase->invoice_no }}"
                       readonly>
            </div>

            <div class="col-md-3">
                <label class="form-label">Purchase Date</label>
                <input type="datetime-local"
                       name="purchase_date"
                       class="form-control"
                       value="{{ old('purchase_date', \Carbon\Carbon::parse($purchase->purchase_date)->format('Y-m-d\TH:i')) }}"
                       required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Farmer</label>
                <select name="farmer_id" class="form-select" required>
                    @foreach ($farmers as $farmer)
                        <option value="{{ $farmer->id }}"
                            @selected(old('farmer_id', $purchase->farmer_id) == $farmer->id)>
                            {{ $farmer->name }}
                            @if ($farmer->phone)
                                - {{ $farmer->phone }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Status</label>
                <input type="text"
                       class="form-control"
                       value="{{ $purchase->status }}"
                       readonly>
                <input type="hidden"
                       name="status"
                       value="{{ $purchase->status }}">
            </div>

            <div class="col-12">
                <label class="form-label">Note</label>
                <textarea name="note"
                          class="form-control"
                          rows="2">{{ old('note', $purchase->note) }}</textarea>
            </div>
        </div>
    </div>

    {{-- Purchase Items --}}
    <div class="dashboard-card shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="mb-1">Purchase Items</h5>
                <small class="text-muted">
                    Update quantities, prices and deductions.
                </small>
            </div>

            <button type="button" class="btn btn-success btn-sm"
                    id="addEditItem">
                <i class="bi bi-plus-lg me-1"></i> Add Item
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Crop</th>
                        <th>Grade</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Reference Price</th>
                        <th>Buy Price</th>
                        <th>Commission</th>
                        <th>Car Fee</th>
                        <th>Net Amount</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody id="editItems">
                    @foreach ($purchase->items as $index => $item)
                        <tr class="edit-item">
                            <td>
                                <select name="items[{{ $index }}][crop_id]"
                                        class="form-select form-select-sm crop-select"
                                        required>
                                    <option value="">Select Crop</option>
                                    @foreach ($crops as $crop)
                                        <option value="{{ $crop->id }}"
                                            @selected(old("items.$index.crop_id", $item->crop_id) == $crop->id)>
                                            {{ $crop->crop_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <select name="items[{{ $index }}][grade_id]"
                                        class="form-select form-select-sm"
                                        required>
                                    <option value="">Select Grade</option>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->id }}"
                                            @selected(old("items.$index.grade_id", $item->grade_id) == $grade->id)>
                                            {{ $grade->grade_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input type="number"
                                       name="items[{{ $index }}][quantity]"
                                       class="form-control form-control-sm qty"
                                       value="{{ old("items.$index.quantity", $item->quantity) }}"
                                       min="0.001" step="0.001" required>
                            </td>

                            <td>
                                <input type="text"
                                       name="items[{{ $index }}][unit]"
                                       class="form-control form-control-sm"
                                       value="{{ old("items.$index.unit", $item->unit) }}"
                                       required>
                            </td>

                            <td>
                                <input type="number"
                                       name="items[{{ $index }}][reference_price]"
                                       class="form-control form-control-sm"
                                       value="{{ old("items.$index.reference_price", $item->reference_price) }}"
                                       min="0" step="0.01" required>
                            </td>

                            <td>
                                <input type="number"
                                       name="items[{{ $index }}][actual_buy_price]"
                                       class="form-control form-control-sm buy-price"
                                       value="{{ old("items.$index.actual_buy_price", $item->actual_buy_price) }}"
                                       min="0" step="0.01" required>
                            </td>

                            <td>
                                <input type="number"
                                       name="items[{{ $index }}][commission_amount]"
                                       class="form-control form-control-sm commission"
                                       value="{{ old("items.$index.commission_amount", $item->commission_amount) }}"
                                       min="0" step="0.01" required>
                            </td>

                            <td>
                                <input type="number"
                                       name="items[{{ $index }}][car_fee_amount]"
                                       class="form-control form-control-sm car-fee"
                                       value="{{ old("items.$index.car_fee_amount", $item->car_fee_amount) }}"
                                       min="0" step="0.01" required>
                            </td>

                            <td class="text-end fw-semibold item-net">0</td>

                            <td>
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger remove-item">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-end mt-3">
            <span class="text-muted me-2">Items Net Total</span>
            <strong class="fs-5 text-success" id="editNetTotal">0 Ks</strong>
        </div>
    </div>

    {{-- Existing Payment Notice --}}
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-1"></i>
        Existing payments: <strong>{{ number_format($purchase->total_paid, 2) }} Ks</strong>.
        This form does not modify payment records. The controller must recalculate
        the purchase totals and balance after updating items.
    </div>

    <div class="dashboard-card shadow-sm mb-5">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <strong>Save purchase changes</strong>
                <div class="text-muted small">
                    Totals should be recalculated on the server before saving.
                </div>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-lg me-1"></i> Update Purchase
            </button>
        </div>
    </div>

</form>

{{-- Template for new rows --}}
<template id="editItemTemplate">
    <tr class="edit-item">
        <td>
            <select data-field="crop_id" class="form-select form-select-sm crop-select" required>
                <option value="">Select Crop</option>
                @foreach ($crops as $crop)
                    <option value="{{ $crop->id }}">{{ $crop->crop_name }}</option>
                @endforeach
            </select>
        </td>

        <td>
            <select data-field="grade_id" class="form-select form-select-sm" required>
                <option value="">Select Grade</option>
                @foreach ($grades as $grade)
                    <option value="{{ $grade->id }}">{{ $grade->grade_name }}</option>
                @endforeach
            </select>
        </td>

        <td><input data-field="quantity" type="number" class="form-control form-control-sm qty" min="0.001" step="0.001" value="1" required></td>
        <td><input data-field="unit" type="text" class="form-control form-control-sm" value="Basket" required></td>
        <td><input data-field="reference_price" type="number" class="form-control form-control-sm" min="0" step="0.01" value="0" required></td>
        <td><input data-field="actual_buy_price" type="number" class="form-control form-control-sm buy-price" min="0" step="0.01" value="0" required></td>
        <td><input data-field="commission_amount" type="number" class="form-control form-control-sm commission" min="0" step="0.01" value="0" required></td>
        <td><input data-field="car_fee_amount" type="number" class="form-control form-control-sm car-fee" min="0" step="0.01" value="0" required></td>
        <td class="text-end fw-semibold item-net">0</td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger remove-item">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tbody = document.getElementById('editItems');
    const template = document.getElementById('editItemTemplate');
    let nextIndex = {{ $purchase->items->count() }};

    function money(value) {
        return Number(value || 0).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function calculate() {
        let total = 0;

        tbody.querySelectorAll('.edit-item').forEach(function (row) {
            const qty = Number(row.querySelector('.qty').value) || 0;
            const price = Number(row.querySelector('.buy-price').value) || 0;
            const commission = Number(row.querySelector('.commission').value) || 0;
            const carFee = Number(row.querySelector('.car-fee').value) || 0;

            const net = (qty * price) - commission - carFee;
            row.querySelector('.item-net').textContent = money(net);
            total += net;
        });

        document.getElementById('editNetTotal').textContent = money(total) + ' Ks';
    }

    document.getElementById('addEditItem').addEventListener('click', function () {
        const fragment = template.content.cloneNode(true);
        const row = fragment.querySelector('.edit-item');

        row.querySelectorAll('[data-field]').forEach(function (input) {
            input.name = `items[${nextIndex}][${input.dataset.field}]`;
        });

        nextIndex++;
        tbody.appendChild(fragment);
        calculate();
    });

    tbody.addEventListener('input', calculate);

    tbody.addEventListener('click', function (event) {
        const button = event.target.closest('.remove-item');

        if (!button) return;

        if (tbody.querySelectorAll('.edit-item').length <= 1) {
            alert('At least one purchase item is required.');
            return;
        }

        button.closest('.edit-item').remove();
        calculate();
    });

    calculate();
});
</script>

@endsection

