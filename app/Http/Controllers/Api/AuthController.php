<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Factory\UserFactory;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $userFactory = new UserFactory();
        $user = $userFactory->createUserFromRequest($request->all());
        $user->save();

        return response($user, Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->all())) {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            $cookie = cookie('cookie_token', $token, 10 * 365 * 60 * 24);
            return response(["token" => $token], Response::HTTP_OK)->withoutCookie($cookie);
        } else {
            return response(["message" => "Invalid Credentials"], Response::HTTP_UNAUTHORIZED);
        }
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
