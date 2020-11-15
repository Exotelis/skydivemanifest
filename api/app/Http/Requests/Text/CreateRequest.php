<?php

namespace App\Http\Requests\Text;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateRequest
 * @package App\Http\Requests\Text
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
            'language_code' => 'required|string|in:' . \implode(',', validLocales()),
            'position'      => 'sometimes|integer|min:1|max:4294967295',
            'text'          => 'required|string|max:65535',
            'title'         => 'sometimes|string|max:255|nullable',
        ];
    }
}
