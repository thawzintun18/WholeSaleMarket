@extends('layouts.app')

@section('page-title', 'New Purchase')

@section('breadcrumb')
    Dashboard / Purchases / New Purchase
@endsection

@section('content')

{{-- =========================================================
    PAGE HEADER
========================================================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="page-title mb-1">New Purchase</h1>

        <p class="text-muted mb-0">
            Create farmer purchase and payment voucher.
        </p>
    </div>

    <div class="d-flex gap-2">

        <a href="#" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

        <button type="button" class="btn btn-success" id="savePurchaseBtn">
            <i class="bi bi-check-lg me-1"></i>
            Save Purchase
        </button>

    </div>

</div>
<form id="purchaseForm">

    {{-- =========================================================
        PURCHASE INFORMATION
    ========================================================= --}}
    <div class="dashboard-card shadow-sm mb-4">
        <div class="card-section-header mb-4">

            <div>
                <h5 class="mb-1">
                    <i class="bi bi-receipt me-2 text-success"></i>
                    Purchase Information
                </h5>

                <small class="text-muted">
                    Basic purchase information
                </small>
            </div>

            <span class="badge bg-warning-subtle text-warning border">
                DRAFT
            </span>

        </div>
        <div class="row g-3">
            {{-- Invoice No --}}
            <div class="col-md-3">

                <label class="form-label">
                    Invoice No
                </label>

                <input
                    type="text"
                    name="invoice_no"
                    class="form-control"
                    value="INV-20261008-001"
                    readonly
                >

            </div>


            {{-- Purchase Date --}}
            <div class="col-md-3">

                <label class="form-label">
                    Purchase Date
                </label>

                <input
                    type="datetime-local"
                    name="purchase_date"
                    class="form-control"
                    value="2026-10-08T10:30"
                >

            </div>


            {{-- Status --}}
            <div class="col-md-3">

                <label class="form-label">
                    Status
                </label>

                <select name="status" class="form-select">

                    <option value="DRAFT" selected>
                        Draft
                    </option>

                    <option value="CONFIRMED">
                        Confirmed
                    </option>

                    <option value="COMPLETE">
                        Complete
                    </option>

                </select>

            </div>


            {{-- Note --}}
            <div class="col-md-3">

                <label class="form-label">
                    Note
                </label>

                <input
                    type="text"
                    name="note"
                    class="form-control"
                    placeholder="Optional note..."
                >

            </div>

        </div>

    </div>


    {{-- =========================================================
        FARMER SECTION
    ========================================================= --}}
    <div class="dashboard-card shadow-sm mb-4">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>
                <h5 class="mb-1">
                    <i class="bi bi-person me-2 text-success"></i>
                    Farmer
                </h5>

                <small class="text-muted">
                    Select existing farmer or create a new farmer quickly.
                </small>
            </div>

            <button
                type="button"
                class="btn btn-sm btn-success"
                data-bs-toggle="modal"
                data-bs-target="#quickFarmerModal"
            >
                <i class="bi bi-person-plus me-1"></i>
                New Farmer
            </button>

        </div>


        <div class="row g-3">

            {{-- Farmer Search --}}
            <div class="col-md-6">

                <label class="form-label">
                    Farmer
                </label>

                <select
                    name="farmer_id"
                    id="farmerSelect"
                    class="form-select"
                >

                    <option value="">
                        -- Select Farmer --
                    </option>

                    <option value="1" selected>
                        U Aung Aung - Lashio
                    </option>

                    <option value="2">
                        U Sai Htun - Namtu
                    </option>

                    <option value="3">
                        U Kyaw Min - Hsipaw
                    </option>

                </select>

                <small class="text-muted">
                    Type to search farmer name, village or phone.
                </small>

            </div>


            {{-- Farmer Phone --}}
            <div class="col-md-3">

                <label class="form-label">
                    Phone
                </label>

                <input
                    type="text"
                    id="farmerPhone"
                    class="form-control"
                    value="09-421234567"
                    readonly
                >

            </div>


            {{-- Village --}}
            <div class="col-md-3">

                <label class="form-label">
                    Village
                </label>

                <input
                    type="text"
                    id="farmerVillage"
                    class="form-control"
                    value="Lashio"
                    readonly
                >

            </div>

        </div>


        {{-- Farmer Balance Info --}}
        <div class="row g-3 mt-2">

            <div class="col-md-4">

                <div class="info-box bg-danger">

                    <small class="text-white">
                        Outstanding Advance
                    </small>

                    <h5 class="mb-0 text-white">
                        150,000 Ks
                    </h5>

                </div>

            </div>


            <div class="col-md-4">

                <div class="info-box">

                    <small class="text-muted">
                        Previous Balance
                    </small>

                    <h5 class="mb-0">
                        0 Ks
                    </h5>

                </div>

            </div>


            <div class="col-md-4">

                <div class="info-box bg-success">

                    <small class="text-white">
                        Current Purchase
                    </small>

                    <h5 class="mb-0 text-white" id="farmerCurrentAmount">
                        0 Ks
                    </h5>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PURCHASE ITEMS
    ========================================================= --}}
    <div class="dashboard-card shadow-sm mb-4">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>
                <h5 class="mb-1">
                    <i class="bi bi-basket me-2 text-success"></i>
                    Purchase Items
                </h5>

                <small class="text-muted">
                    Add crops purchased from this farmer.
                </small>
            </div>

            <button
                type="button"
                class="btn btn-success btn-sm"
                id="addItemBtn"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Add Item
            </button>

        </div>


        <div class="table-responsive">

            <table class="table table-bordered align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th width="40">#</th>

                        <th width="160">
                            Crop
                        </th>

                        <th width="140">
                            Grade
                        </th>

                        <th width="110">
                            Qty
                        </th>

                        <th width="90">
                            Unit
                        </th>

                        <th width="120">
                            Ref. Price
                        </th>

                        <th width="130">
                            Buy Price
                        </th>

                        <th width="120">
                            Commission
                        </th>

                        <th width="120">
                            Car Fee
                        </th>

                        <th width="140" class="text-end">
                            Net Amount
                        </th>

                        <th width="60"></th>

                    </tr>

                </thead>


                <tbody id="purchaseItems">

                    {{-- ITEM 1 --}}
                    <tr class="purchase-item">

                        <td class="item-number">
                            1
                        </td>


                        {{-- Crop --}}
                        <td>

                            <select
                                name="items[0][crop_id]"
                                class="form-select form-select-sm crop-select"
                            >

                                <option value="">
                                    Select Crop
                                </option>

                                <option value="1" selected>
                                    Tomato
                                </option>

                                <option value="2">
                                    Potato
                                </option>

                                <option value="3">
                                    Cabbage
                                </option>

                                <option value="4">
                                    Onion
                                </option>

                            </select>

                        </td>


                        {{-- Grade --}}
                        <td>

                            <select
                                name="items[0][grade_id]"
                                class="form-select form-select-sm"
                            >

                                <option value="">
                                    Select Grade
                                </option>

                                <option value="1" selected>
                                    Grade A
                                </option>

                                <option value="2">
                                    Grade B
                                </option>

                                <option value="3">
                                    Grade C
                                </option>

                            </select>

                        </td>


                        {{-- Quantity --}}
                        <td>

                            <input
                                type="number"
                                name="items[0][quantity]"
                                class="form-control form-control-sm quantity"
                                value="20"
                                step="0.001"
                                min="0"
                            >

                        </td>


                        {{-- Unit --}}
                        <td>

                            <input
                                type="text"
                                name="items[0][unit]"
                                class="form-control form-control-sm"
                                value="Basket"
                            >

                        </td>


                        {{-- Reference Price --}}
                        <td>

                            <input
                                type="number"
                                name="items[0][reference_price]"
                                class="form-control form-control-sm reference-price"
                                value="18000"
                                step="0.01"
                            >

                        </td>


                        {{-- Actual Buy Price --}}
                        <td>

                            <input
                                type="number"
                                name="items[0][actual_buy_price]"
                                class="form-control form-control-sm actual-price"
                                value="17500"
                                step="0.01"
                            >

                        </td>


                        {{-- Commission --}}
                        <td>

                            <input
                                type="number"
                                name="items[0][commission_amount]"
                                class="form-control form-control-sm commission"
                                value="5000"
                                step="0.01"
                            >

                        </td>


                        {{-- Car Fee --}}
                        <td>

                            <input
                                type="number"
                                name="items[0][car_fee_amount]"
                                class="form-control form-control-sm car-fee"
                                value="3000"
                                step="0.01"
                            >

                        </td>


                        {{-- Net Amount --}}
                        <td class="text-end">

                            <strong class="item-net">
                                342,000
                            </strong>

                        </td>


                        {{-- Remove --}}
                        <td class="text-center">

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-danger remove-item"
                            >
                                <i class="bi bi-trash"></i>
                            </button>

                        </td>

                    </tr>


                    {{-- ITEM 2 --}}
                    <tr class="purchase-item">

                        <td class="item-number">
                            2
                        </td>

                        <td>

                            <select
                                name="items[1][crop_id]"
                                class="form-select form-select-sm crop-select"
                            >

                                <option value="">
                                    Select Crop
                                </option>

                                <option value="1">
                                    Tomato
                                </option>

                                <option value="2" selected>
                                    Potato
                                </option>

                                <option value="3">
                                    Cabbage
                                </option>

                                <option value="4">
                                    Onion
                                </option>

                            </select>

                        </td>


                        <td>

                            <select
                                name="items[1][grade_id]"
                                class="form-select form-select-sm"
                            >

                                <option value="">
                                    Select Grade
                                </option>

                                <option value="1">
                                    Grade A
                                </option>

                                <option value="2" selected>
                                    Grade B
                                </option>

                                <option value="3">
                                    Grade C
                                </option>

                            </select>

                        </td>


                        <td>

                            <input
                                type="number"
                                name="items[1][quantity]"
                                class="form-control form-control-sm quantity"
                                value="10"
                                step="0.001"
                            >

                        </td>


                        <td>

                            <input
                                type="text"
                                name="items[1][unit]"
                                class="form-control form-control-sm"
                                value="Bag"
                            >

                        </td>


                        <td>

                            <input
                                type="number"
                                name="items[1][reference_price]"
                                class="form-control form-control-sm reference-price"
                                value="25000"
                                step="0.01"
                            >

                        </td>


                        <td>

                            <input
                                type="number"
                                name="items[1][actual_buy_price]"
                                class="form-control form-control-sm actual-price"
                                value="24000"
                                step="0.01"
                            >

                        </td>


                        <td>

                            <input
                                type="number"
                                name="items[1][commission_amount]"
                                class="form-control form-control-sm commission"
                                value="3000"
                                step="0.01"
                            >

                        </td>


                        <td>

                            <input
                                type="number"
                                name="items[1][car_fee_amount]"
                                class="form-control form-control-sm car-fee"
                                value="2000"
                                step="0.01"
                            >

                        </td>


                        <td class="text-end">

                            <strong class="item-net">
                                235,000
                            </strong>

                        </td>


                        <td class="text-center">

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-danger remove-item"
                            >
                                <i class="bi bi-trash"></i>
                            </button>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>


        {{-- Empty State --}}
        <div
            id="emptyItemMessage"
            class="text-center py-5 d-none"
        >

            <i class="bi bi-basket fs-1 text-muted"></i>

            <p class="text-muted mt-2 mb-2">
                No purchase items added.
            </p>

            <button
                type="button"
                class="btn btn-sm btn-success"
                id="emptyAddItemBtn"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Add Item
            </button>

        </div>

    </div>

    {{-- =========================================================
        ADVANCE / DEDUCTION
    ========================================================= --}}
    <div class="row g-4 mb-4">


        {{-- Farmer Advance --}}
        <div class="col-lg-6">

            <div class="dashboard-card shadow-sm h-100">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <h5 class="mb-1">
                            <i class="bi bi-wallet2 me-2 text-warning"></i>
                            Farmer Advance
                        </h5>

                        <small class="text-muted">
                            Deduct advance used by farmer.
                        </small>

                    </div>

                    <span class="badge bg-warning-subtle text-warning border">
                        Advance
                    </span>

                </div>


                <div class="alert alert-warning py-2">

                    <small>
                        Existing advance balance:
                        <strong>150,000 Ks</strong>
                    </small>

                </div>


                <div class="row g-3">

                    <div class="col-md-7">

                        <label class="form-label">
                            Advance to Deduct
                        </label>

                        <select
                            name="advance_id"
                            class="form-select"
                            id="advanceSelect"
                        >

                            <option value="">
                                No Advance
                            </option>

                            <option value="1" selected>
                                Advance #ADV-001 — 100,000 Ks
                            </option>

                            <option value="2">
                                Advance #ADV-002 — 50,000 Ks
                            </option>

                        </select>

                    </div>


                    <div class="col-md-5">

                        <label class="form-label">
                            Amount
                        </label>

                        <input
                            type="number"
                            name="advance_amount"
                            id="advanceAmount"
                            class="form-control"
                            value="100000"
                            step="0.01"
                        >

                    </div>

                </div>

            </div>

        </div>



        {{-- Other Deduction --}}
        <div class="col-lg-6">

            <div class="dashboard-card shadow-sm h-100">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <h5 class="mb-1">
                            <i class="bi bi-dash-circle me-2 text-danger"></i>
                            Other Deduction
                        </h5>

                        <small class="text-muted">
                            Additional deduction if necessary.
                        </small>

                    </div>

                </div>


                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Deduction Amount
                        </label>

                        <input
                            type="number"
                            name="other_deduction"
                            id="otherDeduction"
                            class="form-control"
                            value="0"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Note
                        </label>

                        <input
                            type="text"
                            name="deduction_note"
                            class="form-control"
                            placeholder="Deduction note..."
                        >

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        PAYMENT
    ========================================================= --}}

    <div class="dashboard-card shadow-sm mb-4">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>

                <h5 class="mb-1">
                    <i class="bi bi-cash-stack me-2 text-success"></i>
                    Payment
                </h5>

                <small class="text-muted">
                    Record payment made to farmer.
                </small>

            </div>

            <button
                type="button"
                class="btn btn-sm btn-outline-success"
                id="addPaymentBtn"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Add Payment
            </button>

        </div>


        <div class="table-responsive">

            <table class="table table-bordered align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>
                            Payment Date
                        </th>

                        <th>
                            Payment Type
                        </th>

                        <th>
                            Amount
                        </th>

                        <th>
                            Payment Method
                        </th>

                        <th>
                            Note
                        </th>

                        <th width="60"></th>

                    </tr>

                </thead>


                <tbody id="paymentRows">

                    <tr>

                        <td>

                            <input
                                type="datetime-local"
                                name="payments[0][payment_date]"
                                class="form-control form-control-sm"
                                value="2026-10-08T11:00"
                            >

                        </td>


                        <td>

                            <select
                                name="payments[0][payment_type]"
                                class="form-select form-select-sm"
                            >

                                <option value="PARTIAL" selected>
                                    Partial Payment
                                </option>

                                <option value="FINAL">
                                    Final Payment
                                </option>

                            </select>

                        </td>


                        <td>

                            <input
                                type="number"
                                name="payments[0][amount]"
                                class="form-control form-control-sm payment-amount"
                                value="300000"
                                step="0.01"
                            >

                        </td>


                        <td>

                            <select
                                name="payments[0][payment_method]"
                                class="form-select form-select-sm"
                            >

                                <option value="CASH" selected>
                                    Cash
                                </option>

                                <option value="BANK">
                                    Bank
                                </option>

                                <option value="KBZ_PAY">
                                    KBZ Pay
                                </option>

                                <option value="WAVE">
                                    Wave Money
                                </option>

                            </select>

                        </td>


                        <td>

                            <input
                                type="text"
                                name="payments[0][note]"
                                class="form-control form-control-sm"
                                placeholder="Payment note..."
                            >

                        </td>


                        <td class="text-center">

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-danger remove-payment"
                            >
                                <i class="bi bi-trash"></i>
                            </button>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>



    {{-- =========================================================
        SUMMARY
    ========================================================= --}}

    <div class="row g-4 mb-4">


        {{-- Note --}}
        <div class="col-lg-7">

            <div class="dashboard-card shadow-sm h-100">

                <h5 class="mb-3">
                    <i class="bi bi-chat-left-text me-2"></i>
                    Purchase Note
                </h5>

                <textarea
                    name="purchase_note"
                    class="form-control"
                    rows="6"
                    placeholder="Additional information..."
                >Farmer brought vegetables in the morning.</textarea>

            </div>

        </div>


        {{-- Summary --}}
        <div class="col-lg-5">

            <div class="dashboard-card shadow-sm">

                <h5 class="mb-3">
                    Purchase Summary
                </h5>


                <div class="summary-row">

                    <span>
                        Gross Amount
                    </span>

                    <strong id="summaryGross">
                        582,000 Ks
                    </strong>

                </div>


                <div class="summary-row">

                    <span>
                        Commission
                    </span>

                    <strong id="summaryCommission">
                        8,000 Ks
                    </strong>

                </div>


                <div class="summary-row">

                    <span>
                        Car Fee
                    </span>

                    <strong id="summaryCarFee">
                        5,000 Ks
                    </strong>

                </div>


                <div class="summary-row">

                    <span>
                        Farmer Advance
                    </span>

                    <strong
                        id="summaryAdvance"
                        class="text-danger"
                    >
                        -100,000 Ks
                    </strong>

                </div>


                <div class="summary-row">

                    <span>
                        Other Deduction
                    </span>

                    <strong
                        id="summaryOtherDeduction"
                        class="text-danger"
                    >
                        -0 Ks
                    </strong>

                </div>


                <hr>


                <div class="summary-total">

                    <span>
                        Net Amount
                    </span>

                    <strong id="summaryNet">
                        469,000 Ks
                    </strong>

                </div>


                <div class="summary-row mt-3">

                    <span>
                        Total Paid
                    </span>

                    <strong
                        id="summaryPaid"
                        class="text-success"
                    >
                        300,000 Ks
                    </strong>

                </div>


                <div class="balance-box mt-3">

                    <div>

                        <small>
                            Remaining Balance
                        </small>

                        <h4
                            class="mb-0"
                            id="summaryBalance"
                        >
                            169,000 Ks
                        </h4>

                    </div>

                    <i class="bi bi-wallet2 fs-3"></i>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        BOTTOM ACTION
    ========================================================= --}}

    <div class="dashboard-card shadow-sm mb-5">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <strong>
                    Ready to save this purchase?
                </strong>

                <div class="text-muted small">
                    You can save as draft and complete the payment later.
                </div>

            </div>


            <div class="d-flex gap-2">

                <button
                    type="button"
                    class="btn btn-outline-secondary"
                >
                    Save as Draft
                </button>


                <button
                    type="button"
                    class="btn btn-success"
                >
                    <i class="bi bi-check-lg me-1"></i>
                    Confirm Purchase
                </button>


                <button
                    type="button"
                    class="btn btn-primary"
                >
                    <i class="bi bi-printer me-1"></i>
                    Save & Print Voucher
                </button>

            </div>

        </div>

    </div>

</form>



{{-- =========================================================
    QUICK CREATE FARMER MODAL
========================================================= --}}

<div
    class="modal fade"
    id="quickFarmerModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <div>

                    <h5 class="modal-title mb-1">
                        <i class="bi bi-person-plus me-2 text-success"></i>
                        New Farmer
                    </h5>

                    <small class="text-muted">
                        Quickly register farmer without leaving purchase.
                    </small>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Farmer Name <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="new_farmer_name"
                            id="newFarmerName"
                            class="form-control"
                            placeholder="Enter farmer name"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Phone
                        </label>

                        <input
                            type="text"
                            name="new_farmer_phone"
                            id="newFarmerPhone"
                            class="form-control"
                            placeholder="09xxxxxxxxx"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Village
                        </label>

                        <input
                            type="text"
                            name="new_farmer_village"
                            id="newFarmerVillage"
                            class="form-control"
                            placeholder="Village"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Township
                        </label>

                        <input
                            type="text"
                            name="new_farmer_township"
                            class="form-control"
                            placeholder="Township"
                        >

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Note
                        </label>

                        <textarea
                            name="new_farmer_note"
                            class="form-control"
                            rows="2"
                            placeholder="Optional note..."
                        ></textarea>

                    </div>

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    class="btn btn-success"
                    id="saveQuickFarmer"
                >
                    <i class="bi bi-check-lg me-1"></i>
                    Create & Select Farmer
                </button>

            </div>

        </div>

    </div>

</div>



{{-- =========================================================
    PAGE CSS
========================================================= --}}

<style>

    .card-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }


    .info-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 14px 16px;
    }


    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 7px 0;
        color: #6c757d;
    }


    .summary-row strong {
        color: #212529;
    }


    .summary-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 18px;
        padding: 10px 0;
    }


    .summary-total strong {
        color: #198754;
        font-size: 22px;
    }


    .balance-box {
        background: #fff3cd;
        border: 1px solid #ffe69c;
        border-radius: 8px;
        padding: 14px 16px;

        display: flex;
        justify-content: space-between;
        align-items: center;

        color: #664d03;
    }


    .balance-box h4 {
        color: #664d03;
    }


    .purchase-item td {
        vertical-align: middle;
    }


    .purchase-item input,
    .purchase-item select {
        min-width: 80px;
    }


    #purchaseItems .form-control,
    #purchaseItems .form-select {
        font-size: 13px;
    }


    .table thead th {
        white-space: nowrap;
        font-size: 13px;
    }

</style>



{{-- =========================================================
    PAGE JAVASCRIPT
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    let itemIndex = 2;
    let paymentIndex = 1;


    /* =====================================================
       FORMAT MONEY
    ===================================================== */

    function money(value) {

        return Number(value || 0).toLocaleString('en-US');

    }


    /* =====================================================
       CALCULATE ITEM
    ===================================================== */

    function calculateItem(row) {

        const quantity =
            parseFloat(row.querySelector('.quantity')?.value) || 0;

        const price =
            parseFloat(row.querySelector('.actual-price')?.value) || 0;

        const commission =
            parseFloat(row.querySelector('.commission')?.value) || 0;

        const carFee =
            parseFloat(row.querySelector('.car-fee')?.value) || 0;


        const gross =
            quantity * price;

        const net =
            gross - commission - carFee;


        const netElement =
            row.querySelector('.item-net');

        if (netElement) {

            netElement.textContent =
                money(net);

        }


        return {
            gross: gross,
            commission: commission,
            carFee: carFee,
            net: net
        };

    }



    /* =====================================================
       CALCULATE SUMMARY
    ===================================================== */

    function calculateSummary() {

        let gross = 0;
        let commission = 0;
        let carFee = 0;
        let net = 0;


        document
            .querySelectorAll('.purchase-item')
            .forEach(function (row) {

                const item =
                    calculateItem(row);

                gross += item.gross;
                commission += item.commission;
                carFee += item.carFee;
                net += item.net;

            });


        const advance =
            parseFloat(
                document.getElementById('advanceAmount')?.value
            ) || 0;


        const otherDeduction =
            parseFloat(
                document.getElementById('otherDeduction')?.value
            ) || 0;


        const paid =
            Array.from(
                document.querySelectorAll('.payment-amount')
            )
            .reduce(function (total, input) {

                return total +
                    (parseFloat(input.value) || 0);

            }, 0);


        /*
         * net here = item amount after
         * commission + car fee.
         *
         * Then advance and other deduction
         * are deducted from farmer amount.
         */

        const finalNet =
            net - advance - otherDeduction;

        const balance =
            finalNet - paid;


        document.getElementById('summaryGross')
            .textContent = money(gross) + ' Ks';

        document.getElementById('summaryCommission')
            .textContent = money(commission) + ' Ks';

        document.getElementById('summaryCarFee')
            .textContent = money(carFee) + ' Ks';

        document.getElementById('summaryAdvance')
            .textContent = '-' + money(advance) + ' Ks';

        document.getElementById('summaryOtherDeduction')
            .textContent = '-' + money(otherDeduction) + ' Ks';

        document.getElementById('summaryNet')
            .textContent = money(finalNet) + ' Ks';

        document.getElementById('summaryPaid')
            .textContent = money(paid) + ' Ks';

        document.getElementById('summaryBalance')
            .textContent = money(balance) + ' Ks';

        document.getElementById('farmerCurrentAmount')
            .textContent = money(finalNet) + ' Ks';

    }



    /* =====================================================
       ITEM INPUT EVENTS
    ===================================================== */

    document.addEventListener('input', function (event) {

        if (
            event.target.classList.contains('quantity') ||
            event.target.classList.contains('actual-price') ||
            event.target.classList.contains('commission') ||
            event.target.classList.contains('car-fee') ||
            event.target.classList.contains('payment-amount') ||
            event.target.id === 'advanceAmount' ||
            event.target.id === 'otherDeduction'
        ) {

            calculateSummary();

        }

    });



    /* =====================================================
       ADD ITEM
    ===================================================== */

    document
        .getElementById('addItemBtn')
        .addEventListener('click', addItem);


    document
        .getElementById('emptyAddItemBtn')
        .addEventListener('click', addItem);


    function addItem() {

        const tbody =
            document.getElementById('purchaseItems');


        const row =
            document.createElement('tr');

        row.classList.add('purchase-item');


        row.innerHTML = `

            <td class="item-number">
                ${itemIndex + 1}
            </td>


            <td>

                <select
                    name="items[${itemIndex}][crop_id]"
                    class="form-select form-select-sm crop-select"
                >

                    <option value="">
                        Select Crop
                    </option>

                    <option value="1">
                        Tomato
                    </option>

                    <option value="2">
                        Potato
                    </option>

                    <option value="3">
                        Cabbage
                    </option>

                    <option value="4">
                        Onion
                    </option>

                </select>

            </td>


            <td>

                <select
                    name="items[${itemIndex}][grade_id]"
                    class="form-select form-select-sm"
                >

                    <option value="">
                        Select Grade
                    </option>

                    <option value="1">
                        Grade A
                    </option>

                    <option value="2">
                        Grade B
                    </option>

                    <option value="3">
                        Grade C
                    </option>

                </select>

            </td>


            <td>

                <input
                    type="number"
                    name="items[${itemIndex}][quantity]"
                    class="form-control form-control-sm quantity"
                    value="0"
                    step="0.001"
                    min="0"
                >

            </td>


            <td>

                <input
                    type="text"
                    name="items[${itemIndex}][unit]"
                    class="form-control form-control-sm"
                    value="Basket"
                >

            </td>


            <td>

                <input
                    type="number"
                    name="items[${itemIndex}][reference_price]"
                    class="form-control form-control-sm reference-price"
                    value="0"
                    step="0.01"
                >

            </td>


            <td>

                <input
                    type="number"
                    name="items[${itemIndex}][actual_buy_price]"
                    class="form-control form-control-sm actual-price"
                    value="0"
                    step="0.01"
                >

            </td>


            <td>

                <input
                    type="number"
                    name="items[${itemIndex}][commission_amount]"
                    class="form-control form-control-sm commission"
                    value="0"
                    step="0.01"
                >

            </td>


            <td>

                <input
                    type="number"
                    name="items[${itemIndex}][car_fee_amount]"
                    class="form-control form-control-sm car-fee"
                    value="0"
                    step="0.01"
                >

            </td>


            <td class="text-end">

                <strong class="item-net">
                    0
                </strong>

            </td>


            <td class="text-center">

                <button
                    type="button"
                    class="btn btn-sm btn-outline-danger remove-item"
                >

                    <i class="bi bi-trash"></i>

                </button>

            </td>

        `;


        tbody.appendChild(row);

        itemIndex++;

        updateItemNumbers();

        calculateSummary();

    }



    /* =====================================================
       REMOVE ITEM
    ===================================================== */

    document.addEventListener('click', function (event) {

        const button =
            event.target.closest('.remove-item');


        if (!button) {
            return;
        }


        const rows =
            document.querySelectorAll('.purchase-item');


        if (rows.length <= 1) {

            alert('At least one purchase item is required.');

            return;

        }


        button
            .closest('.purchase-item')
            .remove();


        updateItemNumbers();

        calculateSummary();

    });



    /* =====================================================
       UPDATE ITEM NUMBERS
    ===================================================== */

    function updateItemNumbers() {

        document
            .querySelectorAll('.purchase-item')
            .forEach(function (row, index) {

                row.querySelector('.item-number')
                    .textContent = index + 1;

            });

    }



    /* =====================================================
       ADD PAYMENT
    ===================================================== */

    document
        .getElementById('addPaymentBtn')
        .addEventListener('click', function () {

            const tbody =
                document.getElementById('paymentRows');


            const row =
                document.createElement('tr');


            row.innerHTML = `

                <td>

                    <input
                        type="datetime-local"
                        name="payments[${paymentIndex}][payment_date]"
                        class="form-control form-control-sm"
                    >

                </td>


                <td>

                    <select
                        name="payments[${paymentIndex}][payment_type]"
                        class="form-select form-select-sm"
                    >

                        <option value="PARTIAL">
                            Partial Payment
                        </option>

                        <option value="FINAL">
                            Final Payment
                        </option>

                    </select>

                </td>


                <td>

                    <input
                        type="number"
                        name="payments[${paymentIndex}][amount]"
                        class="form-control form-control-sm payment-amount"
                        value="0"
                        step="0.01"
                    >

                </td>


                <td>

                    <select
                        name="payments[${paymentIndex}][payment_method]"
                        class="form-select form-select-sm"
                    >

                        <option value="CASH">
                            Cash
                        </option>

                        <option value="BANK">
                            Bank
                        </option>

                        <option value="KBZ_PAY">
                            KBZ Pay
                        </option>

                        <option value="WAVE">
                            Wave Money
                        </option>

                    </select>

                </td>


                <td>

                    <input
                        type="text"
                        name="payments[${paymentIndex}][note]"
                        class="form-control form-control-sm"
                        placeholder="Payment note..."
                    >

                </td>


                <td class="text-center">

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger remove-payment"
                    >

                        <i class="bi bi-trash"></i>

                    </button>

                </td>

            `;


            tbody.appendChild(row);

            paymentIndex++;

            calculateSummary();

        });



    /* =====================================================
       REMOVE PAYMENT
    ===================================================== */

    document.addEventListener('click', function (event) {

        const button =
            event.target.closest('.remove-payment');


        if (!button) {
            return;
        }


        button
            .closest('tr')
            .remove();


        calculateSummary();

    });



    /* =====================================================
       QUICK CREATE FARMER
    ===================================================== */

    document
        .getElementById('saveQuickFarmer')
        .addEventListener('click', function () {

            const name =
                document.getElementById('newFarmerName').value.trim();

            const phone =
                document.getElementById('newFarmerPhone').value.trim();

            const village =
                document.getElementById('newFarmerVillage').value.trim();


            if (!name) {

                alert('Farmer name is required.');

                return;

            }


            /*
             * Demo only.
             *
             * Later:
             *
             * fetch('', ...)
             *
             * Then get created farmer ID
             * and add it to farmerSelect.
             */


            const select =
                document.getElementById('farmerSelect');


            const option =
                document.createElement('option');


            option.value = '999';

            option.textContent =
                name + ' - ' + village;

            option.selected = true;


            select.appendChild(option);


            document.getElementById('farmerPhone')
                .value = phone;

            document.getElementById('farmerVillage')
                .value = village;


            const modalElement =
                document.getElementById('quickFarmerModal');


            const modal =
                bootstrap.Modal.getInstance(modalElement);


            modal.hide();


            document.getElementById('newFarmerName')
                .value = '';

            document.getElementById('newFarmerPhone')
                .value = '';

            document.getElementById('newFarmerVillage')
                .value = '';

        });



    /* =====================================================
       INITIAL CALCULATION
    ===================================================== */

    calculateSummary();

});

</script>

@endsection
