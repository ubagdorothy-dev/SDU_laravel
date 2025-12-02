<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Office; // <-- 1. IMPORT Office Model
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException; // For custom error messages

class AuthController extends Controller
{
    // --- Configuration (Ideally in a dedicated config file) ---
    private const OFFICIAL_DOMAIN = '@sdu.edu.ph';
    private const HEAD_IDENTIFIER = 'head.';
    private const STAFF_IDENTIFIER = 'staff.';
    // ---------------------------------------------------------

    // Show the registration form
    public function showRegisterForm()
    {
        // 2. FETCH office codes from the database
        // This queries the 'offices' table and returns an array of only the 'code' values.
        $officeCodes = Office::pluck('code')->toArray(); 

        return view('auth.register', [
            'offices' => $officeCodes // <-- 3. PASS the array to the view
        ]); 
    }

    /**
     * Handle the registration request, including custom validation and role assignment.
     */
    public function register(Request $request)
    {
        // 1. Basic Laravel Validation
        $request->validate([
            'full_name' => 'required|string|max:255', // Changed from 'name'
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            // 4. ADD 'exists:offices,code' rule for database validation
            'office_code' => 'required|string|max:10|exists:offices,code',
        ], [
            // Custom message for the office code if needed
            'office_code.required' => 'Please select an office/center.',
            'office_code.exists' => 'The selected office code is invalid.', // Custom message for exists rule
        ]);

        $email = trim($request->email);
        $email_lower = strtolower($email);
        $assigned_role = 'unassigned';
        
        // 2. Your Custom Email/Role Validation Logic
        if (str_ends_with($email_lower, self::OFFICIAL_DOMAIN)) {
            $local_part = strstr($email_lower, '@', true);

            if (strpos($local_part, self::HEAD_IDENTIFIER) !== false) {
                $assigned_role = 'head';
            } elseif (strpos($local_part, self::STAFF_IDENTIFIER) !== false) {
                $assigned_role = 'staff';
            } else {
                // Email from official domain but no staff.*/head.* prefix = REJECT
                throw ValidationException::withMessages([
                    'email' => "Error: Email must start with 'staff.' or 'head.' prefix (e.g., staff.yourname@sdu.edu.ph).",
                ]);
            }
        } else {
            // Non-official domain = REJECT
            throw ValidationException::withMessages([
                'email' => "Error: Email must be from @sdu.edu.ph domain with 'staff.' or 'head.' prefix.",
            ]);
        }

        // 3. Create User with Assigned Role and Pending Approval
        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $email,
            'password_hash' => Hash::make($request->password),
            'role' => $assigned_role,
            'office_code' => $request->office_code,
            'is_approved' => false, // All new registrations are INACTIVE by default
        ]);

        // Instead of Auth::login(), you redirect back with a success message
        return redirect()->route('login')->with('register_message', 
            "Registration Successful!<br>Your account has been created as a <strong>" . strtoupper($assigned_role) . "</strong>.
            <br><br>Your account is <strong>pending approval</strong> by your Unit Director."
        )->with('register_type', 'success');
    }

    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login'); 
    }

    /**
     * Handle the login request, including checking for account approval.
     */
    public function login(Request $request)
    {
        // Log the request for debugging
        \Log::info('Login attempt', [
            'email' => $request->email,
            'has_password' => !empty($request->password),
            'password_length' => strlen($request->password ?? ''),
            'all_request_data' => $request->all(),
            'ip' => $request->ip(),
        ]);
        
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        \Log::info('Validated credentials', [
            'credentials' => $credentials,
        ]);

        // 1. Attempt to log in the user (Checks email existence and verifies password)
        \Log::info('Attempting Auth::attempt');
        if (Auth::attempt($credentials)) {
            \Log::info('Login successful - Auth::attempt returned true');
            
            $user = Auth::user();
            \Log::info('User retrieved from Auth', [
                'user_id' => $user->user_id,
                'email' => $user->email,
                'role' => $user->role,
                'is_approved' => $user->is_approved,
            ]);
            
            // 2. Approval Status Check
            if (!$user->is_approved) {
                \Log::info('User not approved, logging out');
                // Logout the user immediately
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Send back the specific error message
                return back()->withErrors([
                    'email' => "Your account is pending approval by your Unit Director. Please try logging in again after approval.",
                ])->withInput();
            }

            // 3. Successful Login: Regenerate session and handle role-based redirection
            \Log::info('Regenerating session');
            $request->session()->regenerate();
            $request->session()->flash('approval_notification', "Your account has been approved by your Unit Director. Welcome!");

            \Log::info('Redirecting to dashboard');
            return $this->redirectToDashboard($user);
            
        }

        // Failed Auth::attempt (Invalid credentials)
        \Log::info('Login failed - Auth::attempt returned false');
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    /**
     * Handles role-based redirection.
     */
    protected function redirectToDashboard(User $user)
    {
        switch ($user->role) {
            case 'unit_director':
            case 'unit director': // You should standardize this value in your DB
                return redirect()->intended(route('admin.dashboard'));
            case 'head':
                return redirect()->intended(route('office_head.dashboard'));
            case 'staff':
                return redirect()->intended(route('staff.dashboard'));
            default:
                // Logout if role is unknown
                Auth::logout();
                return redirect()->route('login')->with('login_error', "Unknown account role. Contact administrator.");
        }
    }

    // Logout user
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}