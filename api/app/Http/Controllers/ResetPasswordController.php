<?php

namespace App\Http\Controllers;

use App\Http\Requests\Password\ChangeRequest;
use App\Traits\ResetPassword;
use Illuminate\Support\Facades\Auth;

/**
 * Class ResetPasswordController
 * @package App\Http\Controllers
 *
 * @property string $broker
 */
class ResetPasswordController extends Controller
{
    use ResetPassword;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->broker = 'users';
    }

    /**
     * Change the users password.
     *
     * @param  ChangeRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function changePassword(ChangeRequest $request)
    {
        $input = $request->only('new_password');
        $user = Auth::user();

        /** @var \App\Models\User $user */
        $user->password = $input['new_password'];
        $user->password_change = false;
        $user->saveOrFail();

        return response()->json(['message' => __('messages.password_changed')]);
    }
}
