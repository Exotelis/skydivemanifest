<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Class CannotChangeOwnRole
 * @package App\Rules
 */
class CannotChangeOwnRole implements Rule
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
        $this->currentUser = Auth::user()->id === Request::route('user')->id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  int     $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = (int) $value;

        if ($this->currentUser) {
            return $value === Auth::user()->role_id;
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
        return __('error.account_cannot_change_own_role');
    }
}
