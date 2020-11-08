<?php

namespace App\Http\Requests\Qualification;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\Qualification
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
            'color'         => 'sometimes|required|regex:/^#([a-f0-9]{3}){1,2}$/i',
            'qualification' => 'sometimes|required|string|max:255',
            'slug'          => [
                'sometimes',
                'required',
                'alpha_dash',
                'max:255',
                Rule::unique('App\Models\Qualification')->ignore($this->route('qualification')),
            ],
        ];
    }
}
