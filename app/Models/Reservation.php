<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reserved_at',
        'purpose',
    ];
    /**
     * relation to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
