<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            
        return view('training_assignments.index', compact('assignments'));
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
        
        // Get all staff members
        $staff = User::where('role', 'staff')->get();
        
        return view('training_assignments.create', compact('trainings', 'staff'));
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
        
        $request->validate([
            'training_id' => 'required|exists:training_records,id',
            'staff_ids' => 'required|array|min:1',
            'staff_ids.*' => 'exists:users,user_id',
            'deadline' => 'required|date|after:today',
        ]);
        
        $assignments = [];
        
        foreach ($request->staff_ids as $staff_id) {
            $assignment = new TrainingAssignment();
            $assignment->training_id = $request->training_id;
            $assignment->staff_id = $staff_id;
            $assignment->assigned_by = $user->user_id;
            $assignment->assigned_date = now();
            $assignment->deadline = $request->deadline;
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
     * @return \Illuminate\Http\Response
     */
    public function myAssignments()
    {
        $user = Auth::user();
        
        // Only staff can view their assigned trainings
        if ($user->role !== 'staff') {
            abort(403, 'Unauthorized access');
        }
        
        // Get training assignments for the current staff member
        $assignments = TrainingAssignment::with(['training', 'assignedBy'])
            ->where('staff_id', $user->user_id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('training_assignments.my_assignments', compact('assignments'));
    }
}
