<?php

namespace App\Claims;

use App\Models\User;
use CorBosman\Passport\AccessToken;

class UserClaim
{
    public function handle(AccessToken $token, $next)
    {
        $user = User::find($token->getUserIdentifier());

        $token->addClaim('user', [
            'id'              => $user->id,
            'email'           => $user->email,
            'username'        => $user->username,
            'firstname'       => $user->firstname,
            'lastname'        => $user->lastname,
            'gender'          => $user->gender,
            'locale'          => $user->locale,
            'password_change' => $user->password_change,
            'timezone'        => $user->timezone,
            'tos'             => $user->tos,
        ]);

        return $next($token);
    }
}
