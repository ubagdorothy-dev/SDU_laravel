<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OfficeHeadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // Add role-based middleware if needed
        // $this->middleware('role:head');
    }

    /**
     * Show the office head dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get current user's office
        $office = $user->office_code;
        
        // Head-specific metrics
        $head_trainings_completed = DB::table('training_records')
            ->where('user_id', $user->user_id)
            ->where('status', 'completed')
            ->count();
            
        $head_trainings_upcoming = DB::table('training_records')
            ->where('user_id', $user->user_id)
            ->where('status', 'upcoming')
            ->count();
        
        $total_staff_in_office = 0;
        $completed_trainings_in_office = 0;
        $training_completed = 0;
        $training_pending = 0;
        $training_overdue = 0;
        
        if ($office) {
            $total_staff_in_office = DB::table('users')
                ->where('role', 'staff')
                ->where('office_code', $office)
                ->count();
                
            $completed_trainings_in_office = DB::table('training_records')
                ->where('office_code', $office)
                ->where('status', 'completed')
                ->count();
                
            // Minimal training status breakdown (for chart): completed, pending, overdue
            $training_completed = DB::table('training_records')
                ->where('office_code', $office)
                ->where('status', 'completed')
                ->count();
                
            $training_pending = DB::table('training_records')
                ->where('office_code', $office)
                ->where('status', 'upcoming')
                ->count();
                
            $training_overdue = DB::table('training_records')
                ->where('office_code', $office)
                ->where('status', 'ongoing')
                ->count();
        }
        
        // Upcoming trainings for this head (for dashboard list)
        $result_head_upcoming_list = DB::table('training_records')
            ->select('id', 'title', 'start_date', 'end_date', 'nature', 'scope')
            ->where('user_id', $user->user_id)
            ->where('status', 'upcoming')
            ->orderBy('start_date', 'ASC')
            ->limit(10)
            ->get();
        
        // Recent activity (head)
        $result_head_activities = DB::table('training_records')
            ->select('id', 'title', 'start_date', 'end_date', 'created_at', 'status', 'nature', 'scope')
            ->where('user_id', $user->user_id)
            ->orderBy('created_at', 'DESC')
            ->limit(6)
            ->get();
        
        // Pass all data to the view
        return view('office_head.dashboard', compact(
            'user',
            'office',
            'head_trainings_completed',
            'head_trainings_upcoming',
            'total_staff_in_office',
            'completed_trainings_in_office',
            'training_completed',
            'training_pending',
            'training_overdue',
            'result_head_upcoming_list',
            'result_head_activities'
        ));
    }
}