<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['contact'];
    protected $casts = ['user_id' => 'integer'];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
