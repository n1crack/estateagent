<?php

namespace App\Repositories;

use App\Models\Appointment;
use App\Utils\Distance;
use App\Utils\Address;
use Carbon\CarbonImmutable;
use Symfony\Component\HttpFoundation\Response;

class AppointmentRepository
{

    public static function all()
    {
        return auth()->user()->appointments()->get();
    }

    public static function save($contactData, $appoinmentData)
    {
        // create customer
        $contact = auth()->user()->contacts()->firstOrCreate(
            ['email' => $contactData['email'],],
            $contactData
        );

        // create appointment
        $appoinmentData['user_id'] = auth()->user()->id;
        $appoinmentData['contact_id'] = $contact->id;
        $appoinmentData = array_merge($appoinmentData, static::calcDistance($appoinmentData));

        $appoinment = Appointment::create($appoinmentData);

        return compact('contact', 'appoinment');
    }

    public static function update(Appointment $appointment, $appoinmentData)
    {
        $appoinmentData = array_merge($appoinmentData, static::calcDistance($appoinmentData));

        $appoinment = $appointment->update($appoinmentData);

        return compact('appoinment');
    }

    public static function delete(Appointment $appointment)
    {
        if ($appointment->user->id !== auth()->user()->id) {
            return response()->json(['error' => 'You are not authorized.'], Response::HTTP_UNAUTHORIZED);
        }

        return $appointment->delete();
    }

    public static function calcDistance($appoinmentData)
    {
        $address = new Address($appoinmentData['address']);
        $distance = new Distance($address);

        $date = CarbonImmutable::make($appoinmentData['date']);

        $when_to_leave = $date->subMilliseconds($distance->time());
        $next_available_date = $date->addSeconds((int) env('APPOINTMENT_TIME'))->addMilliseconds($distance->time());

        return [
            'distance' => $distance->length(),
            'time' => $distance->time(),
            'when_to_leave' => $when_to_leave,
            'next_available_date' => $next_available_date
        ];
    }

}
