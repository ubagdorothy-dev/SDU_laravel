<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
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
        // $this->middleware('role:staff');
    }

    /**
     * Show the staff dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $staff_user_id = $user->user_id;
        $staff_username = $user->full_name ?? ($user->email ?? 'Staff');
        
        // Attempt to fetch the staff member's office code and name (if available).
        $office_code = $user->office_code;
        $office_name = null;
        
        if ($office_code) {
            // Try to resolve office name from an `offices` table if it exists
            $office_name = DB::table('offices')->where('code', $office_code)->value('name');
        }
        
        $office_display = '';
        if (!empty($office_name) && !empty($office_code)) {
            $office_display = $office_name . ' (' . $office_code . ')';
        } elseif (!empty($office_code)) {
            $office_display = $office_code;
        }
        
        // Counts
        $trainings_completed = DB::table('training_records')
            ->where('user_id', $staff_user_id)
            ->where('status', 'completed')
            ->count();
        
        // Ongoing: current date between start and end (and not marked completed)
        $trainings_ongoing = DB::table('training_records')
            ->where('user_id', $staff_user_id)
            ->where('status', 'ongoing')
            ->count();
        
        // Upcoming: future trainings (start date > today) and not completed
        $trainings_upcoming = DB::table('training_records')
            ->where('user_id', $staff_user_id)
            ->where('status', 'upcoming')
            ->count();
        
        // Ongoing trainings (for overview list)
        $ongoing_rows = DB::table('training_records')
            ->select('id', 'title', 'start_date', 'end_date', 'status', 'nature_of_training', 'scope')
            ->where('user_id', $staff_user_id)
            ->where('status', 'ongoing')
            ->orderBy('start_date', 'ASC')
            ->limit(10)
            ->get();
        
        // Upcoming trainings (for overview list) - future only
        $upcoming_rows = DB::table('training_records')
            ->select('id', 'title', 'start_date', 'end_date', 'status', 'nature_of_training', 'scope')
            ->where('user_id', $staff_user_id)
            ->where('status', 'upcoming')
            ->orderBy('start_date', 'ASC')
            ->limit(10)
            ->get();
        
        // Recent activities (last 10)
        $activity_rows = DB::table('training_records')
            ->select('id', 'title', 'status', 'created_at', 'start_date', 'end_date', 'nature_of_training', 'scope')
            ->where('user_id', $staff_user_id)
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get();
        
        // Full records for training-records view
        $record_rows = DB::table('training_records')
            ->where('user_id', $staff_user_id)
            ->orderBy('start_date', 'DESC')
            ->get();
        
        // Pass all data to the view
        return view('staff.dashboard', compact(
            'user',
            'staff_username',
            'office_display',
            'trainings_completed',
            'trainings_ongoing',
            'trainings_upcoming',
            'ongoing_rows',
            'upcoming_rows',
            'activity_rows',
            'record_rows'
        ));
    }
}