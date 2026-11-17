<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SmartSpend AI</title>
    
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
                <!-- Dynamically route to the login page -->
                <a href="{{ route('login') }}" class="btn btn-outline">Log In</a>
            </div>
        </div>
    </nav>

    <!-- Registration Container -->
    <div class="login-wrapper container">
        <div class="card login-card register-card">
            
            <!-- Global Flash Messages -->
            @if(session('error'))
                <div class="alert alert-danger" style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 1.5rem; text-align: center; font-size: 0.9rem;">
                    <i class="fa-solid fa-circle-exclamation me-1"></i> {{ session('error') }}
                </div>
            @endif

            <div class="text-center mb-4" style="text-align: center; margin-bottom: 2rem;">
                <i class="fa-solid fa-user-plus" style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                <h3>Create an Account</h3>
                <p class="subtitle">Join SmartSpend AI and take control of your finances today.</p>
            </div>
            
            <!-- Form Action (Make sure you have a route named 'register.submit') -->
            <form action="{{ route('register.submit') }}" method="POST">
                
                <!-- Laravel Security Token (REQUIRED) -->
                @csrf
                
                <!-- 2-Column Form Grid -->
                <div class="form-grid">
                    
                    <!-- 1. Name Field -->
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" 
                               class="form-input @error('name') is-invalid @enderror" 
                               placeholder="e.g. John Doe" 
                               value="{{ old('name') }}" required>
                        @error('name')
                            <span class="text-danger" style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- 2. Email Field -->
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" 
                               class="form-input @error('email') is-invalid @enderror" 
                               placeholder="john@example.com" 
                               value="{{ old('email') }}" required>
                        @error('email')
                            <span class="text-danger" style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- 3. Contact Field -->
                    <div class="form-group">
                        <label for="contact">Contact Number</label>
                        <input type="tel" id="contact" name="contact" 
                               class="form-input @error('contact') is-invalid @enderror" 
                               placeholder="e.g. +91 98765 43210" 
                               value="{{ old('contact') }}" required>
                        @error('contact')
                            <span class="text-danger" style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>


                    <!-- 5. Username Field -->
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" 
                               class="form-input @error('username') is-invalid @enderror" 
                               placeholder="Choose a username" 
                               value="{{ old('username') }}" required>
                        @error('username')
                            <span class="text-danger" style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- 6. Password Field -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" 
                                   class="form-input @error('password') is-invalid @enderror" 
                                   placeholder="Create a password" required>
                            <i class="fa-regular fa-eye toggle-password" id="togglePassword" title="Show Password" style="cursor: pointer;"></i>
                        </div>
                        @error('password')
                            <span class="text-danger" style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>

                </div> 
                <!-- End of 2-Column Form Grid -->

                <!-- NOTE: Moved this outside the form-grid so it spans full width properly -->
                <div class="terms-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms" required>
                        <span>I understand and agree to the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>.</span>
                    </label>
                    @error('terms')
                        <span class="text-danger" style="color: var(--danger); font-size: 0.85rem; display:block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary btn-block btn-large mt-4">
                    <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> Register Now
                </button>
            </form>

            <div class="auth-links">
                Already have an account? <a href="{{ route('login') }}">Sign In</a>
            </div>
            
        </div>
    </div>


    <script>
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };

    // Password Show / Hide Toggle
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

                // Toggle eye and eye-slash icons
                if (isPassword) {
                    togglePassword.classList.remove('fa-eye');
                    togglePassword.classList.add('fa-eye-slash');
                    togglePassword.setAttribute('title', 'Hide Password');
                } else {
                    togglePassword.classList.remove('fa-eye-slash');
                    togglePassword.classList.add('fa-eye');
                    togglePassword.setAttribute('title', 'Show Password');
                }
            });
        }
    });
</script>

</body>
</html>