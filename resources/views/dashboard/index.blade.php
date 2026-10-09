
@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb', 'Dashboard')

@section('content')

    <div class="mb-4">
        <h1 class="page-title mb-1">
            Dashboard
        </h1>

        <p class="text-muted mb-0">
            Today's wholesale market overview
        </p>
    </div>


    {{-- Summary Cards --}}
    <div class="row g-4 mb-4">

        <div class="col-md-6 col-xl-3">
            <div class="dashboard-card">

                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-muted small">
                            Total Farmers
                        </div>

                        <div class="fs-3 fw-bold mt-2">
                            245
                        </div>
                    </div>

                    <div class="fs-2 text-success">
                        <i class="bi bi-people"></i>
                    </div>
                </div>

            </div>
        </div>


        <div class="col-md-6 col-xl-3">
            <div class="dashboard-card">

                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-muted small">
                            Today's Purchases
                        </div>

                        <div class="fs-3 fw-bold mt-2">
                            35
                        </div>
                    </div>

                    <div class="fs-2 text-primary">
                        <i class="bi bi-cart"></i>
                    </div>
                </div>

            </div>
        </div>


        <div class="col-md-6 col-xl-3">
            <div class="dashboard-card">

                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-muted small">
                            Purchase Amount
                        </div>

                        <div class="fs-3 fw-bold mt-2">
                            25,800,000
                        </div>
                    </div>

                    <div class="fs-2 text-warning">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>

            </div>
        </div>


        <div class="col-md-6 col-xl-3">
            <div class="dashboard-card">

                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-muted small">
                            Outstanding
                        </div>

                        <div class="fs-3 fw-bold mt-2">
                            4,250,000
                        </div>
                    </div>

                    <div class="fs-2 text-danger">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>

            </div>
        </div>

    </div>


    {{-- Recent Purchases --}}
    <div class="dashboard-card">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>
                <h5 class="mb-1">
                    Today's Purchases
                </h5>

                <small class="text-muted">
                    Recent farmer purchases
                </small>
            </div>

            <a href="#" class="btn btn-sm btn-outline-primary">
                View All
            </a>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th>Voucher No</th>
                        <th>Farmer</th>
                        <th>Items</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>PU-000125</td>
                        <td>ဦးအောင်</td>
                        <td>3</td>
                        <td class="text-end">
                            850,000
                        </td>
                    </tr>

                    <tr>
                        <td>PU-000126</td>
                        <td>ဦးမြင့်</td>
                        <td>5</td>
                        <td class="text-end">
                            1,250,000
                        </td>
                    </tr>

                    <tr>
                        <td>PU-000127</td>
                        <td>ကိုလှ</td>
                        <td>2</td>
                        <td class="text-end">
                            450,000
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

@endsection
