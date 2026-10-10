blade
@extends('layouts.app')

@section('page-title', 'Farmer History')

@section('breadcrumb')
    အခြေခံစာရင်းများ / တောင်သူစာရင်း / တောင်သူပြင်ဆင်မှုစာရင်း
@endsection

@section('content')

    {{-- Page Header --}}
    <div class="d-md-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">တောင်သူပြင်ဆင်မှုစာရင်း</h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                တောင်သူအချက်အလက် အဟောင်းနှင့် အသစ်များကို ကြည့်ရှုနိုင်ပါသည်။
            </p>
        </div>
    </div>

    {{-- Search --}}
    <div class="dashboard-card mb-4 shadow-sm">

        <form action="{{ route('farmers#history') }}" method="GET">
            <div class="row g-3">

                <div class="col-md-8">
                    <label class="form-label">
                        တောင်သူနှင့် ရက်စွဲ ရှာဖွေရန်
                    </label>

                    <div class="d-flex gap-2">

                        <select name="farmer_code"
                                id="farmerSearch"
                                class="form-select w-50">

                            <option value="">တောင်သူအားလုံး</option>

                            @foreach ($farmers as $item)
                                <option value="{{ $item->farmer_code }}"
                                    {{ request('farmer_code') == $item->farmer_code ? 'selected' : '' }}>
                                    {{ $item->farmer_code }} - {{ $item->name }}
                                </option>
                            @endforeach

                        </select>

                        <input
                            type="date"
                            id="dateSearch"
                            name="changed_date"
                            class="form-control w-50"
                            value="{{ request('changed_date', now()->toDateString()) }}"
                        >

                    </div>
                </div>

                <div class="col-md-auto d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>
                        ရှာဖွေမည်
                    </button>
                </div>

                <div class="col-md-auto d-flex align-items-end">
                    <a href="{{ route('farmers#history') }}"
                       class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        ပြန်လည်သတ်မှတ်မည်
                    </a>
                </div>

            </div>
        </form>

    </div>

    {{-- Farmer History Table --}}
    <div class="dashboard-card shadow-sm">

        <div class="d-md-flex justify-content-between align-items-center mb-3">

            <div>
                <h5 class="mb-1">တောင်သူပြင်ဆင်မှုစာရင်း</h5>

                <small class="text-muted">
                    ပြင်ဆင်ထားသော တောင်သူအချက်အလက်မှတ်တမ်းများ
                </small>
            </div>

            <div class="d-flex justify-content-end mt-md-0 mt-2">

                <div class="me-2">
                    <span class="badge bg-light text-dark border">
                        {{ $histories->total() }} မှတ်တမ်း
                    </span>
                </div>

                <div>
                    <a href="{{ route('farmers#index') }}"
                       class="btn btn-light">
                        နောက်သို့
                    </a>
                </div>

            </div>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th rowspan="2">စဉ်</th>
                        <th rowspan="2">တောင်သူကုဒ်</th>
                        <th colspan="4" class="text-center">အရင်အချက်အလက်</th>
                        <th colspan="4" class="text-center text-success">ပြင်ဆင်ပြီးအချက်အလက်</th>
                        <th rowspan="2" class="text-info">ပြင်ဆင်ချိန်</th>
                    </tr>

                    <tr>
                        <th>အမည်</th>
                        <th>ဖုန်းနံပါတ်</th>
                        <th>ကျေးရွာ</th>
                        <th>မှတ်ချက်</th>

                        <th class=" text-success">အမည်</th>
                        <th class=" text-success">ဖုန်းနံပါတ်</th>
                        <th class=" text-success">ကျေးရွာ</th>
                        <th class=" text-success">မှတ်ချက်</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($histories as $history)

                        <tr>
                            <td>
                                {{ $histories->firstItem() + $loop->index }}
                            </td>

                            <td class="fw-semibold">
                                {{ $history->farmer_code ?? '-' }}
                            </td>

                            {{-- Old Data --}}
                            <td>{{ $history->old_name }}</td>

                            <td>{{ $history->old_phone ?: '-' }}</td>

                            <td>{{ $history->old_village ?: '-' }}</td>

                            <td style="min-width: 180px; white-space: normal;">
                                {{ $history->old_notes ?: '-' }}
                            </td>

                            {{-- New Data --}}
                            <td class="text-success fw-semibold">
                                {{ $history->new_name }}
                            </td>

                            <td class="text-success fw-semibold">
                                {{ $history->new_phone ?: '-' }}
                            </td>

                            <td class="text-success fw-semibold">
                                {{ $history->new_village ?: '-' }}
                            </td>

                            <td class="text-success"
                                style="min-width: 180px; white-space: normal;">
                                {{ $history->new_notes ?: '-' }}
                            </td>

                            {{-- Changed Date --}}
                            <td style="min-width: 120px;" class="text-info">
                                <div>
                                    {{ \Carbon\Carbon::parse($history->changed_at)->format('d-m-Y') }}
                                </div>

                                <small>
                                    {{ \Carbon\Carbon::parse($history->changed_at)->format('h:i A') }}
                                </small>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="11" class="empty-state-cell">
                                <div class="empty-state">
                                    <h5>ပြင်ဆင်မှုမှတ်တမ်း မရှိသေးပါ</h5>

                                    <p>
                                        တောင်သူအချက်အလက်ကို ပြင်ဆင်ထားသော
                                        မှတ်တမ်း မရှိသေးပါ။
                                    </p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        <div class="d-md-flex justify-content-between align-items-center mt-4">

            <p class="text-muted mb-md-0">
                မှတ်တမ်း {{ $histories->firstItem() ?? 0 }}
                မှ {{ $histories->lastItem() ?? 0 }}
                အထိ ပြသထားပြီး
                စုစုပေါင်း {{ $histories->total() }} ခု ရှိပါသည်။
            </p>

            <div class="d-flex justify-content-end">
                {{ $histories->links() }}
            </div>

        </div>

    </div>

@endsection
