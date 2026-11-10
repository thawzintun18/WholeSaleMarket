@extends('layouts.app')

@section('page-title', 'Users')

@section('breadcrumb')
    အခြေခံစာရင်းများ / Users
@endsection
@section('content')
    <div class="container-fluid p-0 shadow-sm">
        <div class="row">
            <div class="col-md-12">

                {{-- Success Message Alert --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold text-dark">Staff နှင့် Admin အကောင့်များ စာရင်း</h5>
                            <p class="text-muted small mb-0">စနစ်အတွင်းရှိ အသုံးပြုသူအကောင့်များအားလုံးကို စီမံခန့်ခွဲရန်။
                            </p>
                        </div>
                        {{-- အကောင့်အသစ်ဆောက်ရန် Page သို့သွားသော ခလုတ် --}}
                        <a href="{{ route('users#create#page') }}"
                            class="btn btn-success px-3 py-2 fw-semibold rounded-3 shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> အကောင့်အသစ် ဖန်တီးမည်
                        </a>
                    </div>

                    <div class="card-body px-4 pb-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-uppercase fs-7 text-secondary">
                                    <tr>
                                        <th class="py-3 rounded-start">#</th>
                                        <th class="py-3">Name</th>
                                        <th class="py-3">Email</th>
                                        <th class="py-3">Role</th>
                                        <th class="py-3">Date</th>
                                        <th class="py-3 text-center rounded-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $index => $user)
                                        <tr>
                                            <td class="fw-semibold text-muted">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-light text-dark rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold"
                                                        style="width: 35px; height: 35px; font-size: 14px;">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                    <span class="fw-semibold text-dark">{{ $user->name }}</span>
                                                </div>
                                            </td>
                                            <td class="text-muted">{{ $user->email }}</td>
                                            <td>
                                                {{-- Role ပေါ်မူတည်၍ Badge အရောင်ခွဲပြရန် --}}
                                                @if ($user->role === 'superadmin')
                                                    <span
                                                        class="badge bg-danger bg-opacity-10 text-danger px-2.5 py-1.5 rounded-pill">Superadmin</span>
                                                @elseif($user->role === 'admin')
                                                    <span
                                                        class="badge bg-warning bg-opacity-10 text-warning px-2.5 py-1.5 rounded-pill">Admin</span>
                                                @else
                                                    <span
                                                        class="badge bg-info bg-opacity-10 text-info px-2.5 py-1.5 rounded-pill">Staff</span>
                                                @endif
                                            </td>
                                            <td class="text-muted small">{{ $user->created_at->format('d-M-Y') }}</td>
                                            <td class="text-center">
                                                @if ($user->id !== auth()->id())
                                                    <button type="button" onclick="DeleteData({{ $user->id }})"
                                                        class="btn btn-sm btn-outline-danger px-2 py-1 rounded-2"
                                                        title="ဖျက်ရန်">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @else
                                                    <span
                                                        class="badge bg-secondary bg-opacity-10 text-secondary small px-2 py-1">Current
                                                        User</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                                No Account Found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('DeleteData')
    <script>
        function DeleteData(id) {
            Swal.fire({
                title: "အကောင့်ကို ဖျက်ရန် သေချာပါသလား?",
                text: "ဖျက်ပြီးပါက ဤအကောင့်အချက်အလက်ကို ပြန်လည်ရယူ၍ မရတော့ပါ။",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ဖျက်မည်",
                cancelButtonText: "မဖျက်တော့ပါ"
            }).then((result) => {
                if (result.isConfirmed) Swal.fire({
                    title: "ဖျက်ပြီးပါပြီ!",
                    text: "အကောင့်ကို အောင်မြင်စွာ ဖျက်ပြီးပါပြီ။",
                    icon: "success"
                }).then(() => {
                    location.href = '/whole-sale-market/users/delete/' + id;
                });
            });
        }
    </script>
@endsection
