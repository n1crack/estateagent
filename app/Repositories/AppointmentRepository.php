<?php

namespace App\Repositories;

use App\Http\Requests\AppointmentRequest;
use App\Http\Requests\ContactRequest;
use App\Models\Appointment;
use App\Models\Contact;

class AppointmentRepository
{

    public static function all()
    {
        return Appointment::all();
    }

    public static function save($contactData, $appoinmentData)
    {
        // create customer
        $contact = Contact::create($contactData);

        // create appointment
        $appoinmentData['user_id'] = auth()->user()->id;
        $appoinmentData['contact_id'] = $contact->id;

        $appoinmentData['distance'] = 0;
        $appoinmentData['time'] = 0;
        $appoinmentData['when_to_leave'] = now();
        $appoinmentData['next_available_date'] = now();

        $appoinment = Appointment::create($appoinmentData);

        return compact('contact', 'appoinment');
    }

    public static function update(Appointment $appointment, $appoinmentData)
    {
        // create appointment
        $appoinmentData['distance'] = 0;
        $appoinmentData['time'] = 0;
        $appoinmentData['when_to_leave'] = now();
        $appoinmentData['next_available_date'] = now();

        $appoinment = $appointment->update($appoinmentData);

        return compact('appoinment');
    }

    public static function delete(Appointment $appointment)
    {
        $appointment->contact()->delete();

        return $appointment->delete();
    }

}
