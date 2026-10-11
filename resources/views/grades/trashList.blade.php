@extends('layouts.app')

@section('page-title', 'Crops')
@section('breadcrumb')
    အခြေခံစာရင်းများ / သီးနှံအရည်အသွေးစာရင်း / ဖျက်ထားသော သီးနှံအရည်အသွေးစာရင်း
@endsection

@section('content')

    <div class="row align-items-center mb-4">

        <div class=" col-md-10">
            <h1 class="page-title mb-1">ဖျက်ထားသော သီးနှံအရည်အသွေးစာရင်း</h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                ဖျက်ထားသော သီးနှံအရည်အသွေးများကို ဤနေရာတွင် ကြည့်ရှုနိုင်ပြီး လိုအပ်ပါက ပြန်လည်အသုံးပြုနိုင်ပါသည်။
                ပြန်လည်အသုံးပြုထားသော သီးနှံအရည်အသွေးများသည် မူရင်းသီးနှံအရည်အသွေးစာရင်းတွင် ပြန်လည်ပေါ်လာမည်ဖြစ်သည်။
            </p>
        </div>

        <div class=" col-md-2 d-flex justify-content-end mt-md-0 mt-2">
            <a href="{{ route('Quality#list') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>

    </div>


    {{-- Search --}}
    <div class="dashboard-card mb-4 shadow-sm">

        <form action="{{ route('Quality#trashList') }}" method="GET">
            <div class="row g-3">

                <div class="col-md-8">
                    <label class="form-label">သီးနှံနှင့် ရက်စွဲ ရှာဖွေရန်</label>

                    <div class="d-flex">
                        <select name="crop_name" id="cropSearch" class="form-select w-50 me-2">
                            <option value="">သီးနှံအားလုံး</option>

                            @foreach ($crop_name as $item)
                                <option value="{{ $item->crop_name }}"
                                    {{ request('crop_name') == $item->crop_name ? 'selected' : '' }}>
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
                    <a href="{{ route('Quality#trashList') }}" class="btn btn-outline-secondary">
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
                    ဖျက်ထားသော သီးနှံအရည်အသွေးစာရင်း
                </h5>

                <small class="text-muted" style="line-height: 30px">
                    ဖျက်ထားသော သီးနှံအရည်အသွေးများကို ကြည့်ရှုပြီး လိုအပ်ပါက ပြန်လည်အသုံးပြုနိုင်ပါသည်။
                </small>
            </div>

            <div class=" d-flex justify-content-end mt-md-0 mt-2">
                <div class=" me-2">
                    <span class="badge bg-light text-dark border">
                        {{ $grades->count() }} အရည်အသွေး
                    </span>
                </div>
            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>

                        <th>
                            စဉ်
                        </th>

                        <th>
                            သီးနှံအမည်
                        </th>

                        <th>
                            အရည်အသွေး
                        </th>

                        <th width="150" class="text-center">
                            လုပ်ဆောင်ချက်
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @if ($grades->count() > 0)

                        @foreach ($grades as $item)
                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <strong> {{ $item->crop_name }} </strong>
                                </td>

                                <td>
                                    <span class="fs-5">
                                        {{ $item->grade_name }}
                                    </span>
                                </td>

                                <td class="text-center">

                                    <a href="{{ route('Quality#restore', $item->id) }}"
                                        class="btn btn-sm btn-outline-primary me-1" title="ပြန်လည်အသုံးပြုရန်">
                                        ပြန်လည်အသုံးပြုမည်
                                    </a>

                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="empty-state-cell">

                                <div class="empty-state">

                                    <h5>ဖျက်ထားသော သီးနှံအရည်အသွေးစာရင်း မရှိသေးပါ</h5>

                                    <p>
                                        သီးနှံအရည်အသွေးကို ဖျက်ထားသော မှတ်တမ်း မရှိသေးပါ။
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
                သီးနှံအရည်အသွေး {{ $grades->firstItem() ?? 0 }}
                မှ {{ $grades->lastItem() ?? 0 }}
                အထိ ပြသထားပြီး
                စုစုပေါင်း {{ $grades->total() }} မျိုး ရှိပါသည်။
            </p>

            <nav class=" d-flex justify-content-end">

                <ul class="pagination pagination-sm mb-0">

                    <span> {{ $grades->links() }} </span>

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
