@extends('layouts.app')

@section('page-title', 'Crops')
@section('breadcrumb')
    အခြေခံစာရင်းများ / သီးနှံစာရင်း
@endsection

@section('content')
    <div class="container-fluid p-0">
        @include('partials.crop-grade-dailyPrice-tabs')
        <!-- Header Section -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
            <div>
                <h1 class="page-title mb-1 fs-3 fs-md-2">သီးနှံစာရင်း</h1>
                <p class="text-muted mb-0 mt-1" style="line-height: 1.5">
                    ပွဲရုံမှ လက်ခံဝယ်ယူမည့် သီးနှံအမျိုးအစားများနှင့် သက်ဆိုင်သော အချက်အလက်များကို ဤစာရင်းတွင် ကြည့်ရှုနိုင်ပါသည်။
                </p>
            </div>

            <div class="d-flex justify-content-end w-100 w-md-auto">
                <a href="{{ route('Crop#directPage') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i>
                    သီးနှံထပ်ထည့်ရန်
                </a>
            </div>
        </div>

        {{-- Search --}}
        <div class="dashboard-card mb-3 shadow-sm p-3 p-md-4">
            <form action="{{ route('Crop#list') }}" method="GET">
                <div class="row align-items-end g-2">

                    <div class="col-12 col-md-6">
                        <label class="form-label mb-1">
                            သီးနှံရှာဖွေရန်
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="searchData" value="{{ request('searchData') }}" id="cropSearch"
                                class="form-control p-2" placeholder="သီးနှံအမည် ရိုက်ထည့်ပါ...">
                        </div>
                    </div>

                    <div class="col-6 col-md-auto">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm">
                            <i class="bi bi-search me-1"></i>
                            ရှာဖွေမည်
                        </button>
                    </div>

                    <div class="col-6 col-md-auto">
                        <a href="{{ route('Crop#list') }}" class="btn btn-outline-secondary w-100 text-center rounded-pill shadow-sm">
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
                        သီးနှံစာရင်း
                    </h5>
                    <small class="text-muted d-block mt-1" style="line-height: 1.5">
                        စာရင်းသွင်းထားသော သီးနှံအားလုံး
                    </small>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <span class="badge bg-light text-dark border px-3 py-2">
                        {{ $crops->count() }} သီးနှံ
                    </span>
                    <a href="{{ route('Crop#history') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">သီးနှံပြင်ဆင်မှုစာရင်း</a>
                    <a href="{{ route('Crop#trashList') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm">ဖျက်ထားသောသီးနှံစာရင်း</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead>
                        <tr>
                            <th>စဉ်</th>
                            <th>သီးနှံအမည်</th>
                            <th>ပွဲခ</th>
                            <th>တစ်တင်းပါ အရေအတွက်</th>
                            <th width="150" class="text-center">လုပ်ဆောင်ချက်</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($crops->count() > 0)
                            @foreach ($crops as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        <strong> {{ $item->crop_name }} </strong>
                                    </td>

                                    <td>
                                        <span class="fs-6 fw-bold text-success">
                                            {{ $item->commission_amount }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $item->quantity_per_basket }}
                                        @if ($item->unit == 'viss')
                                            ပိဿာ
                                        @else
                                            ပေါင်
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('Crop#edit', $item->id) }}"
                                                class="btn btn-sm btn-outline-dark" title="ပြင်ဆင်ရန်">
                                                ပြင်ရန်
                                            </a>
                                            <button type="button" onclick="DeleteData({{ $item->id }})"
                                                class="btn btn-sm btn-outline-danger" title="ဖျက်ရန်">
                                                ဖျက်ရန်
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <h5 class="mb-1 text-secondary fw-bold">သီးနှံစာရင်း မရှိသေးပါ</h5>
                                        <p class="text-muted mb-0">
                                            သီးနှံကို ထည့်သွင်းထားသော မှတ်တမ်း မရှိသေးပါ။
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
                    သီးနှံ {{ $crops->firstItem() ?? 0 }}
                    မှ {{ $crops->lastItem() ?? 0 }}
                    အထိ ပြသထားပြီး
                    စုစုပေါင်း {{ $crops->total() }} မျိုး ရှိပါသည်။
                </p>

                <nav class="d-flex justify-content-end">
                    <ul class="pagination pagination-sm mb-0">
                        <span> {{ $crops->links() }} </span>
                    </ul>
                </nav>
            </div>

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
