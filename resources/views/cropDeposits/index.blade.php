@extends('layouts.app')

@section('page-title', 'CropDeposit')
@section('breadcrumb')
    အခြေခံစာရင်းများ / သီးနှံအပ်နှံ စာရင်း
@endsection

@section('content')
<div class="container-fluid p-0">

    @include('partials.voucher-list-tabs')

    <!-- Header & Action Button -->
    <div class="d-md-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="bi bi-box-seam me-2 text-primary" style="line-height: 35px"></i>သီးနှံအပ်နှံမှု စာရင်းများ
            </h3>
            <p class="text-muted small mb-0 mt-2" style="line-height: 30px">တောင်သူများ အပ်နှံထားသော သီးနှံများနှင့် အရေအတွက်များကို စစ်ဆေးစီမံရန်</p>
        </div>
        <div class=" d-flex justify-content-end mt-md-0 mt-3">
            <a href="{{ route('crop#deposits#createPage') }}" class="btn btn-primary px-4 shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> အပ်နှံမှုအသစ် လက်ခံရန်
            </a>
        </div>
    </div>

    {{-- @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif --}}

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'အောင်မြင်ပါသည်',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'ကောင်းပြီ',
                    confirmButtonColor: '#198754', // Bootstrap success color
                    timer: 3000, // ၃ စက္ကန့်ကြာရင် အလိုအလျောက် ပိတ်သွားမည် (မလိုချင်ရင် ဖြုတ်လို့ရပါတယ်)
                    timerProgressBar: true
                });
            });
        </script>
    @endif

    <!-- Search & Filter Card (အဆင့်မြှင့်တင်ထားသော Filter များ) -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('crop#deposits#index') }}" method="GET" class="row g-3">
                <!-- ရှာဖွေရန် -->
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">ရှာဖွေရန်</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="ဘောင်ချာ၊ တောင်သူ၊ ဖုန်း၊ ကားနံပါတ်">
                </div>

                <!-- သီးနှံအလိုက် Filter -->
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">သီးနှံအမျိုးအစား</label>
                    <select name="crop_id" class="form-select">
                        <option value="">သီးနှံ အားလုံး</option>
                        @foreach($crops as $crop)
                            <option value="{{ $crop->id }}" {{ request('crop_id') == $crop->id ? 'selected' : '' }}>
                                {{ $crop->crop_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- အခြေအနေ Status -->
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">အခြေအနေ</label>
                    <select name="status" class="form-select">
                        <option value="">အားလုံး</option>
                        <option value="Stored" {{ request('status') === 'Stored' ? 'selected' : '' }}>သိမ်းဆည်းထားဆဲ</option>
                        <option value="Converted" {{ request('status') === 'Converted' ? 'selected' : '' }}>ဝယ်ယူပြီး</option>
                    </select>
                </div>

                <!-- အချိန်ကာလ Filter (တစ်ရက်, တစ်ပတ်, တစ်လ) -->
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">အချိန်ကာလ</label>
                    <select name="date_filter" class="form-select">
                        <option value="this_month" {{ request('date_filter', 'this_month') === 'this_month' ? 'selected' : '' }}>ယခုလ</option>
                        <option value="this_week" {{ request('date_filter') === 'this_week' ? 'selected' : '' }}>ဒီတစ်ပတ်</option>
                        <option value="today" {{ request('date_filter') === 'today' ? 'selected' : '' }}>ယနေ့</option>
                    </select>
                </div>

                <!-- တိကျသော ရက်စွဲအလိုက် (Start & End Date - နာရီမပါ) -->
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">ရက်စွဲအလိုက် (မှ - ထိ)</label>
                    <div class="input-group">
                        <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                        <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                    </div>
                </div>

                <!-- ခလုတ်များ -->
                <div class="col-12 text-end mt-3">
                    <button type="submit" class="btn btn-primary px-4 rounded-3 shadow-sm">
                        <i class="bi bi-filter me-1"></i> စစ်ထုတ်ရှာဖွေမည်
                    </button>
                    <a href="{{ route('crop#deposits#index') }}" class="btn btn-outline-secondary px-3 rounded-3">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> အစမူလအတိုင်း
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Deposits Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary small text-uppercase">
                        <tr>
                            <th class="py-3 px-4" style="width: 70px;">စဉ်</th>
                            <th class="py-3">ရက်စွဲ</th>
                            <th class="py-3">တောင်သူအမည်</th>
                            <th>သီးနှံ / အမျိုးအစား</th>
                            <th>အရေအတွက် / ယူနစ်</th>
                            <th class="py-3">ကားနံပါတ်</th>
                            <th class="py-3">အခြေအနေ</th>
                            <th class="py-3 text-end px-4">လုပ်ဆောင်ချက်</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deposits as $index => $deposit)
                            <tr>
                                <!-- စဉ် (Pagination တွက်ချက်ပြီး အစဉ်လိုက်ပြရန်) -->
                                <td class="px-4 fw-bold text-secondary">
                                    {{ $deposits->firstItem() + $index }}
                                </td>
                                <td>{{ date('d-m-Y', strtotime($deposit->deposit_date)) }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $deposit->farmer_name }}</div>
                                    <div class="small text-muted">{{ $deposit->farmer_phone ?? 'ဖုန်းမရှိ' }} ({{ $deposit->farmer_village ?? 'ရွာမရှိ' }})</div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1 py-1">
                                        @foreach($deposit->items as $item)
                                            <div class="small">
                                                <span class="fw-bold text-dark">
                                                    {{ $item->crop_name }}
                                                </span>
                                                <span class="badge bg-light text-dark border">
                                                    {{ $item->grade_name }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1 py-1">
                                        @foreach($deposit->items as $item)
                                            @php
                                                $rawUnit = strtolower($item->unit ?? '');
                                                $displayUnit = $rawUnit === 'viss'
                                                    ? 'ပိဿာ'
                                                    : ($rawUnit === 'pound' ? 'ပေါင်' : $item->unit);
                                            @endphp

                                            <div class="small">
                                                <strong class="text-success">
                                                    {{ number_format($item->quantity, 2) }}
                                                </strong>
                                                <span class="text-muted">{{ $displayUnit }}</span>

                                                @if($rawUnit === 'pound' && $item->bag_count > 0)
                                                    <span class="text-primary">
                                                        ({{ $item->bag_count }} အိတ်)
                                                    </span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td>{{ $deposit->car_no ?? '-' }}</td>
                                <td>
                                    @if($deposit->status === 'Stored')
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill fw-semibold">သိမ်းဆည်းထားဆဲ</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold">ဝယ်ယူပြီး (Converted)</span>
                                    @endif
                                </td>
                                <td class="text-end px-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        @if($deposit->status === 'Stored')
                                            <!-- Convert လုပ်ရန် ခလုတ် -->
                                            <a href="{{ route('crop#deposits#convert#form', $deposit->id) }}" class="btn btn-success btn-sm rounded-3 px-3 shadow-sm" title="ဝယ်ယူမှုဘောင်ချာသို့ ပြောင်းရန်">
                                                <i class="bi bi-arrow-repeat me-1"></i> ဘောင်ချာပြောင်းမည်
                                            </a>

                                            <button type="button" onclick="DeleteData({{ $deposit->id }})"
                                                class=" btn btn-sm btn-outline-danger " title="ဖျက်ရန်">
                                                ဖျက်ရန်
                                            </button>
                                        @else
                                            <span class="text-muted small">ပြီးစီးပါပြီ<i class="bi bi-correct me-1"></i></span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                    သီးနှံအပ်နှံမှု စာရင်း မရှိသေးပါ။
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($deposits->hasPages())
            <div class="card-footer bg-white py-3 px-4">
                {{ $deposits->withQueryString()->links() }}
            </div>
        @endif
    </>
</div>
@endsection
@section('DeleteData')
    <script>
        function DeleteData(id) {
            Swal.fire({
                title: "အပ်နှံဘောင်ချာကို ဖျက်ရန် သေချာပါသလား?",
                text: "ဖျက်ပြီးပါက ဤအပ်နှံဘောင်ချာအချက်အလက်ကို ပြန်လည်ရယူ၍ မရတော့ပါ။",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ဖျက်မည်",
                cancelButtonText: "မဖျက်တော့ပါ"
            }).then((result) => {
                if (result.isConfirmed) Swal.fire({
                    title: "ဖျက်ပြီးပါပြီ!",
                    text: "အပ်နှံဘောင်ချာကို အောင်မြင်စွာ ဖျက်ပြီးပါပြီ။",
                    icon: "success"
                }).then(() => {
                    location.href = '/whole-sale-market/crop-diposit/delete/' + id;
                });
            });
        }
    </script>
@endsection
