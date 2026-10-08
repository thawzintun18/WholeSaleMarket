
@extends('layouts.app')

@section('page-title', 'Add Crop')

@section('breadcrumb', 'Master / Crops / Add')

@section('content')

<div class="mb-4">

    <h1 class="page-title mb-1">
        Add Crop
    </h1>

    <p class="text-muted mb-0">
        Add a new crop to the system.
    </p>

</div>


<div class="row">

    <div class="offset-2 col-lg-8">

        <div class="dashboard-card shadow-sm">

            <form
                action="{{ route('crops.store') }}"
                method="POST"
            >

                @csrf


                {{-- Crop Name --}}
                <div class="mb-3">

                    <label class="form-label">
                        Crop Name
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="crop_name"
                        class="form-control @error('crop_name') is-invalid @enderror"
                        value="{{ old('crop_name') }}"
                        placeholder="e.g. Tomato"
                    >

                    @error('crop_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Unit --}}
                <div class="mb-3">

                    <label class="form-label">
                        Unit
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="unit"
                        class="form-select @error('unit') is-invalid @enderror"
                    >

                        <option value="">
                            Select Unit
                        </option>

                        <option value="basket"
                            {{ old('unit') == 'basket' ? 'selected' : '' }}>
                            Basket
                        </option>

                        <option value="kg"
                            {{ old('unit') == 'kg' ? 'selected' : '' }}>
                            Kg
                        </option>

                        <option value="bag"
                            {{ old('unit') == 'bag' ? 'selected' : '' }}>
                            Bag
                        </option>

                    </select>

                    @error('unit')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Quantity Per Basket --}}
                <div class="mb-4">

                    <label class="form-label">
                        Quantity Per Basket
                    </label>

                    <input
                        type="number"
                        name="quantity_per_basket"
                        class="form-control @error('quantity_per_basket') is-invalid @enderror"
                        value="{{ old('quantity_per_basket') }}"
                        min="0"
                        step="0.01"
                        placeholder="e.g. 20"
                    >

                    @error('quantity_per_basket')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="form-text">
                        Enter the quantity contained in one basket.
                    </div>

                </div>


                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('crops.index') }}"
                        class="btn btn-light"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        <i class="bi bi-check-lg me-1"></i>
                        Save Crop
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
