<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TrainingRecord;
use App\Models\User;

class TrainingRecordController extends Controller
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
     * Display a listing of the training records.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get training records for the authenticated user
        $trainingRecords = TrainingRecord::where('user_id', $user->user_id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('training_records.index', compact('user', 'trainingRecords'));
    }

    /**
     * Show the form for creating a new training record.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        return view('training_records.create', compact('user'));
    }

    /**
     * Store a newly created training record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'venue' => 'nullable|string|max:255',
                'nature' => 'nullable|string|max:100',
                'scope' => 'nullable|string|max:100',
            ]);
            
            // Automatically determine status based on dates
            $currentDate = now()->format('Y-m-d');
            if ($request->end_date < $currentDate) {
                $status = 'completed';
            } elseif ($request->start_date <= $currentDate && $request->end_date >= $currentDate) {
                $status = 'ongoing';
            } else {
                $status = 'upcoming';
            }
            
            $trainingRecord = new TrainingRecord();
            $trainingRecord->user_id = $user->user_id;
            $trainingRecord->title = $request->title;
            $trainingRecord->description = $request->description;
            $trainingRecord->start_date = $request->start_date;
            $trainingRecord->end_date = $request->end_date;
            $trainingRecord->venue = $request->venue;
            $trainingRecord->nature = $request->nature;
            $trainingRecord->scope = $request->scope;
            $trainingRecord->status = $status;
            $trainingRecord->office_code = $user->office_code;
            $trainingRecord->save();
            
            // Return JSON response for AJAX requests
            \Log::info('Checking if request is AJAX (store): ' . ($request->ajax() ? 'true' : 'false'));
            \Log::info('Request headers (store): ' . json_encode($request->headers->all()));
            if ($request->ajax()) {
                \Log::info('Returning JSON response for AJAX request (store)');
                return response()->json([
                    'success' => true,
                    'message' => 'Training record created successfully.',
                    'data' => $trainingRecord
                ]);
            }
            
            return redirect()->route('training_records.index')->with('success', 'Training record created successfully.');
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
     * Display the specified training record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($training_record)
    {
        $user = Auth::user();
        $trainingRecord = TrainingRecord::where('user_id', $user->user_id)->findOrFail($training_record);
        
        return view('training_records.show', compact('user', 'trainingRecord'));
    }

    /**
     * Show the form for editing the specified training record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($training_record)
    {
        $user = Auth::user();
        $trainingRecord = TrainingRecord::where('user_id', $user->user_id)->findOrFail($training_record);
        
        return view('training_records.edit', compact('user', 'trainingRecord'));
    }

    /**
     * Update the specified training record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $training_record)
    {
        try {
            $user = Auth::user();
            $trainingRecord = TrainingRecord::where('user_id', $user->user_id)->findOrFail($training_record);
            
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'venue' => 'nullable|string|max:255',
                'nature' => 'nullable|string|max:100',
                'scope' => 'nullable|string|max:100',
            ]);
            
            // Automatically determine status based on dates
            $currentDate = now()->format('Y-m-d');
            if ($request->end_date < $currentDate) {
                $status = 'completed';
            } elseif ($request->start_date <= $currentDate && $request->end_date >= $currentDate) {
                $status = 'ongoing';
            } else {
                $status = 'upcoming';
            }
            
            $trainingRecord->title = $request->title;
            $trainingRecord->description = $request->description;
            $trainingRecord->start_date = $request->start_date;
            $trainingRecord->end_date = $request->end_date;
            $trainingRecord->venue = $request->venue;
            $trainingRecord->nature = $request->nature;
            $trainingRecord->scope = $request->scope;
            $trainingRecord->status = $status;
            $trainingRecord->save();
            
            // Return JSON response for AJAX requests
            \Log::info('Checking if request is AJAX: ' . ($request->ajax() ? 'true' : 'false'));
            \Log::info('Request headers: ' . json_encode($request->headers->all()));
            if ($request->ajax()) {
                \Log::info('Returning JSON response for AJAX request');
                return response()->json([
                    'success' => true,
                    'message' => 'Training record updated successfully.',
                    'data' => $trainingRecord
                ]);
            }
            
            return redirect()->route('training_records.index')->with('success', 'Training record updated successfully.');
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
     * Remove the specified training record from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($training_record)
    {
        $user = Auth::user();
        $trainingRecord = TrainingRecord::where('user_id', $user->user_id)->findOrFail($training_record);
        
        $trainingRecord->delete();
        
        return redirect()->route('training_records.index')->with('success', 'Training record deleted successfully.');
    }
    
    /**
     * Update training status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $training_record)
    {
        $user = Auth::user();
        $trainingRecord = TrainingRecord::where('user_id', $user->user_id)->findOrFail($training_record);
        
        $request->validate([
            'status' => 'required|string|in:upcoming,ongoing,completed',
        ]);
        
        $trainingRecord->status = $request->status;
        $trainingRecord->save();
        
        return redirect()->back()->with('success', 'Training status updated successfully.');
    }
}