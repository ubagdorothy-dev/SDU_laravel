<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UnitDirectorController extends Controller
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
        // $this->middleware('role:unit_director');
    }

    /**
     * Show the unit director dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        
        // Fetch dashboard statistics
        $total_staff = DB::table('users')->where('role', 'staff')->where('is_approved', 1)->count();
        $total_heads = DB::table('users')->where('role', 'head')->where('is_approved', 1)->count();
        
        // Training completion percentage
        $total_trainings = DB::table('training_records')
            ->whereIn('user_id', function($query) {
                $query->select('user_id')->from('users')->whereIn('role', ['staff', 'head']);
            })
            ->count();
            
        $completed_trainings = DB::table('training_records')
            ->where('status', 'completed')
            ->whereIn('user_id', function($query) {
                $query->select('user_id')->from('users')->whereIn('role', ['staff', 'head']);
            })
            ->count();
            
        $training_completion_percentage = ($total_trainings > 0) ? round(($completed_trainings / $total_trainings) * 100) : 0;
        
        // Upcoming trainings
        $upcoming_trainings = DB::table('training_records')->where('status', 'upcoming')->count();
        
        // Offices with staff
        $active_offices = DB::table('users')
            ->whereNotNull('office_code')
            ->whereIn('role', ['staff', 'head'])
            ->distinct('office_code')
            ->count('office_code');
        
        // Chart data - Most attended training
        $most_attended = DB::table('training_records')
            ->select('title', DB::raw('COUNT(id) as attendance_count'))
            ->where('status', 'completed')
            ->groupBy('title')
            ->orderBy('attendance_count', 'desc')
            ->limit(1)
            ->first();
            
        $most_attended_title = $most_attended ? $most_attended->title : 'No data available';
        
        // Chart data - Least attended training
        $least_attended = DB::table('training_records')
            ->select('title', DB::raw('COUNT(id) as attendance_count'))
            ->where('status', 'completed')
            ->groupBy('title')
            ->orderBy('attendance_count', 'asc')
            ->limit(1)
            ->first();
            
        $least_attended_title = $least_attended ? $least_attended->title : 'No data available';
        
        // Chart data - Trainings this month
        $this_month_count = DB::table('training_records')
            ->where('status', 'completed')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();
        
        // Chart data - Attendance data (Last 6 months)
        $attendance_labels = [];
        $attendance_data = [];
        
        // Generate last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $month = date('M', strtotime("-{$i} months"));
            $attendance_labels[] = $month;
            
            // Get real data
            $count = DB::table('training_records')
                ->where('status', 'completed')
                ->whereMonth('created_at', date('m', strtotime("-{$i} months")))
                ->whereYear('created_at', date('Y', strtotime("-{$i} months")))
                ->count();
                
            $attendance_data[] = $count;
        }
        
        // Chart data - Training completion trends
        $completion_labels = [];
        $completion_data = [];
        
        $trainings = DB::table('training_records')
            ->select('title', DB::raw('COUNT(id) as completion_count'))
            ->where('status', 'completed')
            ->groupBy('title')
            ->orderBy('completion_count', 'desc')
            ->limit(6)
            ->get();
        
        foreach ($trainings as $training) {
            $completion_labels[] = strlen($training->title) > 15 ? substr($training->title, 0, 15) . '...' : $training->title;
            $completion_data[] = (int)$training->completion_count;
        }
        
        // If no trainings, provide default data
        if (empty($completion_labels)) {
            $completion_labels = ['No data'];
            $completion_data = [0];
        }
        
        // Recent training completions
        $recent_trainings = DB::table('training_records as tr')
            ->join('users as u', 'tr.user_id', '=', 'u.user_id')
            ->select('tr.title', 'tr.created_at as completion_date', 'u.full_name', DB::raw('COALESCE(u.office_code, "Unassigned") as office_code'))
            ->where('tr.status', 'completed')
            ->orderBy('tr.created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Format dates
        foreach ($recent_trainings as $training) {
            $training->completion_date = date('M j, Y', strtotime($training->completion_date));
        }
        
        // Office statistics
        $offices = DB::table('offices')->select('code', 'name')->get();
        
        $office_stats = [];
        foreach ($offices as $office) {
            // Get total staff in office
            $total_staff_count = DB::table('users')
                ->where(function($query) use ($office) {
                    $query->where('office_code', $office->code)
                          ->orWhereNull('office_code');
                })
                ->whereIn('role', ['staff', 'head'])
                ->count();
            
            // Get completed trainings for office
            $completed_trainings_count = DB::table('training_records as tr')
                ->join('users as u', 'tr.user_id', '=', 'u.user_id')
                ->where(function($query) use ($office) {
                    $query->where('u.office_code', $office->code)
                          ->orWhereNull('u.office_code');
                })
                ->where('tr.status', 'completed')
                ->count();
            
            $office_stats[] = [
                'office_name' => $office->name,
                'office_code' => $office->code,
                'total_staff' => $total_staff_count,
                'completed_trainings' => $completed_trainings_count
            ];
        }
        
        // Pending approvals count
        $pending_approvals = DB::table('users')
            ->where('is_approved', 0)
            ->whereIn('role', ['staff', 'head'])
            ->count();
        
        // Pending training assignments count
        $pending_trainings = DB::table('training_assignments')
            ->where('status', 'pending')
            ->count();
        
        // Pending training proofs (reports) count
        $pending_reports = DB::table('training_proofs')
            ->where('status', 'pending')
            ->count();
        
        // Get top performers (staff with most completed trainings)
        $top_performers = DB::table('training_records as tr')
            ->join('users as u', 'tr.user_id', '=', 'u.user_id')
            ->select(
                'u.user_id',
                'u.full_name',
                DB::raw('COUNT(tr.id) as completed_count')
            )
            ->where('tr.status', 'completed')
            ->whereIn('u.role', ['staff', 'head'])
            ->groupBy('u.user_id', 'u.full_name')
            ->orderBy('completed_count', 'desc')
            ->limit(10)
            ->get();
        
        // Pass all data to the view
        return view('unit_director.dashboard', compact(
            'user',
            'total_staff',
            'total_heads',
            'training_completion_percentage',
            'upcoming_trainings',
            'active_offices',
            'most_attended_title',
            'least_attended_title',
            'this_month_count',
            'attendance_labels',
            'attendance_data',
            'completion_labels',
            'completion_data',
            'recent_trainings',
            'office_stats',
            'pending_approvals',
            'pending_trainings',
            'pending_reports',
            'top_performers'
        ));
    }
}