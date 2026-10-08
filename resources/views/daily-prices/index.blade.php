@extends('layouts.app')

@section('page-title', 'Daily Prices')

@section('breadcrumb', 'Master / Daily Prices')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

<div>
    <h1 class="page-title mb-1">Daily Prices</h1>

    <p class="text-muted mb-0">
        Manage daily reference buying prices for crops and grades.
    </p>
</div>

<a href="{{ route('daily-prices.create') }}" class="btn btn-success">
    <i class="bi bi-plus-lg me-1"></i>
    Add Daily Price
</a>
</div>

{{-- Filters --}}

<div class="dashboard-card mb-4 shadow-sm">

<div class="row g-3">

    {{-- Date --}}
    <div class="col-md-3">

        <label class="form-label">
            Price Date
        </label>

        <input
            type="date"
            class="form-control"
            value="{{ date('Y-m-d') }}"
        >

    </div>


    {{-- Crop --}}
    <div class="col-md-3">

        <label class="form-label">
            Crop
        </label>

        <select class="form-select">

            <option value="">
                All Crops
            </option>

            <option>Tomato</option>
            <option>Potato</option>
            <option>Cabbage</option>
            <option>Onion</option>
            <option>Chilli</option>
            <option>Cauliflower</option>

        </select>

    </div>


    {{-- Grade --}}
    <div class="col-md-3">

        <label class="form-label">
            Grade
        </label>

        <select class="form-select">

            <option value="">
                All Grades
            </option>

            <option>Grade A</option>
            <option>Grade B</option>
            <option>Grade C</option>

        </select>

    </div>


    {{-- Buttons --}}
    <div class="col-md-auto d-flex align-items-end gap-2">

        <button class="btn btn-primary">

            <i class="bi bi-search me-1"></i>
            Search

        </button>

        <button class="btn btn-outline-secondary">
            Reset
        </button>

    </div>

</div>

</div>

{{-- Daily Price Table --}}

<div class="dashboard-card shadow-sm">
<div class="d-flex justify-content-between align-items-center mb-3">

    <div>

        <h5 class="mb-1">
            Daily Price List
        </h5>

        <small class="text-muted">
            Reference buying prices by crop and grade
        </small>

    </div>


    <span class="badge bg-light text-dark border">
        8 Prices
    </span>

</div>


<div class="table-responsive">

    <table class="table table-hover align-middle mb-0">

        <thead>

            <tr>

                <th width="70">
                    #
                </th>

                <th>
                    Price Date
                </th>

                <th>
                    Crop
                </th>

                <th>
                    Grade
                </th>

                <th class="text-end">
                    Reference Buy Price
                </th>

                <th width="150" class="text-center">
                    Action
                </th>

            </tr>

        </thead>


        <tbody>

            <tr>

                <td>1</td>

                <td>
                    <strong>08 Oct 2026</strong>
                </td>

                <td>
                    <strong>Tomato</strong>
                </td>

                <td>
                    <span class="badge bg-success-subtle text-success border">
                        Grade A
                    </span>
                </td>

                <td class="text-end">

                    <strong>
                        25,000
                    </strong>

                    <small class="text-muted">
                        MMK
                    </small>

                </td>

                <td class="text-center">

                    <a
                        href=""
                        class="btn btn-sm btn-outline-primary"
                        title="Edit"
                    >
                        <i class="bi bi-pencil"></i>
                    </a>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger"
                        title="Delete"
                    >
                        <i class="bi bi-trash"></i>
                    </button>

                </td>

            </tr>


            <tr>

                <td>2</td>

                <td>
                    <strong>08 Oct 2026</strong>
                </td>

                <td>
                    <strong>Tomato</strong>
                </td>

                <td>
                    <span class="badge bg-warning-subtle text-warning border">
                        Grade B
                    </span>
                </td>

                <td class="text-end">

                    <strong>
                        20,000
                    </strong>

                    <small class="text-muted">
                        MMK
                    </small>

                </td>

                <td class="text-center">

                    <a
                        href="#"
                        class="btn btn-sm btn-outline-primary"
                        title="Edit"
                    >
                        <i class="bi bi-pencil"></i>
                    </a>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger"
                        title="Delete"
                    >
                        <i class="bi bi-trash"></i>
                    </button>

                </td>

            </tr>


            <tr>

                <td>3</td>

                <td>
                    <strong>08 Oct 2026</strong>
                </td>

                <td>
                    <strong>Potato</strong>
                </td>

                <td>
                    <span class="badge bg-success-subtle text-success border">
                        Grade A
                    </span>
                </td>

                <td class="text-end">

                    <strong>
                        30,000
                    </strong>

                    <small class="text-muted">
                        MMK
                    </small>

                </td>

                <td class="text-center">

                    <a
                        href="#"
                        class="btn btn-sm btn-outline-primary"
                        title="Edit"
                    >
                        <i class="bi bi-pencil"></i>
                    </a>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger"
                        title="Delete"
                    >
                        <i class="bi bi-trash"></i>
                    </button>

                </td>

            </tr>


            <tr>

                <td>4</td>

                <td>
                    <strong>08 Oct 2026</strong>
                </td>

                <td>
                    <strong>Potato</strong>
                </td>

                <td>
                    <span class="badge bg-warning-subtle text-warning border">
                        Grade B
                    </span>
                </td>

                <td class="text-end">

                    <strong>
                        25,000
                    </strong>

                    <small class="text-muted">
                        MMK
                    </small>

                </td>

                <td class="text-center">

                    <a
                        href="#"
                        class="btn btn-sm btn-outline-primary"
                        title="Edit"
                    >
                        <i class="bi bi-pencil"></i>
                    </a>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger"
                        title="Delete"
                    >
                        <i class="bi bi-trash"></i>
                    </button>

                </td>

            </tr>


            <tr>

                <td>5</td>

                <td>
                    <strong>08 Oct 2026</strong>
                </td>

                <td>
                    <strong>Cabbage</strong>
                </td>

                <td>
                    <span class="badge bg-success-subtle text-success border">
                        Grade A
                    </span>
                </td>

                <td class="text-end">

                    <strong>
                        18,000
                    </strong>

                    <small class="text-muted">
                        MMK
                    </small>

                </td>

                <td class="text-center">

                    <a
                        href="#"
                        class="btn btn-sm btn-outline-primary"
                        title="Edit"
                    >
                        <i class="bi bi-pencil"></i>
                    </a>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger"
                        title="Delete"
                    >
                        <i class="bi bi-trash"></i>
                    </button>

                </td>

            </tr>


            <tr>

                <td>6</td>

                <td>
                    <strong>08 Oct 2026</strong>
                </td>

                <td>
                    <strong>Onion</strong>
                </td>

                <td>
                    <span class="badge bg-success-subtle text-success border">
                        Grade A
                    </span>
                </td>

                <td class="text-end">

                    <strong>
                        22,000
                    </strong>

                    <small class="text-muted">
                        MMK
                    </small>

                </td>

                <td class="text-center">

                    <a
                        href="#"
                        class="btn btn-sm btn-outline-primary"
                        title="Edit"
                    >
                        <i class="bi bi-pencil"></i>
                    </a>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger"
                        title="Delete"
                    >
                        <i class="bi bi-trash"></i>
                    </button>

                </td>

            </tr>


            <tr>

                <td>7</td>

                <td>
                    <strong>07 Oct 2026</strong>
                </td>

                <td>
                    <strong>Tomato</strong>
                </td>

                <td>
                    <span class="badge bg-success-subtle text-success border">
                        Grade A
                    </span>
                </td>

                <td class="text-end">

                    <strong>
                        24,000
                    </strong>

                    <small class="text-muted">
                        MMK
                    </small>

                </td>

                <td class="text-center">

                    <a
                        href="#"
                        class="btn btn-sm btn-outline-primary"
                        title="Edit"
                    >
                        <i class="bi bi-pencil"></i>
                    </a>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger"
                        title="Delete"
                    >
                        <i class="bi bi-trash"></i>
                    </button>

                </td>

            </tr>


            <tr>

                <td>8</td>

                <td>
                    <strong>07 Oct 2026</strong>
                </td>

                <td>
                    <strong>Potato</strong>
                </td>

                <td>
                    <span class="badge bg-warning-subtle text-warning border">
                        Grade B
                    </span>
                </td>

                <td class="text-end">

                    <strong>
                        24,000
                    </strong>

                    <small class="text-muted">
                        MMK
                    </small>

                </td>

                <td class="text-center">

                    <a
                        href="#"
                        class="btn btn-sm btn-outline-primary"
                        title="Edit"
                    >
                        <i class="bi bi-pencil"></i>
                    </a>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger"
                        title="Delete"
                    >
                        <i class="bi bi-trash"></i>
                    </button>

                </td>

            </tr>

        </tbody>

    </table>

</div>


{{-- Pagination --}}

<div class="d-flex justify-content-between align-items-center mt-4">

    <small class="text-muted">
        Showing 1 to 8 of 8 daily prices
    </small>

    <nav>

        <ul class="pagination pagination-sm mb-0">

            <li class="page-item disabled">

                <a class="page-link" href="#">
                    Previous
                </a>

            </li>

            <li class="page-item active">

                <a class="page-link" href="#">
                    1
                </a>

            </li>

            <li class="page-item disabled">

                <a class="page-link" href="#">
                    Next
                </a>

            </li>

        </ul>

    </nav>

</div>

</div>

@endsection
