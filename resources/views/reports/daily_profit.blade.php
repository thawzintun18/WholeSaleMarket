@extends('layouts.app')

@section('page-title', 'တစ်နေ့တာအမြတ်စာရင်း')

@section('breadcrumb', 'ပင်မစာမျက်နှာ / တစ်နေ့တာအမြတ်စာရင်းချုပ်')
@section('content')
<div class="container-fluid py-4">
    <h3 class="fw-bold mb-4">သီးနှံအမြတ်အစွန်း စာရင်းချုပ်</h3>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('reports#daily#profit') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">သီးနှံရွေးရန်</label>
            <select name="crop_id" class="form-select" onchange="this.form.submit()">
                @foreach($crops as $crop)
                    <option value="{{ $crop->id }}" {{ $cropId == $crop->id ? 'selected' : '' }}>{{ $crop->crop_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">စတင်ရက်စွဲ</label>
            <input type="date" name="start_date" value="{{ $startDate }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">ကုန်ဆုံးရက်စွဲ</label>
            <input type="date" name="end_date" value="{{ $endDate }}" class="form-control">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">ရှာဖွေမည်</button>
        </div>
    </form>

    <!-- Results Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="p-3 border rounded-3 bg-white shadow-sm">
                <div class="text-muted small">စိုက်ထုတ်သုံးစွဲထားသော ငွေစုစုပေါင်း (Investment)</div>
                <h4 class="fw-bold text-danger mb-0">{{ number_format($totalInvestment, 2) }} ကျပ်</h4>
                <div class="small text-muted mt-1">(ရှေ့နေ့လက်ကျန်တန်ဖိုး + ဝယ်ယူငွေ)</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 border rounded-3 bg-white shadow-sm">
                <div class="text-muted small">ပြန်လည်ရရှိမည့် တန်ဖိုးစုစုပေါင်း (Total Return)</div>
                <h4 class="fw-bold text-primary mb-0">{{ number_format($totalReturn, 2) }} ကျပ်</h4>
                <div class="small text-muted mt-1">(ရောင်းရငွေ + လက်ကျန်တန်ဖိုး)</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 border rounded-3 bg-white shadow-sm">
                <div class="text-muted small">အစစ်အမှန် အမြတ်ငွေ (Net Profit)</div>
                <h4 class="fw-bold text-success mb-0">{{ number_format($netProfit, 2) }} ကျပ်</h4>
                <div class="small text-muted mt-1">(Return - Investment)</div>
            </div>
        </div>
    </div>
</div>
@endsection