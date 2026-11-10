@extends('layouts.app')

@section('page-title', 'Crops')
@section('breadcrumb')
    အခြေခံစာရင်းများ / သီးနှံစာရင်း / ဖျက်ထားသော သီးနှံစာရင်း
@endsection

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
        <div>
            <h1 class="page-title mb-1 fs-3 fs-md-2">ဖျက်ထားသော သီးနှံစာရင်း</h1>

            <p class="text-muted mb-0 mt-1" style="line-height: 1.5">
                ဖျက်ထားသော သီးနှံများကို ဤနေရာတွင် ကြည့်ရှုနိုင်ပြီး လိုအပ်ပါက ပြန်လည်အသုံးပြုနိုင်ပါသည်။
                ပြန်လည်အသုံးပြုထားသော သီးနှံများသည် မူရင်းသီးနှံစာရင်းတွင် ပြန်လည်ပေါ်လာမည်ဖြစ်သည်။
            </p>
        </div>

        <div class="d-flex justify-content-end w-100 w-md-auto">
            <a href="{{ route('Crop#list') }}" class="btn rounded-pill px-4 shadow-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>

    </div>

    {{-- Search --}}
    <div class="dashboard-card mb-3 shadow-sm p-3 p-md-4">
        <form action="{{ route('Crop#trashList') }}" method="GET">
            <div class="row align-items-end g-2">

                <div class="col-12 col-lg-8">
                    <label class="form-label mb-1">သီးနှံနှင့် ရက်စွဲ ရှာဖွေရန်</label>

                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <select name="crop_name" id="cropSearch" class="form-select w-100 w-sm-50">
                            <option value="">သီးနှံအားလုံး</option>

                            @foreach ($crop_name as $item)
                                <option value="{{ $item->crop_name }}"
                                    {{ request('crop_name') == $item->crop_name ? 'selected' : '' }}>
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

                    <a href="{{ route('Crop#trashList') }}" class="btn btn-outline-secondary text-center flex-fill flex-lg-grow-0 rounded-pill shadow-sm">
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
                    ဖျက်ထားသော သီးနှံစာရင်း
                </h5>

                <small class="text-muted d-block mt-1" style="line-height: 1.5">
                    ဖျက်ထားသော သီးနှံများကို ကြည့်ရှုပြီး လိုအပ်ပါက ပြန်လည်အသုံးပြုနိုင်ပါသည်။
                </small>
            </div>

            <div class="d-flex justify-content-end">
                <div>
                    <span class="badge bg-light text-dark border px-3 py-2">
                        {{ $crops->count() }} သီးနှံ
                    </span>
                </div>
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
                                    <span class="fs-6">
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
                                    <a href="{{ route('Crop#restore', $item->id) }}"
                                        class="btn btn-sm btn-outline-primary" title="ပြန်လည်အသုံးပြုရန်">
                                        ပြန်လည်အသုံးပြုမည်
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <h5 class="mb-1 text-secondary fw-bold">ဖျက်ထားသော သီးနှံစာရင်း မရှိသေးပါ</h5>
                                    <p class="text-muted mb-0">
                                        သီးနှံကို ဖျက်ထားသော မှတ်တမ်း မရှိသေးပါ။
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
