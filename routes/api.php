<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum', 'log.request'])->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Projects
    Route::get('projects', [ProjectController::class, 'index']);
    Route::get('projects/{project}', [ProjectController::class, 'show']);

    Route::middleware('role:admin')->group(function () {
        Route::post('projects', [ProjectController::class, 'store']);
        Route::put('projects/{project}', [ProjectController::class, 'update']);
        Route::delete('projects/{project}', [ProjectController::class, 'destroy']);
    });

    // Tasks
    Route::get('projects/{project}/tasks', [TaskController::class, 'index']);
    Route::get('tasks/{task}', [TaskController::class, 'show']);

    Route::middleware('role:manager')->group(function () {
        Route::post('projects/{project}/tasks', [TaskController::class, 'store']);
        Route::delete('tasks/{task}', [TaskController::class, 'destroy']);
    });

    // Update allowed for manager OR assigned user handled in controller
    Route::put('tasks/{task}', [TaskController::class, 'update']);

    // Comments
    Route::post('tasks/{task}/comments', [CommentController::class, 'store']);
    Route::get('tasks/{task}/comments', [CommentController::class, 'index']);
});
