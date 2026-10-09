@extends('layouts.app')

@section('page-title', 'Edit Grade')

@section('breadcrumb', 'Master / Grades / Edit')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="page-title mb-1">Edit Grade</h1>

            <p class="text-muted mb-0">
                Update the crop grade information.
            </p>
        </div>

        <a href="{{ route('grades.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Grades
        </a>

    </div>

    <div class="dashboard-card shadow-sm">

        <div class="mb-4">

            <h5 class="mb-1">
                Grade Information
            </h5>

            <small class="text-muted">
                Update the crop and grade name.
            </small>

        </div>

        <form action="{{ route('grades.update', $grade) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="row g-3">

                {{-- Crop --}}
                <div class="col-md-6">

                    <label for="crop_id" class="form-label">
                        Crop <span class="text-danger">*</span>
                    </label>

                    <select name="crop_id" id="crop_id" class="form-select @error('crop_id') is-invalid @enderror">

                        <option value="">Select Crop</option>

                        @foreach ($crops as $crop)
                            <option value="{{ $crop->id }}"
                                {{ old('crop_id', $grade->crop_id) == $crop->id ? 'selected' : '' }}>
                                {{ $crop->crop_name }}
                            </option>
                        @endforeach

                    </select>

                    @error('crop_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                {{-- Grade Name --}}
                <div class="col-md-6">

                    <label for="grade_name" class="form-label">
                        Grade Name <span class="text-danger">*</span>
                    </label>

                    <input type="text" name="grade_name" id="grade_name"
                        value="{{ old('grade_name', $grade->grade_name) }}"
                        class="form-control @error('grade_name') is-invalid @enderror" placeholder="e.g. A, B, C"
                        maxlength="50">

                    @error('grade_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-between align-items-center">

                <form action="{{ route('grades.destroy', $grade) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this grade?');">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-trash me-1"></i>
                        Delete
                    </button>

                </form>

                <div class="d-flex gap-2">

                    <a href="{{ route('grades.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>
                        Update Grade
                    </button>

                </div>

            </div>

        </form>

    </div>

@endsection
