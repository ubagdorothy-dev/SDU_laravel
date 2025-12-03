<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\TrainingAssignment;
use App\Models\TrainingRecord;
use App\Models\User;

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
        // Load the staff detail and office relationships for profile modal
        $user->load('staffDetail', 'office');
        
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
            ->select('id', 'title', 'start_date', 'end_date', 'nature_of_training as nature', 'scope')
            ->where('user_id', $user->user_id)
            ->where('status', 'upcoming')
            ->orderBy('start_date', 'ASC')
            ->limit(10)
            ->get();
        
        // Recent activity (head)
        $result_head_activities = DB::table('training_records')
            ->select('id', 'title', 'start_date', 'end_date', 'created_at', 'status', 'nature_of_training as nature', 'scope')
            ->where('user_id', $user->user_id)
            ->orderBy('created_at', 'DESC')
            ->limit(6)
            ->get();
        
        // Training records (for training-records view)
        $training_records = DB::table('training_records')
            ->select('id', 'title', 'description', 'start_date', 'end_date', 'venue', 'nature_of_training as nature', 'scope', 'status', 'proof_uploaded')
            ->where('user_id', $user->user_id)
            ->orderBy('created_at', 'DESC')
            ->get();
        
        // Office staff (for office-directory view) - Updated to include more details
        $office_staff = collect();
        if ($office) {
            $office_staff = DB::table('users')
                ->leftJoin('staff_details', 'users.user_id', '=', 'staff_details.user_id')
                ->select(
                    'users.user_id', 
                    'users.full_name', 
                    'users.email', 
                    'staff_details.position', 
                    'staff_details.program', 
                    'staff_details.job_function',
                    'staff_details.employment_status',
                    'staff_details.degree_attained'
                )
                ->where('users.role', 'staff')
                ->where('users.office_code', $office)
                ->orderBy('users.full_name')
                ->get();
        }
        
        // Prepare office display information for profile modal
        $office_display = '';
        if ($user->office) {
            $office_display = $user->office->name;
            if ($user->office->code) {
                $office_display .= ' (' . $user->office->code . ')';
            }
        } elseif (!empty($user->office_code)) {
            $office_display = $user->office_code;
        }
        
        // Pass all data to the view
        return view('office_head.dashboard', compact(
            'user',
            'office',
            'office_display',
            'head_trainings_completed',
            'head_trainings_upcoming',
            'total_staff_in_office',
            'completed_trainings_in_office',
            'training_completed',
            'training_pending',
            'training_overdue',
            'result_head_upcoming_list',
            'result_head_activities',
            'training_records',
            'office_staff'
        ));
    }
    
    /**
     * Get training records for a specific staff member in the office head's office.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStaffTrainings($userId)
    {
        $user = Auth::user();
        
        // Verify that the staff member belongs to the office head's office
        $staffMember = User::where('user_id', $userId)
            ->where('office_code', $user->office_code)
            ->where('role', 'staff')
            ->first();
            
        if (!$staffMember) {
            return response()->json([
                'success' => false,
                'message' => 'Staff member not found or does not belong to your office.'
            ], 404);
        }
        
        // Get training records for this staff member with proof relationship
        $trainings = TrainingRecord::with('proof')
            ->where('user_id', $userId)
            ->select('id', 'title', 'description', 'start_date', 'end_date', 'venue', 'nature_of_training', 'scope', 'status')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Add proof information to each training record
        $trainings->each(function ($training) {
            $training->proof_document = $training->proof ? $training->proof->file_path : null;
        });
        
        return response()->json([
            'success' => true,
            'trainings' => $trainings
        ]);
    }
}