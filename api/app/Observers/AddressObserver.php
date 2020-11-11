<?php

namespace App\Observers;

use App\Models\Address;
use Illuminate\Support\Facades\Log;

/**
 * Class AddressObserver
 * @package App\Observers
 */
class AddressObserver extends BaseObserver
{
    /**
     * Handle the address "created" event.
     *
     * @param  Address  $address
     * @return void
     */
    public function created(Address $address)
    {
        Log::info("[Address] '{$address->logString()}' has been created for user " .
            "'{$address->user->logString()}' by '{$this->executedBy}'");
    }

    /**
     * Handle the address "updated" event.
     *
     * @param  Address  $address
     * @return void
     */
    public function updated(Address $address)
    {
        // Do not display diff because of privacy reasons, most of the address data are personal data.
        Log::info("[Address] '{$address->logString()}' of user '{$address->user->logString()}' has been " .
            "updated by '{$this->executedBy}'");
    }

    /**
     * Handle the address "deleted" event.
     *
     * @param  Address  $address
     * @return void
     */
    public function deleted(Address $address)
    {
        $user = "{$address->user->id}|{$address->user->email}";
        Log::info("[Address] '{$address->logString()}' of user '{$address->user->logString()}' has been " .
            "deleted by '{$this->executedBy}'");
    }
}
