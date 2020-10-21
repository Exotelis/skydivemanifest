<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class CreateRequest
 * @package App\Http\Requests\Address
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
            'city'             => 'required|string|max:255',
            'company'          => 'sometimes|string|max:255|nullable',
            'country_id'       => 'required|exists:App\Models\Country,id',
            'default_invoice'  => 'sometimes|boolean',
            'default_shipping' => 'sometimes|boolean',
            'firstname'        => 'required|string|max:255',
            'lastname'         => 'required|string|max:255',
            'middlename'       => 'sometimes|string|max:255|nullable',
            'postal'           => 'required|string|max:255',
            'region_id'        => [
                'required',
                Rule::exists('App\Models\Region', 'id')->where(function ($query) {
                    $query->where('country_id', $this->country_id);
                })
            ],
            'street'           => 'required|string|max:255',
        ];
    }
}
