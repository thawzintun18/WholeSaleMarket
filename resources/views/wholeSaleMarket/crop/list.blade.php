@extends('wholeSaleMarket.layout.main')

@section('content')
    <div class="container-fluid px-3 px-lg-4 py-4">
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon"><i class="bi bi-flower1"></i></span>
                <div>
                    <h1 class="h3 mb-1">🌾 သီးနှံစာရင်း</h1>
                    <div class=" d-flex mt-3">
                        <a href="{{ route('WholeSaleMarket#dashboard') }}" class=" mx-2">
                            <small>ပင်မစာမျက်နှာ</small>
                        </a>
                        <i class="bi bi-arrow-right font-weight-bolder"></i>
                        <p class=" mx-2">သီးနှံစာရင်း</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid py-4">

            <div class="crop-table-card">

                <!-- Table Header -->
                <div class="crop-table-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h5 class="crop-title">
                                <i class="bi bi-flower1 me-2"></i>
                                သီးနှံစာရင်း
                            </h5>
                        </div>

                        <!-- Add Crop Button -->
                        <a href="{{ route('Crop#directPage') }}" class="add-crop-btn">
                            <i class="bi bi-plus-lg"></i>
                            သီးနှံထပ်ထည့်ရန်
                        </a>

                    </div>

                </div>


                <!-- Responsive Table -->
                <div class="table-responsive">

                    <table class="table crop-table align-middle">

                        <thead>
                            <tr>
                                <th>သီးနှံအမည်</th>
                                <th>ပွဲခ</th>
                                <th>တစ်တင်းပါ အရေအတွက်</th>
                                <th class="text-center">လုပ်ဆောင်ချက်</th>
                            </tr>
                        </thead>

                        <tbody>

                            @if ($crops->count() > 0)
                                @foreach ($crops as $item)
                                    <tr>

                                        <td>
                                            <span class="crop-name">
                                                {{ $item->crop_name }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="commission">
                                                {{ $item->commission_amount }} ကျပ်
                                            </span>
                                        </td>

                                        <td>
                                            <span class="crop-name">
                                                {{ $item->quantity_per_basket }}
                                                @if ($item->unit == 'viss')
                                                    ပိဿာ
                                                @else
                                                    ပေါင်
                                                @endif
                                            </span>
                                        </td>

                                        <td class="text-center">

                                            <a href="{{ route('Crop#edit', $item->id) }}"
                                                class="btn btn-sm btn-outline-primary me-1" title="ပြင်ဆင်ရန်">
                                                ပြင်ဆင်ရန်
                                            </a>

                                            <button type="button" onclick="DeleteData({{ $item->id }})"
                                                class=" btn btn-sm btn-outline-danger mt-md-0 mt-2" title="ဖျက်ရန်">
                                                ဖျက်ရန်
                                            </button>

                                        </td>

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="empty-state-cell">

                                        <div class="empty-state">

                                            <div class="empty-icon">
                                                <i class="bi bi-flower1"></i>
                                            </div>

                                            <h5>သီးနှံအချက်အလက် မရှိသေးပါ</h5>

                                            <p>
                                                လက်ရှိတွင် သီးနှံအချက်အလက် ထည့်သွင်းထားခြင်းမရှိသေးပါ။
                                            </p>

                                            <a href="{{ route('Crop#directPage') }}" class="btn btn-primary empty-add-btn">
                                                <i class="bi bi-plus-circle me-1"></i>
                                                သီးနှံထည့်သွင်းရန်
                                            </a>

                                        </div>

                                    </td>
                                </tr>
                            @endif


                        </tbody>

                    </table>

                </div>

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
                    location.href = '/WholeSaleMarket/Crop/delete/' + id;
                });

            });
        }
    </script>
@endsection
