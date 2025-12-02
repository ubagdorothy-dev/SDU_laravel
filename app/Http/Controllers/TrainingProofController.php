<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\TrainingRecord;
use App\Models\TrainingProof;
use App\Models\User;
use App\Models\Notification;

class TrainingProofController extends Controller
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
     * Upload a proof for a training record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $trainingRecordId
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request, $training_record)
    {
        $user = Auth::user();
        
        try {
            // Validate the request
            $request->validate([
                'proof_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // Max 2MB
            ]);
            
            // Find the training record
            $trainingRecord = TrainingRecord::where('user_id', $user->user_id)
                ->where('id', $training_record)
                ->firstOrFail();
                
            // Check if training is completed
            if ($trainingRecord->status !== 'completed') {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'You can only upload proof for completed trainings.'
                    ]);
                }
                return redirect()->back()->withErrors(['error' => 'You can only upload proof for completed trainings.']);
            }
            
            // Handle file upload
            if ($request->hasFile('proof_file')) {
                $file = $request->file('proof_file');
                $filename = 'proof_' . $training_record . '_' . $user->user_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Store the file
                $path = $file->storeAs('training_proofs', $filename, 'public');
                
                // Create training proof record
                $trainingProof = new TrainingProof();
                $trainingProof->training_id = $training_record;
                $trainingProof->user_id = $user->user_id;
                $trainingProof->file_path = $path;
                $trainingProof->status = 'pending';
                $trainingProof->save();
                
                // Update training record
                $trainingRecord->proof_uploaded = true;
                $trainingRecord->save();
                
                // Notify office head(s) in the same office
                $this->notifyOfficeHeads($trainingRecord, $user);
                
                \Log::info('Checking if request is AJAX (upload): ' . ($request->ajax() ? 'true' : 'false'));
                \Log::info('Request headers (upload): ' . json_encode($request->headers->all()));
                if ($request->ajax()) {
                    \Log::info('Returning JSON response for AJAX request (upload)');
                    return response()->json([
                        'success' => true,
                        'message' => 'Proof uploaded successfully.'
                    ]);
                }
                
                return redirect()->back()->with('success', 'Proof uploaded successfully.');
            }
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'No file was uploaded.'
                ]);
            }
            
            return redirect()->back()->withErrors(['error' => 'No file was uploaded.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation errors for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            // Re-throw for non-AJAX requests
            throw $e;
        } catch (\Exception $e) {
            // Handle any other exceptions
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage()
                ], 500);
            }
            // Re-throw for non-AJAX requests
            throw $e;
        }
    }
    
    /**
     * Notify office heads when a training proof is uploaded.
     *
     * @param  TrainingRecord  $trainingRecord
     * @param  User  $staffUser
     * @return void
     */
    private function notifyOfficeHeads($trainingRecord, $staffUser)
    {
        // Find office heads in the same office
        $officeHeads = User::where('role', 'head')
            ->where('office_code', $staffUser->office_code)
            ->get();
            
        // Find the training proof that was just uploaded
        $trainingProof = TrainingProof::where('training_id', $trainingRecord->id)
            ->where('user_id', $staffUser->user_id)
            ->latest()
            ->first();
            
        foreach ($officeHeads as $head) {
            $notification = new Notification();
            $notification->user_id = $head->user_id;
            $notification->title = 'New Training Proof Uploaded';
            
            // Include a reference to the training proof in the message
            if ($trainingProof) {
                $notification->message = "Staff member {$staffUser->full_name} has uploaded proof for training: {$trainingRecord->title}. Please review. [View Proof](proof:{$trainingProof->id})";
            } else {
                $notification->message = "Staff member {$staffUser->full_name} has uploaded proof for training: {$trainingRecord->title}. Please review.";
            }
            
            $notification->is_read = false;
            $notification->save();
        }
    }
    
    /**
     * View or download a training proof file.
     *
     * @param  int  $proof_id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function view($proof_id, Request $request)
    {
        $user = Auth::user();
        
        // Find the training proof
        $trainingProofQuery = TrainingProof::where('id', $proof_id);
        
        // If user is not the owner, check if they are an office head for the same office
        if ($user->role !== 'staff') {
            $trainingProofQuery->whereHas('trainingRecord', function($query) use ($user) {
                $query->where('office_code', $user->office_code);
            });
        } else {
            // For staff users, ensure they own the proof
            $trainingProofQuery->where('user_id', $user->user_id);
        }
        
        $trainingProof = $trainingProofQuery->firstOrFail();
            
        // Check if file exists
        if (!Storage::disk('public')->exists($trainingProof->file_path)) {
            abort(404);
        }
        
        // Check if user wants to download instead of view
        if ($request->has('download')) {
            return Storage::disk('public')->download($trainingProof->file_path);
        }
        
        // Return the file for viewing in browser
        return Storage::disk('public')->response($trainingProof->file_path);
    }
    
    /**
     * Download a training proof file (deprecated, kept for backward compatibility).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download($proof_id)
    {
        return $this->view($proof_id, request());
    }
}