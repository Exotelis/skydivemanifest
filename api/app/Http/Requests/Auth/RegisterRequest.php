<?php

namespace App\Http\Requests\Auth;

use Carbon\CarbonTimeZone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

/**
 * Class RegisterRequest
 * @package App\Http\Requests
 */
class RegisterRequest extends FormRequest
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
        return [
            'dob'       => 'required|date|before:'.Carbon::now(),
            'email'     => 'required|email:rfc,dns,spoof|unique:users|max:255',
            'firstname' => 'required|string|max:255',
            'gender'    => 'sometimes|required|in:' . implode(',', validGender()),
            'lastname'  => 'required|string|max:255',
            'locale'    => 'sometimes|required|string|in:' . implode(',', validLocales()),
            'password'  => 'required|regex:'. passwordStrength() . '|confirmed',
            'username'  => 'sometimes|required|alpha_num|unique:users|max:255|nullable',
            'timezone'  => 'sometimes|required|string|in:' . implode(',', CarbonTimeZone::listIdentifiers()),
        ];
    }
}
