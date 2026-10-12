@extends('layouts.app')

@section('page-title', 'Add Grade')

@section('breadcrumb', 'အခြေခံစာရင်းများ / သီးနှံ အရည်အသွေးစာရင်း / သီးနှံအရည်အသွေး ထပ်မံထည့်သွင်းခြင်း')

@section('content')

    <div class="d-md-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="page-title mb-1">သီးနှံအရည်အသွေး ထပ်မံထည့်သွင်းခြင်း</h1>

            <p class="text-muted mb-0 mt-2" style="line-height: 30px;">
                သီးနှံအမည်ကို ရွေးချယ်ပြီး အရည်အသွေးအဆင့်အမည်များကို လိုအပ်သလို ထပ်မံထည့်သွင်းနိုင်ပါသည်။
            </p>
        </div>

        <div class=" d-flex justify-content-end mt-md-0 mt-2">
            <a href="{{ route('Quality#list') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                နောက်သို့
            </a>
        </div>
    </div>

    <div class="dashboard-card shadow-sm">

        <div class="mb-4">

            <h5 class="mb-1">
                သီးနှံအရည်အသွေး အချက်အလက်
            </h5>

            <small class="text-muted" style="line-height: 30px;">
                သီးနှံအမည်ကို ရွေးချယ်ပြီး အရည်အသွေးအဆင့်အမည်ကို ထည့်သွင်းပါ။
            </small>

        </div>

        <form action="{{ route('Quality#create') }}" method="POST">
            @csrf

            <div class="d-flex justify-content-end align-items-center mb-3">

                <button type="button" class="btn btn-primary" id="addRow">
                    <i class="bi bi-plus-circle me-1"></i>
                    Row ထပ်ထည့်မည်
                </button>

            </div>

            <div id="gradeRows">

                <div class="grade-row border rounded-3 p-3 mb-3 bg-light">
                    <div class="row g-3 align-items-end">

                        <div class="col-md-5">
                            <label class="form-label">သီးနှံအမည်</label>

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

                        <div class="col-md-4">
                            <label class="form-label">အရည်အသွေးအမည်</label>

                            <input type="text" name="grades[0][grade_name]"
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

                        <div class="col-md-3">
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
    </script>
@endsection
