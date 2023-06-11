<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\PostLike;
use App\Models\AlbumCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\api\AlbumController;
use App\Http\Controllers\Api\UserStylusController;
use App\Http\Controllers\Api\UserPlaySessionController;
use App\Models\UserFriend;

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

Route::get('runFactory', function () {
    $maxUsers = User::count();
    $randUsers = User::inRandomOrder()
        ->limit(rand(1, $maxUsers))
        ->get();
    foreach ($randUsers as $randUser) {
        $comment = new UserFriend();
        $comment->friend_id = $randUser->id;
        $comment->user_id = 7;
        $comment->save();
    }

    $albumCaches = AlbumCache::all();
    $users = (App\Models\User::factory()->count(50)->make());
    foreach ($users as $user) {
        $user->save();
        for ($i = 0; $i < 3; $i++) {
            $post = new Post();
            $post->text = fake()->text();
            $post->user_id = $user->id;
            $post->album_cache_id = $albumCaches[rand(0, sizeof($albumCaches) - 1)]->id;
            $post->save();
        }
    }

    Post::all()->each(function ($post) {
        $randUsers = User::inRandomOrder()
            ->limit(5)
            ->get();
        foreach ($randUsers as $randUser) {
            $comment = new Comment();
            $comment->body = fake()->text();
            $comment->post_id = $post->id;
            $comment->user_id = $randUser->id;
            $comment->comment_id = null;
            $comment->save();
        }

        $maxUsers = User::count();
        $randUsers = User::inRandomOrder()
            ->limit(rand(1, $maxUsers))
            ->get();
        foreach ($randUsers as $randUser) {
            $comment = new PostLike();
            $comment->post_id = $post->id;
            $comment->user_id = $randUser->id;
            $comment->save();
        }
    });
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::prefix('user')->group(function () {
    Route::get('profile/{user}', [UserController::class, 'getProfile']);
});

Route::prefix('post')->group(function () {
    Route::get('', [PostController::class, 'index']);
    Route::get('{id}', [PostController::class, 'show']);
    Route::get('/{post}/comment', [PostController::class, 'getComments']);
});

Route::prefix('album')->group(function () {
    Route::get('{discogsId}', [AlbumController::class, 'get']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('user')->group(function () {
        Route::post('sync-collection', [UserController::class, 'syncCollection']);
        Route::get('get-collection', [UserController::class, 'getUserCollection']);
        Route::post('toggle-follow/{friend}', [UserController::class, 'toggleFollow']);
        // Route::post('add-friend', [UserController::class, 'addFriend']);
        // Route::delete('remove-friend', [UserController::class, 'removeFriend']);
        Route::get('search-users', [UserController::class, 'searchUsers']);
        Route::post('change-password', [UserController::class, 'changePassword']);
        Route::post('change-profile-visibility', [UserController::class, 'changeProfileVisibility']);
        Route::get('/', [UserController::class, 'getMyProfile']);
    });

    Route::prefix('user-play-session')->group(function () {
        Route::get('get-for-album-cachephp artisan make:factory AddressFactory', [UserPlaySessionController::class, 'getForAlbumCache']);
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
        // Route::get('{id}', [PostController::class, 'show']);
        Route::post('/create', [PostController::class, 'create']);
        Route::post('/{post}/like', [PostController::class, 'toggleLike']);
        Route::put('/{post}/edit', [PostController::class, 'edit']);
        Route::delete('/{post}/delete', [PostController::class, 'delete']);
        Route::post('/{post}/comment/add', [PostController::class, 'addComment']);
    });
});

Route::get('users', [AuthController::class, 'allUsers']);
