<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DateValidation implements Rule
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
        return !auth()->user()->appointments()
            ->whereDate('when_to_leave', '<=', $value)
            ->whereDate('next_available_date', '>=', $value)
            ->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You have another appointment on this date.';
    }
}
