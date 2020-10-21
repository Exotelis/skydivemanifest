<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\Address
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
            'city'             => 'sometimes|required|string|max:255',
            'company'          => 'sometimes|string|max:255|nullable',
            'country_id'       => 'required_with:region_id|exists:App\Models\Country,id',
            'default_invoice'  => 'sometimes|boolean',
            'default_shipping' => 'sometimes|boolean',
            'firstname'        => 'sometimes|required|string|max:255',
            'lastname'         => 'sometimes|required|string|max:255',
            'middlename'       => 'sometimes|sometimes|string|max:255|nullable',
            'postal'           => 'sometimes|required|string|max:255',
            'region_id'        => [
                'required_with:country_id',
                Rule::exists('App\Models\Region', 'id')->where(function ($query) {
                    $query->where('country_id', $this->country_id);
                })
            ],
            'street'           => 'sometimes|required|string|max:255',
        ];
    }
}
