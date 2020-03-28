<?php

namespace App\Listeners\Auth;

use \App\Models\User;
use \Laravel\Passport\Events\AccessTokenCreated;

/**
 * Class RevokeOldTokens
 * @package App\Listeners\Auth
 */
class RevokeOldTokens
{
    /**
     * Handle the event.
     *
     * @param  AccessTokenCreated  $event
     * @return void
     */
    public function handle(AccessTokenCreated $event)
    {
        if (! allowMultipleTokens()) {
            $user = User::find($event->userId);

            foreach($user->tokens as $token) {
                if ((int) $event->clientId === $token->client_id && $event->tokenId !== $token->id) {
                    $token->revoke();
                }
            }
        }
    }
}
