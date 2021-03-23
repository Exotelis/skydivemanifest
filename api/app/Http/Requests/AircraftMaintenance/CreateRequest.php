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
    public function authorize(): bool
    {
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
        $manufacturingDate = \is_null($aircraft->dom) ?
            Carbon::minValue()->toDateString() :
            $aircraft->dom->toDateString();
        $operationTime = $aircraft->operation_time;

        // If the aircraft has already been maintained - dom (date of maintenance) set
        if (! \is_null($this->dom)) {
            $rules = [
                'dom'                 => [
                    'required',
                    'date',
                    'after_or_equal:' . $manufacturingDate,
                    'before_or_equal:' . Carbon::now(),
                ],
                'maintenance_at'      => 'required|integer|between:0,' . $operationTime,
                'notify_at'           => 'sometimes|not_present',
                'repetition_interval' => 'sometimes|not_present',
            ];
        } else {
            $rules = [
                'maintenance_at'      => 'required|integer|gt:' . $operationTime . '|max:4294967295',
                'notify_at'           => 'sometimes|bail|integer|gt:' . $operationTime . '|lte:maintenance_at|nullable',
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
    public function attributes(): array
    {
        return [
            'dom' => __('validation.attributes.maintenance_date'),
        ];
    }
}
