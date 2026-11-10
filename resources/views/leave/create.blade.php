@extends('layouts.app')

@section('page-title', 'Add Daily Price')

@section('breadcrumb', 'Stock / နေ့စဉ်သီးနှံလက်ကျန်စာရင်း / သီးနှံလက်ကျန်ထည့်သွင်းခြင်း')

@section('content')

    <div class="d-md-flex justify-content-between align-items-center mb-4">


        <div>
            <h1 class="page-title mb-1" style="line-height: 35px">သီးနှံလက်ကျန်ထည့်သွင်းခြင်း</h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                ရောင်းချရန်အတွက် လက်ရှိရှိနေသော သီးနှံလက်ကျန်များကို အမျိုးအစား၊ အရေအတွက်၊ အလေးချိန်နှင့်
                ခန့်မှန်းတန်ဖိုးများ သတ်မှတ်၍ စာရင်းသွင်းနိုင်ပါသည်။
            </p>
        </div>

        <div class=" d-flex justify-content-end mt-md-0 mt-2">
            <a href="{{ route('Leave#list') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>


    </div>

    <div class="dashboard-card border shadow-sm">

        <div class="card-header bg-white py-3">
            <h5 class="mb-1">သီးနှံလက်ကျန်စာရင်း</h5>
            <small class="text-muted">
                သီးနှံတစ်မျိုးချင်းစီ၏ လက်ကျန်အရေအတွက်နှင့် ပျမ်းမျှတန်ဖိုးကို ထည့်သွင်းပါ။
            </small>
        </div>

        <div class="card-body">

            <form action="{{ route('Leave#existCreate') }}" method="POST" id="balanceForm">

                @csrf

                {{-- Balance Date --}}
                <div class="row mb-4">
                    <div class="col-md-5">

                        <label class="form-label fw-semibold">
                            စာရင်းသွင်းမည့်နေ့
                        </label>

                        <input type="date" name="balance_date" id="balance_date"
                            class="form-control @error('balance_date') is-invalid @enderror"
                            value="{{ old('balance_date', date('Y-m-d')) }}" required>

                        @error('balance_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div id="dateMessage" class="small mt-2"></div>

                    </div>
                </div>

                <hr>

                {{-- Add Row --}}
                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>
                        <h6 class="mb-1">သီးနှံအချက်အလက်များ</h6>
                        <small class="text-muted">
                            သီးနှံတစ်မျိုးကို တစ်ကြိမ်သာ ထည့်သွင်းနိုင်ပါသည်။
                        </small>
                    </div>

                    <button type="button" class="btn btn-primary" id="addRow">
                        <i class="bi bi-plus-circle me-1"></i>
                        အတန်းထပ်ထည့်မည်
                    </button>

                </div>

                <div id="balanceRows"></div>

                {{-- Row Template --}}
                <template id="rowTemplate">

                    <div class="balance-row border rounded-3 p-3 mb-3">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <strong class="row-title">အတန်း</strong>

                            <button type="button" class="btn btn-sm btn-outline-danger delete-row">

                                <i class="bi bi-trash"></i>
                                ဖျက်မည်

                            </button>

                        </div>

                        <div class="row g-3">

                            {{-- Crop --}}
                            <div class="col-md-3">

                                <label class="form-label">သီးနှံအမည်</label>

                                <select name="balances[__INDEX__][crop_id]" class="form-select crop-select" required>

                                    <option value="">သီးနှံရွေးချယ်ပါ</option>

                                    @foreach ($crops as $crop)
                                        <option value="{{ $crop->id }}">
                                            {{ $crop->crop_name }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>

                            {{-- Daily Balance --}}
                            <div class="col-md-3">

                                <label class="form-label">နေ့စဉ်လက်ကျန်</label>

                                <input type="number" name="balances[__INDEX__][daily_balance]" class="form-control"
                                    placeholder="ဥပမာ - 100" min="0" step="0.01" required>

                            </div>

                            {{-- Total Balance --}}
                            <div class="col-md-3">

                                <label class="form-label">စုစုပေါင်းလက်ကျန်</label>

                                <input type="number" name="balances[__INDEX__][total_balance]" class="form-control"
                                    placeholder="ဥပမာ - 500" min="0" step="0.01" required>

                            </div>

                            {{-- Average Cost --}}
                            <div class="col-md-3">

                                <label class="form-label">ပျမ်းမျှတန်ဖိုး (ကျပ်)</label>

                                <input type="number" name="balances[__INDEX__][average_cost]" class="form-control"
                                    placeholder="ဥပမာ - 100000" min="0" step="0.01" required>

                            </div>

                        </div>

                    </div>

                </template>

                {{-- Submit --}}
                <div class="d-flex justify-content-end gap-2 mt-4">

                    <button type="submit" class="btn btn-success" id="submitBtn">

                        <i class="bi bi-check-lg me-1"></i>
                        စာရင်းသွင်းမည်

                    </button>

                </div>

            </form>

        </div>
    </div>

@endsection



@push('scripts')
    <script>
        $(document).ready(function() {

            let rowIndex = 0;

            const rowTemplate = $('#rowTemplate').html();

            let dateAvailable = true;

            // ----------------------------------
            // Add Row
            // ----------------------------------
            function addRow() {

                let html = rowTemplate.replace(/__INDEX__/g, rowIndex);

                let $row = $(html);

                $row.find('.row-title').text('အတန်း ' + (rowIndex + 1));

                $('#balanceRows').append($row);

                rowIndex++;
            }

            // ပထမ Row
            addRow();

            $('#addRow').on('click', function() {

                if (!dateAvailable) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'သတိပြုရန်',
                        text: 'ဤနေ့အတွက် စာရင်းသွင်းပြီးဖြစ်ပါသည်။'
                    });
                    return;
                }

                addRow();

            });


            // ----------------------------------
            // Delete Row
            // ----------------------------------
            $('#balanceRows').on('click', '.delete-row', function() {

                if ($('#balanceRows .balance-row').length === 1) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'သတိပြုရန်',
                        text: 'အနည်းဆုံး အတန်းတစ်ခု ရှိရပါမည်။'
                    });

                    return;
                }

                $(this).closest('.balance-row').remove();

                updateRowNumbers();

            });


            // ----------------------------------
            // Update Row Numbers
            // ----------------------------------
            function updateRowNumbers() {

                $('#balanceRows .balance-row').each(function(index) {

                    $(this).find('.row-title').text('အတန်း ' + (index + 1));

                });

            }


            // ----------------------------------
            // Duplicate Crop Check
            // ----------------------------------
            $('#balanceRows').on('change', '.crop-select', function() {

                let selectedCrop = $(this).val();
                let $currentRow = $(this).closest('.balance-row');

                if (!selectedCrop) return;

                let duplicate = false;

                $('#balanceRows .crop-select').not(this).each(function() {

                    if ($(this).val() == selectedCrop) {
                        duplicate = true;
                        return false;
                    }

                });

                if (duplicate) {

                    $(this).val('');

                    Swal.fire({
                        icon: 'warning',
                        title: 'သတိပြုရန်',
                        text: 'သီးနှံတစ်မျိုးကို တစ်ကြိမ်သာ ထည့်သွင်းခွင့်ရှိပါသည်။'
                    });

                }

            });


            // ----------------------------------
            // Check Existing Balance Date
            // ----------------------------------
            function checkBalanceDate() {

                let balanceDate = $('#balance_date').val();

                if (!balanceDate) return;

                dateAvailable = false;

                $('#submitBtn').prop('disabled', true);

                $('#dateMessage')
                    .removeClass('text-success text-danger')
                    .addClass('text-muted')
                    .text('ရက်စွဲစစ်ဆေးနေပါသည်...');

                $.ajax({

                    url: @json(route('Leave#checkCreate')),

                    type: 'GET',

                    data: {
                        balance_date: balanceDate
                    },

                    success: function(response) {

                        dateAvailable = !response.exists;

                        if (response.exists) {

                            $('#dateMessage')
                                .removeClass('text-muted text-success')
                                .addClass('text-danger')
                                .text(response.message);

                            $('#submitBtn').prop('disabled', true);

                            $('#addRow').prop('disabled', true);

                        } else {

                            $('#dateMessage')
                                .removeClass('text-muted text-danger')
                                .addClass('text-success')
                                .text(response.message);

                            $('#submitBtn').prop('disabled', false);

                            $('#addRow').prop('disabled', false);

                        }

                    },

                    error: function() {

                        dateAvailable = false;

                        $('#dateMessage')
                            .removeClass('text-muted text-success')
                            .addClass('text-danger')
                            .text('ရက်စွဲစစ်ဆေးရာတွင် အမှားဖြစ်နေပါသည်။');

                        $('#submitBtn').prop('disabled', true);

                    }

                });

            }

            $('#balance_date').on('change', function() {
                checkBalanceDate();
            });

            // စတင်ချိန်မှာ စစ်မယ်
            checkBalanceDate();


            // ----------------------------------
            // Prevent Double Submit
            // ----------------------------------
            $('#balanceForm').on('submit', function(e) {

                if (!dateAvailable) {

                    e.preventDefault();

                    Swal.fire({
                        icon: 'warning',
                        title: 'သတိပြုရန်',
                        text: 'ဤနေ့အတွက် စာရင်းသွင်း၍ မရနိုင်ပါ။'
                    });

                    return;
                }

                if ($('#balanceRows .balance-row').length === 0) {

                    e.preventDefault();

                    Swal.fire({
                        icon: 'warning',
                        title: 'သတိပြုရန်',
                        text: 'အနည်းဆုံး သီးနှံတစ်မျိုး ထည့်သွင်းပေးပါ။'
                    });

                    return;
                }

                $('#submitBtn')
                    .prop('disabled', true)
                    .html(
                        '<span class="spinner-border spinner-border-sm me-1"></span> စာရင်းသွင်းနေပါသည်...'
                    );

            });

        });
    </script>
@endpush
