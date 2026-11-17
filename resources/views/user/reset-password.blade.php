<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Expense Tracker - Reset Password</title>

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
                        <h4 class="mb-2 fw-bold text-dark">Reset Password 🔑</h4>
                        <p class="mb-4 text-muted small">Please enter your new password below.</p>

                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email Address</label>
                                <input type="email" name="email" class="form-control" value="{{ $email ?? old('email') }}" required readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">New Password</label>
                                <input type="password" name="password" class="form-control" required autofocus placeholder="Min 8 characters">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required placeholder="Re-type password">
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3 fw-semibold">Update Password</button>
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