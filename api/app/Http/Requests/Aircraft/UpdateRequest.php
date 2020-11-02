<?php

namespace App\Http\Requests\Aircraft;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\Aircraft
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
            'registration' => [
                'sometimes',
                'required',
                'alpha_dash',
                Rule::unique('App\Models\Aircraft')
                    ->ignore($this->route('aircraft')->registration, 'registration'),
            ],
            'dom'          => 'sometimes|date|before_or_equal:' . Carbon::now() . '|nullable',
            'flight_time'  => 'sometimes|required|integer|min:0|max:4294967295',
            'model'        => 'sometimes|required|string|max:255',
            'seats'        => 'sometimes|required|integer|min:1|max:4294967295',
        ];
    }
}
