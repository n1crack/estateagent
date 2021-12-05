<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        //  return parent::toArray($request);
        return [
            'id' => $this->id,
            'employee_id' => $this->user_id,
            'address' => $this->address,
            'distance' => $this->distance,
            'time' => $this->time,
            'date' => $this->date,
            'when_to_leave' => $this->when_to_leave->format('Y-m-d H:i:s'),
            'next_available_date' => $this->next_available_date->format('Y-m-d H:i:s'),
            'contact_id' => $this->contact_id,
            'contact_name' => $this->contact->name,
            'contact_surname' => $this->contact->surname,
            'contact_phone' => $this->contact->phone,
            'contact_email' => $this->contact->email,
        ];
    }
}
