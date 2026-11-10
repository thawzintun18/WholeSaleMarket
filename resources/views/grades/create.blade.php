@extends('layouts.app')

@section('page-title', 'Add Grade')

@section('breadcrumb', 'အခြေခံစာရင်းများ / သီးနှံ အရည်အသွေးစာရင်း / သီးနှံအရည်အသွေး ထပ်မံထည့်သွင်းခြင်း')

@section('content')

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">

        <div>
            <h1 class="page-title mb-1 fs-3 fs-md-2">သီးနှံအရည်အသွေး ထပ်မံထည့်သွင်းခြင်း</h1>

            <p class="text-muted mb-0 mt-1" style="line-height: 1.5;">
                သီးနှံအမည်ကို ရွေးချယ်ပြီး အရည်အသွေးအဆင့်အမည်များကို လိုအပ်သလို ထပ်မံထည့်သွင်းနိုင်ပါသည်။
            </p>
        </div>

        <div class="d-flex justify-content-end w-100 w-md-auto">
            <a href="{{ route('Quality#list') }}" class="btn rounded-pill px-4 shadow-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>
    </div>

    <div class="dashboard-card shadow-sm p-3 p-md-4">

        <div class="mb-3">

            <h5 class="mb-1 fw-bold" style="line-height: 1.4;">
                သီးနှံအရည်အသွေး အချက်အလက်
            </h5>

            <small class="text-muted d-block mt-1" style="line-height: 1.5;">
                သီးနှံအမည်ကို ရွေးချယ်ပြီး အရည်အသွေးအဆင့်အမည်ကို ထည့်သွင်းပါ။
            </small>

        </div>

        <form action="{{ route('Quality#create') }}" method="POST" id="qualityForm" >
            @csrf

            <div class="d-flex justify-content-end align-items-center mb-3">

                <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" id="addRow">
                    <i class="bi bi-plus-circle me-1"></i>
                    Row ထပ်ထည့်မည်
                </button>

            </div>

            <div id="gradeRows">

                <div class="grade-row border rounded-3 p-3 mb-3 bg-light">
                    <div class="row g-3 align-items-end">

                        <div class="col-12 col-md-5">
                            <label class="form-label mb-1">သီးနှံအမည်</label>

                            <select name="grades[0][crop_id]"
                                class="form-select crop-select @error('grades.0.crop_id')
                                is-invalid
                            @enderror">
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

                        <div class="col-12 col-md-4">
                            <label class="form-label mb-1">အရည်အသွေး</label>

                            <input type="text" name="grades[0][grade_name]" id="grade_name"
                                class="form-control grade-input @error('grades.0.grade_name')
                                is-invalid
                            @enderror"
                                placeholder="ဥပမာ - Grade A" maxlength="100">

                            @error('grades.0.grade_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="form-label mb-1 d-none d-md-block">လုပ်ဆောင်ချက်</label>

                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm reset-row flex-fill flex-md-grow-0"
                                    title="ဤ Row ကို ပြန်လည်သတ်မှတ်မည်">
                                    data ရှင်း
                                </button>

                                <button type="button" class="btn btn-outline-danger btn-sm delete-row flex-fill flex-md-grow-0" title="ဤ Row ကို ဖျက်မည်">
                                    row ဖျက်
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm" id="submitButton">
                    <i class="bi bi-check-lg me-1"></i>
                    အားလုံး စာရင်းသွင်းမည်
                </button>
            </div>

        </form>

    </div>

@endsection

@section('JS-Content')
    <script>
        const form = document.getElementById('qualityForm');
        const button = document.getElementById('submitButton');
        let isSubmitting = false;
        form.addEventListener('submit', function(event) {
            if (isSubmitting) {
                event.preventDefault();
                return;
            }
            if (!form.checkValidity()) {
                return;
            }
            isSubmitting = true;
            // Submit Button Disable
            button.disabled = true;
            // Row ထပ်ထည့်မည့် Button ကိုပါ Disable လုပ်မည်
            document.getElementById('addRow').disabled = true;
            // Loading Spinner
            button.innerHTML = `
                <span class="spinner-border spinner-border-sm me-1"></span>
                စာရင်းသွင်းနေသည်...
            `;
        });

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

        document.addEventListener('DOMContentLoaded', function() {
            const grade_name = document.getElementById('grade_name');

            if (!grade_name) return;

            grade_name.addEventListener('input', function() {
                this.value = this.value.replace(/[၀-၉]/g, function(digit) {
                    return '၀၁၂၃၄၅၆၇၈၉'.indexOf(digit).toString();
                });
            });
        });
    </script>
@endsection
