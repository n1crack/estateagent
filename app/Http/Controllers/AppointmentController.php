<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Http\Requests\AppointmentUpdateRequest;
use App\Http\Requests\ContactRequest;
use App\Models\Appointment;
use App\Repositories\AppointmentRepository;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $appointments = AppointmentRepository::all($request);

        return $appointments;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AppointmentRequest  $appointmentRequest
     * @param  ContactRequest  $contactRequest
     * @return \Illuminate\Http\Response
     */
    public function store(ContactRequest $contactRequest, AppointmentRequest $appointmentRequest)
    {
        $appointmentValidated = $appointmentRequest->validated();
        $contactValidated = $contactRequest->validated();

        return AppointmentRepository::save($contactValidated, $appointmentValidated);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment)
    {
        return AppointmentRepository::get($appointment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AppointmentRequest  $appointmentRequest
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Appointment $appointment, AppointmentRequest $appointmentRequest)
    {
        $appointmentValidated = $appointmentRequest->validated();

        return AppointmentRepository::update($appointment, $appointmentValidated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        return AppointmentRepository::delete($appointment);
    }
}
