
@extends('layouts.app')

@section('page-title', 'Edit Crop')

@section('breadcrumb', 'Master / Crops / Edit')

@section('content')

<div class="mb-4">

    <h1 class="page-title mb-1">
        Edit Crop
    </h1>

    <p class="text-muted mb-0">
        Update crop information.
    </p>

</div>


<div class="row">

    <div class="col-lg-8">

        <div class="dashboard-card shadow-sm">

            <form
                action="{{ route('crops.update', $crop) }}"
                method="POST"
            >

                @csrf
                @method('PUT')


                <div class="mb-3">

                    <label class="form-label">
                        Crop Name
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="crop_name"
                        class="form-control @error('crop_name') is-invalid @enderror"
                        value="{{ old('crop_name', $crop->crop_name) }}"
                    >

                    @error('crop_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Unit
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="unit"
                        class="form-select @error('unit') is-invalid @enderror"
                    >

                        <option value="basket"
                            {{ old('unit', $crop->unit) == 'basket' ? 'selected' : '' }}>
                            Basket
                        </option>

                        <option value="kg"
                            {{ old('unit', $crop->unit) == 'kg' ? 'selected' : '' }}>
                            Kg
                        </option>

                        <option value="bag"
                            {{ old('unit', $crop->unit) == 'bag' ? 'selected' : '' }}>
                            Bag
                        </option>

                    </select>

                    @error('unit')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="mb-4">

                    <label class="form-label">
                        Quantity Per Basket
                    </label>

                    <input
                        type="number"
                        name="quantity_per_basket"
                        class="form-control"
                        value="{{ old('quantity_per_basket', $crop->quantity_per_basket) }}"
                        min="0"
                        step="0.01"
                    >

                </div>


                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('crops.index') }}"
                        class="btn btn-light"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-lg me-1"></i>
                        Update Crop
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
