<?php

namespace App\Http\Requests\Waiver;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SignRequest
 * @package App\Http\Requests\Waiver
 */
class SignRequest extends FormRequest
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
            'signature' => 'required|string|starts_with:data:image/png;base64|max:100000',
        ];
    }
}
