<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

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

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('user-profile', [AuthController::class, 'userProfile']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('user')->group(function () {
        Route::post('sync-collection', [UserController::class, 'syncCollection']);
        Route::get('get-collection', [UserController::class, 'getUserCollection']);
        Route::post('add-friend', [UserController::class, 'addFriend']);
        Route::delete('remove-friend', [UserController::class, 'removeFriend']);
        Route::get('search-users', [UserController::class, 'searchUsers']);
        Route::post('change-password', [UserController::class, 'changePassword']);
        Route::post('change-profile-visibility', [UserController::class, 'changeProfileVisibility']);
    });
});

Route::get('users', [AuthController::class, 'allUsers']);
