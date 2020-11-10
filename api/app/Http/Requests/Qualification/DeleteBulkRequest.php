<?php

namespace App\Http\Requests\Qualification;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class DeleteBulkRequest
 * @package App\Http\Requests\Qualification
 */
class DeleteBulkRequest extends FormRequest
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
            'slugs'   => 'required|array',
            'slugs.*' => 'bail|alpha_dash|max:255',
        ];
    }
}
