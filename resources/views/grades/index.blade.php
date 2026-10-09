@extends('layouts.app')

@section('page-title', 'Grades')

@section('breadcrumb', 'Master / Grades')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>

            <h1 class="page-title mb-1">
                Grades
            </h1>

            <p class="text-muted mb-0">
                Manage crop grades and quality levels.
            </p>

        </div>


        <a href="{{ route('grades.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i>
            Add Grade
        </a>

    </div>

    {{-- Search & Filter --}}

    <div class="dashboard-card mb-4 shadow-sm">
        <div class="row g-3">

            {{-- Search --}}
            <div class="col-md-5">

                <label class="form-label">
                    Search Grade
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>

                    <input type="text" class="form-control" placeholder="Enter grade name...">

                </div>

            </div>


            {{-- Crop Filter --}}
            <div class="col-md-4">

                <label class="form-label">
                    Crop
                </label>

                <select class="form-select">

                    <option value="">
                        All Crops
                    </option>

                    <option>
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

                </select>

            </div>


            {{-- Search Button --}}
            <div class="col-md-auto d-flex align-items-end">

                <button class="btn btn-primary">

                    <i class="bi bi-search me-1"></i>

                    Search

                </button>

            </div>


            {{-- Reset --}}
            <div class="col-md-auto d-flex align-items-end">

                <button class="btn btn-outline-secondary">

                    Reset

                </button>

            </div>

        </div>

    </div>

    {{-- Grade Table --}}
    <div class="dashboard-card shadow-sm">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>

                <h5 class="mb-1">
                    Grade List
                </h5>

                <small class="text-muted">
                    All registered crop grades
                </small>

            </div>


            <span class="badge bg-light text-dark border">
                8 Grades
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
                            Crop
                        </th>

                        <th>
                            Grade Name
                        </th>

                        <th>
                            Created Date
                        </th>

                        <th width="150" class="text-center">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    {{-- Grade 1 --}}
                    <tr>

                        <td>
                            1
                        </td>

                        <td>

                            <strong>
                                Tomato
                            </strong>

                        </td>

                        <td>

                            <span class="badge bg-light text-dark border">
                                Grade A
                            </span>

                        </td>

                        <td>
                            08 Oct 2026
                        </td>

                        <td class="text-center">

                            <a href="#" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>


                            <button type="button" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>

                        </td>

                    </tr>


                    {{-- Grade 2 --}}
                    <tr>

                        <td>
                            2
                        </td>

                        <td>

                            <strong>
                                Tomato
                            </strong>

                        </td>

                        <td>

                            <span class="badge bg-light text-dark border">
                                Grade B
                            </span>

                        </td>

                        <td>
                            08 Oct 2026
                        </td>

                        <td class="text-center">

                            <a href="#" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>


                            <button type="button" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>

                        </td>

                    </tr>


                    {{-- Grade 3 --}}
                    <tr>

                        <td>
                            3
                        </td>

                        <td>

                            <strong>
                                Tomato
                            </strong>

                        </td>

                        <td>

                            <span class="badge bg-light text-dark border">
                                Grade C
                            </span>

                        </td>

                        <td>
                            08 Oct 2026
                        </td>

                        <td class="text-center">

                            <a href="#" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>


                            <button type="button" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>

                        </td>

                    </tr>


                    {{-- Grade 4 --}}
                    <tr>

                        <td>
                            4
                        </td>

                        <td>

                            <strong>
                                Potato
                            </strong>

                        </td>

                        <td>

                            <span class="badge bg-light text-dark border">
                                Grade A
                            </span>

                        </td>

                        <td>
                            07 Oct 2026
                        </td>

                        <td class="text-center">

                            <a href="#" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>


                            <button type="button" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>

                        </td>

                    </tr>


                    {{-- Grade 5 --}}
                    <tr>

                        <td>
                            5
                        </td>

                        <td>

                            <strong>
                                Potato
                            </strong>

                        </td>

                        <td>

                            <span class="badge bg-light text-dark border">
                                Grade B
                            </span>

                        </td>

                        <td>
                            07 Oct 2026
                        </td>

                        <td class="text-center">

                            <a href="#" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>


                            <button type="button" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>

                        </td>

                    </tr>


                    {{-- Grade 6 --}}
                    <tr>

                        <td>
                            6
                        </td>

                        <td>

                            <strong>
                                Cabbage
                            </strong>

                        </td>

                        <td>

                            <span class="badge bg-light text-dark border">
                                Grade A
                            </span>

                        </td>

                        <td>
                            06 Oct 2026
                        </td>

                        <td class="text-center">

                            <a href="#" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>


                            <button type="button" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>

                        </td>

                    </tr>


                    {{-- Grade 7 --}}
                    <tr>

                        <td>
                            7
                        </td>

                        <td>

                            <strong>
                                Onion
                            </strong>

                        </td>

                        <td>

                            <span class="badge bg-light text-dark border">
                                Grade A
                            </span>

                        </td>

                        <td>
                            06 Oct 2026
                        </td>

                        <td class="text-center">

                            <a href="#" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>


                            <button type="button" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>

                        </td>

                    </tr>


                    {{-- Grade 8 --}}
                    <tr>

                        <td>
                            8
                        </td>

                        <td>

                            <strong>
                                Chilli
                            </strong>

                        </td>

                        <td>

                            <span class="badge bg-light text-dark border">
                                Grade A
                            </span>

                        </td>

                        <td>
                            05 Oct 2026
                        </td>

                        <td class="text-center">

                            <a href="#" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>


                            <button type="button" class="btn btn-sm btn-outline-danger" title="Delete">
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
                Showing 1 to 8 of 8 grades
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
