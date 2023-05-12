<?php

namespace App\Factory;

use App\Models\UserStylus;
use Illuminate\Support\Facades\Auth;

class UserStylusFactory {
    public function createUserStylus(string $stylusName): UserStylus {
        $userStylus = new UserStylus();
        $userStylus->stylus_name = $stylusName;
        $userStylus->user_id = Auth::user()->id;

        return $userStylus;
    }
}
