@extends('wholeSaleMarket.layout.main')

@section('content')
    <div class="container-fluid px-3 px-lg-4 py-4">
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
                <div>
                    <h1 class="h3 mb-1">ပင်မစာမျက်နှာ</h1>
                </div>
            </div>
        </div>

        <section class="row g-3 mt-1" aria-label="Dashboard metrics">
            <div class="col-12 col-sm-6 col-xl-3">
                <article class="metric-card metric-primary">
                    <div class="metric-top">
                        <span class="metric-label">Revenue</span>
                        <span class="metric-icon"><i class="bi bi-currency-dollar" aria-hidden="true"></i></span>
                    </div>
                    <div class="metric-value">$48,240</div>
                    <div class="metric-meta">
                        <span class="text-success">+12.5%</span>
                        <span>from last month</span>
                    </div>
                </article>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <article class="metric-card metric-success">
                    <div class="metric-top">
                        <span class="metric-label">Orders</span>
                        <span class="metric-icon"><i class="bi bi-bag-check" aria-hidden="true"></i></span>
                    </div>
                    <div class="metric-value">1,284</div>
                    <div class="metric-meta">
                        <span class="text-success">+8.2%</span>
                        <span>new orders</span>
                    </div>
                </article>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <article class="metric-card metric-warning">
                    <div class="metric-top">
                        <span class="metric-label">Customers</span>
                        <span class="metric-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                    </div>
                    <div class="metric-value">8,742</div>
                    <div class="metric-meta">
                        <span class="text-success">+5.1%</span>
                        <span>active users</span>
                    </div>
                </article>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <article class="metric-card metric-danger">
                    <div class="metric-top">
                        <span class="metric-label">Tickets</span>
                        <span class="metric-icon"><i class="bi bi-life-preserver" aria-hidden="true"></i></span>
                    </div>
                    <div class="metric-value">36</div>
                    <div class="metric-meta">
                        <span class="text-danger">3 urgent</span>
                        <span>need review</span>
                    </div>
                </article>
            </div>
        </section>
    </div>
@endsection
