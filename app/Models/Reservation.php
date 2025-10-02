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
     * 予約（履歴を除く）を取り出す
     */
    public function scopeUpcoming($query)
    {
        return $query->where('reserved_at', '>=', now());
    }
    /**
     * 履歴を取り出す
     */
    public function scopePast($query)
    {
        return $query->where('reserved_at', '<', now());
    }
    
    /**
     * relation to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
