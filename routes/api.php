<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EngagementController;
use App\Http\Controllers\TalentController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::prefix('engagements')->group(function () {
    Route::get('/', [EngagementController::class, 'index']);          // Get all engagements with pagination
    Route::post('/', [EngagementController::class, 'store']);         // Create a new engagement
    Route::get('/{id}', [EngagementController::class, 'show']);       // Get a specific engagement
    Route::put('/{id}', [EngagementController::class, 'update']);     // Update an existing engagement
    Route::delete('/{id}', [EngagementController::class, 'destroy']); // Delete an engagement
});



// Talent routes
Route::prefix('talents')->group(function () {
    Route::get('/', [TalentController::class, 'index']);              // Get all talents with pagination
    Route::post('/', [TalentController::class, 'store']);             // Create a new talent
    Route::get('/{talent}', [TalentController::class, 'show']);       // Get a specific talent
    Route::put('/{talent}', [TalentController::class, 'update']);     // Update an existing talent
    Route::delete('/{talent}', [TalentController::class, 'destroy']); // Delete a talent
});



