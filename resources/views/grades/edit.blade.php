@extends('layouts.app')

@section('page-title', 'Edit Grade')

@section('breadcrumb', 'အခြေခံစာရင်းများ / သီးနှံ အရည်အသွေးစာရင်း / သီးနှံ အရည်အသွေးများကိုပြင်ဆင်ရန်')

@section('content')

    <div class="d-md-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="page-title mb-1">သီးနှံ အရည်အသွေးများကိုပြင်ဆင်ရန်</h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px;">
                သီးနှံအရည်အသွေးအမည်များကို လိုအပ်သလို ပြင်ဆင်နိုင်ပါသည်။
            </p>
        </div>

        <div class="d-flex justify-content-end mt-md-0 mt-2">
            <a href="{{ route('Quality#list') }}" class="btn btn-outline-secondary mt-3">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>

    </div>

    <div class="dashboard-card shadow-sm">

        <div class="mb-4">

            <h5 class="mb-1" style="line-height: 35px;">
                {{ $grade->crop_name }}သီးနှံအတွက် အရည်အသွေးပြင်ဆင်ရန်
            </h5>

            <small class="text-muted mt-2" style="line-height: 30px;">
                သတ်မှတ်ထားသော သီးနှံအရည်အသွေးအမည်ကို လိုအပ်သလို ပြင်ဆင်၊ ပြောင်းလဲနိုင်ပါသည်။
            </small>

        </div>

        <form action="{{ route('Quality#update', $grade->id) }}" method="POST">

            @csrf

            <input type="hidden" name="crop_id" value="{{ $grade->crop_id }}">

            <div class="row g-3">

                {{-- Grade Name --}}
                <div class="col-md-5 offset-md-2">

                    <label for="grade_name" class="form-label">
                        အရည်အသွေးအမည် <span class="text-danger">*</span>
                    </label>

                    <input type="text" name="grade_name" id="grade_name"
                        value="{{ old('grade_name', $grade->grade_name) }}"
                        class="form-control @error('grade_name') is-invalid @enderror" placeholder="e.g. A, B, C"
                        maxlength="50">

                    @error('grade_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="col-md-3 d-flex justify-content-md-start justify-content-end pt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i>
                        သီးနှံအရည်အသွေးပြင်ဆင်မည်
                    </button>
                </div>

            </div>

        </form>

    </div>

@endsection
