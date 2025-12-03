<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Office;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = Auth::user();
        // Load the staff detail relationship
        $user->load('staffDetail');
        return view('profile.show', compact('user'));
    }

    /**
     * Show the form for editing the user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();
        // Load office relationship for Office Heads to display office name
        if ($user->role === 'head') {
            $user->load('office');
        }
        $staffDetail = $user->staffDetail;
        $offices = Office::all();
        return view('profile.edit', compact('user', 'staffDetail', 'offices'));
    }

    /**
     * Update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        try {
            // Validate based on user role
            $rules = [
                'full_name' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
                'employment_status' => 'nullable|string|max:50',
                'degree_attained' => 'nullable|string|max:100',
                'degree_other' => 'nullable|string|max:255',
                'program' => 'nullable|string|max:255',
            ];
            
            // For Staff, validate job function selection
            if ($user->role !== 'head') {
                $rules['job_function'] = 'required|in:Program Officer,Admin Officer';
            }
            
            $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }
            
            throw $e;
        }
        
        try {
            $user->full_name = $request->full_name;
            $user->email = $request->email;
            $user->save();
            
            // Update staff details
            $staffDetail = $user->staffDetail;
            if ($staffDetail) {
                // For Office Heads, auto-assign job function based on office
                if ($user->role === 'head') {
                    $autoAssignedJobFunction = 'Director/Office Head - ' . ($user->office ? $user->office->name : 'Unknown Office');
                    $staffDetail->job_function = $autoAssignedJobFunction;
                } else {
                    // For Staff, use the provided job function
                    $staffDetail->job_function = $request->job_function;
                }
                
                $staffDetail->employment_status = $request->employment_status;
                $staffDetail->program = $request->program;
                
                // Handle degree attained
                if ($request->degree_attained === 'Other' && $request->degree_other) {
                    $staffDetail->degree_attained = $request->degree_other;
                } else {
                    $staffDetail->degree_attained = $request->degree_attained;
                }
                
                $staffDetail->save();
            } else {
                // For Office Heads, auto-assign job function based on office
                $jobFunction = $request->job_function;
                if ($user->role === 'head') {
                    $jobFunction = 'Director/Office Head - ' . ($user->office ? $user->office->name : 'Unknown Office');
                }
                
                // Create staff details if they don't exist
                $user->staffDetail()->create([
                    'job_function' => $jobFunction,
                    'employment_status' => $request->employment_status,
                    'program' => $request->program,
                    'degree_attained' => $request->degree_attained === 'Other' ? $request->degree_other : $request->degree_attained,
                ]);
            }
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update profile: ' . $e->getMessage()
                ], 500);
            }
            
            throw $e;
        }
        
        // Check if request is AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully.'
            ]);
        }
        
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }

    /**
     * Show the form for changing the user's password.
     *
     * @return \Illuminate\Http\Response
     */
    public function showChangePasswordForm()
    {
        return view('profile.change_password');
    }

    /**
     * Change the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
        
        // Check if the current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }
        
        // Update the password
        $user->password = Hash::make($request->new_password);
        $user->save();
        
        return redirect()->route('profile.show')->with('success', 'Password changed successfully.');
    }
}