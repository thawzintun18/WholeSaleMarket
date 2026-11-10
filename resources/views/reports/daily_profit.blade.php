@extends('layouts.app')

@section('page-title', 'တစ်နေ့တာအမြတ်စာရင်း')

@section('breadcrumb', 'ပင်မစာမျက်နှာ / တစ်နေ့တာအမြတ်စာရင်းချုပ်')

@section('content')
<div class="container-fluid p-0">

    @include('partials.report-purchase-sale-tabs')

    <!-- Header Title -->
    <div class="d-flex align-items-center mb-4">
        <div class="bg-primary-subtle text-primary p-3 rounded-4 me-3">
            <i class="bi bi-wallet2 fs-4"></i>
        </div>
        <div>
            <h4 class="fw-bold text-dark mb-1">သီးနှံအမြတ်အစွန်း စာရင်းချုပ်</h4>
            <p class="text-muted small mb-0">ရွေးချယ်ထားသော ရက်စွဲအလိုက် စုစုပေါင်းရင်းနှီးငွေ၊ ရောင်းရငွေနှင့် အမြတ်အစွန်းများ တွက်ချက်မှု</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('reports#daily#profit') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary small">သီးနှံရွေးရန်</label>
                    <select name="crop_id" class="form-select rounded-3 py-2" onchange="this.form.submit()">
                        @foreach($crops as $crop)
                            <option value="{{ $crop->id }}" {{ $cropId == $crop->id ? 'selected' : '' }}>{{ $crop->crop_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary small">စတင်ရက်စွဲ (မှ)</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="form-control rounded-3 py-2">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary small">ကုန်ဆုံးရက်စွဲ (ထိ)</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="form-control rounded-3 py-2">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-semibold">
                        <i class="bi bi-search me-1"></i> ရှာဖွေမည်
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Cards (Modern Grid Layout) -->
    <div class="row g-4 mb-4">
        <!-- Card 1: Investment -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-3 h-100 border-start border-danger border-4">
                <div class="text-muted small fw-semibold text-uppercase mb-1">စိုက်ထုတ်သုံးစွဲငွေ (Investment)</div>
                <h4 class="fw-bold text-danger mb-2">{{ number_format($totalInvestment, 2) }} <span class="fs-6 fw-normal">ကျပ်</span></h4>
                <div class="text-secondary small bg-light p-2 rounded-3 mt-auto">
                    <i class="bi bi-info-circle me-1"></i> ရှေ့နေ့လက်ကျန် + ဝယ်ယူငွေ
                </div>
            </div>
        </div>

        <!-- Card 2: Return -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-3 h-100 border-start border-primary border-4">
                <div class="text-muted small fw-semibold text-uppercase mb-1">ပြန်လည်ရရှိမည့်တန်ဖိုး (Return)</div>
                <h4 class="fw-bold text-primary mb-2">{{ number_format($totalReturn, 2) }} <span class="fs-6 fw-normal">ကျပ်</span></h4>
                <div class="text-secondary small bg-light p-2 rounded-3 mt-auto">
                    <i class="bi bi-info-circle me-1"></i> ရောင်းရငွေ + လက်ကျန်တန်ဖိုး
                </div>
            </div>
        </div>

        <!-- Card 3: Net Profit -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-3 h-100 border-start border-success border-4">
                <div class="text-muted small fw-semibold text-uppercase mb-1">အစစ်အမှန် အမြတ်ငွေ</div>
                <h4 class="fw-bold text-success mb-2">{{ number_format($netProfit, 2) }} <span class="fs-6 fw-normal">ကျပ်</span></h4>
                <div class="text-secondary small bg-light p-2 rounded-3 mt-auto">
                    <i class="bi bi-check-circle me-1"></i> စုစုပေါင်း Return - Investment
                </div>
            </div>
        </div>

        <!-- Card 4: Remainder Amount (အစွန်းထွက်ငွေ စုစုပေါင်း) -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-3 h-100 border-start border-warning border-4">
                <div class="text-muted small fw-semibold text-uppercase mb-1">အစွန်းထွက်ငွေ စုစုပေါင်း</div>
                <h4 class="fw-bold text-warning-emphasis mb-2">{{ number_format($totalRemainderAmount ?? 0, 2) }} <span class="fs-6 fw-normal">ကျပ်</span></h4>
                <div class="text-secondary small bg-light p-2 rounded-3 mt-auto">
                    <i class="bi bi-bookmark-star me-1"></i> အဝယ်ဘောင်ချာ အစွန်းထွက်များ
                </div>
            </div>
        </div>
    </div>

</div>
@endsection