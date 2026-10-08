<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>404 - Page Not Found</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #f4fbf6, #ffffff);
            font-family: "Segoe UI", sans-serif;
        }

        .error-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        .error-card {
            width: 100%;
            max-width: 650px;
            background: #ffffff;
            border-radius: 25px;
            padding: 55px 40px;
            text-align: center;
            box-shadow: 0 15px 50px rgba(25, 135, 84, 0.10);
            border: 1px solid rgba(25, 135, 84, 0.08);
        }

        .error-icon {
            width: 90px;
            height: 90px;
            margin: 0 auto 25px;
            border-radius: 50%;
            background: #eaf7ef;
            color: #b43131;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
        }

        .error-number {
            font-size: 120px;
            line-height: 1;
            font-weight: 800;
            letter-spacing: -5px;
            color: #b93b3b;
            margin-bottom: 15px;
        }

        .error-title {
            font-size: 30px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 12px;
        }

        .error-text {
            max-width: 450px;
            margin: 0 auto 30px;
            color: #6c757d;
            font-size: 16px;
            line-height: 1.7;
        }

        .home-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 28px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.25s ease;
        }

        .home-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(25, 135, 84, 0.20);
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 22px;
            border-radius: 10px;
            font-weight: 600;
            margin-left: 8px;
        }

        .error-footer {
            margin-top: 35px;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
            color: #adb5bd;
            font-size: 13px;
        }

        @media (max-width: 576px) {

            .error-card {
                padding: 40px 22px;
                border-radius: 20px;
            }

            .error-number {
                font-size: 85px;
            }

            .error-title {
                font-size: 24px;
            }

            .error-text {
                font-size: 14px;
            }

            .home-btn,
            .back-btn {
                width: 100%;
                justify-content: center;
                margin: 5px 0;
            }
        }
    </style>
</head>

<body>

    <div class="error-wrapper">

        <div class="error-card">

            {{-- Icon --}}
            <div class="error-icon">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            {{-- Error Number --}}
            <div class="error-number">
                404
            </div>

            {{-- Title --}}
            <h1 class="error-title">
                Page Not Found
            </h1>

            {{-- Description --}}
            <p class="error-text">
                Sorry, the page you are looking for could not be found.
                It may have been moved, deleted, or the URL may be incorrect.
            </p>

            {{-- Buttons --}}
            <div class="d-flex justify-content-center flex-wrap">

                <a href="{{ url('/') }}"
                    class="btn btn-danger home-btn">

                    <i class="fa-solid fa-house"></i>

                    Go Home

                </a>

                <button type="button"
                    onclick="history.back()"
                    class="btn btn-outline-secondary back-btn">

                    <i class="fa-solid fa-arrow-left"></i>

                    Go Back

                </button>

            </div>

            {{-- Footer --}}
            <div class="error-footer">

                <i class="fa-solid fa-circle-info me-1"></i>

                If you believe this is an error, please try again.

            </div>

        </div>

    </div>

</body>

</html>
