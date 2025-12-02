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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all offices
        $offices = Office::all();
        
        // Get all users with their office information, staff details, and training records
        $users = User::with(['office', 'staffDetail', 'trainingRecords'])->get();
        
        // Get training statistics
        $trainingStats = $this->getTrainingStats();
        
        // Get office statistics
        $officeStats = $this->getOfficeStats();
        
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
     */
    private function getTrainingStats()
    {
        $totalTrainings = TrainingRecord::count();
        $completedTrainings = TrainingRecord::where('status', 'completed')->count();
        $upcomingTrainings = TrainingRecord::where('status', 'upcoming')->count();
        $ongoingTrainings = TrainingRecord::where('status', 'ongoing')->count();
        
        return [
            'total' => $totalTrainings,
            'completed' => $completedTrainings,
            'upcoming' => $upcomingTrainings,
            'ongoing' => $ongoingTrainings
        ];
    }
    
    /**
     * Get office statistics.
     */
    private function getOfficeStats()
    {
        $offices = Office::all();
        $officeStats = [];
        
        foreach ($offices as $office) {
            $staffCount = User::where('office_code', $office->code)
                ->whereIn('role', ['staff', 'head'])
                ->count();
                
            $trainingCount = TrainingRecord::where('office_code', $office->code)
                ->count();
                
            $completedTrainingCount = TrainingRecord::where('office_code', $office->code)
                ->where('status', 'completed')
                ->count();
                
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