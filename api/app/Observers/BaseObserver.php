<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;

class BaseObserver
{
    /**
     * Information of the person who executed the event.
     *
     * @var string
     */
    protected $executedBy = 'system';

    /**
     * Create a new observer instance.
     */
    public function __construct()
    {
        $user = Auth::user();

        if (! \is_null($user) && $user instanceof \App\Models\User) {
            $this->executedBy = "{$user->id}|{$user->email}|{$user->lastname}, {$user->firstname}";
        }
    }
}
