<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\FieldTaskController;
use App\Http\Controllers\Api\ChannelController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\OrganisationModuleController;

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

Route::middleware('auth:sanctum')->group(function () {
    // User endpoints
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/user/org-id', [UserController::class, 'getOrganisationId']);
    
    // Store endpoints
    Route::apiResource('stores', StoreController::class);
    
    // Client endpoints
    Route::apiResource('clients', ClientController::class);
    
    // Product endpoints
    Route::apiResource('products', ProductController::class);
    
    // Order endpoints
    Route::apiResource('orders', OrderController::class);
    Route::get('/orders/{order}/items', [OrderController::class, 'items']);
    
    // Project endpoints
    Route::apiResource('projects', ProjectController::class);
    Route::get('/projects/{project}/tasks', [ProjectController::class, 'tasks']);
    
    // Task endpoints
    Route::apiResource('tasks', TaskController::class);
    
    // Field Task endpoints
    Route::apiResource('field-tasks', FieldTaskController::class);
    
    // Channel endpoints
    Route::apiResource('channels', ChannelController::class);
    Route::get('/channels/{channel}/messages', [ChannelController::class, 'messages']);
    
    // Message endpoints
    Route::post('/channels/{channel}/messages', [MessageController::class, 'store']);
    
    // Organisation Modules endpoints
    Route::get('/modules', [OrganisationModuleController::class, 'index']);
    Route::put('/modules', [OrganisationModuleController::class, 'update']);
});
