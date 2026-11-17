<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - SmartSpend AI</title>
    
    <!-- Link to your consolidated CSS file using Laravel asset helper -->
    <link rel="stylesheet" href="{{ asset('register/assets/style.css') }}">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ url('/') }}" class="logo">
                <i class="fa-solid fa-brain-circuit"></i> SmartSpend<span>AI</span>
            </a>
            <div class="nav-buttons">
                <a href="{{ route('login') }}" class="btn btn-outline">Log In</a>
            </div>
        </div>
    </nav>

    <!-- OTP Verification Container -->
    <div class="login-wrapper container">
        <div class="card login-card register-card" style="max-width: 450px; margin: 0 auto;">
            
            <div class="text-center mb-4" style="text-align: center; margin-bottom: 2rem;">
                <i class="fa-solid fa-envelope-circle-check" style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                <h3>Verify Your Email</h3>
                <p class="subtitle">We have sent a verification code to your email address. Please enter the 6 digits below to continue.</p>
            </div>
            
            <form action="{{ route('verify.otp') }}" method="POST">
                @csrf
                
                <input type="hidden" name="email" value="{{ old('email', session('email')) }}">

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <!-- Digit Box Setup -->
                    <input
                        type="text"
                        name="otp"
                        id="otp"
                        class="form-input"
                        placeholder="------"
                        maxlength="6"
                        pattern="[0-9]{6}"
                        title="Please enter exactly 6 digits"
                        style="font-size: 28px; text-align: center; letter-spacing: 12px; font-weight: bold;"
                        required>
                    
                    @error('otp')
                        <span class="text-danger" style="color: var(--danger); font-size: 0.85rem; display: block; text-align: center; margin-top: 8px;">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary btn-block btn-large mt-4" style="width: 100%;">
                    <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> Verify Email
                </button>
            </form>

            <div class="auth-links" style="text-align: center; margin-top: 1.5rem;">
                Didn't receive the code? <a href="{{ route('register') }}">Back to Register</a>
            </div>
            
        </div>
    </div>

</body>
</html>