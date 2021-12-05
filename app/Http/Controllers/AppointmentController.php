<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Http\Requests\AppointmentUpdateRequest;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\DateFilterRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Repositories\AppointmentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(DateFilterRequest $request)
    {
        $appointments = AppointmentRepository::all($request);

        return AppointmentResource::collection($appointments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AppointmentRequest  $appointmentRequest
     * @param  ContactRequest  $contactRequest
     * @return AppointmentResource
     */
    public function store(ContactRequest $contactRequest, AppointmentRequest $appointmentRequest)
    {
        $appointmentValidated = $appointmentRequest->validated();
        $contactValidated = $contactRequest->validated();

        return new AppointmentResource(AppointmentRepository::save($contactValidated, $appointmentValidated));
    }

    /**
     * Display the specified resource.
     *
     * @param  Appointment  $appointment
     * @return AppointmentResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);

        return new AppointmentResource($appointment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Appointment  $appointment
     * @param  AppointmentRequest  $appointmentRequest
     * @return AppointmentResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Appointment $appointment, AppointmentRequest $appointmentRequest)
    {
        $appointmentValidated = $appointmentRequest->validated();

        return new AppointmentResource(AppointmentRepository::update($appointment, $appointmentValidated));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Appointment  $appointment
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);

        AppointmentRepository::delete($appointment);

        return response()->json(['message' => 'The appointment has deleted.'], Response::HTTP_OK);
    }
}
