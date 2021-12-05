<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['contact'];
    protected $casts = [
        'user_id' => 'integer',
        'when_to_leave' => 'datetime',
        'next_available_date' => 'datetime',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function scopeFilterDate($query, $operator, $date)
    {
        return $query->where('date', $operator, $date);
    }
}
