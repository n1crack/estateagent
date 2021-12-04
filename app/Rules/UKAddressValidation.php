<?php

namespace App\Rules;

use App\Utils\Address;
use Illuminate\Contracts\Validation\Rule;

class UKAddressValidation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $address = new Address($value);

        return $address->isValid();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The address is not valid. Please enter a valid UK Zip Code.';
    }
}
