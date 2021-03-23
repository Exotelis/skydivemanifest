<?php

namespace App\Http\Requests\UnassignedWaiver;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AssignRequest
 * @package App\Http\Requests\UnassignedWaiver
 */
class AssignRequest extends FormRequest
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
            'user_id' => 'required|integer|min:0|exists:App\Models\User,id'
        ];
    }
}
