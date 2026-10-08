@extends('layouts.app')

@section('page-title', 'Crops')
@section('breadcrumb')
    Dashboard / Crops
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="page-title mb-1">Crops</h1>

        <p class="text-muted mb-0">
            Manage crops and their basic information.
        </p>
    </div>

    <a href="{{ route('crops.create')}}" class="btn btn-success">
        <i class="bi bi-plus-lg me-1"></i>
        Add Crop
    </a>

</div>


{{-- Search --}}
<div class="dashboard-card mb-4 shadow-sm">

    <div class="row g-3">

        <div class="col-md-6">

            <label class="form-label">
                Search Crop
            </label>

            <div class="input-group">

                <span class="input-group-text">
                    <i class="bi bi-search"></i>
                </span>

                <input
                    type="text"
                    class="form-control"
                    placeholder="Enter crop name..."
                >

            </div>

        </div>

        <div class="col-md-auto d-flex align-items-end">

            <button class="btn btn-primary">
                <i class="bi bi-search me-1"></i>
                Search
            </button>

        </div>

        <div class="col-md-auto d-flex align-items-end">

            <button class="btn btn-outline-secondary">
                Reset
            </button>

        </div>

    </div>

</div>


{{-- Crop Table --}}
<div class="dashboard-card shadow-sm">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h5 class="mb-1">
                Crop List
            </h5>

            <small class="text-muted">
                All registered crops
            </small>
        </div>

        <span class="badge bg-light text-dark border">
            6 Crops
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
                        Crop Name
                    </th>

                    <th>
                        Unit
                    </th>

                    <th>
                        Quantity / Basket
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
                        <strong>Tomato</strong>
                    </td>

                    <td>
                        <span class="badge bg-light text-dark border">
                            Basket
                        </span>
                    </td>

                    <td>
                        20
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

                    <td>2</td>

                    <td>
                        <strong>Potato</strong>
                    </td>

                    <td>
                        <span class="badge bg-light text-dark border">
                            Bag
                        </span>
                    </td>

                    <td>
                        50
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
                        <strong>Cabbage</strong>
                    </td>

                    <td>
                        <span class="badge bg-light text-dark border">
                            Basket
                        </span>
                    </td>

                    <td>
                        25
                    </td>

                    <td class="text-center">

                        <a href="#"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>

                    </td>

                </tr>


                <tr>

                    <td>4</td>

                    <td>
                        <strong>Onion</strong>
                    </td>

                    <td>
                        <span class="badge bg-light text-dark border">
                            Bag
                        </span>
                    </td>

                    <td>
                        40
                    </td>

                    <td class="text-center">

                        <a href="#"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>

                    </td>

                </tr>


                <tr>

                    <td>5</td>

                    <td>
                        <strong>Chilli</strong>
                    </td>

                    <td>
                        <span class="badge bg-light text-dark border">
                            Kg
                        </span>
                    </td>

                    <td>
                        10
                    </td>

                    <td class="text-center">

                        <a href="#"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>

                    </td>

                </tr>


                <tr>

                    <td>6</td>

                    <td>
                        <strong>Cauliflower</strong>
                    </td>

                    <td>
                        <span class="badge bg-light text-dark border">
                            Basket
                        </span>
                    </td>

                    <td>
                        20
                    </td>

                    <td class="text-center">

                        <a href="#"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>


    {{-- Pagination Example --}}
    <div class="d-flex justify-content-between align-items-center mt-4">

        <small class="text-muted">
            Showing 1 to 6 of 6 crops
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
