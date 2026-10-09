@extends('layouts.app')

@section('page-title', 'Edit Daily Price')

@section('breadcrumb')
    Dashboard / Daily Prices / Edit
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">


        <div>
            <h1 class="page-title mb-1">Edit Daily Price</h1>

            <p class="text-muted mb-0">
                Update the reference buying price.
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
                Update the daily reference buying price.
            </small>

        </div>


        <div class="row g-4">

            {{-- Price Date --}}
            <div class="col-md-6">

                <label class="form-label">
                    Price Date
                    <span class="text-danger">*</span>
                </label>

                <input type="date" class="form-control" value="2026-10-08">

            </div>


            {{-- Crop --}}
            <div class="col-md-6">

                <label class="form-label">
                    Crop
                    <span class="text-danger">*</span>
                </label>

                <select class="form-select">

                    <option>
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

                    <option>
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

            </div>


            {{-- Reference Buy Price --}}
            <div class="col-md-6">

                <label class="form-label">
                    Reference Buy Price
                    <span class="text-danger">*</span>
                </label>

                <div class="input-group">

                    <input type="number" class="form-control text-end" value="25000" placeholder="Enter buying price">

                    <span class="input-group-text">
                        MMK
                    </span>

                </div>

            </div>

        </div>


        <hr class="my-4">


        {{-- Current Price Info --}}
        <div class="row g-3 mb-4">

            <div class="col-md-4">

                <div class="bg-light rounded p-3">

                    <small class="text-muted d-block">
                        Current Crop
                    </small>

                    <strong>
                        Tomato
                    </strong>

                </div>

            </div>


            <div class="col-md-4">

                <div class="bg-light rounded p-3">

                    <small class="text-muted d-block">
                        Current Grade
                    </small>

                    <strong>
                        Grade A
                    </strong>

                </div>

            </div>


            <div class="col-md-4">

                <div class="bg-light rounded p-3">

                    <small class="text-muted d-block">
                        Current Price
                    </small>

                    <strong>
                        25,000 MMK
                    </strong>

                </div>

            </div>

        </div>


        {{-- Actions --}}
        <div class="d-flex justify-content-end gap-2">

            <a href="{{ route('daily-prices.index') }}" class="btn btn-outline-secondary">
                Cancel
            </a>

            <button type="button" class="btn btn-primary">
                <i class="bi bi-check-lg me-1"></i>
                Update Daily Price
            </button>

        </div>


    </div>

@endsection
