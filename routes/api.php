<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum', 'permission'])->group(function() {

    /*
     * Users routes.
     */
    Route::get('/user', [AuthController::class, 'user']);
    Route::apiResource('/users', UserController::class);

    /*
     * Roles routes.
     */
    Route::apiResource('/roles', RoleController::class);

    /**
     * Permissions routes.
     */
    Route::apiResource('/permissions', PermissionController::class)->only('index');

    /*
     * Auth routes.
     */
    Route::post('/logout', [AuthController::class, 'logout']);
});

/*
 * Guest routes.
 */
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

