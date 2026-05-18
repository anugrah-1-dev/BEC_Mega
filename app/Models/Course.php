<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'courses';

    // Kolom yang boleh diisi
    protected $fillable = [
        'name',
        'price',
        'description'
    ];

    // Relasi ke students
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}