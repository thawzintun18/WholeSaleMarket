@extends('layouts.app')

@section('page-title', 'Crops')
@section('breadcrumb')
    အခြေခံစာရင်းများ / သီးနှံစာရင်း
@endsection


@section('content')

    <div class="d-md-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="page-title mb-1">သီးနှံစာရင်း</h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                ပွဲရုံမှ လက်ခံဝယ်ယူမည့် သီးနှံအမျိုးအစားများနှင့် သက်ဆိုင်သော အချက်အလက်များကို ဤစာရင်းတွင်
                ကြည့်ရှုနိုင်ပါသည်။
            </p>
        </div>

        <a href="{{ route('Crop#directPage') }}" class="btn btn-success mt-md-0 mt-3">
            <i class="bi bi-plus-lg me-1"></i>
            သီးနှံထပ်ထည့်ရန်
        </a>

    </div>


    {{-- Search --}}
    <div class="dashboard-card mb-4 shadow-sm">
        <form action="{{ route('Crop#list') }}" method="GET">
            <div class="row g-3">

                <div class="col-md-6">

                    <label class="form-label">
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

                <div class="col-md-auto d-flex align-items-end">

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>
                        ရှာဖွေမည်
                    </button>

                </div>

                <div class="col-md-auto d-flex align-items-end">

                    <button class="btn btn-outline-secondary" onclick="resetSearch()">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        ပြန်လည်သတ်မှတ်မည်
                    </button>

                </div>

            </div>
        </form>

    </div>


    {{-- Crop Table --}}
    <div class="dashboard-card shadow-sm">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>
                <h5 class="mb-1">
                    သီးနှံစာရင်း
                </h5>

                <small class="text-muted">
                    စာရင်းသွင်းထားသော သီးနှံအားလုံး
                </small>
            </div>

            <span class="badge bg-light text-dark border">
                {{ $crops->count() }} သီးနှံ
            </span>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>

                        <th>
                            သီးနှံအမည်
                        </th>

                        <th>
                            ပွဲခ
                        </th>

                        <th>
                            တစ်တင်းပါ အရေအတွက်
                        </th>

                        <th width="150" class="text-center">
                            လုပ်ဆောင်ချက်
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @if ($crops->count() > 0)

                        @foreach ($crops as $item)
                            <tr>

                                <td>
                                    <strong> {{ $item->crop_name }} </strong>
                                </td>

                                <td>
                                    <span class="fs-5 fw-bold text-success">
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

                                    <div class=" d-md-flex pe-3">
                                        <div class="">
                                            <a href="" class="btn btn-sm btn-outline-primary me-1"
                                                title="ပြင်ဆင်ရန်">
                                                သီးနှံအရည်အသွေးထည့်ရန်
                                            </a>
                                        </div>

                                        <div class=" d-flex mt-md-0 mt-2">
                                            <a href="{{ route('Crop#edit', $item->id) }}"
                                                class="btn btn-sm btn-outline-dark btn-edit me-1 " title="ပြင်ဆင်ရန်">
                                                ပြင်ဆင်ရန်
                                            </a>

                                            <button type="button" onclick="DeleteData({{ $item->id }})"
                                                class=" btn btn-sm btn-outline-danger " title="ဖျက်ရန်">
                                                ဖျက်ရန်
                                            </button>
                                        </div>
                                    </div>

                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="text-center py-4">

                                        {{-- <i class="bi bi-people fs-1 text-muted"></i> --}}
                                        <p class="text-muted mt-2 mb-1">
                                            သီးနှံစာရင်း မတွေ့ရှိပါ။
                                        </p>
                                        <small class="text-muted">
                                            ရှာဖွေမှုကို ပြန်စစ်ပါ၊ သို့မဟုတ် သီးနှံအသစ် ထည့်ပါ။
                                        </small>
                                    </td>
                                </tr>

                            </td>
                        </tr>
                    @endif

                </tbody>

            </table>

        </div>

        {{-- Pagination Example --}}
        <div class="d-md-flex justify-content-between align-items-center mt-4">

            <p class="text-muted">
                သီးနှံ {{ $crops->firstItem() ?? 0 }}
                မှ {{ $crops->lastItem() ?? 0 }}
                အထိ ပြသထားပြီး
                စုစုပေါင်း {{ $crops->total() }} မျိုး ရှိပါသည်။
            </p>

            <nav class=" d-flex justify-content-end">

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
