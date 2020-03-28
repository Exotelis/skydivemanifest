<?php

namespace App\Http\Requests\Password;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ResetRequest
 * @package App\Http\Requests\Password
 */
class ResetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'password.regex' => __('validation.' . defaultPasswordStrength())
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
            'token'    => 'required',
            'email'    => 'required|email|exists:users|max:255',
            'password' => 'required|regex:'. passwordStrength() . '|confirmed|max:255',
        ];
    }
}
