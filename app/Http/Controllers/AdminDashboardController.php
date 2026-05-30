<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Period;
use App\Models\Transport;
use App\Models\Registration;
use App\Models\RegistrationComment;
use App\Exports\LaporanExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $total_income = Registration::where('payment_status', 'paid')
            ->join('courses', 'registrations.course_id', '=', 'courses.id')
            ->sum('courses.price');

        // Add transport price to income if applicable
        $transport_income = Registration::where('payment_status', 'paid')
            ->join('transports', 'registrations.transport_id', '=', 'transports.id')
            ->sum('transports.price');
            
        $stats = [
            'total_applicants' => Registration::count(),
            'pending_verifications' => Registration::where('status', 'pending')->count(),
            'pending_payments' => Registration::where('payment_status', 'pending_validation')->count(),
            'total_income' => $total_income + $transport_income,
            'total_programs' => Course::count(),
            'total_students' => User::where('role', 'student')->count(),
        ];
        return view('admin.dashboard', compact('stats'));
    }

    public function pendaftar(Request $request)
    {
        $query = Registration::with(['user', 'course', 'period', 'transport']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        $applicants = $query->latest()->get();
        return view('admin.pendaftar.index', compact('applicants'));
    }

    public function showPendaftar($id)
    {
        $applicant = Registration::with(['user.studentDetail', 'course', 'period', 'transport'])->findOrFail($id);
        $comments = RegistrationComment::with('user')->where('registration_id', $id)->get();
        return view('admin.pendaftar.show', compact('applicant', 'comments'));
    }

    public function verify($id, Request $request)
    {
        $registration = Registration::findOrFail($id);
        $registration->update(['status' => 'verified']);
        return back()->with('success', 'Pendaftaran berhasil diverifikasi.');
    }

    public function validatePayment($id, Request $request)
    {
        $registration = Registration::findOrFail($id);
        $registration->update([
            'payment_status' => 'paid',
            'status' => 'completed'
        ]);
        return back()->with('success', 'Transaksi berhasil ditandai sebagai LUNAS.');
    }

    public function kelolaData()
    {
        $courses = Course::all();
        $periods = Period::all();
        $transports = Transport::all();
        $banks = \App\Models\Bank::all();
        return view('admin.kelola_data', compact('courses', 'periods', 'transports', 'banks'));
    }

    public function storeCourse(Request $request)
    {
        $request->validate(['name' => 'required', 'price' => 'required|numeric']);
        Course::create($request->all());
        return back()->with('success', 'Kursus berhasil ditambahkan.');
    }

    public function destroyCourse($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return back()->with('success', 'Kursus berhasil dihapus.');
    }

    public function storePeriod(Request $request)
    {
        $request->validate(['name' => 'required', 'start_date' => 'required|date']);
        Period::create($request->all());
        return back()->with('success', 'Periode berhasil ditambahkan.');
    }

    public function destroyPeriod($id)
    {
        $period = Period::findOrFail($id);
        $period->delete();
        return back()->with('success', 'Periode berhasil dihapus.');
    }

    public function storeTransport(Request $request)
    {
        $request->validate(['name' => 'required', 'price' => 'required|numeric']);
        Transport::create($request->all());
        return back()->with('success', 'Transport berhasil ditambahkan.');
    }

    public function destroyTransport($id)
    {
        $transport = Transport::findOrFail($id);
        $transport->delete();
        return back()->with('success', 'Transport berhasil dihapus.');
    }

    public function storeBank(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'number' => 'required',
            'owner' => 'required'
        ]);
        
        $data = $request->all();
        $data['status'] = 'active'; // Default active

        \App\Models\Bank::create($data);
        return back()->with('success', 'Rekening Bank berhasil ditambahkan.');
    }

    public function destroyBank($id)
    {
        $bank = \App\Models\Bank::findOrFail($id);
        $bank->delete();
        return back()->with('success', 'Rekening Bank berhasil dihapus.');
    }

    // ================= ADMIN POS =================

    public function showPOS()
    {
        $courses = Course::all();
        $periods = Period::all();
        $transports = Transport::all();
        $users = User::where('role', 'student')->get();
        return view('admin.pos', compact('courses', 'periods', 'transports', 'users'));
    }

    public function processPOS(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'period_id' => 'required|exists:periods,id',
            'transport_id' => 'required|exists:transports,id',
        ]);

        Registration::create([
            'user_id' => $request->user_id,
            'course_id' => $request->course_id,
            'period_id' => $request->period_id,
            'transport_id' => $request->transport_id,
            'status' => 'completed',
            'payment_status' => 'paid', // Admin bypasses payment proof
        ]);

        return redirect()->route('admin.pendaftar')->with('success', 'Pendaftaran melalui POS Admin berhasil diproses dan otomatis LUNAS.');
    }

    // ================= LAPORAN & SISWA =================

    public function laporan(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $query = Registration::where('payment_status', 'paid')
            ->with(['course', 'transport', 'user']);

        if ($period == 'daily') {
            $reportData = $query->whereDate('updated_at', today())->get();
        } else {
            $reportData = $query->whereMonth('updated_at', now()->month)
                               ->whereYear('updated_at', now()->year)
                               ->get();
        }

        $total_income = $reportData->sum(function($reg) {
            return ($reg->course->price ?? 0) + ($reg->transport->price ?? 0);
        });

        return view('admin.laporan', compact('reportData', 'total_income', 'period'));
    }

    public function exportExcel(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $query  = Registration::where('payment_status', 'paid')
            ->with(['course', 'transport', 'user']);

        if ($period == 'daily') {
            $reportData = $query->whereDate('updated_at', today())->get();
        } else {
            $reportData = $query->whereMonth('updated_at', now()->month)
                               ->whereYear('updated_at', now()->year)
                               ->get();
        }

        $total_income = $reportData->sum(function ($reg) {
            return ($reg->course->price ?? 0)
                 + ($reg->transport->price ?? 0)
                 + ($reg->course->admin_tax ?? 0);
        });

        $label    = $period === 'daily' ? 'harian' : 'bulanan';
        $filename = 'laporan-' . $label . '-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new LaporanExport($period, $reportData, $total_income), $filename);
    }

    public function siswa()
    {
        $students = User::where('role', 'student')->with('studentDetail')->latest()->get();
        return view('admin.siswa.index', compact('students'));
    }
}
