<?php

namespace App\Http\Requests\Password;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ForgotRequest
 * @package App\Http\Requests\Password
 */
class ForgotRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|max:255'
        ];
    }
}
