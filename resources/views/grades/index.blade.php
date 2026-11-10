@extends('layouts.app')

@section('page-title', 'Grades')

@section('breadcrumb', 'အခြေခံစာရင်းများ / သီးနှံ အရည်အသွေးစာရင်း')

@section('content')
<div class="container-fluid p-0">

    @include('partials.crop-grade-dailyPrice-tabs')

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">

        <div>
            <h1 class="page-title mb-1 fs-3 fs-md-2">
                သီးနှံ အရည်အသွေးစာရင်း
            </h1>

            <p class="text-muted mb-0 mt-1" style="line-height: 1.5">
                သီးနှံတစ်မျိုးစီအတွက် အရည်အသွေးအဆင့်များကို ဤစာရင်းတွင်
                ကြည့်ရှုနိုင်ပါသည်။
            </p>
        </div>

        <div class="d-flex justify-content-end w-100 w-md-auto">
            <a href="{{ route('Quality#directPage') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-plus-lg me-1"></i>
                သီးနှံအရည်အသွေးထပ်ထည့်ရန်
            </a>
        </div>

    </div>

    {{-- Search & Filter --}}
    <div class="dashboard-card mb-3 shadow-sm p-3 p-md-4">
        <form action="{{ route('Quality#list') }}" method="GET">
            <div class="row align-items-end g-2">

                {{-- သီးနှံအလိုက် စစ်ထုတ်ရန် --}}
                <div class="col-12 col-md-4">
                    <label class="form-label mb-1">
                        သီးနှံအမည်
                    </label>
                    <select name="crop_id" class="form-select" onchange="this.form.submit()">
                        <option value="" {{ request('crop_id') == '' ? 'selected' : '' }}>
                            သီးနှံအားလုံးအတွက် ကြည့်ရန်
                        </option>

                        @foreach ($crops as $item)
                            <option value="{{ $item->id }}" {{ request('crop_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->crop_name }} သီးနှံအတွက် ကြည့်ရန်
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- အဆင့်အမည် ရှာဖွေရန် --}}
                <div class="col-12 col-md-4">
                    <label class="form-label mb-1">
                        အရည်အသွေး
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="searchData" value="{{ request('searchData') }}" id="search"
                            class="form-control" placeholder="အရည်အသွေး ရိုက်ထည့်ပါ...">
                    </div>
                </div>

                {{-- ရှာဖွေရန် ခလုတ်နှင့် ပြန်လည်သတ်မှတ်ရန် --}}
                <div class="col-12 col-md-4 d-flex flex-wrap gap-2 mt-2 mt-md-0">
                    <button type="submit" class="btn btn-primary flex-fill flex-md-grow-0 rounded-pill shadow-sm">
                        <i class="bi bi-search me-1"></i>
                        ရှာဖွေမည်
                    </button>

                    <a href="{{ route('Quality#list') }}" class="btn btn-outline-secondary text-center flex-fill flex-md-grow-0 rounded-pill shadow-sm">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        ပြန်လည်သတ်မှတ်မည်
                    </a>
                </div>

            </div>
        </form>
    </div>

    {{-- Grade Table --}}
    <div class="dashboard-card shadow-sm p-3 p-md-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">

            <div>
                <h5 class="mb-1">
                    သီးနှံ အရည်အသွေးစာရင်း
                </h5>

                <small class="text-muted d-block mt-1" style="line-height: 1.5">
                    စာရင်းသွင်းထားသော သီးနှံအရည်အသွေးအဆင့်များ
                </small>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="badge bg-light text-dark border px-3 py-2">
                    {{ $grades->count() }} အရည်အသွေး
                </span>
                <a href="{{ route('Quality#history') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">ပြင်ထားသောစာရင်း</a>
                <a href="{{ route('Quality#trashList') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm">ဖျက်ထားသောစာရင်း</a>
            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0 text-nowrap">

                <thead>

                    <tr>
                        <th>စဉ်</th>
                        <th>သီးနှံအမည်</th>
                        <th>အရည်အသွေး</th>
                        <th width="150" class="text-center">လုပ်ဆောင်ချက်</th>
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
                                    <span class="fs-6">
                                        {{ $item->grade_name }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('Quality#edit', $item->id) }}"
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
                            <td colspan="4" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <h5 class="mb-1 text-secondary fw-bold">သီးနှံ အရည်အသွေးစာရင်း မရှိသေးပါ</h5>
                                    <p class="text-muted mb-0">
                                        လက်ရှိတွင် သီးနှံအရည်အသွေး ထည့်သွင်းထားခြင်းမရှိသေးပါ။
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
                သီးနှံအရည်အသွေး {{ $grades->firstItem() ?? 0 }}
                မှ {{ $grades->lastItem() ?? 0 }}
                အထိ ပြသထားပြီး
                စုစုပေါင်း {{ $grades->total() }} မျိုး ရှိပါသည်။
            </p>

            <nav class="d-flex justify-content-end">
                <ul class="pagination pagination-sm mb-0">
                    <span> {{ $grades->links() }} </span>
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
                text: "ဖျက်ပြီးပါက ဤသီးနှံအရည်အသွေးအချက်အလက်ကို ပြန်လည်ရယူ၍ မရတော့ပါ။",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ဖျက်မည်",
                cancelButtonText: "မဖျက်တော့ပါ"
            }).then((result) => {
                if (result.isConfirmed) Swal.fire({
                    title: "ဖျက်ပြီးပါပြီ!",
                    text: "သီးနှံအရည်အသွေးအချက်အလက်ကို အောင်မြင်စွာ ဖျက်ပြီးပါပြီ။",
                    icon: "success"
                }).then(() => {
                    location.href = '/whole-sale-market/Quality/delete/' + id;
                });

            });
        }
    </script>
@endsection
