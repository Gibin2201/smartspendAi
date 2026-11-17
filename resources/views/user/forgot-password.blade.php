<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Expense Tracker - Forgot Password</title>

    <!-- Core CSS (Make sure asset path matches your public folder setup) -->
    <link rel="stylesheet" href="{{ asset('user/assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('user/assets/vendor/css/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('user/assets/css/demo.css') }}" />
</head>
<body class="bg-light">

    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="col-12 col-md-6 col-lg-5">
                <div class="card shadow-sm border-0 p-4">
                    <div class="card-body">
                        <h4 class="mb-2 fw-bold text-dark">Forgot Password 🔒</h4>
                        <p class="mb-4 text-muted small">Enter your email address and we'll send you a link to reset your password.</p>

                        @if (session('status'))
                            <div class="alert alert-success mb-3">{{ session('status') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('password.email') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email Address</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="Enter your email">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mb-3 fw-semibold">Send Reset Link</button>
                        </form>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="text-muted small">Back to Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>