<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalService extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'is_active'];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
