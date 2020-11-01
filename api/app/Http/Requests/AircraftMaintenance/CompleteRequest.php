<?php

namespace App\Http\Requests\AircraftMaintenance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

/**
 * Class CompleteRequest
 * @package App\Http\Requests\AircraftMaintenance
 */
class CompleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route('aircraftMaintenance')->isCompleted()) {
            abort(400, __('error.aircraft_maintenance_completed'));
        }

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
        $newFlightTime = $this->flight_time ?? $flightTime;

        return [
            'dom'            => 'required|date|before_or_equal:' . Carbon::now(),
            'flight_time'    => 'sometimes|integer|gte:' . $flightTime .'|max:4294967295|nullable',
            'maintenance_at' => 'required|integer|min:0|lte:' . $newFlightTime,
        ];
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
