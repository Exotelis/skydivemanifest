<?php

namespace App\Http\Requests\Qualification;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateRequest
 * @package App\Http\Requests\Qualification
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'color'         => 'sometimes|required|regex:/^#([a-f0-9]{3}){1,2}$/i',
            'qualification' => 'required|string|max:255',
            'slug'          => 'required|alpha_dash|max:255|unique:App\Models\Qualification',
        ];
    }
}
