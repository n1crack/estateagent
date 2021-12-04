<?php

namespace App\Repositories;

use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Utils\Distance;
use App\Utils\Address;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AppointmentRepository
{

    public static function all(Request $request)
    {
        $from = $request->get('from') ?? false;
        $to = $request->get('to') ?? false;

        return auth()->user()->appointments()
            ->when($from, function ($query) use ($from) {
                $query->where('date', '>=', Carbon::make($from)->startOfDay());
            })
            ->when($to, function ($query) use ($to) {
                $query->where('date', '<=', Carbon::make($to)->endOfDay());
            })
            ->get();
    }

    public static function get(Appointment $appointment)
    {
        if ($appointment->user->id !== auth()->user()->id) {
            return response()->json(['error' => 'You are not authorized.'], Response::HTTP_UNAUTHORIZED);
        }

        return $appointment;
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

        return Appointment::create($appoinmentData);
    }

    public static function update(Appointment $appointment, $appoinmentData)
    {
        $appoinmentData = array_merge($appoinmentData, static::calcDistance($appoinmentData));
        $appointment->update($appoinmentData);

        return $appointment;
    }

    public static function delete(Appointment $appointment)
    {
        if ($appointment->user->id !== auth()->user()->id) {
            return response()->json(['error' => 'You are not authorized.'], Response::HTTP_UNAUTHORIZED);
        }

        $appointment->delete();

        return response()->json(['success' => 'The appointment has deleted.'], Response::HTTP_OK);
    }

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
