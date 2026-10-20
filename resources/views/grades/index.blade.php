@extends('layouts.app')

@section('page-title', 'Grades')

@section('breadcrumb', 'အခြေခံစာရင်းများ / သီးနှံ အရည်အသွေးစာရင်း')

@section('content')

    <div class="d-md-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="page-title mb-1">
                သီးနှံ အရည်အသွေးစာရင်း
            </h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                သီးနှံတစ်မျိုးစီအတွက် အရည်အသွေးအဆင့်များကို ဤစာရင်းတွင်
                ကြည့်ရှုနိုင်ပါသည်။
            </p>
        </div>

        <div class=" d-flex justify-content-end">
            <a href="{{ route('Quality#directPage') }}" class="btn btn-success mt-md-0 mt-3">
                <i class="bi bi-plus-lg me-1"></i>
                သီးနှံအရည်အသွေးထပ်ထည့်ရန်
            </a>
        </div>

    </div>

    {{-- Search & Filter --}}

    <div class="dashboard-card mb-4 shadow-sm">
        <form action="{{ route('Quality#list') }}" method="GET">
            <div class="row g-3">

                {{-- သီးနှံအလိုက် စစ်ထုတ်ရန် --}}
                <div class="col-md-4">

                    <label class="form-label">
                        သီးနှံအမည်
                    </label>

                    <form action="{{ route('Quality#list') }}" method="GET">
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
                    </form>

                </div>

                {{-- အဆင့်အမည် ရှာဖွေရန် --}}
                <div class="col-md-4">

                    <label class="form-label">
                        အဆင့်အမည် ရှာဖွေရန်
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="text" name="searchData" value="{{ request('searchData') }}" id="search"
                            class="form-control" placeholder="အဆင့်အမည် ရိုက်ထည့်ပါ...">

                    </div>

                </div>

                {{-- ရှာဖွေရန် ခလုတ် --}}
                <div class="col-md-auto d-flex align-items-end">

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>
                        ရှာဖွေမည်
                    </button>

                </div>

                {{-- ပြန်လည်သတ်မှတ်ရန် --}}
                <div class="col-md-auto d-flex align-items-end">

                    <a href="{{ route('Quality#list') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        ပြန်လည်သတ်မှတ်မည်
                    </a>

                </div>

            </div>
        </form>

    </div>

    {{-- Grade Table --}}
    <div class="dashboard-card shadow-sm">

        <div class="d-md-flex justify-content-between align-items-center mb-3">

            <div>

                <h5 class="mb-1">
                    သီးနှံ အရည်အသွေးစာရင်း
                </h5>

                <small class="text-muted mt-2">
                    စာရင်းသွင်းထားသော သီးနှံအရည်အသွေးအဆင့်များ
                </small>

            </div>


            <div class="d-md-flex justify-content-end mt-md-0 mt-2">
                <div class="me-2">
                    <span class="badge bg-light text-dark border">
                        {{ $grades->count() }} အရည်အသွေး
                    </span>
                </div>

                <div class="d-md-flex mt-md-0 mt-2">
                    <div class=" me-2">
                        <a href="{{ route('Quality#history') }}" class=" btn btn-primary">အရည်အသွေးပြင်ဆင်မှုစာရင်း</a>
                    </div>

                    <div class=" me-2 mt-md-0 mt-2">
                        <a href="{{ route('Quality#trashList') }}" class=" btn btn-primary">ဖျက်ထားသောအရည်အသွေးစာရင်း</a>
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

                                    <div class=" d-flex mt-md-0">
                                        <a href="{{ route('Quality#edit', $item->id) }}"
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
                            <td colspan="4" class="empty-state-cell">

                                <div class="empty-state">

                                    <h5>သီးနှံ အရည်အသွေးစာရင်း မရှိသေးပါ</h5>

                                    <p>
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
