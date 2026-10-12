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


        <div class="row g-3">

            {{-- ရက်စွဲ --}}
            <div class="col-md-3">

                <label class="form-label">
                    ဈေးနှုန်းရက်စွဲ
                </label>

                <input type="date" class="form-control" value="{{ date('Y-m-d') }}">

            </div>


            {{-- သီးနှံ --}}
            <div class="col-md-3">

                <label class="form-label">
                    သီးနှံအမျိုးအစား
                </label>

                <select class="form-select">

                    <option value="">
                        သီးနှံအားလုံး
                    </option>

                    <option>ခရမ်းချဉ်သီး</option>
                    <option>အာလူး</option>
                    <option>ဂေါ်ဖီထုပ်</option>
                    <option>ကြက်သွန်နီ</option>
                    <option>ငရုတ်သီး</option>
                    <option>ပန်းဂေါ်ဖီ</option>

                </select>

            </div>


            {{-- အရည်အသွေးအဆင့် --}}
            <div class="col-md-3">

                <label class="form-label">
                    အရည်အသွေးအဆင့်
                </label>

                <select class="form-select">

                    <option value="">
                        အဆင့်အားလုံး
                    </option>

                    <option>အဆင့် (က)</option>
                    <option>အဆင့် (ခ)</option>
                    <option>အဆင့် (ဂ)</option>

                </select>

            </div>


            {{-- ရှာဖွေရန်နှင့် ပြန်လည်သတ်မှတ်ရန် --}}
            <div class="col-md-auto d-md-flex align-items-end gap-2">

                <button type="submit" class="btn btn-primary">

                    <i class="bi bi-search me-1"></i>
                    ရှာဖွေရန်

                </button>

                <button type="reset" class="btn btn-outline-secondary mt-md-0 mt-3">

                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                    ပြန်လည်သတ်မှတ်ရန်

                </button>

            </div>

        </div>


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
                        8 Prices
                    </span>
                </div>

                <div class="d-md-flex mt-md-0 mt-2">
                    <div class=" me-2">
                        <a href="{{ route('Crop#history') }}" class=" btn btn-primary">သီးနှံဈေးနှုန်းပြင်ဆင်မှုစာရင်း</a>
                    </div>

                    <div class=" me-2">
                        <a href="{{ route('Crop#trashList') }}"
                            class=" btn btn-primary mt-md-0 mt-2">ဖျက်ထားသောသီးနှံဈေးနှုန်းစာရင်း</a>
                    </div>
                </div>
            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>

                        <th width="70">
                            #
                        </th>

                        <th>
                            Price Date
                        </th>

                        <th>
                            Crop
                        </th>

                        <th>
                            Grade
                        </th>

                        <th class="text-end">
                            Reference Buy Price
                        </th>

                        <th width="150" class="text-center">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <tr>

                        <td>1</td>

                        <td>
                            <strong>08 Oct 2026</strong>
                        </td>

                        <td>
                            <strong>Tomato</strong>
                        </td>

                        <td>
                            <span class="badge bg-success-subtle text-success border">
                                Grade A
                            </span>
                        </td>

                        <td class="text-end">

                            <strong>
                                25,000
                            </strong>

                            <small class="text-muted">
                                MMK
                            </small>

                        </td>

                        <td class="text-center">

                            <a href="" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <button type="button" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>

                        </td>

                    </tr>


                    <tr>

                        <td>2</td>

                        <td>
                            <strong>08 Oct 2026</strong>
                        </td>

                        <td>
                            <strong>Tomato</strong>
                        </td>

                        <td>
                            <span class="badge bg-warning-subtle text-warning border">
                                Grade B
                            </span>
                        </td>

                        <td class="text-end">

                            <strong>
                                20,000
                            </strong>

                            <small class="text-muted">
                                MMK
                            </small>

                        </td>

                        <td class="text-center">

                            <a href="#" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <button type="button" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>

                        </td>

                    </tr>


                    <tr>

                        <td>3</td>

                        <td>
                            <strong>08 Oct 2026</strong>
                        </td>

                        <td>
                            <strong>Potato</strong>
                        </td>

                        <td>
                            <span class="badge bg-success-subtle text-success border">
                                Grade A
                            </span>
                        </td>

                        <td class="text-end">

                            <strong>
                                30,000
                            </strong>

                            <small class="text-muted">
                                MMK
                            </small>

                        </td>

                        <td class="text-center">

                            <a href="#" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <button type="button" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>

                        </td>

                    </tr>


                    <tr>

                        <td>4</td>

                        <td>
                            <strong>08 Oct 2026</strong>
                        </td>

                        <td>
                            <strong>Potato</strong>
                        </td>

                        <td>
                            <span class="badge bg-warning-subtle text-warning border">
                                Grade B
                            </span>
                        </td>

                        <td class="text-end">

                            <strong>
                                25,000
                            </strong>

                            <small class="text-muted">
                                MMK
                            </small>

                        </td>

                        <td class="text-center">

                            <a href="#" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <button type="button" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>

                        </td>

                    </tr>


                    <tr>

                        <td>5</td>

                        <td>
                            <strong>08 Oct 2026</strong>
                        </td>

                        <td>
                            <strong>Cabbage</strong>
                        </td>

                        <td>
                            <span class="badge bg-success-subtle text-success border">
                                Grade A
                            </span>
                        </td>

                        <td class="text-end">

                            <strong>
                                18,000
                            </strong>

                            <small class="text-muted">
                                MMK
                            </small>

                        </td>

                        <td class="text-center">

                            <a href="#" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <button type="button" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>

                        </td>

                    </tr>


                    <tr>

                        <td>6</td>

                        <td>
                            <strong>08 Oct 2026</strong>
                        </td>

                        <td>
                            <strong>Onion</strong>
                        </td>

                        <td>
                            <span class="badge bg-success-subtle text-success border">
                                Grade A
                            </span>
                        </td>

                        <td class="text-end">

                            <strong>
                                22,000
                            </strong>

                            <small class="text-muted">
                                MMK
                            </small>

                        </td>

                        <td class="text-center">

                            <a href="#" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <button type="button" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>

                        </td>

                    </tr>


                    <tr>

                        <td>7</td>

                        <td>
                            <strong>07 Oct 2026</strong>
                        </td>

                        <td>
                            <strong>Tomato</strong>
                        </td>

                        <td>
                            <span class="badge bg-success-subtle text-success border">
                                Grade A
                            </span>
                        </td>

                        <td class="text-end">

                            <strong>
                                24,000
                            </strong>

                            <small class="text-muted">
                                MMK
                            </small>

                        </td>

                        <td class="text-center">

                            <a href="#" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <button type="button" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>

                        </td>

                    </tr>


                    <tr>

                        <td>8</td>

                        <td>
                            <strong>07 Oct 2026</strong>
                        </td>

                        <td>
                            <strong>Potato</strong>
                        </td>

                        <td>
                            <span class="badge bg-warning-subtle text-warning border">
                                Grade B
                            </span>
                        </td>

                        <td class="text-end">

                            <strong>
                                24,000
                            </strong>

                            <small class="text-muted">
                                MMK
                            </small>

                        </td>

                        <td class="text-center">

                            <a href="#" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <button type="button" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}

        <div class="d-flex justify-content-between align-items-center mt-4">

            <small class="text-muted">
                Showing 1 to 8 of 8 daily prices
            </small>

            <nav>

                <ul class="pagination pagination-sm mb-0">

                    <li class="page-item disabled">

                        <a class="page-link" href="#">
                            Previous
                        </a>

                    </li>

                    <li class="page-item active">

                        <a class="page-link" href="#">
                            1
                        </a>

                    </li>

                    <li class="page-item disabled">

                        <a class="page-link" href="#">
                            Next
                        </a>

                    </li>

                </ul>

            </nav>

        </div>

    </div>

@endsection
