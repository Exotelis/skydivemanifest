<?php

namespace App\Http\Requests\Aircraft;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

/**
 * Class CreateRequest
 * @package App\Http\Requests\Aircraft
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
            'registration' => 'required|alpha_dash|unique:App\Models\Aircraft',
            'dom'          => 'sometimes|date|before_or_equal:' . Carbon::now() . '|nullable',
            'flight_time'  => 'required|integer|min:0|max:4294967295',
            'model'        => 'required|string|max:255',
            'seats'        => 'required|integer|min:1|max:4294967295',
        ];
    }
}
