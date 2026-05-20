<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDetail extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'phone', 'address', 'gender', 'birth_date', 'birth_place', 'uniform_size', 'guardian_phone'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
