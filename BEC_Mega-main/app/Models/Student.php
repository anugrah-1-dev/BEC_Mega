<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'students';

    // Kolom yang boleh diisi
    protected $fillable = [
        'fullname',
        'email',
        'course_id'
    ];

    // Relasi ke tabel courses
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}