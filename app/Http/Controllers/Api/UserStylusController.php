<?php

namespace App\Http\Controllers\Api;

use App\Models\UserStylus;
use Illuminate\Http\Request;
use App\Models\AlbumPlayUser;
use App\Factory\UserStylusFactory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DeleteStylusRequest;
use App\Http\Requests\RetireStylusRequest;
use App\Http\Requests\GetUserStylusesRequest;
use App\Http\Requests\CreateUserStylusRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\GetUserStylusHoursRequest;

class UserStylusController extends Controller
{
    public function getUserStyluses(GetUserStylusesRequest $request)
    {
        return Auth::user()
            ->userStyluses()
            ->when($request->retired != null, function ($query) use ($request) {
                $query->where('is_retired', $request->retired);
            })->get();
    }

    public function getStylusHours(GetUserStylusHoursRequest $request)
    {
        $sum = AlbumPlayUser::leftJoin('albums_cache', 'album_play_user.album_cache_id', '=', 'albums_cache.id')
            ->where('album_play_user.user_stylus_id', $request->stylus_id)
            ->sum(DB::raw('CASE WHEN albums_cache.length_in_seconds = 0 THEN 2400 ELSE albums_cache.length_in_seconds END'));

        return response(['sum' => $sum], Response::HTTP_OK);
    }

    public function create(CreateUserStylusRequest $request)
    {
        $userStylusFactory = new UserStylusFactory();
        $userStylus = $userStylusFactory->createUserStylus($request->stylus_name);
        $userStylus->save();

        return response(['message' => 'Stylus create successfully'], Response::HTTP_CREATED);
    }

    public function retire(RetireStylusRequest $request)
    {
        $user = Auth::user();
        $stylus = UserStylus::find($request->stylus_id);
        if ($user->id !== $stylus->user_id) {
            return response(['message' => "You do not own this stylus"], Response::HTTP_UNAUTHORIZED);
        }

        $stylus->is_retired = true;
        $stylus->save();

        return response(['message' => 'Stylus retired successfully'], Response::HTTP_OK);
    }

    public function delete(DeleteStylusRequest $request)
    {
        $user = Auth::user();
        $stylus = UserStylus::find($request->stylus_id);
        if ($user->id != $stylus->id) {
            return response(['message' => "You do not own this stylus"], Response::HTTP_UNAUTHORIZED);
        }

        $stylus->delete();

        return response(['message' => 'Stylus deleted successfully'], Response::HTTP_OK);
    }
}
