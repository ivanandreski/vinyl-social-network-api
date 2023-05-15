<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Factory\UserFactory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Http\Requests\StoreUserRequest;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request)
    {
        $userFactory = new UserFactory();
        $user = $userFactory->createUserFromRequest($request->all());
        $user->save();

        return response($user, Response::HTTP_CREATED);
    }

    // todo: make custom request and validate if email exists in db
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            $cookie = cookie('cookie_token', $token, 10 * 365 * 60 * 24);
            return response(["token" => $token], Response::HTTP_OK)->withoutCookie($cookie);
        } else {
            return response(["message" => "Invalid Credentials"], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function userProfile(Request $request)
    {
        return response()->json([
            "message" => "userProfile OK",
            "userData" => auth()->user()
        ], Response::HTTP_OK);
    }

    public function logout()
    {
        $cookie = Cookie::forget('cookie_token');
        return response(["message" => "Cleared session"], Response::HTTP_OK)->withCookie($cookie);
    }

    public function allUsers()
    {
        return User::all();
    }
}
