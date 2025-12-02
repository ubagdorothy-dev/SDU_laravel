<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\TrainingRecord;
use App\Models\TrainingProof;

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
    public function upload(Request $request, $id)
    {
        $user = Auth::user();
        
        try {
            // Validate the request
            $request->validate([
                'proof_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // Max 2MB
            ]);
            
            // Find the training record
            $trainingRecord = TrainingRecord::where('user_id', $user->user_id)
                ->where('id', $id)
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
                $filename = 'proof_' . $id . '_' . $user->user_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Store the file
                $path = $file->storeAs('training_proofs', $filename, 'public');
                
                // Create training proof record
                $trainingProof = new TrainingProof();
                $trainingProof->training_id = $id;
                $trainingProof->user_id = $user->user_id;
                $trainingProof->file_path = $path;
                $trainingProof->status = 'pending';
                $trainingProof->save();
                
                // Update training record
                $trainingRecord->proof_uploaded = true;
                $trainingRecord->save();
                
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
     * Download a training proof file.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download($proof_id)
    {
        $user = Auth::user();
        
        // Find the training proof
        $trainingProof = TrainingProof::where('user_id', $user->user_id)
            ->where('id', $proof_id)
            ->firstOrFail();
            
        // Check if file exists
        if (!Storage::disk('public')->exists($trainingProof->file_path)) {
            abort(404);
        }
        
        // Return the file for download
        return Storage::disk('public')->download($trainingProof->file_path);
    }
}