<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class QualificationsRequest
 * @package App\Http\Requests\User
 */
class QualificationsRequest extends FormRequest
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
            'qualifications'   => 'sometimes|required|array',
            'qualifications.*' => 'exists:App\Models\Qualification,slug',
        ];
    }
}
