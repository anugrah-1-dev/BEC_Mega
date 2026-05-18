<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'period_id',
        'transport_id',
        'payment_method',
        'invoice_number',
        'snap_token',
        'payment_proof',
        'status',
        'payment_status',
        'has_catering',
        'has_laundry',
        'has_holiday',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function transport()
    {
        return $this->belongsTo(Transport::class);
    }

    public function comments()
    {
        return $this->hasMany(RegistrationComment::class);
    }
}
