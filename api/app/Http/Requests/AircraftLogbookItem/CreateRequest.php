<?php

namespace App\Http\Requests\AircraftLogbookItem;

use App\Rules\CorrectAirfield;
use App\Rules\NotBetweenDates;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

/**
 * Class CreateRequest
 * @package App\Http\Requests\AircraftLogbookItem
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
        $items = \is_null($aircraft->logbook) ? null : $aircraft->logbook->items()->get();

        return [
            'arrival'         => [
                'bail',
                'required',
                'date',
                'after:departure',
                'before:' . Carbon::now(),
                new NotBetweenDates($items,'departure','arrival'),
            ],
            'crew'            => 'required|integer|min:1|max:1000',
            'departure'       => [
                'bail',
                'required',
                'date',
                'after:' . ($aircraft->dom ?? Carbon::minValue()),
                'before:arrival',
                new NotBetweenDates($items,'departure','arrival'),
            ],
            'destination'     => [
                'required',
                'string',
                'max:255',
                new CorrectAirfield($items, 'departure', $this->departure, 'origin', false),
            ],
            'notes'           => 'sometimes|string|max:10000|nullable',
            'origin'          => [
                'required',
                'string',
                'max:255',
                new CorrectAirfield($items, 'arrival', $this->arrival, 'destination'),
            ],
            'pax'             => 'required|integer|min:0|max:' . $aircraft->seats,
            'pilot_id'        => 'required|integer|min:1|exists:users,id',
            'pilot_signature' => 'sometimes|required|string|starts_with:data:image/png;base64|max:100000|nullable',
        ];
    }
}
