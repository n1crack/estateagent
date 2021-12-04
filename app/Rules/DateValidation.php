<?php

namespace App\Rules;

use App\Utils\Address;
use App\Utils\Distance;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Validation\Rule;

class DateValidation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($zip)
    {
        $this->address = new Address($zip);
        $this->distance = new Distance($this->address);
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
        $date = CarbonImmutable::make($value);
        $min_date = $this->distance->minDate($date);
        $max_date = $this->distance->maxDate($date);

        return !auth()->user()->appointments()
            ->where(function ($query) use ($min_date) {
                $query->whereDate('when_to_leave', '<=', $min_date)
                    ->whereDate('next_available_date', '>=', $min_date);
            })
            ->orWhere(function ($query) use ($max_date) {
                $query->whereDate('when_to_leave', '<=', $max_date)
                    ->whereDate('next_available_date', '>=', $max_date);
            })
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