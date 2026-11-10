@extends('layouts.app')

@section('page-title', 'Create User')

@section('breadcrumb')
    အခြေခံစာရင်းများ / Users / Create
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <div class="d-flex align-items-center">
                        <div class=" text-success p-2 rounded-3 me-3">
                            <i class="bi bi-person-plus-fill fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-bold text-dark">Staff / Admin အကောင့်အသစ် ဖန်တီးရန်</h5>
                            <p class="text-muted small mb-0">စနစ်သုံး အကောင့်အသစ်တစ်ခုကို ဖန်တီးပေးပါ။</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('users#store') }}" method="POST">
                        @csrf

                        <!-- နာမည် -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">Name</label>
                            <input type="text" name="name" class="form-control form-control-lg fs-6 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="နာမည်ထည့်ပါ" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">Email</label>
                            <input type="email" name="email" class="form-control form-control-lg fs-6 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="example@email.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Field with Show/Hide Toggle -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="passwordField" class="form-control form-control-lg fs-6 @error('password') is-invalid @enderror" placeholder="••••••••" required>
                                <button class="btn btn-outline-secondary border" type="button" id="togglePassword">
                                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Role ရွေးချယ်ခွင့် -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary small">Role</label>
                            <select name="role" class="form-select form-select-lg fs-6 @error('role') is-invalid @enderror" required>
                                <option value="" selected disabled>Select Role</option>
                                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                {{-- <option value="superadmin">Superadmin (စူပါအက်မင်)</option> --}}
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 pt-2">
                            <button type="submit" class="btn btn-success px-4 py-2 fw-semibold">
                                <i class="bi bi-check-lg me-1"></i> Create
                            </button>
                            <a href="{{ route('users#index') }}" class="btn btn-light border px-4 py-2 fw-semibold text-secondary">
                                Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('passwordField');
        const toggleIcon = document.getElementById('toggleIcon');

        // Password Type ဖြစ်နေရင် Text ပြောင်းမယ်၊ Text ဖြစ်နေရင် Password ပြန်ပြောင်းမယ်
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye'); // မျက်စိပွင့်တဲ့ Icon သို့ပြောင်း
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash'); // မျက်စိပိတ်တဲ့ Icon သို့ပြောင်း
        }
    });
</script>
@endpush