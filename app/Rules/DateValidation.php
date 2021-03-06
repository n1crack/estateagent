<?php

namespace App\Rules;

use App\Utils\Address;
use App\Utils\Distance;
use Carbon\CarbonImmutable;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Contracts\Validation\Rule;

class DateValidation implements Rule
{
    private $appointment;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($zip, $appointment)
    {
        $this->address = new Address($zip);
        $this->distance = new Distance($this->address);
        $this->appointment = $appointment;
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
        try {
            $date = CarbonImmutable::make($value);
        } catch (InvalidFormatException $e) {
            return false;
        }

        $min_date = $this->distance->minDate($date);
        $max_date = $this->distance->maxDate($date);

        return !auth()->user()->appointments()
            ->when(isset($this->appointment), function ($query) {
                $query->where('id', '!=', $this->appointment->id);
            })
            ->where(function ($query) use ($min_date, $max_date) {
                $query->whereBetween('when_to_leave', [$min_date, $max_date])
                    ->orWhereBetween('next_available_date', [$min_date, $max_date])
                    ->orWhere(function ($query) use ($min_date, $max_date) {
                        $query->whereDate('when_to_leave', '<=', $min_date)
                            ->whereDate('next_available_date', '>=', $max_date);
                    });
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
