<?php

namespace App\Http\Requests\Waiver;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class WebSignRequest
 * @package App\Http\Requests\Waiver
 */
class WebSignRequest extends FormRequest
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
            'email'           => 'required_without:ticket_number|email:rfc,dns,spoof|max:255',
            'firstname'       => 'required_without:ticket_number|string|max:255',
            'lastname'        => 'required_without:ticket_number|string|max:255',
            'recaptcha_token' => 'sometimes|required|string',
            'signature'       => 'required|string|starts_with:data:image/png;base64|max:100000',
            'ticket_number'   => 'required_without:email,firstname,lastname|uuid'
        ];
    }
}
