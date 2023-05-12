<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Factory\UserStylusFactory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DeleteStylusRequest;
use App\Http\Requests\RetireStylusRequest;
use App\Http\Requests\CreateUserStylusRequest;
use App\Http\Requests\GetUserStylusesRequest;
use App\Models\UserStylus;

class UserStylusController extends Controller
{
    public function getUserStyluses(GetUserStylusesRequest $request) {
        return Auth::user()
            ->userStyluses()
            ->when($request->retired != null, function($query) use($request) {
                $query->where('is_retired', $request->retired);
            })->get();
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
        if($user->id !== $stylus->user_id) {
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
        if($user->id != $stylus->id) {
            return response(['message' => "You do not own this stylus"], Response::HTTP_UNAUTHORIZED);
        }

        $stylus->delete();

        return response(['message' => 'Stylus deleted successfully'], Response::HTTP_OK);
    }
}
