<?php

namespace App\Http\Requests\User;

use Carbon\CarbonTimeZone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

/**
 * Class CreateRequest
 * @package App\Http\Requests\User
 */
class CreateRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (! is_null($this->timezone)) {
            // Try to guess the timezone, when an integer was submitted
            $timezone = isDigit($this->timezone) ?
                CarbonTimeZone::createFromMinuteOffset((int) $this->timezone)->toRegionName() :
                $this->timezone;

            if (! $timezone) {
                return;
            }

            $this->merge([
                'timezone' => $timezone,
            ]);
        }
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
            'dob'             => 'required|date|before:' . Carbon::now(),
            'email'           => 'required|email:rfc,dns,spoof|unique:users|max:255',
            'firstname'       => 'required|string|max:255',
            'gender'          => 'sometimes|required|in:' . implode(',', validGender()),
            'is_active'       => 'sometimes|required|boolean',
            'lastname'        => 'required|string|max:255',
            'locale'          => 'sometimes|required|string|in:' . implode(',', validLocales()),
            'middlename'      => 'sometimes|string|max:255|nullable',
            'mobile'          => 'sometimes|string|max:255|nullable',
            'password_change' => 'sometimes|required|boolean',
            'phone'           => 'sometimes|string|max:255|nullable',
            'role'            => 'sometimes|bail|required|exists:roles,id|in:' . $validRoles . '',
            'username'        => 'sometimes|alpha_num|unique:users|max:255|nullable',
            'timezone'        => 'sometimes|required|string|timezone',
        ];
    }
}
