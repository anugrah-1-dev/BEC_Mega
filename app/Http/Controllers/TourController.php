<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use App\Models\Registration;
use App\Models\Period;
use App\Models\StudentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TourController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        $students = Student::with('course')->latest()->take(10)->get();
        $newRegistrations = Registration::with(['user', 'course', 'period', 'comments.user'])->latest()->take(15)->get();
        
        // Additional data for the comprehensive UI
        $banks = DB::table('banks')->get();
        $courseFeatures = DB::table('course_features')->get()->groupBy('course_id');
        $payments = DB::table('payments')
            ->join('students', 'payments.student_id', '=', 'students.id')
            ->select('payments.*', 'students.fullname as student_name')
            ->latest()
            ->take(15)
            ->get();
        $periods = DB::table('periods')->latest()->take(12)->get();
        $transports = DB::table('transports')->get();
        $permits = DB::table('permits')->get();
        $users = User::latest()->take(5)->get();

        return view('tour', compact(
            'courses', 
            'students', 
            'newRegistrations', 
            'banks', 
            'courseFeatures', 
            'payments', 
            'periods', 
            'transports', 
            'permits', 
            'users'
        ));
    }
}
