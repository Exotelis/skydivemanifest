<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateRequest
 * @package App\Http\Requests\Role
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
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.unique' => __('validation.custom.role.unique'),
        ];
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
            'deletable'     => 'sometimes|required|boolean',
            'editable'      => 'sometimes|required|boolean',
            'name'          => 'required|unique:roles|string|max:255',
            'permissions'   => 'sometimes|required|array',
            'permissions.*' => 'exists:permissions,slug',
        ];
    }
}
