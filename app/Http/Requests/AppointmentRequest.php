<?php

namespace App\Http\Requests;

use App\Rules\DateValidation;
use App\Rules\UKAddressValidation;
use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->appointment) {
            return $this->appointment->user_id === auth()->user()->id;
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

        return [
            'address' => ['required', new UKAddressValidation],
            'date' => ['required', 'date', 'date_format:Y-m-d H:i:s', 'after:now', new DateValidation($this->address)],
        ];
    }
}
