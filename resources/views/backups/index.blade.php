@extends('layouts.app')

@section('page-title', 'Crops')
@section('breadcrumb')
    Backup / Backup စာရင်း
@endsection

@section('content')

    <div class="container-fluid p-0">

        <div class=" d-flex justify-content-start align-items-center mb-4">

            <div>
                <h1 class="page-title mb-1">Backup စာရင်း</h1>

                <p class="text-muted mb-0 mt-2" style="line-height: 30px">
                    ဤစာရင်းတွင် Database Backup ပြုလုပ်ထားသည့် မှတ်တမ်းများ၊ Backup ဖိုင်တည်နေရာများနှင့် Local၊ USB Backup
                    တို့၏ အခြေအနေများကို ကြည့်ရှုစစ်ဆေးနိုင်ပါသည်။
                </p>
            </div>

        </div>

    </div>

    <div class="container-fluid">

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- Backup Button --}}
        <div class="dashboard-card mb-3">
            <div class="card-body d-md-flex justify-content-between align-items-center">

                <div>
                    <h5 class="mb-1">Database Backup</h5>
                    <small class="text-muted">
                        Local Drive နှင့် USB Drive သို့ Backup လုပ်ရန်
                    </small>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-md-0 mt-3">

                    {{-- Database Restore --}}
                    @if (auth()->user()->role === 'superadmin')
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                            data-bs-target="#databaseRestoreModal">

                                            <i class="bi bi-arrow-counterclockwise"></i>
                                            Restore လုပ်ရန်
                            </button>
                    @endif

                    {{-- Manual Backup --}}
                    <form action="{{ route('Backup#manual') }}" method="POST" id="manualBackupForm">

                        @csrf

                        <button type="submit" class="btn btn-primary" id="manualBackupBtn">
                            <i class="bi bi-database"></i>
                            Backup လုပ်မည်
                        </button>
                    </form>

                </div>

            </div>
        </div>

        {{-- Backup History --}}
        <div class="card">

            <div class="card-header">
                <h5 class="mb-0">Backup History</h5>
            </div>

            <div class="card-body table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Backup Type</th>
                            <th>Backup Date</th>
                            <th>File Size</th>
                            <th>Local</th>
                            <th>USB</th>
                            <th>Overall</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($backups as $backup)
                            <tr>
                                <td>
                                    {{ $backups->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    {{ match ($backup->backup_type) {
                                        'Manual' => 'ကိုယ်တိုင် Backup',
                                        'Automatic' => 'အလိုအလျောက် Backup',
                                        'Restore' => 'Restore',
                                        'BeforeRestore' => 'Restore မလုပ်မီ Backup',
                                        default => $backup->backup_type,
                                    } }}
                                </td>

                                <td>
                                    {{ $backup->started_at?->format('d-m-Y h:i A') }}
                                </td>

                                <td>
                                    {{ number_format($backup->file_size / 1024, 2) }}
                                    KB
                                </td>

                                <td>
                                    <span
                                        class="badge
                                        {{ $backup->local_status == 'Success'
                                            ? 'bg-success'
                                            : ($backup->local_status == 'Pending'
                                                ? 'bg-warning text-dark'
                                                : 'bg-danger') }}">

                                        {{ match ($backup->local_status) {
                                            'Success' => 'အောင်မြင်သည်',
                                            'Failed' => 'မအောင်မြင်ပါ',
                                            'Pending' => 'လုပ်ဆောင်ဆဲ',
                                            default => $backup->local_status,
                                        } }}
                                    </span>
                                </td>

                                <td>
                                    <span
                                        class="badge
                                        {{ $backup->usb_status == 'Success'
                                            ? 'bg-success'
                                            : ($backup->usb_status == 'Pending'
                                                ? 'bg-warning text-dark'
                                                : 'bg-danger') }}">

                                        {{ match ($backup->usb_status) {
                                            'Success' => 'အောင်မြင်သည်',
                                            'Failed' => 'မအောင်မြင်ပါ',
                                            'Pending' => 'မလုပ်ဆောင်ရသေးပါ',
                                            default => $backup->usb_status,
                                        } }}
                                    </span>
                                </td>

                                <td>
                                    <span
                                        class="badge
                                        {{ $backup->status == 'Success'
                                            ? 'bg-success'
                                            : ($backup->status == 'Partial'
                                                ? 'bg-warning text-dark'
                                                : ($backup->status == 'Pending'
                                                    ? 'bg-secondary'
                                                    : 'bg-danger')) }}">

                                        {{ match ($backup->status) {
                                            'Success' => 'အောင်မြင်သည်',
                                            'Failed' => 'မအောင်မြင်ပါ',
                                            'Partial' => 'တစ်စိတ်တစ်ပိုင်း အောင်မြင်သည်',
                                            'Pending' => 'လုပ်ဆောင်ဆဲ',
                                            default => $backup->status,
                                        } }}
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex gap-1">

                                        {{-- USB Retry --}}
                                        @if ($backup->local_status == 'Success' && $backup->usb_status != 'Success' && $backup->backup_type != 'Restore')
                                            <form action="{{ route('Backup#retryUsb', $backup->id) }}" method="POST"
                                                class="retryUsbForm">

                                                @csrf

                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                    USB Retry
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Restore --}}
                                        @if (auth()->user()->role === 'superadmin')
                                            @if (
                                                $backup->backup_type != 'Restore' &&
                                                    $backup->local_status == 'Success' &&
                                                    str_ends_with(strtolower($backup->file_name ?? ''), '.sql.gz'))
                                                <form action="{{ route('Backup#restore', $backup->id) }}" method="POST"
                                                    class="restoreForm" data-filename="{{ $backup->file_name }}">

                                                    @csrf

                                                    <button type="submit" class="btn btn-sm btn-outline-danger restoreBtn">
                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                        Restore
                                                    </button>
                                                </form>
                                            @endif
                                        @endif

                                        {{-- Delete Backup --}}
                                        {{-- Today ထက်ငယ်ပြီး နောက်ဆုံး ၃ ရက်အတွင်းရှိတဲ့ Backup Data တွေအတွက် Delete Button ကို ဖျောက် --}}
                                        @if (!$backup->created_at->between(now()->subDays(2)->startOfDay(), now()->endOfDay()))
                                            <form action="{{ route('Backup#delete', $backup->id) }}" method="POST"
                                                class="deleteBackupForm" data-filename="{{ $backup->file_name }}">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="text-center">
                                    Backup History မရှိသေးပါ။
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>



            </div>

            {{-- Pagination Example --}}
            <div class="d-md-flex justify-content-between align-items-center my-4 mx-3">

                <p class="text-muted">
                    မှတ်တမ်း {{ $backups->firstItem() ?? 0 }}
                    မှ {{ $backups->lastItem() ?? 0 }}
                    အထိ ပြသထားပြီး
                    စုစုပေါင်း {{ $backups->total() }} မျိုး ရှိပါသည်။
                </p>

                <nav class=" d-flex justify-content-end">

                    <ul class="pagination pagination-sm mb-0">

                        <span> {{ $backups->links() }} </span>

                    </ul>

                </nav>

            </div>
        </div>

        {{-- Database Restore Modal --}}
        <div class="modal fade" id="databaseRestoreModal" tabindex="-1" aria-labelledby="databaseRestoreModalLabel"
            aria-hidden="true">

            <div class="modal-dialog modal-lg modal-dialog-centered">

                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="databaseRestoreModalLabel">
                            <i class="bi bi-database-fill-gear"></i>
                            Database Restore
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('Backup#restoreLocal') }}" method="POST" id="localRestoreForm">

                        @csrf

                        <div class="modal-body">

                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle-fill"></i>

                                Restore လုပ်ခြင်းဖြင့် လက်ရှိ Database Data များ
                                အစားထိုးသွားနိုင်ပါသည်။ Restore မလုပ်မီ Safety Backup
                                အရင်ပြုလုပ်ပါမည်။
                            </div>

                            <div class="mb-3">
                                <label for="relative_path" class="form-label fw-bold">
                                    Restore ပြုလုပ်မည့် Backup File ရွေးချယ်ပါ
                                </label>

                                <select name="relative_path" id="relative_path" class="form-select" required>

                                    <option value="">-- Backup File ရွေးချယ်ပါ --</option>

                                    @foreach ($databaseBackupFiles as $file)
                                        <option value="{{ $file['relative_path'] }}">
                                            {{ $file['file_name'] }}
                                            ({{ number_format($file['file_size'] / 1024, 2) }} KB)
                                            - {{ $file['modified_at'] }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            @if (count($databaseBackupFiles) === 0)
                                <div class="alert alert-danger mb-0">
                                    Local Database Backup ဖိုင် မတွေ့ပါ။
                                </div>
                            @else
                                <small class="text-muted">
                                    <i class="bi bi-folder"></i>
                                    C:\WholeSaleMarket-Backup\database
                                    <br>
                                    Backup ဖိုင် {{ count($databaseBackupFiles) }} ခု တွေ့ရှိထားပါသည်။
                                </small>
                            @endif

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                ပိတ်မည်
                            </button>

                            <button type="submit" class="btn btn-danger" id="localRestoreBtn"
                                {{ count($databaseBackupFiles) === 0 ? 'disabled' : '' }}>

                                <i class="bi bi-arrow-counterclockwise"></i>
                                Restore လုပ်မည်
                            </button>

                        </div>

                    </form>

                </div>

            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        document.getElementById('manualBackupForm')
            ?.addEventListener('submit', function(e) {

                e.preventDefault();

                Swal.fire({
                    title: 'Backup လုပ်မှာ သေချာပါသလား?',
                    text: 'Database ကို Local နှင့် USB Drive သို့ Backup လုပ်ပါမည်။',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Backup လုပ်မည်',
                    cancelButtonText: 'မလုပ်တော့ပါ'
                }).then((result) => {

                    if (result.isConfirmed) {

                        const button = document.getElementById('manualBackupBtn');

                        button.disabled = true;
                        button.innerHTML = 'Backup လုပ်နေပါသည်...';

                        this.submit();
                    }

                });
            });

        document.querySelectorAll('.retryUsbForm')
            .forEach(function(form) {

                form.addEventListener('submit', function(e) {

                    e.preventDefault();

                    Swal.fire({
                        title: 'USB Backup ပြန်လုပ်မည်လား?',
                        text: 'Local Backup File ကို USB သို့ ပြန်ကူးပါမည်။',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'ပြန်ကူးမည်',
                        cancelButtonText: 'မလုပ်တော့ပါ'
                    }).then((result) => {

                        if (result.isConfirmed) {
                            form.submit();
                        }

                    });

                });

            });


        //restore
        document.querySelectorAll('.restoreForm')
            .forEach(function(form) {

                form.addEventListener('submit', function(e) {

                    e.preventDefault();

                    const fileName = form.dataset.filename;
                    const button = form.querySelector('.restoreBtn');

                    Swal.fire({
                        title: 'Database Restore လုပ်မှာ သေချာပါသလား?',
                        html: `
                    <div class="text-start">
                        <p>ရွေးချယ်ထားသော Backup File ကို အသုံးပြုပြီး Database ကို Restore လုပ်ပါမည်။</p>

                        <p class="text-danger fw-bold mb-1">
                            သတိပြုရန်
                        </p>

                        <p>
                            လက်ရှိ Database Data များကို အစားထိုးသွားနိုင်ပါသည်။
                            Restore မလုပ်မီ Safety Backup ပြုလုပ်ပါမည်။
                        </p>

                        <div class="border rounded p-2 text-break">
                            ${fileName}
                        </div>
                    </div>
                `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Restore လုပ်မည်',
                        cancelButtonText: 'မလုပ်တော့ပါ',
                        confirmButtonColor: '#dc3545'
                    }).then((result) => {

                        if (result.isConfirmed) {

                            button.disabled = true;

                            button.innerHTML = `
                        <span class="spinner-border spinner-border-sm"></span>
                        Restore လုပ်နေပါသည်...
                    `;

                            form.submit();
                        }

                    });

                });

            });

        // Local Database Restore
        document.getElementById('localRestoreForm')
            ?.addEventListener('submit', function(e) {

                e.preventDefault();

                const form = this;
                const select = document.getElementById('relative_path');
                const button = document.getElementById('localRestoreBtn');

                const selectedOption = select.options[select.selectedIndex];

                if (!select.value) {

                    Swal.fire({
                        title: 'Backup File ရွေးချယ်ပါ',
                        text: 'Restore ပြုလုပ်မည့် Backup File ကို ရွေးချယ်ပေးပါ။',
                        icon: 'warning'
                    });

                    return;
                }

                const fileName = selectedOption.text;

                Swal.fire({
                    title: 'Database Restore လုပ်မှာ သေချာပါသလား?',
                    text: `ရွေးချယ်ထားသော Backup: ${fileName}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Restore လုပ်မည်',
                    cancelButtonText: 'မလုပ်တော့ပါ',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {

                    if (result.isConfirmed) {

                        button.disabled = true;

                        button.innerHTML = `
                    <span class="spinner-border spinner-border-sm"></span>
                    Restore လုပ်နေပါသည်...
                `;

                        form.submit();
                    }

                });

            });

        document.querySelectorAll('.deleteBackupForm').forEach(form => {

            form.addEventListener('submit', function(e) {

                e.preventDefault();

                let fileName = this.dataset.filename;

                Swal.fire({
                    title: 'Backup ဖျက်မှာ သေချာပါသလား?',
                    html: `
                <strong>${fileName}</strong><br>
                Local နှင့် USB ထဲရှိ Backup File များကိုပါ
                ဖျက်သွားပါမည်။
            `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ဖျက်မည်',
                    cancelButtonText: 'မဖျက်တော့ပါ',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                }).then((result) => {

                    if (result.isConfirmed) {
                        this.submit();
                    }

                });

            });

        });
    </script>
@endpush
