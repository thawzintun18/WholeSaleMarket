@extends('layouts.app')

@section('page-title', 'Add Daily Price')

@section('breadcrumb', 'ဈေးနှုန်းများ / နေ့စဉ်သီးနှံဈေးနှုန်းများ / နေ့စဉ်သီးနှံဈေးနှုန်းများ ထပ်မံထည့်သွင်းခြင်း')

@section('content')

    <div class="d-md-flex justify-content-between align-items-center mb-4">


        <div>
            <h1 class="page-title mb-1" style="line-height: 35px">နေ့စဉ်သီးနှံဈေးနှုန်းများ ထပ်မံထည့်သွင်းခြင်း</h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                သီးနှံနှင့် အရည်အသွေးအဆင့်အလိုက် ရည်ညွှန်းဝယ်ဈေး သတ်မှတ်ပါ။
            </p>
        </div>

        <div class=" d-flex justify-content-end mt-md-0 mt-2">
            <a href="{{ route('DailyPrice#list') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>


    </div>

    <div class="dashboard-card shadow-sm">


        <div class="mb-4">

            <h5 class="mb-1" style="line-height: 35px">
                နေ့စဉ်သီးနှံဈေးနှုန်းများ ထည့်သွင်းရန်
            </h5>

            <small class="text-muted mt-2" style="line-height: 30px">
                သီးနှံအမည် နှင့် အရည်အသွေးအဆင့်အမည် ရွေးချယ်ပြီး နေ့စဉ်သီးနှံဈေးနှုန်းများကို လိုအပ်သလို
                ထပ်မံထည့်သွင်းနိုင်ပါသည်။
            </small>

        </div>


        {{-- <form action="{{ route('DailyPrice#create') }}" method="POST">
            @csrf

            <div class="d-flex justify-content-end align-items-center mb-3">

                <button type="button" class="btn btn-primary" id="addRow">
                    <i class="bi bi-plus-circle me-1"></i>
                    Row ထပ်ထည့်မည်
                </button>

            </div>

            <div id="gradeRows">

                <div class="grade-row border rounded-3 p-3 mb-3 bg-light">


                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label class="form-label">သီးနှံအမည်</label>

                            <select name="grades[0][crop_id]" id="crop_id"
                                class="form-select crop-select @error('grades.0.crop_id') is-invalid @enderror">

                                <option value="">သီးနှံရွေးချယ်ပါ</option>

                                @foreach ($crops as $crop)
                                    <option value="{{ $crop->id }}">
                                        {{ $crop->crop_name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('grades.0.crop_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mt-md-0 mt-3">
                            <label class="form-label">အရည်အသွေးအမည်</label>

                            <select name="grades[0][grade_id]" id="grade_id" class="form-select">

                                <option value="">သီးနှံအရည်အသွေးရွေးချယ်ပါ</option>

                            </select>

                            @error('grades.0.crop_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">နေ့စဉ်သီးနှံဈေးနှုန်း</label>

                            <input type="text" name="grades[0][grade_name]"
                                class="form-control grade-input @error('grades.0.grade_name')
                                is-invalid
                            @enderror"
                                placeholder="ဥပမာ - 100000" maxlength="100">

                            @error('grades.0.grade_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mt-md-0 mt-3">
                            <label class="form-label">လုပ်ဆောင်ချက်</label>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary reset-row"
                                    title="ဤ Row ကို ပြန်လည်သတ်မှတ်မည်">
                                    ပြန်လည်ရှင်းလင်းမည်
                                </button>

                                <button type="button" class="btn btn-outline-danger delete-row" title="ဤ Row ကို ဖျက်မည်">
                                    ဤအတန်းကို ဖျက်မည်
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-lg me-1"></i>
                    အားလုံး စာရင်းသွင်းမည်
                </button>
            </div>

        </form> --}}


        <form action="{{ route('DailyPrice#create') }}" method="POST" id="dailyPriceForm">
            @csrf

            {{-- Price Date --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">ဈေးနှုန်းသတ်မှတ်သည့်နေ့</label>
                    <input type="date" name="price_date" class="form-control @error('price_date') is-invalid @enderror"
                        value="{{ old('price_date', date('Y-m-d')) }}" required>

                    @error('price_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end mb-3">
                <button type="button" class="btn btn-primary" id="addRow">
                    <i class="bi bi-plus-circle me-1"></i>
                    Row ထပ်ထည့်မည်
                </button>
            </div>

            <div id="gradeRows"></div>

            {{-- Row Template --}}
            <template id="rowTemplate">
                <div class="grade-row border rounded-3 p-3 mb-3 bg-light">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <strong class="row-title">အတန်း</strong>

                        <button type="button" class="btn btn-sm btn-outline-danger delete-row">
                            <i class="bi bi-trash"></i>
                            အတန်းဖျက်မည်
                        </button>
                    </div>

                    <div class="row g-3">
                        {{-- Crop --}}
                        <div class="col-md-4">
                            <label class="form-label">သီးနှံအမည်</label>

                            <select name="grades[__INDEX__][crop_id]" class="form-select crop-select" required>
                                <option value="">သီးနှံရွေးချယ်ပါ</option>

                                @foreach ($crops as $crop)
                                    <option value="{{ $crop->id }}">
                                        {{ $crop->crop_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Grade --}}
                        <div class="col-md-4">
                            <label class="form-label">အရည်အသွေးအမည်</label>

                            <select name="grades[__INDEX__][grade_id]" class="form-select grade-select" required>
                                <option value="">သီးနှံအရည်အသွေးရွေးချယ်ပါ</option>
                            </select>
                        </div>

                        {{-- Price --}}
                        <div class="col-md-4">
                            <label class="form-label">ရည်ညွှန်းဝယ်ဈေး (ကျပ်)</label>

                            <input type="number" name="grades[__INDEX__][reference_buy_price]" class="form-control"
                                placeholder="ဥပမာ - 100000" min="0" max="9999999999999.99" step="0.01" required>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-outline-secondary btn-sm reset-row">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            ပြန်လည်ရှင်းလင်းမည်
                        </button>
                    </div>

                </div>
            </template>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-lg me-1"></i>
                    အားလုံး စာရင်းသွင်းမည်
                </button>
            </div>
        </form>




    </div>

@endsection

@section('JS-Content')
    <script>
        const gradeRows = document.getElementById('gradeRows');
        const addRowBtn = document.getElementById('addRow');

        function updateIndexes() {
            gradeRows.querySelectorAll('.grade-row').forEach((row, index) => {
                row.querySelector('.crop-select')
                    .name = `grades[${index}][crop_id]`;

                row.querySelector('.grade-input')
                    .name = `grades[${index}][grade_name]`;
            });
        }

        addRowBtn.addEventListener('click', function() {
            const firstRow = gradeRows.querySelector('.grade-row');
            const newRow = firstRow.cloneNode(true);

            newRow.querySelector('.crop-select').value = '';
            newRow.querySelector('.grade-input').value = '';

            gradeRows.appendChild(newRow);
            updateIndexes();
        });

        gradeRows.addEventListener('click', function(event) {
            const resetBtn = event.target.closest('.reset-row');
            const deleteBtn = event.target.closest('.delete-row');

            if (resetBtn) {
                const row = resetBtn.closest('.grade-row');

                row.querySelector('.crop-select').value = '';
                row.querySelector('.grade-input').value = '';
            }

            if (deleteBtn) {
                const row = deleteBtn.closest('.grade-row');

                if (gradeRows.querySelectorAll('.grade-row').length > 1) {
                    row.remove();
                    updateIndexes();
                } else {
                    row.querySelector('.crop-select').value = '';
                    row.querySelector('.grade-input').value = '';
                }
            }
        });




        $(document).ready(function() {

            $('#crop_id').on('change', function() {

                let cropId = $(this).val();
                let gradeSelect = $('#grade_id');

                gradeSelect.empty().append(
                    new Option('Loading...', '')
                );

                if (!cropId) {
                    gradeSelect.empty().append(
                        new Option('သီးနှံအရည်အသွေးရွေးချယ်ပါ', '')
                    );
                    return;
                }

                let url = @json(route('DailyPrice#getGrades', ['id' => '__ID__']));
                url = url.replace('__ID__', cropId);

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',

                    success: function(grades) {

                        gradeSelect.empty().append(
                            new Option('သီးနှံအရည်အသွေးရွေးချယ်ပါ', '')
                        );

                        if (grades.length === 0) {
                            gradeSelect.append(
                                new Option('ဤသီးနှံအတွက် အရည်အသွေးအမည် မရှိသေးပါ', '')
                            );
                            return;
                        }

                        $.each(grades, function(index, grade) {
                            gradeSelect.append(
                                new Option(grade.grade_name, grade.id)
                            );
                        });
                    },

                    error: function(xhr, status, error) {

                        gradeSelect.empty().append(
                            new Option('Grade ရယူရာတွင် အမှားဖြစ်နေပါသည်', '')
                        );
                    }
                });
            });
        });

        //*******************

        $(document).ready(function() {

            let rowIndex = 0;

            const rowTemplate = $('#rowTemplate').html();

            // ----------------------------------
            // Add Row
            // ----------------------------------
            function addRow() {
                let html = rowTemplate.replace(/__INDEX__/g, rowIndex);

                let $row = $(html);

                $row.find('.row-title').text('အတန်း ' + (rowIndex + 1));

                $('#gradeRows').append($row);

                rowIndex++;
            }

            // ပထမ Row တစ်ခု စတင်ပြမည်
            addRow();

            $('#addRow').on('click', function() {
                addRow();
            });

            // ----------------------------------
            // Crop Change => Load Grades
            // ----------------------------------
            $('#gradeRows').on('change', '.crop-select', function() {

                let $row = $(this).closest('.grade-row');
                let cropId = $(this).val();
                let $gradeSelect = $row.find('.grade-select');

                $gradeSelect.empty().append(
                    new Option('သီးနှံအရည်အသွေးရွေးချယ်ပါ', '')
                );

                if (!cropId) {
                    return;
                }

                $gradeSelect.empty().append(
                    new Option('Loading...', '')
                );

                let url = @json(route('DailyPrice#getGrades', ['id' => '__ID__']));
                url = url.replace('__ID__', encodeURIComponent(cropId));

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',

                    success: function(grades) {

                        $gradeSelect.empty().append(
                            new Option('သီးနှံအရည်အသွေးရွေးချယ်ပါ', '')
                        );

                        if (grades.length === 0) {
                            $gradeSelect.append(
                                new Option('သီးနှံအရည်အသွေး မရှိသေးပါ', '')
                            );
                            return;
                        }

                        $.each(grades, function(index, grade) {
                            $gradeSelect.append(
                                new Option(grade.grade_name, grade.id)
                            );
                        });
                    },

                    error: function(xhr) {
                        console.error(xhr.status, xhr.responseText);

                        $gradeSelect.empty().append(
                            new Option('Grade ရယူရာတွင် အမှားဖြစ်နေပါသည်', '')
                        );
                    }
                });
            });

            // ----------------------------------
            // Delete Row
            // ----------------------------------
            $('#gradeRows').on('click', '.delete-row', function() {

                if ($('#gradeRows .grade-row').length === 1) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'သတိပြုရန်',
                        text: 'အနည်းဆုံး အတန်းတစ်ခု ရှိရပါမည်။',
                        confirmButtonText: 'နားလည်ပါပြီ'
                    });

                    return;
                }

                $(this).closest('.grade-row').remove();

                // အတန်းနံပါတ်များ ပြန်စီမည်
                $('#gradeRows .grade-row').each(function(index) {
                    $(this).find('.row-title').text('အတန်း ' + (index + 1));
                });
            });

            // ----------------------------------
            // Reset Row
            // ----------------------------------
            $('#gradeRows').on('click', '.reset-row', function() {

                let $row = $(this).closest('.grade-row');

                $row.find('.crop-select').val('');

                $row.find('.grade-select')
                    .empty()
                    .append(
                        new Option('သီးနှံအရည်အသွေးရွေးချယ်ပါ', '')
                    );

                $row.find('input').val('');
            });

        });
    </script>
@endsection
