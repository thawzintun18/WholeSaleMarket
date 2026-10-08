@extends('layouts.app')

@section('page-title', 'Edit Farmer')

@section('breadcrumb')
Dashboard / Farmers / Edit Farmer
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">


<div>
    <h1 class="page-title mb-1">Edit Farmer</h1>

    <p class="text-muted mb-0">
        Update farmer information.
    </p>
</div>

<a href="{{ route('farmers.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i>
    Back to Farmers
</a>


</div>

<div class="dashboard-card shadow-sm">


<form
    action="{{ route('farmers.update', $farmer['id']) }}"
    method="POST"
>

    @csrf
    @method('PUT')


    <div class="row g-4">

        {{-- Farmer Code --}}
        <div class="col-md-6">

            <label class="form-label">
                Farmer Code
                <span class="text-danger">*</span>
            </label>

            <input
                type="text"
                name="farmer_code"
                class="form-control"
                value="{{ $farmer['farmer_code'] }}"
                required
            >

            <small class="text-muted">
                Unique code for identifying the farmer.
            </small>

        </div>


        {{-- Farmer Name --}}
        <div class="col-md-6">

            <label class="form-label">
                Farmer Name
                <span class="text-danger">*</span>
            </label>

            <input
                type="text"
                name="name"
                class="form-control"
                value="{{ $farmer['name'] }}"
                required
            >

        </div>


        {{-- Phone --}}
        <div class="col-md-6">

            <label class="form-label">
                Phone
            </label>

            <input
                type="text"
                name="phone"
                class="form-control"
                value="{{ $farmer['phone'] }}"
                placeholder="09xxxxxxxxx"
            >

        </div>


        {{-- Address --}}
        <div class="col-md-6">

            <label class="form-label">
                Address
            </label>

            <input
                type="text"
                name="address"
                class="form-control"
                value="{{ $farmer['address'] }}"
                placeholder="Village / Township / Address"
            >

        </div>


        {{-- Note --}}
        <div class="col-12">

            <label class="form-label">
                Note
            </label>

            <textarea
                name="note"
                class="form-control"
                rows="4"
                placeholder="Enter any additional notes..."
            >{{ $farmer['note'] }}</textarea>

        </div>

    </div>


    <hr class="my-4">


    <div class="d-flex justify-content-between align-items-center">

        <div>
            <small class="text-muted">
                Farmer ID: #{{ $farmer['id'] }}
            </small>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('farmers.index') }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                <i class="bi bi-check-lg me-1"></i>
                Update Farmer
            </button>

        </div>

    </div>

</form>


</div>

@endsection
