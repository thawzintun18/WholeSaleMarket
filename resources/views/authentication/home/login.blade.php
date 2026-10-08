<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Wholesale Market</title>

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    {{-- Font Awesome --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: "Segoe UI", sans-serif;
            background: #f1f8f4;
        }

        /* ==============================
            MAIN
        ============================== */

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        /* ==============================
            LOGIN CARD
        ============================== */

        .login-card {
            width: 100%;
            max-width: 950px;
            min-height: 560px;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;

            box-shadow:
                0 20px 60px rgba(25, 135, 84, 0.12);

            border: 1px solid rgba(25, 135, 84, 0.08);
        }

        /* ==============================
            LEFT SIDE
        ============================== */

        .login-left {
            background: linear-gradient(
                145deg,
                #198754,
                #157347
            );

            color: white;

            padding: 55px 45px;

            display: flex;
            flex-direction: column;
            justify-content: center;

            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: "";
            position: absolute;

            width: 280px;
            height: 280px;

            border-radius: 50%;

            background: rgba(255, 255, 255, 0.07);

            top: -100px;
            right: -100px;
        }

        .login-left::after {
            content: "";
            position: absolute;

            width: 220px;
            height: 220px;

            border-radius: 50%;

            background: rgba(255, 255, 255, 0.05);

            bottom: -90px;
            left: -80px;
        }

        .brand-icon {
            width: 70px;
            height: 70px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: rgba(255, 255, 255, 0.15);

            border-radius: 18px;

            font-size: 30px;

            margin-bottom: 25px;

            position: relative;
            z-index: 2;
        }

        .login-left h1 {
            font-size: 34px;
            font-weight: 700;

            margin-bottom: 15px;

            position: relative;
            z-index: 2;
        }

        .login-left p {
            font-size: 16px;
            line-height: 1.7;

            opacity: 0.9;

            max-width: 380px;

            position: relative;
            z-index: 2;
        }

        .feature-list {
            margin-top: 30px;

            position: relative;
            z-index: 2;
        }

        .feature-item {
            display: flex;
            align-items: center;

            margin-bottom: 17px;

            font-size: 14px;
        }

        .feature-item i {
            width: 30px;
            height: 30px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: rgba(255, 255, 255, 0.15);

            border-radius: 50%;

            margin-right: 12px;
        }

        /* ==============================
            RIGHT SIDE
        ============================== */

        .login-right {
            padding: 55px 50px;

            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-title {
            font-size: 30px;
            font-weight: 700;

            color: #212529;

            margin-bottom: 8px;
        }

        .login-subtitle {
            color: #6c757d;

            margin-bottom: 32px;
        }

        /* ==============================
            FORM
        ============================== */

        .form-label {
            font-weight: 600;

            color: #343a40;

            margin-bottom: 8px;
        }

        .input-group {
            position: relative;
        }

        .input-group-text {
            background: #f8faf9;

            border-color: #dee2e6;

            color: #198754;

            padding-left: 15px;
            padding-right: 15px;
        }

        .form-control {
            height: 50px;

            border-color: #dee2e6;

            box-shadow: none !important;
        }

        .form-control:focus {
            border-color: #198754;

            box-shadow:
                0 0 0 0.2rem rgba(25, 135, 84, 0.10) !important;
        }

        /* ==============================
            REMEMBER ME
        ============================== */

        .form-check-input {
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
        }

        .form-check-label {
            color: #6c757d;

            cursor: pointer;
        }

        /* ==============================
            LOGIN BUTTON
        ============================== */

        .login-btn {
            height: 50px;

            border-radius: 10px;

            font-weight: 600;

            font-size: 15px;

            background: #198754;

            border: none;

            transition: all 0.25s ease;
        }

        .login-btn:hover {
            background: #157347;

            transform: translateY(-1px);

            box-shadow:
                0 8px 20px rgba(25, 135, 84, 0.20);
        }

        /* ==============================
            REGISTER
        ============================== */

        .register-text {
            text-align: center;

            margin-top: 25px;

            color: #6c757d;

            font-size: 14px;
        }

        .register-text a {
            color: #198754;

            font-weight: 600;

            text-decoration: none;
        }

        .register-text a:hover {
            text-decoration: underline;
        }

        /* ==============================
            ERROR
        ============================== */

        .login-error {
            border-radius: 10px;

            font-size: 14px;
        }

        /* ==============================
            MOBILE
        ============================== */

        @media (max-width: 767px) {

            .login-wrapper {
                padding: 20px 15px;
            }

            .login-card {
                max-width: 500px;
                min-height: auto;
            }

            .login-left {
                padding: 35px 30px;

                text-align: center;
            }

            .brand-icon {
                margin-left: auto;
                margin-right: auto;
            }

            .login-left h1 {
                font-size: 28px;
            }

            .login-left p {
                margin-left: auto;
                margin-right: auto;
            }

            .feature-list {
                display: none;
            }

            .login-right {
                padding: 40px 30px;
            }

        }

        @media (max-width: 400px) {

            .login-right {
                padding: 35px 20px;
            }

            .login-title {
                font-size: 26px;
            }

        }

    </style>

</head>

<body>

<div class="login-wrapper">

    <div class="login-card">

        <div class="row g-0 h-100">

            <div class=" offset-2 col-md-8">

                <div class="login-right">

                    <h2 class="login-title">
                        မြရတနာပွဲရုံ
                    </h2>

                    <p class="login-subtitle">
                        အကောင့် အရင်ဝင်ပါ
                    </p>


                    {{-- Validation Error --}}
                    @if ($errors->any())

                        <div class="alert alert-danger login-error">

                            <div class="d-flex align-items-center">

                                <i class="fa-solid fa-circle-exclamation me-2"></i>

                                <strong>
                                    မအောင်မြင်ပါ
                                </strong>

                            </div>

                            <ul class="mb-0 mt-2 ps-4">

                                @foreach ($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>
                        </div>
                    @endif

                    {{-- Login Form --}}
                    <form method="POST" action="{{ route('login') }}">

                        @csrf

                        {{-- Email --}}

                        <div class="mb-3">

                            <label for="email" class="form-label">
                                အသုံးပြုသူအမည်
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="fa-solid fa-envelope"></i>
                                </span>

                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="အသုံးပြုသူအမည်ထည့်ရန်"
                                    required
                                    autofocus
                                    autocomplete="username"
                                >

                            </div>

                        </div>


                        {{-- Password --}}

                        <div class="mb-3">

                            <label for="password" class="form-label">
                                စကားဝှက်ထည့်မည်
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="fa-solid fa-lock"></i>
                                </span>

                                <input
                                    type="password"
                                    class="form-control"
                                    id="password"
                                    name="password"
                                    placeholder="စကားဝှက်ထည်ရန်"
                                    required
                                    autocomplete="current-password"
                                >

                                <button
                                    type="button"
                                    class="btn btn-light border"
                                    id="togglePassword">

                                    <i class="fa-solid fa-eye"
                                        id="passwordIcon">
                                    </i>

                                </button>

                            </div>

                        </div>


                        {{-- Remember Me --}}

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div class="form-check">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="remember"
                                    id="remember">

                                <label
                                    class="form-check-label"
                                    for="remember">

                                    မှတ်ထားမည်

                                </label>

                            </div>

                        </div>


                        {{-- Login Button --}}

                        <button
                            type="submit"
                            class="btn btn-success w-100 login-btn">

                            <i class="fa-solid fa-right-to-bracket me-2"></i>

                            အကောင့်ဝင်မည်

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- Password Show / Hide --}}

<script>

    const togglePassword =
        document.getElementById('togglePassword');

    const password =
        document.getElementById('password');

    const passwordIcon =
        document.getElementById('passwordIcon');


    togglePassword.addEventListener('click', function () {

        const type =
            password.getAttribute('type') === 'password'
                ? 'text'
                : 'password';

        password.setAttribute('type', type);


        if (type === 'text') {

            passwordIcon.classList.remove('fa-eye');

            passwordIcon.classList.add('fa-eye-slash');

        } else {

            passwordIcon.classList.remove('fa-eye-slash');

            passwordIcon.classList.add('fa-eye');

        }

    });

</script>


</body>
</html>
