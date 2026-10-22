@extends('layouts.app')

@section('page-title', 'Crops')
@section('breadcrumb')
    အခြေခံစာရင်းများ / သီးနှံအရည်အသွေးစာရင်း / ဖျက်ထားသော နေ့စဉ်သီးနှံဈေးနှုန်းများစာရင်း
@endsection

@section('content')

    <div class="row align-items-center mb-4">

        <div class=" col-md-10">
            <h1 class="page-title mb-1">ဖျက်ထားသော နေ့စဉ်သီးနှံဈေးနှုန်းများစာရင်း</h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                ဖျက်ထားသော နေ့စဉ်သီးနှံဈေးနှုန်းများကို ဤနေရာတွင် ကြည့်ရှုနိုင်ပြီး လိုအပ်ပါက ပြန်လည်အသုံးပြုနိုင်ပါသည်။
                ပြန်လည်အသုံးပြုထားသော နေ့စဉ်သီးနှံဈေးနှုန်းများသည် မူရင်းနေ့စဉ်သီးနှံဈေးနှုန်းစာရင်းတွင်
                ပြန်လည်ပေါ်လာမည်ဖြစ်သည်။
            </p>
        </div>

        <div class=" col-md-2 d-flex justify-content-end mt-md-0 mt-2">
            <a href="{{ route('DailyPrice#list') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>

    </div>


    {{-- Search --}}
    <div class="dashboard-card mb-4 shadow-sm">

        <form action="{{ route('DailyPrice#trashList') }}" method="GET">
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

                    <a href="{{ route('DailyPrice#trashList') }}" class="btn btn-outline-secondary">
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
                    ဖျက်ထားသော နေ့စဉ်သီးနှံဈေးနှုန်းများစာရင်း
                </h5>

                <small class="text-muted" style="line-height: 30px">
                    ဖျက်ထားသော နေ့စဉ်သီးနှံဈေးနှုန်းများကို ကြည့်ရှုပြီး လိုအပ်ပါက ပြန်လည်အသုံးပြုနိုင်ပါသည်။
                </small>
            </div>

            <div class=" d-flex justify-content-end mt-md-0 mt-2">
                <div class=" me-2">
                    <span class="badge bg-light text-dark border">
                        {{ $dailyPrice->count() }} နေ့စဉ်သီးနှံဈေးနှုန်း
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
                            ဈေးနှုန်းသတ်မှတ်သည့်နေ့
                        </th>

                        <th>
                            သီးနှံအမည်
                        </th>

                        <th>
                            အရည်အသွေးအမည်
                        </th>

                        <th>
                            ရည်ညွှန်းဝယ်ဈေး (ကျပ်)
                        </th>

                        <th width="150" class="text-center">
                            လုပ်ဆောင်ချက်
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @if ($dailyPrice->count() > 0)

                        @foreach ($dailyPrice as $item)
                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ $item->price_date }}
                                </td>

                                <td>
                                    <strong> {{ $item->crop_name }} </strong>
                                </td>

                                <td>
                                    <span class="fs-5">
                                        {{ $item->grade_name }}
                                    </span>
                                </td>

                                <td>
                                    {{ $item->reference_buy_price }}
                                </td>

                                <td class="text-center">

                                    <a href="{{ route('DailyPrice#restore', $item->id) }}"
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

                                    <h5>ဖျက်ထားသော နေ့စဉ်သီးနှံဈေးနှုန်းစာရင်း မရှိသေးပါ</h5>

                                    <p>
                                        နေ့စဉ်သီးနှံဈေးနှုန်းများကို ဖျက်ထားသော မှတ်တမ်း မရှိသေးပါ။
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
                နေ့စဉ်သီးနှံဈေးနှုန်း {{ $dailyPrice->firstItem() ?? 0 }}
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
