<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PendingApprovalsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // Only unit directors can access this
        $this->middleware('checkrole:unit_director,unit director');
    }

    /**
     * Display pending approvals.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get pending approvals
        $pendingUsers = User::where('is_approved', 0)
            ->whereIn('role', ['staff', 'head'])
            ->with('office')
            ->get();
        
        // Calculate pending approvals count
        $pendingApprovalsCount = $pendingUsers->count();
        
        return view('pending_approvals.index', compact('user', 'pendingUsers', 'pendingApprovalsCount'));
    }
    
    /**
     * Approve a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Only allow approval if user is not already approved
        if (!$user->is_approved) {
            $user->is_approved = 1;
            $user->save();
            
            return redirect()->back()->with('success', 'User approved successfully.');
        }
        
        return redirect()->back()->with('error', 'User is already approved.');
    }
    
    /**
     * Reject a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Delete the user
        $user->delete();
        
        return redirect()->back()->with('success', 'User rejected and removed successfully.');
    }
}