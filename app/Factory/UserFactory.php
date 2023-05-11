<?php

namespace App\Factory;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserFactory {

    public function createUserFromRequest(array $requestParams): User {
        $user = new User();
        $user->email = $requestParams['email'];
        $user->password = Hash::make($requestParams['password']);
        $user->first_name = $requestParams['first_name'];
        $user->last_name = $requestParams['last_name'];

        return $user;
    }
}