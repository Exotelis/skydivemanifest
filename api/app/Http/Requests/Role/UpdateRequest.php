<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\Role
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
            'name'          => [
                'sometimes',
                'required',
                Rule::unique('App\Models\Role')->ignore($this->route('role')),
                'string',
                'max:255',
            ],
            'permissions'   => 'sometimes|required|array',
            'permissions.*' => 'exists:permissions,slug',
        ];
    }
}
