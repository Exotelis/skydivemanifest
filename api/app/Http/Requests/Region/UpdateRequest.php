<?php

namespace App\Http\Requests\Region;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\Region
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
            'region' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('App\Models\Region')->where('country_id', $this->route('country')->id)
                    ->ignore($this->route('region')),
            ]
        ];
    }
}
