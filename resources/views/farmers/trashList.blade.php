blade
@extends('layouts.app')

@section('page-title', 'Farmers')

@section('breadcrumb')
    အခြေခံစာရင်းများ / တောင်သူစာရင်း / ဖျက်ထားသော တောင်သူစာရင်း
@endsection

@section('content')

    {{-- Page Header --}}
    <div class="d-md-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="page-title mb-1">ဖျက်ထားသော တောင်သူစာရင်း</h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                ဖျက်ထားသော တောင်သူများကို ဤနေရာတွင် ကြည့်ရှုနိုင်ပြီး
                လိုအပ်ပါက ပြန်လည်အသုံးပြုနိုင်ပါသည်။
                ပြန်လည်အသုံးပြုထားသော တောင်သူများသည် မူရင်းတောင်သူစာရင်းတွင်
                ပြန်လည်ပေါ်လာမည်ဖြစ်သည်။
            </p>
        </div>

    </div>

    {{-- Search --}}
    <div class="dashboard-card mb-4 shadow-sm">

        <form action="{{ route('farmers#trashList') }}" method="GET">

            <div class="row g-3">

                <div class="col-md-8">
                    <label class="form-label">
                        တောင်သူနှင့် ရက်စွဲ ရှာဖွေရန်
                    </label>

                    <div class="d-flex gap-2">

                        <select name="farmer_id"
                                id="farmerSearch"
                                class="form-select w-50">

                            <option value="">တောင်သူအားလုံး</option>

                            @foreach ($farmer_name as $item)
                                <option value="{{ $item->id }}"
                                    {{ request('farmer_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach

                        </select>

                        <input
                            type="date"
                            id="dateSearch"
                            name="deleted_date"
                            class="form-control w-50"
                            value="{{ request('deleted_date') }}"
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
                    <a href="{{ route('farmers#trashList') }}"
                       class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        ပြန်လည်သတ်မှတ်မည်
                    </a>
                </div>

            </div>

        </form>

    </div>

    {{-- Farmer Trash Table --}}
    <div class="dashboard-card shadow-sm">

        <div class="d-md-flex justify-content-between align-items-center mb-3">

            <div>
                <h5 class="mb-1">
                    ဖျက်ထားသော တောင်သူစာရင်း
                </h5>

                <small class="text-muted">
                    ဖျက်ထားသော တောင်သူများကို ကြည့်ရှုပြီး
                    လိုအပ်ပါက ပြန်လည်အသုံးပြုနိုင်ပါသည်။
                </small>
            </div>

            <div class="d-flex justify-content-end mt-md-0 mt-2">

                <div class="me-2">
                    <span class="badge bg-light text-dark border">
                        {{ $farmers->total() }} တောင်သူ
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

                                <a href="{{ route('farmers#restore', $item->id) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="ပြန်လည်အသုံးပြုရန်">

                                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                                    ပြန်လည်အသုံးပြုမည်

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="empty-state-cell">

                                <div class="empty-state">

                                    <h5>ဖျက်ထားသော တောင်သူစာရင်း မရှိသေးပါ</h5>

                                    <p>
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
        <div class="d-md-flex justify-content-between align-items-center mt-4">

            <p class="text-muted">
                တောင်သူ {{ $farmers->firstItem() ?? 0 }}
                မှ {{ $farmers->lastItem() ?? 0 }}
                အထိ ပြသထားပြီး
                စုစုပေါင်း {{ $farmers->total() }} ဦး ရှိပါသည်။
            </p>

            <div class="d-flex justify-content-end">
                {{ $farmers->links() }}
            </div>

        </div>

    </div>

@endsection
