@extends('layouts.app')

@section('page-title', 'Crops')
@section('breadcrumb')
    အခြေခံစာရင်းများ / သီးနှံအရည်အသွေးစာရင်း / သီးနှံအရည်အသွေးပြင်ဆင်မှုစာရင်း
@endsection

@section('content')

    <div class="d-md-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="page-title mb-1">သီးနှံအရည်အသွေးပြင်ဆင်မှုစာရင်း</h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                သီးနှံအရည်အသွေးအချက်အလက် အဟောင်းနှင့် အသစ်များကို ကြည့်ရှုနိုင်ပါသည်။
            </p>
        </div>

        <div class=" d-flex justify-content-end mt-md-0 mt-2">
            <a href="{{ route('Quality#list') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>

    </div>


    {{-- Search --}}
    <div class="dashboard-card mb-4 shadow-sm">

        <form action="{{ route('Quality#history') }}" method="GET">
            <div class="row g-3">

                <div class="col-md-8">
                    <label class="form-label">သီးနှံနှင့် ရက်စွဲ ရှာဖွေရန်</label>

                    <div class="d-flex">
                        <select name="crop_id" id="cropSearch" class="form-select w-50 me-2">
                            <option value="">သီးနှံအားလုံး</option>

                            @foreach ($crop_name as $item)
                                <option value="{{ $item->crop_id }}"
                                    {{ request('crop_id') == $item->crop_id ? 'selected' : '' }}>
                                    {{ $item->crop_name }}
                                </option>
                            @endforeach
                        </select>

                        <input type="date" id="dateSearch" name="changed_date" class="form-control w-50"
                            value="{{ request('changed_date', now()->subDay()->toDateString()) }}">
                    </div>
                </div>

                <div class="col-md-auto d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>
                        ရှာဖွေမည်
                    </button>
                </div>

                <div class="col-md-auto d-flex align-items-end">
                    <a href="{{ route('Quality#history') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        ပြန်လည်သတ်မှတ်မည်
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
                    သီးနှံအရည်အသွေးပြင်ဆင်မှုစာရင်း
                </h5>

                <small class="text-muted" style="line-height: 30px">
                    ပြင်ဆင်ထားသော သီးနှံအရည်အသွေးအားလုံး
                </small>
            </div>

            <div class=" d-flex justify-content-end mt-md-0 mt-2">
                <div class=" me-2">
                    <span class="badge bg-light text-dark border">
                        {{ $histories->count() }} အရည်အသွေး
                    </span>
                </div>
            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>
                        <th rowspan="2">စဉ်</th>
                        <th colspan="2" class=" text-center">အရင်အချက်အလက်</th>
                        <th colspan="2" class=" text-center">ပြင်ဆင်ပြီးအချက်အလက်</th>
                        <th rowspan="2">ပြင်ဆင်ချိန်</th>
                    </tr>

                    <tr>
                        <th>သီးနှံအမည်</th>
                        <th>အရည်အသွေး</th>

                        <th>သီးနှံအမည်</th>
                        <th>အရည်အသွေး</th>
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
                            <td colspan="8" class="empty-state-cell">

                                <div class="empty-state">

                                    <h5>ပြင်ဆင်မှုမှတ်တမ်း မရှိသေးပါ</h5>

                                    <p>
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
        <div class="d-md-flex justify-content-between align-items-center mt-4">

            <p class="text-muted">
                သီးနှံအရည်အသွေး {{ $histories->firstItem() ?? 0 }}
                မှ {{ $histories->lastItem() ?? 0 }}
                အထိ ပြသထားပြီး
                စုစုပေါင်း {{ $histories->total() }} မျိုး ရှိပါသည်။
            </p>

            <nav class=" d-flex justify-content-end">

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
