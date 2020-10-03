<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Class CannotDisableOwnAccount
 * @package App\Rules
 */
class CannotDisableOwnAccount implements Rule
{
    /**
     * Determines if the owner of the requested resource is the current user.
     *
     * @var bool
     */
    private $currentUser;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->currentUser = Auth::user()->id === (int)Request::route()->parameters['id'];
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  bool    $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = (boolean) $value;

        if ($this->currentUser) {
            return $value;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('error.account_cannot_disable');
    }
}
