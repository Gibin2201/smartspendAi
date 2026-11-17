<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - SmartSpend AI</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('login/assets/style.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">

            <a href="{{ url('/') }}" class="logo">
                <i class="fa-solid fa-brain"></i>
                SmartSpend<span>AI</span>
            </a>

            <div class="nav-buttons">
                <a href="{{ url('/') }}" class="btn btn-outline">
                    Back to Home
                </a>
            </div>

        </div>
    </nav>


    <!-- Login Container -->
    <div class="login-wrapper container">

        <div class="card login-card">

            <!-- Error Message -->
            @if(session('error'))
                <div class="alert alert-danger"
                     style="color:#721c24;
                            background-color:#f8d7da;
                            border:1px solid #f5c6cb;
                            padding:10px;
                            border-radius:5px;
                            margin-bottom:1.5rem;
                            text-align:center;
                            font-size:0.9rem;">

                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ session('error') }}

                </div>
            @endif


            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success"
                     style="color:#155724;
                            background-color:#d4edda;
                            border:1px solid #c3e6cb;
                            padding:10px;
                            border-radius:5px;
                            margin-bottom:1.5rem;
                            text-align:center;
                            font-size:0.9rem;">

                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}

                </div>
            @endif


            <!-- Login Heading -->
            <div class="text-center mb-4"
                 style="text-align:center; margin-bottom:1.5rem;">

                <i class="fa-solid fa-user-shield"
                   style="font-size:2.5rem;
                          color:var(--primary-color);
                          margin-bottom:1rem;">
                </i>

                <h3>Welcome Back</h3>

                <p class="subtitle">
                    Enter your credentials to access your dashboard.
                </p>

            </div>


            <!-- Login Form -->
            <form action="{{ route('login.submit') }}" method="POST">

                @csrf


                <!-- Username -->
                <div class="form-group">

                    <label for="username">
                        Username
                    </label>

                    <input type="text"
                           id="username"
                           name="username"
                           class="form-input @error('username') is-invalid @enderror"
                           placeholder="Enter your username"
                           value="{{ old('username') }}"
                           required>

                    @error('username')

                        <span class="text-danger"
                              style="color:var(--danger);
                                     font-size:0.85rem;
                                     margin-top:5px;
                                     display:block;">

                            {{ $message }}

                        </span>

                    @enderror

                </div>


                <!-- Password -->
                <div class="form-group">

                    <label for="password">
                        Password
                    </label>


                    <div class="password-wrapper">

                        <input type="password"
                               id="password"
                               name="password"
                               class="form-input @error('password') is-invalid @enderror"
                               placeholder="Enter your password"
                               required>


                        <!-- Show / Hide Password -->
                        <i class="fa-regular fa-eye toggle-password"
                           id="togglePassword"
                           title="Show Password"
                           style="cursor:pointer;">
                        </i>

                    </div>


                    @error('password')

                        <span class="text-danger"
                              style="color:var(--danger);
                                     font-size:0.85rem;
                                     margin-top:5px;
                                     display:block;">

                            {{ $message }}

                        </span>

                    @enderror


                    <!-- Forgot Password -->
                    <a href="{{ route('password.request') }}"
                       class="forgot-password">

                        Forgot Password?

                    </a>

                </div>


                <!-- Login Button -->
                <button type="submit"
                        class="btn btn-primary btn-block btn-large mt-4">

                    <i class="fa-solid fa-right-to-bracket"
                       style="margin-right:8px;">
                    </i>

                    Login

                </button>

            </form>


            <!-- Register -->
            <div class="auth-links">

                Don't have an account?

                <a href="{{ route('register') }}">
                    Create one
                </a>

            </div>

        </div>

    </div>



    <!-- Password Show / Hide JavaScript -->
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const passwordInput =
                document.getElementById('password');

            const togglePassword =
                document.getElementById('togglePassword');


            togglePassword.addEventListener('click', function () {

                if (passwordInput.type === 'password') {

                    // Show password
                    passwordInput.type = 'text';

                    togglePassword.classList.remove('fa-eye');
                    togglePassword.classList.add('fa-eye-slash');

                    togglePassword.title = 'Hide Password';

                } else {

                    // Hide password
                    passwordInput.type = 'password';

                    togglePassword.classList.remove('fa-eye-slash');
                    togglePassword.classList.add('fa-eye');

                    togglePassword.title = 'Show Password';

                }

            });

        });

    </script>

</body>
</html>