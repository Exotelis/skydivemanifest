<?php

namespace App\Http\Requests\Country;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateRequest
 * @package App\Http\Requests\Country
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
            'code'    => 'required|string|max:255|unique:App\Models\Country',
            'country' => 'required|string|max:255|unique:App\Models\Country',
        ];
    }
}
