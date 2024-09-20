<?php

namespace App\Http\Controllers;

use App\Models\Engagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class EngagementController extends Controller
{
    // Get all engagements with pagination
    public function index(Request $request)
    {
        $page = $request->input('page', 1); // Get the page number from query parameters
        $limit = $request->input('per_page', 10); // Get the limit from query parameters
        $search = $request->input('search', ''); // Get the search query from query parameters
    
      

    
        // Fetch engagements with pagination and search
        $engagements = Engagement::where('name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
            ->orWhere('phone', 'like', "%$search%")
            ->orWhere('company', 'like', "%$search%")
            ->paginate($limit, ['*'], 'page', $page);
    
    
        return response()->json($engagements);
    }
    
    
    
    
    

    // Create a new engagement
    public function store(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'company' => 'required|string|max:255',
            'message' => 'required|string',
            'date' => 'required|date',
        ]);




        // Concatenate firstName and lastName to create the name field
        $name = $validatedData['firstName'] . ' ' . $validatedData['lastName'];
        $validatedData['name'] = $name;
        unset($validatedData['firstName'], $validatedData['lastName']);
        // Create a new engagement
        $engagement = Engagement::create($validatedData);

        return response()->json($engagement, 201);
    }

    // Get a specific engagement
    public function show($id)
    {
        $engagement = Engagement::findOrFail($id);
        return response()->json($engagement);
    }

    // Update an engagement
    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'company' => 'sometimes|required|string|max:255',
            'message' => 'sometimes|required|string',
            'date' => 'sometimes|required|date',
        ]);

        $engagement = Engagement::findOrFail($id);
        $engagement->update($request->all());

        return response()->json($engagement);
    }

    // Delete an engagement
    public function destroy($id)
    {
        $engagement = Engagement::findOrFail($id);
        $engagement->delete();

        return response()->json(null, 204);
    }
}
