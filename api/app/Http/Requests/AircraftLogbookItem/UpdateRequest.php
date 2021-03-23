<?php

namespace App\Http\Requests\AircraftLogbookItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\AircraftLogbookItem
 */
class UpdateRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $item = $this->route('aircraftLogbookItem');

        $this->merge([
            'arrival'   => $this->arrival ?? $item->arrival->toDateTimeString(),
            'departure' => $this->departure ?? $item->departure->toDateTimeString(),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $aircraft = $this->route('aircraft');
        $item = $this->route('aircraftLogbookItem');
        $items = $aircraft->logbook->items()->get();

        $prevArrival = (($items->where('arrival', '<', $item->departure)->max('arrival')) ?? $aircraft->dom)
            ->toDateTimeString();
        $nextDeparture = (($items->where('departure', '>', $item->arrival)->min('departure')) ?? Carbon::maxValue())
            ->toDateTimeString();

        return [
            'arrival'         => 'sometimes|required|date|after:departure|before:' . $nextDeparture,
            'crew'            => 'sometimes|required|integer|min:1|max:1000',
            'departure'       => 'sometimes|required|date|after:' . $prevArrival . '|before:arrival',
            'notes'           => 'sometimes|string|max:10000|nullable',
            'pax'             => 'sometimes|required|integer|min:0|max:' . $aircraft->seats,
            'pilot_id'        => 'sometimes|integer|min:1|exists:users,id|nullable',
            'pilot_signature' => 'sometimes|required|string|starts_with:data:image/png;base64|max:100000|nullable',
        ];
    }
}
