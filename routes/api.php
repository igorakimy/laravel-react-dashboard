<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\TypeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\ZohoBooksSettingsController;
use App\Http\Controllers\Integration\ZohoBooksController;
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
     * Auth routes.
     */
    Route::post('/logout', [AuthController::class, 'logout']);

    /*
     * Roles routes.
     */
    Route::apiResource('/roles', RoleController::class);

    /**
     * Permissions routes.
     */
    Route::apiResource('/permissions', PermissionController::class)->only('index');

    /*
     * Categories routes.
     */
    Route::apiResource('/categories', CategoryController::class);

    /*
     * Colors routes.
     */
    Route::apiResource('/colors', ColorController::class)->only('index');

    /*
     * Materials routes.
     */
    Route::apiResource('/materials', MaterialController::class)->only('index');

    /*
     * Vendors routes.
     */
    Route::apiResource('/vendors', VendorController::class)->only('index');

    /*
     * Types routes.
     */
    Route::apiResource('/types', TypeController::class)->only('index');

    /*
     * Products routes.
     */
    Route::apiResource('/products', ProductController::class);
    Route::post('/products/{product}/upload-media', [ProductController::class, 'uploadMedia'])
         ->name('products.upload_media');
    Route::delete('/products/{product}/delete-media/{media}', [ProductController::class, 'deleteMedia'])
         ->name('products.delete_media');

    /**
     * Settings routes.
     */
    Route::get('/settings/zoho-books', [ZohoBooksSettingsController::class, 'index'])
         ->name('settings.zoho_books.index');
    Route::put('/settings/zoho-books', [ZohoBooksSettingsController::class, 'update'])
         ->name('settings.zoho_books.update');
});

/**
 * Integrations routes.
 */
Route::middleware(['auth:sanctum'])->prefix('integrations')->group(function () {
    /**
     * Zoho Books routes.
     */
    Route::group(['prefix' => 'zoho-books'], function () {
        Route::get('/authenticate', [ZohoBooksController::class, 'authenticateUrl'])
             ->name('integrations.zoho_books.authenticate');
        Route::get('/callback', [ZohoBooksController::class, 'handleAuthCallback'])
             ->name('integrations.zoho_books.callback');
    });
});

/*
 * Guest auth routes.
 */
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

/**
 * Guest user routes.
 */
Route::get('/statuses', [UserController::class, 'showStatuses']);

