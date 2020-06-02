<?php

namespace App\Claims;

use CorBosman\Passport\AccessToken;

class UserClaim
{
    public function handle(AccessToken $token, $next)
    {
        //  $token->addClaim('my-claim', 'my custom claim data');
        return $next($token);
    }
}
