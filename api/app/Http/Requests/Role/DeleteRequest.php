<?php

namespace App\Http\Requests\Role;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class DeleteRequest
 * @package App\Http\Requests\Role
 */
class DeleteRequest extends FormRequest
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
            'ids.*.unique' => __('error.roles_in_use', ['id' => ':input']),
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
            'ids'   => 'required|array',
            'ids.*' => [
                'bail',
                'integer',
                function ($attribute, $value, $fail) {
                    $role = Role::find($value);
                    if (! \is_null($role) && !$role->deletable) {
                        $fail(__('error.role_not_deletable_id', ['id' => $value]));
                    }
                },
                'unique:users,role_id',
            ],
        ];
    }
}
