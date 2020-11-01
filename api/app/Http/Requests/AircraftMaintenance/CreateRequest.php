<?php

namespace App\Http\Requests\AircraftMaintenance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

/**
 * Class CreateRequest
 * @package App\Http\Requests\AircraftMaintenance
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
        $flightTime = $this->route('aircraft')->flight_time;

        // If maintenance date is present
        if (! \is_null($this->dom)) {
            $rules = [
                'dom'                 => 'required|date|before_or_equal:' . Carbon::now(),
                'maintenance_at'      => 'required|integer|between:0,' . $flightTime,
                'notify_at'           => 'sometimes|not_present',
                'repetition_interval' => 'sometimes|not_present',
            ];
        } else {
            $rules = [
                'maintenance_at'      => 'required|integer|gt:' . $flightTime . '|max:4294967295',
                'notify_at'           => 'sometimes|bail|integer|gt:' . $flightTime . '|lte:maintenance_at|nullable',
                'repetition_interval' => 'sometimes|integer|min:60|max:4294967295|nullable',
            ];
        }

        return \array_merge($rules, [
            'name'           => 'sometimes|string|max:255|nullable',
            'notes'          => 'sometimes|present:name|string|max:10000|nullable',
        ]);
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'dom' => __('validation.attributes.maintenance_date'),
        ];
    }
}
