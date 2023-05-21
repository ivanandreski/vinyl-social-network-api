<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\UserFriend;
use Illuminate\Http\Request;
use App\Factory\AlbumFactory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AddFriendRequest;
use App\Http\Requests\ChangeEmailRequest;
use App\Http\Requests\SearchUsersRequest;
use App\Http\Requests\RemoveFriendRequest;
use App\Http\Requests\GetCollectionRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\SyncCollectionRequest;
use App\Http\Requests\DeleteMyAccountRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Enums\UserProfileVisibilityEnum;
use App\Http\Requests\ChangeProfileVisibilityRequest;
use App\Repository\Interface\AlbumCacheRepositoryInterface;

class UserController extends Controller
{

    private AlbumCacheRepositoryInterface $albumCacheRepository;

    public function __construct(AlbumCacheRepositoryInterface $albumCacheRepository)
    {
        $this->albumCacheRepository = $albumCacheRepository;
    }

    public function getMyProfile(): User {
        return Auth::user();
    }

    public function getProfile(User $user, Request $request) {
        // TODO: put this in a helper method
        $token = \Laravel\Sanctum\PersonalAccessToken::findToken($request->bearerToken());

        // Get the assigned user
        $requestUser = $token?->tokenable;
        // todo: maybe make some checks for private

        return $user;
    }

    public function syncCollection(SyncCollectionRequest $request)
    {
        $albumFactory = new AlbumFactory();

        $userAlbumIds = [];
        foreach ($request->albums as $albumRequest) {
            $album = $albumFactory->createAlbumFromRequest($albumRequest);
            $albumCache = $this->albumCacheRepository->findAlbumCacheByDiscogsId($album->discogs_id);
            if ($albumCache == null) {
                $album->save();
            } else if ($album->column_hash != $albumCache->column_hash) {
                $albumCache->updateColumns($album);
                $albumCache->save();
                $album = $albumCache;
            } else {
                $album = $albumCache;
            }

            $userAlbumIds[] = $album->id;
        }

        $user = Auth::user();
        $user->albumCaches()->sync($userAlbumIds);
        $user->save();

        return response(['message' => "Sucesfully imported " . count($userAlbumIds) . "."], Response::HTTP_CREATED);
    }

    public function getUserCollection(GetCollectionRequest $request)
    {
        $user = Auth::user();

        return $user->albumCaches()->paginate(perPage: 10, page: $request->page);
    }

    public function addFriend(AddFriendRequest $request)
    {
        $friend = User::find($request->user_id);
        $user = Auth::user();
        if ($friend->visibility == UserProfileVisibilityEnum::PRIVATE) {
            return response(['message' => "This person's profile is private"], Response::HTTP_UNAUTHORIZED);
        }
        if (UserFriend::where('user_id', $user->id)->where('friend_id', $friend->id)->exists()) {
            return response(['message' => "You are already friends with " . $friend->getFullName()], Response::HTTP_NOT_FOUND);
        }

        $userFriend = new UserFriend();
        $userFriend->user_id = $user->id;
        $userFriend->friend_id = $friend->id;
        $userFriend->save();

        return response(['message' => 'You are now friends with ' . $friend->getFullName()], Response::HTTP_CREATED);
    }

    public function removeFriend(RemoveFriendRequest $request)
    {
        $friend = User::find($request->user_id);
        $user = Auth::user();
        if ($friend->visibility == UserProfileVisibilityEnum::PRIVATE) {
            return response(['message' => "This person's profile is private"], Response::HTTP_UNAUTHORIZED);
        }
        if (!UserFriend::where('user_id', $user->id)->where('friend_id', $friend->id)->exists()) {
            return response(['message' => "You are not friends with " . $friend->getFullName()], Response::HTTP_NOT_FOUND);
        }

        UserFriend::where('user_id', $user->id)
            ->where('friend_id', $friend->id)
            ->delete();

        return response(['message' => 'You are no longer friends with ' . $friend->getFullName()], Response::HTTP_CREATED);
    }

    public function searchUsers(SearchUsersRequest $request)
    {
        return User::select('first_name', 'last_name')
            ->where('email', 'like', '%' . $request->name . '%')
            ->orWhere(DB::raw("concat(first_name, ' ', last_name)"), 'like', '%' . $request->name . '%')
            ->limit(10)
            ->get();
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();
        if (!Hash::check($request->old_password, $user->password)) {
            return response(['message' => 'Old password is incorect'], Response::HTTP_UNAUTHORIZED);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response(['message' => 'Password changed successfully'], Response::HTTP_OK);
    }

    public function changeProfileVisibility(ChangeProfileVisibilityRequest $request): User
    {
        $user = Auth::user();
        $user->visibility = $request->visibility_type;
        $user->save();

        return $user;
    }

    public function changeEmail(ChangeEmailRequest $request): User
    {
        $user = Auth::user();
        if($user->email != $request->old_email) {
            return response(['message' => 'This email is wrong'], Response::HTTP_UNAUTHORIZED);
        }

        $user->email = $request->new_email;
        $user->save();

        return $user;
    }

    public function deleteMyAccount(DeleteMyAccountRequest $request)
    {
        $user = Auth::user();
        if (!Hash::check($request->password, $user->password)) {
            return response(['message' => 'Old password is incorect'], Response::HTTP_UNAUTHORIZED);
        }

        $user->delete();

        return response(['message' => 'User deleted'], Response::HTTP_OK);
    }
}
