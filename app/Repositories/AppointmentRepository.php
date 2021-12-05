<?php

namespace App\Repositories;

use App\Http\Requests\DateFilterRequest;
use App\Models\Appointment;
use App\Utils\Distance;
use App\Utils\Address;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class AppointmentRepository
{

    /**
     * @param  DateFilterRequest  $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function all(DateFilterRequest $request)
    {
        $from = $request->get('from') ?? false;
        $to = $request->get('to') ?? false;

        return auth()->user()->appointments()
            ->when($from, fn($query) => $query->filterDate('>=', Carbon::make($from)->startOfDay()))
            ->when($to, fn($query) => $query->filterDate('<=', Carbon::make($to)->endOfDay()))
            ->get();
    }

    /**
     * @param $contactData
     * @param $appoinmentData
     * @return mixed
     */
    public static function save($contactData, $appoinmentData)
    {
        // create contact
        $contact = auth()->user()->contacts()->create($contactData);

        // create appointment
        $appoinmentData['user_id'] = auth()->user()->id;
        $appoinmentData['contact_id'] = $contact->id;
        $appoinmentData = array_merge($appoinmentData, static::calcDistance($appoinmentData));

        return Appointment::create($appoinmentData);
    }

    /**
     * @param  Appointment  $appointment
     * @param $appoinmentData
     * @return Appointment
     */
    public static function update(Appointment $appointment, $appoinmentData)
    {
        $appoinmentData = array_merge($appoinmentData, static::calcDistance($appoinmentData));
        $appointment->update($appoinmentData);

        return $appointment;
    }

    /**
     * @param  Appointment  $appointment
     * @return bool|null
     */
    public static function delete(Appointment $appointment)
    {
        $appointment->contact()->delete();

        return $appointment->delete();
    }

    /**
     * @param $appoinmentData
     * @return array
     */
    public static function calcDistance($appoinmentData)
    {
        $address = new Address($appoinmentData['address']);
        $distance = new Distance($address);

        $date = CarbonImmutable::make($appoinmentData['date']);

        return [
            'distance' => $distance->length(),
            'time' => $distance->time(),
            'when_to_leave' => $distance->minDate($date),
            'next_available_date' => $distance->maxDate($date),
        ];
    }

}
