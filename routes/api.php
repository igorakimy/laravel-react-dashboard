<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\ImportController;
use App\Http\Controllers\Api\IntegrationController;
use App\Http\Controllers\Api\InvitationController;
use App\Http\Controllers\Api\LocalFieldController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\MetaController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\TemporaryFileController;
use App\Http\Controllers\Api\TypeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\ZohoBooksSettingsController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Integration\IntegrationFieldController;
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

    /**
     * Invitations routes.
     */
    Route::group(['prefix' => 'invitations', 'as' => 'invitations.'], function () {
        Route::get('/', [InvitationController::class, 'index'])->name('index');
        Route::post('/send', [InvitationController::class, 'send'])->name('send');
        Route::post('/resend/{invitation}', [InvitationController::class, 'resend'])->name('resend');
        Route::post('/revoke/{invitation}', [InvitationController::class, 'revoke'])->name('revoke');
    });

    /*
     * Categories routes.
     */
    Route::apiResource('/categories', CategoryController::class);

    /**
     * Meta fields routes.
     */
    Route::apiResource('/metas', MetaController::class);

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
    Route::post('/products/export', [ProductController::class, 'export'])
         ->name('products.export');
    Route::post('/products/import', [ProductController::class, 'import'])
         ->name('products.import');
    Route::delete('/products/bulk-delete', [ProductController::class, 'bulkDestroy'])
         ->name('products.bulk-delete');
    Route::apiResource('/products', ProductController::class);
    Route::post('/products/{product}/upload-media/{collection}', [ProductController::class, 'uploadMedia'])
         ->name('products.upload_media');
    Route::delete('/products/{product}/delete-media/{media}', [ProductController::class, 'deleteMedia'])
         ->name('products.delete_media');

    /*
     * Local product fields.
     */
    Route::apiResource('/local-fields', LocalFieldController::class)
         ->only('index');

    /**
     * Integration product fields
     */
    Route::post('/integration-fields/mapping/{integration}', [IntegrationFieldController::class, 'mapping']);
    Route::get('/integration-fields/mapped/{integration}', [IntegrationFieldController::class, 'mapped']);
    Route::apiResource('/integration-fields/{integration}', IntegrationFieldController::class)
         ->only('index');

    /**
     * Settings routes.
     */
    Route::get('/settings/zoho-books', [ZohoBooksSettingsController::class, 'index'])
         ->name('settings.zoho_books.index');
    Route::put('/settings/zoho-books', [ZohoBooksSettingsController::class, 'update'])
         ->name('settings.zoho_books.update');

    /**
     * Media routes.
     */
    Route::put('/medias/bulk-update', [MediaController::class, 'bulkUpdate'])->name('medias.bulk-update');
    Route::delete('/medias/bulk-delete', [MediaController::class, 'bulkDelete'])->name('medias.bulk-delete');
    Route::apiResource('/medias', MediaController::class)->only('show', 'update');

    /**
     * Integrations routes.
     */
    Route::apiResource('/integrations', IntegrationController::class)->only('index');

    /**
     * Import routes.
     */
    Route::get('/imports/headers', [ImportController::class, 'headers'])
         ->name('imports.headers');
});

/**
 * Specific integrations routes.
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
 * Temporary files.
 */
Route::middleware(['auth:sanctum'])->prefix('temporary')->group(function () {
   Route::post('/upload', [TemporaryFileController::class, 'upload'])
        ->name('temporary.upload');
});

/*
 * Guest auth routes.
 */
Route::post('/register/{token}', [AuthController::class, 'register'])
    ->name('register');
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

/**
 * Guest user routes.
 */
Route::get('/statuses', [UserController::class, 'showStatuses']);

Route::post('/test', [Controller::class, 'test']);

