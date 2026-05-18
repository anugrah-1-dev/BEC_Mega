<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // Menampilkan form pendaftaran
    public function create()
    {
        $courses = Course::all(); // ambil semua data course
        return view('register', compact('courses'));
    }

    // Menyimpan data ke database
    public function store(Request $request)
    {
        // VALIDASI DATA
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'course_id' => 'required|exists:courses,id',
        ]);

        // SIMPAN DATA
        Student::create([
            'fullname' => $request->name,
            'email' => $request->email,
            'course_id' => $request->course_id,
        ]);

        // REDIRECT
        return redirect()->back()->with('success', 'Pendaftaran berhasil');
    }
}