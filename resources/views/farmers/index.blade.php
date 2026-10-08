@extends('layouts.app')

@section('page-title', 'Farmers')

@section('breadcrumb')
Dashboard / Farmers
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">


<div>
    <h1 class="page-title mb-1">Farmers</h1>

    <p class="text-muted mb-0">
        Manage farmers and their basic information.
    </p>
</div>

<a href="{{ route('farmers.create') }}" class="btn btn-success">
    <i class="bi bi-plus-lg me-1"></i>
    Add Farmer
</a>


</div>

{{-- Search --}}

<div class="dashboard-card mb-4 shadow-sm">


<div class="row g-3">

    <div class="col-md-4">

        <label class="form-label">
            Search Farmer
        </label>

        <div class="input-group">

            <span class="input-group-text">
                <i class="bi bi-search"></i>
            </span>

            <input
                type="text"
                class="form-control"
                placeholder="Code, name or phone..."
            >

        </div>

    </div>


    <div class="col-md-3">

        <label class="form-label">
            Address
        </label>

        <input
            type="text"
            class="form-control"
            placeholder="Enter address..."
        >

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

{{-- Farmer Table --}}

<div class="dashboard-card shadow-sm">


<div class="d-flex justify-content-between align-items-center mb-3">

    <div>

        <h5 class="mb-1">
            Farmer List
        </h5>

        <small class="text-muted">
            All registered farmers
        </small>

    </div>

    <span class="badge bg-light text-dark border">
        6 Farmers
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
                    Farmer Code
                </th>

                <th>
                    Farmer Name
                </th>

                <th>
                    Phone
                </th>

                <th>
                    Address
                </th>

                <th width="150" class="text-center">
                    Action
                </th>

            </tr>

        </thead>


        <tbody>


            <tr>

                <td>
                    1
                </td>

                <td>
                    <span class="badge bg-light text-dark border">
                        FRM-0001
                    </span>
                </td>

                <td>
                    <strong>U Aung Min</strong>
                </td>

                <td>
                    09-123456789
                </td>

                <td>
                    Taunggyi
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

                <td>
                    2
                </td>

                <td>
                    <span class="badge bg-light text-dark border">
                        FRM-0002
                    </span>
                </td>

                <td>
                    <strong>Daw Mya Mya</strong>
                </td>

                <td>
                    09-987654321
                </td>

                <td>
                    Aungban
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

                <td>
                    3
                </td>

                <td>
                    <span class="badge bg-light text-dark border">
                        FRM-0003
                    </span>
                </td>

                <td>
                    <strong>U Kyaw Kyaw</strong>
                </td>

                <td>
                    09-555666777
                </td>

                <td>
                    Kalaw
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

                <td>
                    4
                </td>

                <td>
                    <span class="badge bg-light text-dark border">
                        FRM-0004
                    </span>
                </td>

                <td>
                    <strong>U Than Htike</strong>
                </td>

                <td>
                    09-222333444
                </td>

                <td>
                    Nyaungshwe
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

                <td>
                    5
                </td>

                <td>
                    <span class="badge bg-light text-dark border">
                        FRM-0005
                    </span>
                </td>

                <td>
                    <strong>Daw Hla Hla</strong>
                </td>

                <td>
                    09-444555666
                </td>

                <td>
                    Hopong
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

                <td>
                    6
                </td>

                <td>
                    <span class="badge bg-light text-dark border">
                        FRM-0006
                    </span>
                </td>

                <td>
                    <strong>U Zaw Win</strong>
                </td>

                <td>
                    09-777888999
                </td>

                <td>
                    Pindaya
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


{{-- Pagination Example --}}
<div class="d-flex justify-content-between align-items-center mt-4">

    <small class="text-muted">
        Showing 1 to 6 of 6 farmers
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
