<?php

namespace App\Http\Requests\AircraftMaintenance;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\AircraftMaintenance
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
        if ($this->route('aircraftMaintenance')->isCompleted()) {
            abort(400, __('error.aircraft_maintenance_completed_update'));
        }

        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Add maintenance_at in case it has not been submitted
        $this->merge([
            'maintenance_at' => $this->maintenance_at ?? $this->route('aircraftMaintenance')->maintenance_at,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $flightTime = $this->route('aircraft')->flight_time;
        return [
            'maintenance_at'      => 'sometimes|required|integer|min:0|max:4294967295',
            'name'                => 'sometimes|string|max:255|nullable',
            'notes'               => 'sometimes|string|max:10000|nullable',
            'notify_at'           => 'sometimes|bail|integer|lte:maintenance_at|gt:' . $flightTime . '|nullable',
            'repetition_interval' => 'sometimes|integer|min:60|max:4294967295|nullable',
        ];
    }
}
