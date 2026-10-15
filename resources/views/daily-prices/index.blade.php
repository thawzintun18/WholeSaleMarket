@extends('layouts.app')

@section('page-title', 'Daily Prices')

@section('breadcrumb', 'ဈေးနှုန်းများ / နေ့စဉ်သီးနှံဈေးနှုန်းများ')

@section('content')

    <div class="d-md-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="page-title mb-1">နေ့စဉ်သီးနှံဈေးနှုန်းများ</h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                သီးနှံအမျိုးအစားအလိုက် နေ့စဉ်သတ်မှတ်ထားသော ဈေးနှုန်းများကို ကြည့်ရှုနိုင်ပြီး ဈေးကွက်အခြေအနေများကို အလွယ်တကူ
                လေ့လာနိုင်ပါသည်။
            </p>
        </div>

        <div class="d-flex justify-content-end">
            <a href="{{ route('DailyPrice#directPage') }}" class="btn btn-success mt-md-0 mt-3">
                <i class="bi bi-plus-lg me-1"></i>
                နေ့စဉ်သီးနှံဈေးနှုန်းများထပ်ထည့်ရန်
            </a>
        </div>

    </div>

    {{-- Filters --}}

    <div class="dashboard-card mb-4 shadow-sm">

        <form action="{{ route('DailyPrice#list') }}" method="GET">
            <div class="row g-3">

                {{-- ပထမရက်စွဲ --}}
                <div class="col-md-3">
                    <label class="form-label">ပထမရက်စွဲ</label>

                    <input type="date" name="first_date" class="form-control"
                        value="{{ request('first_date', now()->subDay()->toDateString()) }}" required>
                </div>

                {{-- ဒုတိယရက်စွဲ --}}
                <div class="col-md-3">
                    <label class="form-label">ဒုတိယရက်စွဲ</label>

                    <input type="date" name="second_date" class="form-control"
                        value="{{ request('second_date', now()->toDateString()) }}" required>
                </div>

                {{-- သီးနှံ --}}
                <div class="col-md-3">
                    <label class="form-label">သီးနှံအမျိုးအစား</label>

                    <select name="crop_id" class="form-select">
                        <option value="">သီးနှံအားလုံး</option>

                        @foreach ($crop_names as $item)
                            <option value="{{ $item->id }}" @selected((string) request('crop_id') === (string) $item->id)>
                                {{ $item->crop_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Grade --}}
                <div class="col-md-3">
                    <label class="form-label">အရည်အသွေးအဆင့်</label>

                    <select name="grade_name" class="form-select">
                        <option value="">အဆင့်အားလုံး</option>

                        @foreach ($grade_names as $item)
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

                    <a href="{{ route('DailyPrice#list') }}" class="btn btn-outline-secondary mt-md-0 mt-3">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        ပြန်လည်သတ်မှတ်ရန်
                    </a>
                </div>

            </div>
        </form>



    </div>

    {{-- Daily Price Table --}}

    <div class="dashboard-card shadow-sm">
        <div class="d-md-flex justify-content-between align-items-center mb-3">

            <div>

                <h5 class="mb-1">
                    နေ့စဉ်သီးနှံဈေးနှုန်းများ
                </h5>

                <small class="text-muted mt-2" style="line-height: 30px">
                    သီးနှံနှင့် အရည်အသွေးအဆင့်အလိုက် ရည်ညွှန်းဝယ်ယူဈေးနှုန်းများ
                </small>

            </div>

            <div class="d-md-flex justify-content-end mt-md-0 mt-2">

                <div class="me-2">
                    <span class="badge bg-light text-dark border">
                        {{ $dailyPrice->count() }} နေ့စဉ်သီးနှံဈေးနှုန်း
                    </span>
                </div>

                <div class="d-md-flex mt-md-0 mt-2">
                    <div class=" me-2">
                        <a href="{{ route('DailyPrice#history') }}"
                            class=" btn btn-primary">သီးနှံဈေးနှုန်းပြင်ဆင်မှုစာရင်း</a>
                    </div>

                    <div class=" me-2">
                        <a href="{{ route('DailyPrice#trashList') }}"
                            class=" btn btn-primary mt-md-0 mt-2">ဖျက်ထားသောသီးနှံဈေးနှုန်းစာရင်း</a>
                    </div>
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

                        <th>မနေ့ကဈေး (ကျပ်)</th>

                        <th>ဒီနေ့ဈေး (ကျပ်)</th>

                        <th>ဈေးနှုန်းပြောင်းလဲမှု</th>

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


                                {{-- မနေ့ကဈေး --}}
                                <td>
                                    @if ($item->yesterday_price !== null)
                                        {{ number_format((float) $item->yesterday_price, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- ဒီနေ့ဈေး --}}
                                <td>
                                    @if ($item->today_price !== null)
                                        {{ number_format((float) $item->today_price, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- ဈေးတက်၊ ဈေးကျ --}}
                                <td>
                                    @if ($item->price_status === 'တက်')
                                        <span class="text-dark fw-bold">
                                            ▲ ဈေးတက်
                                            (+{{ number_format($item->price_difference, 2) }} ကျပ်)
                                        </span>
                                    @elseif ($item->price_status === 'ကျ')
                                        <span class="text-dark fw-bold">
                                            ▼ ဈေးကျ
                                            ({{ number_format($item->price_difference, 2) }} ကျပ်)
                                        </span>
                                    @elseif ($item->price_status === 'မပြောင်း')
                                        <span class="text-dark">
                                            — ဈေးမပြောင်းပါ
                                        </span>
                                    @else
                                        <span class="text-secondary">
                                            နှိုင်းယှဉ်ရန် ဈေးမရှိပါ
                                        </span>
                                    @endif
                                </td>


                                <td class="text-center">

                                    <div class=" d-flex mt-md-0">
                                        <a href="{{ route('DailyPrice#edit', $item->id) }}"
                                            class="btn btn-sm btn-outline-dark btn-edit me-1 " title="ပြင်ဆင်ရန်">
                                            ပြင်ဆင်ရန်
                                        </a>

                                        <button type="button" onclick="DeleteData({{ $item->id }})"
                                            class=" btn btn-sm btn-outline-danger " title="ဖျက်ရန်">
                                            ဖျက်ရန်
                                        </button>
                                    </div>

                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="empty-state-cell">

                                <div class="empty-state">

                                    <h5>နေ့စဉ်သီးနှံဈေးနှုန်းများ မရှိသေးပါ</h5>

                                    <p>
                                        လက်ရှိတွင် နေ့စဉ်သီးနှံဈေးနှုန်းများ ထည့်သွင်းထားခြင်းမရှိသေးပါ။
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
                text: "ဖျက်ပြီးပါက ဤသီးနှံဈေးနှုန်းအချက်အလက်ကို ပြန်လည်ရယူ၍ မရတော့ပါ။",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ဖျက်မည်",
                cancelButtonText: "မဖျက်တော့ပါ"
            }).then((result) => {
                if (result.isConfirmed) Swal.fire({
                    title: "ဖျက်ပြီးပါပြီ!",
                    text: "သီးနှံဈေးနှုန်းအချက်အလက်ကို အောင်မြင်စွာ ဖျက်ပြီးပါပြီ။",
                    icon: "success"
                }).then(() => {
                    location.href = '/whole-sale-market/DailyPrice/delete/' + id;
                });

            });
        }
    </script>
@endsection
