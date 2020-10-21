<?php

namespace App\Http\Requests\Currency;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateRequest
 * @package App\Http\Requests\Currency
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
            'code'     => 'required|alpha_num|size:3|unique:App\Models\Currency',
            'currency' => 'required|string|max:255',
            'symbol'   => 'sometimes|string|max:255|nullable',
        ];
    }
}
