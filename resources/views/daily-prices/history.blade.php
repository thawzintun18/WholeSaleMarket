@extends('layouts.app')

@section('page-title', 'Crops')
@section('breadcrumb')
    ဈေးနှုန်းများ / နေ့စဉ်သီးနှံဈေးနှုန်းများ / သီးနှံဈေးနှုန်းပြင်ဆင်မှုစာရင်း
@endsection

@section('content')

    <div class="d-md-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="page-title mb-1">သီးနှံဈေးနှုန်းပြင်ဆင်မှုစာရင်း</h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                သီးနှံဈေးနှုန်းအချက်အလက် အဟောင်းနှင့် အသစ်များကို ကြည့်ရှုနိုင်ပါသည်။
            </p>
        </div>

        <div class=" d-flex justify-content-end mt-md-0 mt-2">
            <a href="{{ route('DailyPrice#list') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>

    </div>


    {{-- Search --}}
    <div class="dashboard-card mb-4 shadow-sm">

        <form action="{{ route('DailyPrice#history') }}" method="GET">
            <div class="row g-3">

                <div class="col-md-3">
                    <label class="form-label">ရက်စွဲဖြင့်ရှာဖွေရန်</label>

                    <input type="date" name="changed_date" class="form-control"
                        value="{{ request('second_date', now()->subDay()->toDateString()) }}" required>
                </div>

                {{-- သီးနှံ --}}
                <div class="col-md-3">
                    <label class="form-label">သီးနှံအမျိုးအစား</label>

                    <select name="crop_name" class="form-select">
                        <option value="">သီးနှံအားလုံး</option>

                        @foreach ($crop_name as $item)
                            <option value="{{ $item->crop_name }}" @selected(request('crop_name') === $item->crop_name)>
                                {{ $item->crop_name }}
                            </option>
                        @endforeach
                    </select>

                </div>

                {{-- Grade --}}
                <div class="col-md-3">
                    <label class="form-label">အရည်အသွေးအဆင့်</label>

                    <select name="grade_name" class="form-select">
                        <option value="">သီးနှံအရည်အသွေး</option>

                        @foreach ($grade_name as $item)
                            <option value="{{ $item->grade_name }}" @selected(request('grade_name') === $item->grade_name)>
                                {{ $item->grade_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="col-12 d-md-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>
                        ရှာဖွေရန်
                    </button>

                    <a href="{{ route('DailyPrice#history') }}" class="btn btn-outline-secondary mt-md-0 mt-3">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        ပြန်လည်သတ်မှတ်ရန်
                    </a>
                </div>

            </div>
        </form>

    </div>


    {{-- Crop Table --}}
    <div class="dashboard-card shadow-sm">

        <div class="d-md-flex justify-content-between align-items-center mb-3">

            <div>
                <h5 class="mb-1">
                    သီးနှံဈေးနှုန်းပြင်ဆင်မှုစာရင်း
                </h5>

                <small class="text-muted" style="line-height: 30px">
                    ပြင်ဆင်ထားသော သီးနှံဈေးနှုန်းအားလုံး
                </small>
            </div>

            <div class=" d-flex justify-content-end mt-md-0 mt-2">
                <div class=" me-2">
                    <span class="badge bg-light text-dark border">
                        {{ $dailyPrice->count() }} သီးနှံဈေးနှုန်း
                    </span>
                </div>
            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>
                        <th rowspan="2">စဉ်</th>
                        <th colspan="3" class=" text-center">အရင်အချက်အလက်</th>
                        <th colspan="3" class=" text-center text-success">ပြင်ဆင်ပြီးအချက်အလက်</th>
                        <th rowspan="2" class=" text-info">ပြင်ဆင်ချိန်</th>
                    </tr>

                    <tr>
                        <th>သီးနှံအမည်</th>
                        <th>အရည်အသွေး</th>
                        <th>သီးနှံဈေးနှုန်း (ကျပ်)</th>

                        <th class=" text-success">သီးနှံအမည်</th>
                        <th class=" text-success">အရည်အသွေး</th>
                        <th class=" text-success">သီးနှံဈေးနှုန်း (ကျပ်)</th>
                    </tr>

                </thead>


                <tbody>

                    @if ($dailyPrice->count() > 0)

                        @foreach ($dailyPrice as $history)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                {{-- Old Data --}}
                                <td>{{ $history->crop_name }}</td>
                                <td>{{ $history->grade_name }}</td>
                                <td>{{ $history->old_reference_buy_price }}</td>

                                {{-- New Data --}}
                                <td class="text-success fw-semibold">
                                    {{ $history->crop_name }}
                                </td>
                                <td class="text-success fw-semibold">
                                    {{ $history->grade_name }}
                                </td>
                                <td class="text-success fw-semibold">
                                    {{ $history->new_reference_buy_price }}
                                </td>

                                <td>
                                    <div>{{ \Carbon\Carbon::parse($history->changed_at)->format('d-m-Y') }}</div>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($history->changed_at)->format('h:i A') }}
                                    </small>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10" class="empty-state-cell">

                                <div class="empty-state">

                                    <h5>ပြင်ဆင်မှုမှတ်တမ်း မရှိသေးပါ</h5>

                                    <p>
                                        သီးနှံဈေးနှုန်းကို Update လုပ်ထားသော မှတ်တမ်း မရှိသေးပါ။
                                    </p>

                                </div>

                            </td>
                        </tr>
                    @endif

                </tbody>

            </table>

        </div>

        {{-- Pagination Example --}}
        <div class="d-md-flex justify-content-between align-items-center mt-4">

            <p class="text-muted">
                သီးနှံဈေးနှုန်း {{ $dailyPrice->firstItem() ?? 0 }}
                မှ {{ $dailyPrice->lastItem() ?? 0 }}
                အထိ ပြသထားပြီး
                စုစုပေါင်း {{ $dailyPrice->total() }} မျိုး ရှိပါသည်။
            </p>

            <nav class=" d-flex justify-content-end">

                <ul class="pagination pagination-sm mb-0">

                    <span> {{ $dailyPrice->links() }} </span>

                </ul>

            </nav>

        </div>

    </div>

@endsection


@section('DeleteData')
    <script>
        function DeleteData(id) {
            Swal.fire({
                title: "ဖျက်ရန် သေချာပါသလား?",
                text: "ဖျက်ပြီးပါက ဤသီးနှံအချက်အလက်ကို ပြန်လည်ရယူ၍ မရတော့ပါ။",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ဖျက်မည်",
                cancelButtonText: "မဖျက်တော့ပါ"
            }).then((result) => {
                if (result.isConfirmed) Swal.fire({
                    title: "ဖျက်ပြီးပါပြီ!",
                    text: "သီးနှံအချက်အလက်ကို အောင်မြင်စွာ ဖျက်ပြီးပါပြီ။",
                    icon: "success"
                }).then(() => {
                    location.href = '/whole-sale-market/Crop/delete/' + id;
                });

            });
        }
    </script>
@endsection
