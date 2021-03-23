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
    public function authorize(): bool
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
    public function rules(): array
    {
        $aircraft = $this->route('aircraft');
        $manufacturingDate = $aircraft->dom ?? Carbon::minValue()->toDateString();
        $operationTime = $aircraft->operation_time;

        return [
            'dom'            => [
                'required',
                'date',
                'after_or_equal:' . $manufacturingDate,
                'before_or_equal:' . Carbon::now(),
            ],
            'maintenance_at' => 'required|integer|min:0|lte:' . $operationTime,
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'dom' => __('validation.attributes.maintenance_date'),
        ];
    }
}
