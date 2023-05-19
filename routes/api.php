<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserPlaySessionController;
use App\Http\Controllers\Api\UserStylusController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::prefix('post')->group(function () {
    Route::get('/', [PostController::class, 'index']);
    Route::get('{id}/no-auth', [PostController::class, 'show']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('user')->group(function () {
        Route::get('my-profile', [UserController::class, 'getMyProfile']);
        Route::get('{user}', [UserController::class, 'getProfile']);
        Route::post('sync-collection', [UserController::class, 'syncCollection']);
        Route::get('get-collection', [UserController::class, 'getUserCollection']);
        Route::post('add-friend', [UserController::class, 'addFriend']);
        Route::delete('remove-friend', [UserController::class, 'removeFriend']);
        Route::get('search-users', [UserController::class, 'searchUsers']);
        Route::post('change-password', [UserController::class, 'changePassword']);
        Route::post('change-profile-visibility', [UserController::class, 'changeProfileVisibility']);
    });

    Route::prefix('user-play-session')->group(function () {
        Route::post('create', [UserPlaySessionController::class, 'create']);
        Route::delete('delete', [UserPlaySessionController::class, 'delete']);
    });

    Route::prefix('user-stylus')->group(function () {
        Route::get('get-user-styluses', [UserStylusController::class, 'getUserStyluses']);
        Route::get('get-stylus-play-time', [UserStylusController::class, 'getStylusHours']);
        Route::post('create', [UserStylusController::class, 'create']);
        Route::put('{stylus}/toggle-retire', [UserStylusController::class, 'toggleRetire']);
        Route::delete('{stylus}/delete', [UserStylusController::class, 'delete']);
    });

    Route::prefix('post')->group(function () {
        Route::get('{id}', [PostController::class, 'show']);
        Route::post('/create', [PostController::class, 'create']);
        Route::post('/{post}/like', [PostController::class, 'toggleLike']);
        Route::put('/{post}/edit', [PostController::class, 'edit']);
        Route::delete('/{post}/delete', [PostController::class, 'delete']);
    });
});

Route::get('users', [AuthController::class, 'allUsers']);
