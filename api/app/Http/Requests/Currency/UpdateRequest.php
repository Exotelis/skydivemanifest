<?php

namespace App\Http\Requests\Currency;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\Currency
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
            'code'     => [
                'sometimes',
                'required',
                'alpha_num',
                'size:3',
                Rule::unique('App\Models\Currency')->ignore($this->route()->currencyCode, 'code'),
            ],
            'currency' => 'sometimes|required|string|max:255',
            'symbol'   => 'sometimes|string|max:255|nullable',
        ];
    }
}
