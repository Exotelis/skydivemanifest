<?php

namespace App\Http\Requests\Password;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class ChangeRequest
 * @package App\Http\Requests\Password
 */
class ChangeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (Auth::check() &&
            array_key_exists('password_change', $this->user()->attributesToArray()) &&
            $this->user()->password_change);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'new_password.regex' => __('validation.' . defaultPasswordStrength())
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password'     => 'required|password|max:255',
            'new_password' => 'required|different:password|regex:'. passwordStrength() . '|confirmed|max:255',
        ];
    }
}
