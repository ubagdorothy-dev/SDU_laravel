<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Office;
use App\Models\TrainingRecord;

class DirectoryReportsController extends Controller
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
     * Display directory and reports.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get filter parameters
        $officeFilter = $request->get('office', 'all');
        $roleFilter = $request->get('role', 'all');
        $periodFilter = $request->get('period', 'all');
        
        // Get all offices
        $offices = Office::all();
        
        // Build user query with potential filtering
        $userQuery = User::with(['office', 'staffDetail', 'trainingRecords.proof']);
        
        // Apply office filter
        if ($officeFilter !== 'all') {
            $userQuery->where('office_code', $officeFilter);
        }
        
        // Apply role filter
        if ($roleFilter !== 'all') {
            $userQuery->where('role', $roleFilter);
        }
        
        // Apply period filter (based on created_at year)
        if ($periodFilter !== 'all') {
            $userQuery->whereYear('created_at', $periodFilter);
        }
        
        // Get filtered users
        $users = $userQuery->get();
        
        // Get training statistics (filtered)
        $trainingStats = $this->getTrainingStats($officeFilter, $roleFilter, $periodFilter);
        
        // Get office statistics (filtered)
        $officeStats = $this->getOfficeStats($officeFilter, $roleFilter, $periodFilter);
        
        // Get pending approvals count for unit directors
        $pendingApprovalsCount = 0;
        if (in_array($user->role, ['unit director', 'unit_director'])) {
            $pendingApprovalsCount = User::where('is_approved', 0)
                ->whereIn('role', ['staff', 'head'])
                ->count();
        }
        
        return view('directory_reports.index', compact('user', 'offices', 'users', 'trainingStats', 'officeStats', 'pendingApprovalsCount'));
    }
    
    /**
     * Get training statistics.
     *
     * @param string $officeFilter
     * @param string $roleFilter
     * @param string $periodFilter
     */
    private function getTrainingStats($officeFilter = 'all', $roleFilter = 'all', $periodFilter = 'all')
    {
        // Build training record query with potential filtering
        $trainingQuery = TrainingRecord::query();
        $completedQuery = clone $trainingQuery;
        $upcomingQuery = clone $trainingQuery;
        $ongoingQuery = clone $trainingQuery;
        
        // Apply office filter if not 'all'
        if ($officeFilter !== 'all') {
            $trainingQuery->where('office_code', $officeFilter);
            $completedQuery->where('office_code', $officeFilter);
            $upcomingQuery->where('office_code', $officeFilter);
            $ongoingQuery->where('office_code', $officeFilter);
        }
        
        // Apply role filter if not 'all' (need to join with users table)
        if ($roleFilter !== 'all') {
            $userIds = User::where('role', $roleFilter);
            if ($officeFilter !== 'all') {
                $userIds->where('office_code', $officeFilter);
            }
            $userIds = $userIds->pluck('user_id');
            
            $trainingQuery->whereIn('user_id', $userIds);
            $completedQuery->whereIn('user_id', $userIds);
            $upcomingQuery->whereIn('user_id', $userIds);
            $ongoingQuery->whereIn('user_id', $userIds);
        }
        
        // Apply period filter if not 'all'
        if ($periodFilter !== 'all') {
            $trainingQuery->whereYear('created_at', $periodFilter);
            $completedQuery->whereYear('created_at', $periodFilter);
            $upcomingQuery->whereYear('created_at', $periodFilter);
            $ongoingQuery->whereYear('created_at', $periodFilter);
        }
        
        $totalTrainings = $trainingQuery->count();
        $completedTrainings = $completedQuery->where('status', 'completed')->count();
        $upcomingTrainings = $upcomingQuery->where('status', 'upcoming')->count();
        $ongoingTrainings = $ongoingQuery->where('status', 'ongoing')->count();
        
        return [
            'total' => $totalTrainings,
            'completed' => $completedTrainings,
            'upcoming' => $upcomingTrainings,
            'ongoing' => $ongoingTrainings
        ];
    }
    
    /**
     * Get office statistics.
     *
     * @param string $officeFilter
     * @param string $roleFilter
     * @param string $periodFilter
     */
    private function getOfficeStats($officeFilter = 'all', $roleFilter = 'all', $periodFilter = 'all')
    {
        // Get offices (filtered if needed)
        $officesQuery = Office::query();
        if ($officeFilter !== 'all') {
            $officesQuery->where('code', $officeFilter);
        }
        $offices = $officesQuery->get();
        
        $officeStats = [];
        
        foreach ($offices as $office) {
            // Build staff query with potential filtering
            $staffQuery = User::where('office_code', $office->code)
                ->whereIn('role', ['staff', 'head']);
                
            // Apply role filter if not 'all'
            if ($roleFilter !== 'all') {
                $staffQuery->where('role', $roleFilter);
            }
            
            // Apply period filter if not 'all'
            if ($periodFilter !== 'all') {
                $staffQuery->whereYear('created_at', $periodFilter);
            }
            
            $staffCount = $staffQuery->count();
                
            // Build training query with potential filtering
            $trainingQuery = TrainingRecord::where('office_code', $office->code);
            $completedTrainingQuery = clone $trainingQuery;
                
            // Apply role filter if not 'all' (need to join with users table)
            if ($roleFilter !== 'all') {
                $userIds = User::where('role', $roleFilter)
                    ->where('office_code', $office->code)
                    ->pluck('user_id');
                
                $trainingQuery->whereIn('user_id', $userIds);
                $completedTrainingQuery->whereIn('user_id', $userIds);
            }
            
            // Apply period filter if not 'all'
            if ($periodFilter !== 'all') {
                $trainingQuery->whereYear('created_at', $periodFilter);
                $completedTrainingQuery->whereYear('created_at', $periodFilter);
            }
                
            $trainingCount = $trainingQuery->count();
            $completedTrainingCount = $completedTrainingQuery->where('status', 'completed')->count();
                
            $officeStats[] = [
                'office' => $office,
                'staff_count' => $staffCount,
                'training_count' => $trainingCount,
                'completed_training_count' => $completedTrainingCount
            ];
        }
        
        return $officeStats;
    }
}