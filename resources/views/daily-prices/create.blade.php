@extends('layouts.app')

@section('page-title', 'Add Daily Price')

@section('breadcrumb')
    Dashboard / Daily Prices / Add
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">


<div>
    <h1 class="page-title mb-1">Add Daily Price</h1>

    <p class="text-muted mb-0">
        Set a reference buying price for a crop and grade.
    </p>
</div>

<a href="{{ route('daily-prices.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i>
    Back
</a>


</div>

<div class="dashboard-card shadow-sm">


<div class="mb-4">

    <h5 class="mb-1">
        Daily Price Information
    </h5>

    <small class="text-muted">
        Enter the daily reference buying price.
    </small>

</div>


<div class="row g-4">

    {{-- Price Date --}}
    <div class="col-md-6">

        <label class="form-label">
            Price Date
            <span class="text-danger">*</span>
        </label>

        <input
            type="date"
            class="form-control"
            value="2026-10-08"
        >

        <small class="text-muted">
            The date this price applies to.
        </small>

    </div>


    {{-- Crop --}}
    <div class="col-md-6">

        <label class="form-label">
            Crop
            <span class="text-danger">*</span>
        </label>

        <select class="form-select">

            <option value="">
                Select Crop
            </option>

            <option selected>
                Tomato
            </option>

            <option>
                Potato
            </option>

            <option>
                Cabbage
            </option>

            <option>
                Onion
            </option>

            <option>
                Chilli
            </option>

            <option>
                Cauliflower
            </option>

        </select>

    </div>


    {{-- Grade --}}
    <div class="col-md-6">

        <label class="form-label">
            Grade
            <span class="text-danger">*</span>
        </label>

        <select class="form-select">

            <option value="">
                Select Grade
            </option>

            <option selected>
                Grade A
            </option>

            <option>
                Grade B
            </option>

            <option>
                Grade C
            </option>

        </select>

        <small class="text-muted">
            Select a grade belonging to the selected crop.
        </small>

    </div>


    {{-- Reference Buy Price --}}
    <div class="col-md-6">

        <label class="form-label">
            Reference Buy Price
            <span class="text-danger">*</span>
        </label>

        <div class="input-group">

            <input
                type="number"
                class="form-control text-end"
                value="25000"
                placeholder="Enter buying price"
            >

            <span class="input-group-text">
                MMK
            </span>

        </div>

        <small class="text-muted">
            Reference price used when purchasing from farmers.
        </small>

    </div>

</div>


<hr class="my-4">


{{-- Preview --}}
<div class="bg-light rounded p-3 mb-4">

    <div class="d-flex justify-content-between align-items-center">

        <div>

            <small class="text-muted d-block">
                Price Preview
            </small>

            <strong>
                Tomato · Grade A
            </strong>

        </div>

        <div class="text-end">

            <small class="text-muted d-block">
                Reference Buy Price
            </small>

            <strong class="fs-5">
                25,000 MMK
            </strong>

        </div>

    </div>

</div>


{{-- Actions --}}
<div class="d-flex justify-content-end gap-2">

    <a
        href="{{ route('daily-prices.index') }}"
        class="btn btn-outline-secondary"
    >
        Cancel
    </a>

    <button
        type="button"
        class="btn btn-success"
    >
        <i class="bi bi-check-lg me-1"></i>
        Save Daily Price
    </button>

</div>


</div>

@endsection
