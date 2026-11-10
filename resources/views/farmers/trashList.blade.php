@extends('layouts.app')

@section('page-title', 'Farmers')

@section('breadcrumb')
    အခြေခံစာရင်းများ / တောင်သူစာရင်း / ဖျက်ထားသော တောင်သူစာရင်း
@endsection

@section('content')
    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">

        <div>
            <h1 class="page-title mb-1 fs-3 fs-md-2">ဖျက်ထားသော တောင်သူစာရင်း</h1>

            <p class="text-muted mb-0 mt-1" style="line-height: 1.5">
                ဖျက်ထားသော တောင်သူများကို ဤနေရာတွင် ကြည့်ရှုနိုင်ပြီး
                လိုအပ်ပါက ပြန်လည်အသုံးပြုနိုင်ပါသည်။
                ပြန်လည်အသုံးပြုထားသော တောင်သူများသည် မူရင်းတောင်သူစာရင်းတွင်
                ပြန်လည်ပေါ်လာမည်ဖြစ်သည်။
            </p>
        </div>

        <div class="d-flex justify-content-end w-100 w-md-auto">
            <a href="{{ route('farmers#index') }}" class="btn rounded-pill px-4 shadow-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>

    </div>

    {{-- Search --}}
    <div class="dashboard-card mb-3 shadow-sm p-3 p-md-4">

        <form action="{{ route('farmers#trashList') }}" method="GET">

            <div class="row align-items-end g-2">

                <div class="col-12 col-lg-8">
                    <label class="form-label mb-1">
                        တောင်သူနှင့် ရက်စွဲ ရှာဖွေရန်
                    </label>

                    <div class="d-flex flex-column flex-sm-row gap-2">

                        <select name="farmer_id" id="farmerSearch" class="form-select w-100 w-sm-50">

                            <option value="">တောင်သူအားလုံး</option>

                            @foreach ($farmer_name as $item)
                                <option value="{{ $item->id }}"
                                    {{ request('farmer_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach

                        </select>

                        <input type="date" id="dateSearch" name="deleted_date" class="form-control w-100 w-sm-50"
                            value="{{ request('deleted_date', now()->subDay()->toDateString()) }}">

                    </div>
                </div>

                <div class="col-12 col-lg-4 d-flex flex-wrap gap-2 mt-2 mt-lg-0">
                    <button type="submit" class="btn btn-primary flex-fill flex-lg-grow-0 rounded-pill shadow-sm">
                        <i class="bi bi-search me-1"></i>
                        ရှာဖွေမည်
                    </button>

                    <a href="{{ route('farmers#trashList') }}" class="btn btn-outline-secondary text-center flex-fill flex-lg-grow-0 rounded-pill shadow-sm">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        ပြန်လည်သတ်မှတ်မည်
                    </a>
                </div>

            </div>

        </form>

    </div>

    {{-- Farmer Trash Table --}}
    <div class="dashboard-card shadow-sm p-3 p-md-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">

            <div>
                <h5 class="mb-1">
                    ဖျက်ထားသော တောင်သူစာရင်း
                </h5>

                <small class="text-muted d-block mt-1" style="line-height: 1.5">
                    ဖျက်ထားသော တောင်သူများကို ကြည့်ရှုပြီး
                    လိုအပ်ပါက ပြန်လည်အသုံးပြုနိုင်ပါသည်။
                </small>
            </div>

            <div class="d-flex justify-content-end">
                <div>
                    <span class="badge bg-light text-dark border px-3 py-2">
                        {{ $farmers->total() }} တောင်သူ
                    </span>
                </div>
            </div>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0 text-nowrap">

                <thead>
                    <tr>
                        <th>စဉ်</th>
                        <th>တောင်သူကုဒ်</th>
                        <th>တောင်သူအမည်</th>
                        <th>ဖုန်းနံပါတ်</th>
                        <th>ကျေးရွာအမည်</th>
                        <th>မှတ်ချက်</th>
                        <th width="150" class="text-center">
                            လုပ်ဆောင်ချက်
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($farmers as $item)
                        <tr>

                            <td>
                                {{ $farmers->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <strong>{{ $item->farmer_code }}</strong>
                            </td>

                            <td>
                                <strong>{{ $item->name }}</strong>
                            </td>

                            <td>
                                {{ $item->phone ?: '-' }}
                            </td>

                            <td>
                                {{ $item->village ?: '-' }}
                            </td>

                            <td style="min-width: 180px; white-space: normal;">
                                {{ $item->notes ?: '-' }}
                            </td>

                            <td class="text-center">

                                <a href="{{ route('farmers#restore', $item->id) }}" class="btn btn-sm btn-outline-primary"
                                    title="ပြန်လည်အသုံးပြုရန်">

                                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                                    ပြန်လည်အသုံးပြုမည်

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="text-center py-5">

                                <div class="d-flex flex-column align-items-center justify-content-center">

                                    <h5 class="mb-1 text-secondary fw-bold">ဖျက်ထားသော တောင်သူစာရင်း မရှိသေးပါ</h5>

                                    <p class="text-muted mb-0">
                                        တောင်သူကို ဖျက်ထားသော မှတ်တမ်း မရှိသေးပါ။
                                    </p>

                                </div>

                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 gap-3">

            <p class="text-muted mb-0 text-center text-md-start">
                တောင်သူ {{ $farmers->firstItem() ?? 0 }}
                မှ {{ $farmers->lastItem() ?? 0 }}
                အထိ ပြသထားပြီး
                စုစုပေါင်း {{ $farmers->total() }} ဦး ရှိပါသည်။
            </p>

            <nav class="d-flex justify-content-end">
                <ul class="pagination pagination-sm mb-0">
                    {{ $farmers->links() }}
                </ul>
            </nav>

        </div>

    </div>

@endsection
