@extends('layouts.app')

@section('page-title', 'Crops')
@section('breadcrumb')
    အခြေခံစာရင်းများ / သီးနှံအရည်အသွေးစာရင်း / သီးနှံအရည်အသွေးပြင်ဆင်မှုစာရင်း
@endsection

@section('content')

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
        <div>
            <h1 class="page-title mb-1 fs-3 fs-md-2">သီးနှံအရည်အသွေးပြင်ဆင်မှုစာရင်း</h1>

            <p class="text-muted mb-0 mt-1" style="line-height: 1.5">
                သီးနှံအရည်အသွေးအချက်အလက် အဟောင်းနှင့် အသစ်များကို ကြည့်ရှုနိုင်ပါသည်။
            </p>
        </div>

        <div class="d-flex justify-content-end w-100 w-md-auto">
            <a href="{{ route('Quality#list') }}" class="btn rounded-pill px-4 shadow-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>

    </div>


    {{-- Search --}}
    <div class="dashboard-card mb-3 shadow-sm p-3 p-md-4">

        <form action="{{ route('Quality#history') }}" method="GET">
            <div class="row align-items-end g-2">

                <div class="col-12 col-lg-8">
                    <label class="form-label mb-1">သီးနှံနှင့် ရက်စွဲ ရှာဖွေရန်</label>

                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <select name="crop_id" id="cropSearch" class="form-select w-100 w-sm-50">
                            <option value="">သီးနှံအားလုံး</option>

                            @foreach ($crop_name as $item)
                                <option value="{{ $item->crop_id }}"
                                    {{ request('crop_id') == $item->crop_id ? 'selected' : '' }}>
                                    {{ $item->crop_name }}
                                </option>
                            @endforeach
                        </select>

                        <input type="date" id="dateSearch" name="changed_date" class="form-control w-100 w-sm-50"
                            value="{{ request('changed_date', now()->subDay()->toDateString()) }}">
                    </div>
                </div>

                <div class="col-12 col-lg-4 d-flex flex-wrap gap-2 mt-2 mt-lg-0">
                    <button type="submit" class="btn btn-primary flex-fill flex-lg-grow-0 rounded-pill shadow-sm">
                        <i class="bi bi-search me-1"></i>
                        ရှာဖွေမည်
                    </button>

                    <a href="{{ route('Quality#history') }}" class="btn btn-outline-secondary text-center flex-fill flex-lg-grow-0 rounded-pill shadow-sm">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        ပြန်လည်သတ်မှတ်မည်
                    </a>
                </div>

            </div>
        </form>

    </div>


    {{-- Crop Table --}}
    <div class="dashboard-card shadow-sm p-3 p-md-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">

            <div>
                <h5 class="mb-1">
                    သီးနှံအရည်အသွေးပြင်ဆင်မှုစာရင်း
                </h5>

                <small class="text-muted d-block mt-1" style="line-height: 1.5">
                    ပြင်ဆင်ထားသော သီးနှံအရည်အသွေးအားလုံး
                </small>
            </div>

            <div class="d-flex justify-content-end">
                <div>
                    <span class="badge bg-light text-dark border px-3 py-2">
                        {{ $histories->count() }} အရည်အသွေး
                    </span>
                </div>
            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0 text-nowrap">

                <thead>

                    <tr>
                        <th rowspan="2">စဉ်</th>
                        <th colspan="2" class="text-center">အရင်အချက်အလက်</th>
                        <th colspan="2" class="text-center text-success">ပြင်ဆင်ပြီးအချက်အလက်</th>
                        <th rowspan="2" class="text-info">ပြင်ဆင်ချိန်</th>
                    </tr>

                    <tr>
                        <th>သီးနှံအမည်</th>
                        <th>အရည်အသွေး</th>

                        <th class="text-success">သီးနှံအမည်</th>
                        <th class="text-success">အရည်အသွေး</th>
                    </tr>

                </thead>


                <tbody>

                    @if ($histories->count() > 0)

                        @foreach ($histories as $history)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                {{-- Old Data --}}
                                <td>{{ $history->crop_name }}</td>
                                <td>{{ $history->old_grade_name }}</td>

                                {{-- New Data --}}
                                <td class="text-success fw-semibold">
                                    {{ $history->crop_name }}
                                </td>
                                <td class="text-success fw-semibold">
                                    {{ $history->new_grade_name }}
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
                            <td colspan="6" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <h5 class="mb-1 text-secondary fw-bold">ပြင်ဆင်မှုမှတ်တမ်း မရှိသေးပါ</h5>
                                    <p class="text-muted mb-0">
                                        သီးနှံအရည်အသွေးကို Update လုပ်ထားသော မှတ်တမ်း မရှိသေးပါ။
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endif

                </tbody>

            </table>

        </div>

        {{-- Pagination Example --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 gap-3">

            <p class="text-muted mb-0 text-center text-md-start">
                သီးနှံအရည်အသွေး {{ $histories->firstItem() ?? 0 }}
                မှ {{ $histories->lastItem() ?? 0 }}
                အထိ ပြသထားပြီး
                စုစုပေါင်း {{ $histories->total() }} မျိုး ရှိပါသည်။
            </p>

            <nav class="d-flex justify-content-end">
                <ul class="pagination pagination-sm mb-0">
                    <span> {{ $histories->links() }} </span>
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
