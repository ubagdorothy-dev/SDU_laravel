<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\TrainingAssignment;
use App\Models\TrainingRecord;
use App\Models\User;

class TrainingAssignmentController extends Controller
{
    /**
     * Display a listing of training assignments for unit directors.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        
        // Only unit directors can view all training assignments
        if ($user->role !== 'unit_director' && $user->role !== 'unit director') {
            abort(403, 'Unauthorized access');
        }
        
        // Get all training assignments with related data
        $assignments = TrainingAssignment::with(['training', 'staff', 'assignedBy'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Get pending approvals count for sidebar
        $pendingApprovalsCount = User::where('is_approved', 0)
            ->whereIn('role', ['staff', 'head'])
            ->count();
            
        return view('training_assignments.index', compact('assignments', 'user', 'pendingApprovalsCount'));
    }
    
    /**
     * Show the form for assigning trainings (Unit Director).
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        
        // Only unit directors can assign trainings
        if ($user->role !== 'unit_director' && $user->role !== 'unit director') {
            abort(403, 'Unauthorized access');
        }
        
        // Get all training records that can be assigned
        $trainings = TrainingRecord::all();
        
        // Get all staff members and office heads with office information
        $staff = User::whereIn('role', ['staff', 'head'])
            ->with('office')
            ->get()
            ->groupBy('office_code');
        
        // Get pending approvals count for unit directors
        $pendingApprovalsCount = 0;
        if (in_array($user->role, ['unit director', 'unit_director'])) {
            $pendingApprovalsCount = User::where('is_approved', 0)
                ->whereIn('role', ['staff', 'head'])
                ->count();
        }
        
        return view('training_assignments.create', compact('trainings', 'staff', 'user', 'pendingApprovalsCount'));
    }
    
    /**
     * Store a newly created training assignment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Only unit directors can assign trainings
        if ($user->role !== 'unit_director' && $user->role !== 'unit director') {
            abort(403, 'Unauthorized access');
        }
        
        // Validate the request
        $validatedData = $request->validate([
            'training_id' => 'nullable|exists:training_records,id',
            'custom_training_title' => 'nullable|string|max:255',
            'staff_ids' => 'required|array|min:1',
            'staff_ids.*' => 'exists:users,user_id',
            'deadline' => 'required|date|after:today',
            'deadline_date' => 'nullable|date|after:today',
            'deadline_time' => 'nullable|date_format:H:i',
        ]);
        
        // Handle combined deadline if sent as separate date/time
        if (!empty($validatedData['deadline_date'])) {
            $deadlineTime = $validatedData['deadline_time'] ?? '23:59';
            $validatedData['deadline'] = $validatedData['deadline_date'] . ' ' . $deadlineTime;
        }
        
        // Custom validation to ensure either training_id or custom_training_title is provided
        if (empty($validatedData['training_id']) && empty($validatedData['custom_training_title'])) {
            return redirect()->back()->withErrors(['training_id' => 'Either select a training or enter a custom training title.'])->withInput();
        }
        
        // Handle custom training creation
        $trainingId = $validatedData['training_id'] ?? null;
        if (!empty($validatedData['custom_training_title'])) {
            $trainingRecord = new TrainingRecord();
            $trainingRecord->user_id = $user->user_id;
            $trainingRecord->title = $validatedData['custom_training_title'];
            $trainingRecord->description = 'Custom training created on ' . now()->format('Y-m-d');
            $trainingRecord->start_date = now();
            $trainingRecord->end_date = now();
            $trainingRecord->status = 'pending';
            $trainingRecord->venue = 'Custom';
            $trainingRecord->proof_uploaded = false;
            $trainingRecord->office_code = $user->office_code ?? 'MAIN';
            $trainingRecord->scope = 'individual';
            $trainingRecord->save();
            
            $trainingId = $trainingRecord->id;
        }
        
        $assignments = [];
        
        foreach ($validatedData['staff_ids'] as $staff_id) {
            $assignment = new TrainingAssignment();
            $assignment->training_id = $trainingId;
            $assignment->staff_id = $staff_id;
            $assignment->assigned_by = $user->user_id;
            $assignment->assigned_date = now();
            $assignment->deadline = $validatedData['deadline'];
            $assignment->status = 'pending';
            $assignment->save();
            
            $assignments[] = $assignment;
        }
        
        return redirect()->route('training_assignments.index')
            ->with('success', 'Trainings assigned successfully.');
    }
    
    /**
     * Display a listing of assigned trainings for staff members.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function myAssignments(Request $request)
    {
        $user = Auth::user();
        
        // Both staff and office heads can view their assigned trainings
        if (!in_array($user->role, ['staff', 'head'])) {
            abort(403, 'Unauthorized access');
        }
        
        // Get training assignments for the current user (staff or office head)
        $assignments = TrainingAssignment::with(['training', 'assignedBy'])
            ->where('staff_id', $user->user_id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Return JSON for AJAX requests (modal loading)
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'assignments' => $assignments
            ]);
        }
            
        return view('training_assignments.my_assignments', compact('assignments'));
    }
}