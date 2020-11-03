<?php

namespace App\Http\Requests\User;

use App\Rules\CannotChangeOwnRole;
use App\Rules\CannotDisableOwnAccount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\User
 */
class UpdateRequest extends FormRequest
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
        $validRoles = implode(',', validRoles(auth()->user())->pluck('id')->all());

        return [
            'dob'             => 'sometimes|required|date|before:' . Carbon::now(),
            'email'           => [
                'sometimes',
                'required',
                'email:rfc,dns,spoof',
                Rule::unique('users')->ignore($this->route('user')->id),
                'max:255',
            ],
            'firstname'       => 'sometimes|required|string|max:255',
            'gender'          => 'sometimes|required|in:' . implode(',', validGender()),
            'is_active'       => [
                'sometimes',
                'bail',
                'required',
                'boolean',
                new CannotDisableOwnAccount,
            ],
            'lastname'        => 'sometimes|required|string|max:255',
            'locale'          => 'sometimes|required|string|in:' . implode(',', validLocales()),
            'middlename'      => 'sometimes|string|max:255|nullable',
            'mobile'          => 'sometimes|string|max:255|nullable',
            'password_change' => 'sometimes|required|boolean',
            'phone'           => 'sometimes|string|max:255|nullable',
            'role'            => [
                'sometimes',
                'bail',
                'required',
                'exists:roles,id',
                'in:' . $validRoles . '',
                new CannotChangeOwnRole,
            ],
            'username'        => [
                'sometimes',
                'alpha_num',
                Rule::unique('users')->ignore($this->route('user')->id),
                'max:255',
                'nullable',
            ],
            'timezone'        => 'sometimes|required|string|timezone',
        ];
    }
}
