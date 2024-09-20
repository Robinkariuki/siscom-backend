<?php

namespace App\Http\Controllers;

use App\Models\Talent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class TalentController extends Controller
{
    /**
     * Display a listing of the talents with pagination.
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1); // Get the page number, default to 1
        $limit = $request->input('per_page', 10); // Get the limit per page, default to 10
        $search = $request->input('search', ''); // Get the search query from request
    
        // Query talents with dynamic search filters
        $query = Talent::query();
    
        if (!empty($search)) {
            // Check if the search term is numeric
            if (is_numeric($search)) {
                // If the search term is numeric, only search in years_of_experience
                $query->where('years_of_experience', (int)$search);
            } else {
                // Otherwise, search in other text-based fields
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%")
                      ->orWhere('linkedin_profile', 'like', "%$search%")
                      ->orWhere('specialization', 'like', "%$search%")
                      ->orWhere('technical_skills', 'like', "%$search%");
                });
            }
        }
    
        // Collect years of experience filter from request
        $years_of_experience = $request->input('years_of_experience', null);
    
        // Apply additional years_of_experience filter if provided
        if (!is_null($years_of_experience) && is_numeric($years_of_experience)) {
            $query->where('years_of_experience', $years_of_experience); // Exact match for years of experience
        }
    
        // Paginate the results
        $talents = $query->paginate($limit, ['*'], 'page', $page);
    
        return response()->json($talents);
    }
    
    
    
    

    /**
     * Store a newly created talent in storage.
     */
    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:talents,email',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'years_of_experience' => 'nullable|integer',
            'linkedin_profile' => 'nullable|url',
            'previous_work_portfolio' => 'nullable|string',
            'specialization' => 'nullable|string|max:255',
            'technical_skills' => 'nullable|string',
        ]);
    
        // Handle file upload
        if ($request->hasFile('resume')) {
            try {
                $path = $request->file('resume')->store('resumes', 'public');
                $validatedData['resume'] = $path;
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Failed to upload resume',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    
        // Create a new talent record
        try {
            $talent = Talent::create($validatedData);
            return response()->json([
                'message' => 'Talent created successfully',
                'data' => $talent
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create talent record',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    

    /**
     * Display the specified talent.
     */
    public function show(Talent $talent)
    {
        return response()->json($talent);
    }

    /**
     * Update the specified talent in storage.
     */
    public function update(Request $request, Talent $talent)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:talents,email,' . $talent->id,
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'years_of_experience' => 'nullable|integer',
            'linkedin_profile' => 'nullable|url',
            'previous_work_portfolio' => 'nullable|string',
            'specialization' => 'nullable|string|max:255',
            'technical_skills' => 'nullable|string',
        ]);

        // Handle file upload
        if ($request->hasFile('resume')) {
            // Delete the old file if exists
            if ($talent->resume) {
                Storage::disk('public')->delete($talent->resume);
            }

            $path = $request->file('resume')->store('resumes', 'public');
            $validatedData['resume'] = $path;
        }

        // Update the talent record
        $talent->update($validatedData);

        return response()->json($talent);
    }

    /**
     * Remove the specified talent from storage.
     */
    public function destroy(Talent $talent)
    {
        // Delete the file if exists
        if ($talent->resume) {
            Storage::disk('public')->delete($talent->resume);
        }

        // Delete the talent record
        $talent->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
