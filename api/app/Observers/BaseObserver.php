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
        $this->executedBy = currentUserLogString() ?? 'system';
    }
}
