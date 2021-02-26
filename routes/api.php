<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/galleries', [GallerrController::class, 'index']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/galleries/{id}', [GalleryController::class, 'show']);
    Route::get('/author/{id}', [UserController::class, 'show']);
    Route::post('/galleries', [GalleryController::class, 'store']);
    Route::post('/galleries/{id}/comments', [CommentController::class, 'store']);
    Route::put('/edit-gallery/{id}', [GalleryController::class, 'edit']);
    Route::delete('/delete-comment/{id}', [CommentController::class, 'destroy']);
    Route::delete('/galleries/{id}', [GalleryController::class, 'destroy']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/usergallery', [AuthController::class, 'userGallery']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::post('/refresh', [AuthController::class, 'refreshToken']);
