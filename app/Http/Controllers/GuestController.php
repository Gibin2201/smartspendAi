<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class GuestController extends Controller
{
    public function guesthome()
    {
        return view('guest.guesthome');
    }  

    public function login()
    {
        return view('guest.login'); 
    }

    public function register()
    {
        return view('guest.register'); 
    }

    // Note: You named this 'loginn' (with two n's). Make sure your route matches this!
    public function loginn(Request $request)
    {
        // Step A: Validate the inputs
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Step B: Attempt to log them in
        if (Auth::attempt($credentials)) {
            
            // Step C: Regenerate session ID for security
            $request->session()->regenerate();

            // Step D: Store details in the session
            $user = Auth::user(); 
            $request->session()->put('logged_in_name', $user->name);
            $request->session()->put('logged_in_role', $user->role);
            $request->session()->put('logged_in_id', $user->userid);

            // Step E: Redirect based on role
            if ($user->role === 'admin') {
                return redirect()->route('adminhome')->with('success', 'Welcome back, Admin!');
            }

            // Normal user redirect
            return redirect()->route('userhome')->with('success', 'Logged in successfully!');
        }

        // Step F: Wrong password/username
        return back()->with('error', 'Invalid username or password.')->withInput();
    }

    public function logout(Request $request)
    {
        // 1. Log the user out of Laravel's authentication
        Auth::logout();

        // 2. Destroy the session and wipe out session variables
        $request->session()->invalidate();

        // 3. Regenerate the CSRF security token
        $request->session()->regenerateToken();

        // 4. Redirect them back to the login page with a message
        return redirect('/')->with('success', 'You have been successfully logged out.');  
    }
public function store(Request $request)
    {
        // 1. Validate the registration inputs with Indian phone & uppercase password constraints
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:user,email',
            'contact'  => ['required', 'regex:/^(\+91[\-\s]?)?[6-9]\d{9}$/'], // Indian standard format
            'username' => 'required|string|max:50|unique:user,username',
            'password' => ['required', 'string', 'min:6', 'regex:/[A-Z]/'], // At least one uppercase letter
            'terms'    => 'accepted',
        ], [
            'contact.regex'  => 'Please enter a valid Indian phone number (e.g., +91 9876543210 or 10 digits starting with 6-9).',
            'password.regex' => 'The password must contain at least one uppercase letter.',
        ]);

        // 2. Generate a random 6-digit OTP code
        $otp = rand(100000, 999999);

        // 3. Temporarily store user data and the OTP in the session
        $request->session()->put('pending_user', [
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'contact'  => $validated['contact'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role'     => 'user',
            'otp'      => $otp
        ]);
        
        $request->session()->put('email', $validated['email']);

        // 4. SEND THE EMAIL VIA GMAIL SMTP
        Mail::raw("Your SmartSpend AI verification code is: {$otp}", function ($message) use ($validated) {
            $message->to($validated['email'])
                    ->subject('Email Verification OTP');
        });

        // 5. Redirect to the OTP input page
        return redirect()->route('otp.form')->with('success', 'Verification code sent to your email.');
    }

    public function showOtpForm()
    {
        // Make sure they actually went through registration first
        if (!session()->has('pending_user')) {
            return redirect()->route('register');
        }
        return view('guest.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $pendingData = session('pending_user');

        if (!$pendingData || $request->otp != $pendingData['otp']) {
            return back()->withErrors(['otp' => 'The verification code you entered is invalid.']);
        }

        // 1. OTP matches! Now permanently create the user in your database
        $user = User::create([
            'name'     => $pendingData['name'],
            'email'    => $pendingData['email'],
            'contact'  => $pendingData['contact'],
            'username' => $pendingData['username'],
            'password' => $pendingData['password'],
            'role'     => $pendingData['role'],
        ]);

        // 2. Clear the temporary pending session data
        $request->session()->forget('pending_user');

        // 3. Log the user in automatically
        Auth::login($user);

        // 4. Set your custom session variables
        $request->session()->put('logged_in_name', $user->name);
        $request->session()->put('logged_in_role', $user->role);
        $request->session()->put('logged_in_id', $user->userid);

        // 5. Send them to their home page
        return redirect()->route('userhome')->with('success', 'Email verified and account created successfully!');
    }
}