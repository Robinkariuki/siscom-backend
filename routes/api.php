<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EngagementController;
use App\Http\Controllers\TalentController;
use App\Http\Controllers\AuthController;

/*
|-------------------------------------------------------------------------- 
| API Routes 
|-------------------------------------------------------------------------- 
| 
| Here is where you can register API routes for your application. These 
| routes are loaded by the RouteServiceProvider and all of them will 
| be assigned to the "api" middleware group. Make something great! 
| 
*/

// Public POST routes (not protected by auth)
Route::prefix('engagements')->group(function () {
    Route::post('/', [EngagementController::class, 'store']); // Create a new engagement
});

Route::prefix('talents')->group(function () {
    Route::post('/', [TalentController::class, 'store']); // Create a new talent
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    Route::post('user/update', [AuthController::class, 'update']);

    Route::prefix('engagements')->group(function () {
        Route::get('/', [EngagementController::class, 'index']);          // Get all engagements with pagination
        Route::get('/{id}', [EngagementController::class, 'show']);       // Get a specific engagement
        Route::put('/{id}', [EngagementController::class, 'update']);     // Update an existing engagement
        Route::delete('/{id}', [EngagementController::class, 'destroy']); // Delete an engagement
    });

    // Talent routes
    Route::prefix('talents')->group(function () {
        Route::get('/', [TalentController::class, 'index']);              // Get all talents with pagination
        Route::get('/{talent}', [TalentController::class, 'show']);       // Get a specific talent
        Route::put('/{talent}', [TalentController::class, 'update']);     // Update an existing talent
        Route::delete('/{talent}', [TalentController::class, 'destroy']); // Delete a talent
    });
});




// Health check / show available endpoints
Route::get('/endpoints', function () {
    return response()->json([
        'endpoints' => [
            'POST /api/engagements' => 'Create a new engagement',
            'GET /api/engagements' => 'Get all engagements with pagination',
            'GET /api/engagements/{id}' => 'Get a specific engagement',
            'PUT /api/engagements/{id}' => 'Update an engagement',
            'DELETE /api/engagements/{id}' => 'Delete an engagement',
            'POST /api/talents' => 'Create a new talent',
            'GET /api/talents' => 'Get all talents with pagination',
            'GET /api/talents/{talent}' => 'Get a specific talent',
            'PUT /api/talents/{talent}' => 'Update a talent',
            'DELETE /api/talents/{talent}' => 'Delete a talent',
            'POST /api/login' => 'User login',
            'POST /api/register' => 'User registration',
            'GET /api/user' => 'Get authenticated user information',
            'POST /api/user/update' => 'Update authenticated user information'
        ]
    ]);
});