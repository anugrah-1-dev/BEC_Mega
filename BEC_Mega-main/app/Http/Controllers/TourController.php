<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\AdditionalService;
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
        $newRegistrations = Registration::with(['user', 'course', 'period', 'comments.user'])->latest()->take(15)->get();
        $periods = DB::table('periods')->latest()->take(12)->get();
        $transports = DB::table('transports')->get();
        $additionalServices = AdditionalService::where('is_active', true)->orderBy('name')->get();
        $users = User::latest()->take(5)->get();

        // Tables that may not exist yet — wrapped safely
        $students = collect();
        $banks = collect();
        $courseFeatures = collect();
        $payments = collect();
        $permits = collect();

        try {
            $students = Student::with('course')->latest()->take(10)->get();
        } catch (\Exception $e) {}

        try {
            $banks = DB::table('banks')->get();
        } catch (\Exception $e) {}

        try {
            $courseFeatures = DB::table('course_features')->get()->groupBy('course_id');
        } catch (\Exception $e) {}

        try {
            $payments = DB::table('payments')
                ->join('students', 'payments.student_id', '=', 'students.id')
                ->select('payments.*', 'students.fullname as student_name')
                ->latest()
                ->take(15)
                ->get();
        } catch (\Exception $e) {}

        try {
            $permits = DB::table('permits')->get();
        } catch (\Exception $e) {}

        return view('tour', compact(
            'courses', 
            'students', 
            'newRegistrations', 
            'banks', 
            'courseFeatures', 
            'payments', 
            'periods', 
            'transports',
            'additionalServices',
            'permits', 
            'users'
        ));
    }
}
