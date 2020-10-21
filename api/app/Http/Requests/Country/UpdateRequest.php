<?php

namespace App\Http\Requests\Country;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\Country
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
        return [
            'code'    => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('App\Models\Country')->ignore($this->route()->countryID),
            ],
            'country' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('App\Models\Country')->ignore($this->route()->countryID),
            ],
        ];
    }
}
