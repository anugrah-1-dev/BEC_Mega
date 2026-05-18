<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Period;
use App\Models\StudentDetail;
use App\Models\Registration;
use App\Models\Transport;
use App\Models\RegistrationComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class AdmissionController extends Controller
{
    // ================= AUTH =================

    public function showRegister()
    {
        return view('admission.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'Status' => 'Aktif',
        ]);

        Auth::login($user);

        return redirect()->route('pos.index');
    }

    public function showLogin()
    {
        return view('admission.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return Auth::user()->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ================= DASHBOARD =================

    public function dashboard()
    {
        $user = Auth::user();

        $registration = Registration::with(['course', 'period', 'transport'])
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        $banks = DB::table('banks')->where('status', 'active')->get();
        return view('admission.dashboard', compact('user', 'registration', 'banks'));
    }

    // ================= DATA DIRI =================

    public function showIsiData()
    {
        $detail = StudentDetail::where('user_id', Auth::id())->first();

        return view('admission.isi_data', compact('detail'));
    }

    public function storeIsiData(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'birth_date' => 'required|date',
        ]);

        StudentDetail::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only(['phone', 'address', 'gender', 'birth_date'])
        );

        return redirect()->route('pilih_course')
            ->with('success', 'Data profil berhasil disimpan.');
    }

    // ================= PILIH KURSUS =================

    public function showPilihCourse()
    {
        $courses = Course::all();
        $periods = Period::all();
        $transports = Transport::where('status', 'active')->get();
        $features = DB::table('course_features')->where('status', 'active')->get()->groupBy('course_id');

        return view('admission.pilih_course', compact('courses', 'periods', 'features', 'transports'));
    }

    public function storePilihCourse(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'period_id' => 'required|exists:periods,id',
            'transport_id' => 'required|exists:transports,id',
        ]);

        Registration::updateOrCreate(
            ['user_id' => Auth::id(), 'status' => 'pending'],
            [
                'course_id' => $request->course_id,
                'period_id' => $request->period_id,
                'transport_id' => $request->transport_id,
                'payment_status' => 'unpaid',
            ]
        );

        return redirect()->route('upload_bayar')
            ->with('success', 'Kursus berhasil dipilih.');
    }

    public function destroyPilihCourse()
    {
        $registration = Registration::where('user_id', Auth::id())->latest()->first();
        if ($registration) {
            $registration->delete();
            return redirect()->route('dashboard')->with('success', 'Pilihan kursus berhasil dihapus/dibatalkan.');
        }
        return back()->with('error', 'Tidak ada data pendaftaran aktif yang bisa dihapus.');
    }

    // ================= UPLOAD PEMBAYARAN =================

    public function showUploadBayar()
    {
        $registration = Registration::where('user_id', Auth::id())->latest()->first();

        $banks = DB::table('banks')->where('status', 'active')->get();
        return view('admission.upload_bayar', compact('registration', 'banks'));
    }

    public function storeUploadBayar(Request $request)
    {
        $request->validate([
            'payment_proof' => 'required|image|max:2048',
        ]);

        $registration = Registration::where('user_id', Auth::id())->latest()->first();

        if (!$registration) {
            return back()->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payments', 'public');

            $registration->update([
                'payment_proof' => $path,
                'payment_status' => 'pending_validation',
            ]);
        }

        return redirect()->route('lihat_status')
            ->with('success', 'Bukti pembayaran berhasil diunggah.');
    }

    // ================= STATUS =================

    public function lihatStatus()
    {
        $registration = Registration::with(['course', 'period', 'transport'])
            ->where('user_id', Auth::id())
            ->latest()
            ->first();

        // ✅ FIX: hindari error jika null
        $comments = $registration
            ? RegistrationComment::where('registration_id', $registration->id)->get()
            : collect();

        return view('admission.lihat_status', compact('registration', 'comments'));
    }

    // ================= KOMENTAR =================

    public function storeKomentar(Request $request)
    {
        $request->validate([
            'comment' => 'required',
        ]);

        $registration = Registration::where('user_id', Auth::id())->latest()->first();

        if (!$registration) {
            return back()->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        RegistrationComment::create([
            'registration_id' => $registration->id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        // Automated Admin Reply Logic
        $lower = strtolower($request->comment);
        $reply = "";
        $admin = User::where('role', 'admin')->first();
        
        if ($admin) {
            $prefixes = [
                "Halo Kak!", "Siap Kak!", "Wah, terima kasih atas pertanyaannya.", 
                "Senang sekali Kakak tanya ini.", "Berikut infonya ya Kak:",
                "Terima kasih atas masukannya!", "Mari saya bantu jelaskan ya."
            ];
            $prefix = $prefixes[array_rand($prefixes)];

            if (str_contains($lower, 'daftar') || str_contains($lower, 'verifikasi') || str_contains($lower, 'cek') || str_contains($lower, 'sudah') || str_contains($lower, 'acc')) {
                $reply = "$prefix Pendaftaran Kakak sedang kami proses. Mohon ditunggu proses verifikasi dari tim Frontliner kami ya. Jangan lupa cek status pembayaran secara berkala! 👍";
            } else if (str_contains($lower, 'bayar') || str_contains($lower, 'lunas') || str_contains($lower, 'bukti') || str_contains($lower, 'uang') || str_contains($lower, 'transfer') || str_contains($lower, 'rekening')) {
                $reply = "$prefix Jika sudah mengunggah bukti pembayaran, tim keuangan kami akan memvalidasi dalam 1x24 jam. Pastikan nominal transfer sesuai dengan total biaya ya Kak! Rekening tujuan ada di halaman 'Upload Pembayaran'.";
            } else if (str_contains($lower, 'kelas') || str_contains($lower, 'mulai') || str_contains($lower, 'kapan') || str_contains($lower, 'belajar')) {
                $reply = "$prefix Kelas baru biasanya dimulai pada tanggal 10 dan 25 setiap bulannya. Pastikan Kakak sudah menyelesaikan administrasi sebelum tanggal tersebut ya!";
            } else if (str_contains($lower, 'bantuan') || str_contains($lower, 'tolong') || str_contains($lower, 'tanya') || str_contains($lower, 'cs') || str_contains($lower, 'wa') || str_contains($lower, 'admin')) {
                $reply = "$prefix Tentu Kak, ada yang bisa kami bantu lebih lanjut? Atau Kakak bisa hubungi CS WhatsApp kami untuk respon yang lebih cepat. Kami siap membantu 24/7!";
            } else if (str_contains($lower, 'asrama') || str_contains($lower, 'camp') || str_contains($lower, 'kamar') || str_contains($lower, 'tinggal')) {
                $reply = "$prefix Untuk fasilitas asrama sudah tersedia di BEC. Kakak akan mendapatkan fasilitas kamar yang nyaman dan lingkungan yang suportif untuk belajar bahasa Inggris.";
            } else if (str_contains($lower, 'makan') || str_contains($lower, 'fasilitas') || str_contains($lower, 'modul') || str_contains($lower, 'buku')) {
                $reply = "$prefix Seluruh fasilitas seperti modul, buku, dan akses ke Virtual Tour ini sudah termasuk dalam paket pendaftaran Kakak. Jadi tinggal fokus belajar saja!";
            } else if (str_contains($lower, 'makasih') || str_contains($lower, 'terima kasih') || str_contains($lower, 'thanks') || str_contains($lower, 'tq')) {
                $reply = "Sama-sama Kak! Senang bisa melayani Kakak. Semoga lancar ya proses pendaftarannya dan sampai jumpa di BEC! 🔥";
            } else {
                $reply = "Halo Kak! Pesan Kakak sudah kami terima. Admin akan segera meninjau dan memberikan info lebih lanjut. Ada lagi yang ingin ditanyakan terkait pendaftaran BEC?";
            }

            RegistrationComment::create([
                'registration_id' => $registration->id,
                'user_id' => $admin->id,
                'comment' => $reply,
            ]);
        }

        return back()->with('success', 'Komentar berhasil dikirim dan telah dibalas otomatis oleh Admin.');
    }

    // ================= POS SYSTEM =================

    public function showPOS()
    {
        $courses = Course::all();
        $periods = Period::all();
        $transports = Transport::where('status', 'active')->get();
        
        $registration = Registration::where('user_id', Auth::id())->latest()->first();

        return view('admission.pos', compact('courses', 'periods', 'transports', 'registration'));
    }

    public function processPOS(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'period_id' => 'required|exists:periods,id',
            'transport_id' => 'required|exists:transports,id',
        ]);

        $course = Course::findOrFail($request->course_id);
        $transport = Transport::findOrFail($request->transport_id);
        $total_price = $course->price + ($course->admin_tax ?? 0) + $transport->price;
        $invoice = 'INV-' . strtoupper(Str::random(8));

        // Create/Update Registration
        $registration = Registration::updateOrCreate(
            ['user_id' => Auth::id(), 'status' => 'pending'],
            [
                'course_id' => $request->course_id,
                'period_id' => $request->period_id,
                'transport_id' => $request->transport_id,
                'invoice_number' => $invoice,
                'payment_status' => 'unpaid',
            ]
        );

        // Generate Midtrans Snap Token
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        if (str_contains(Config::$serverKey, 'placeholder')) {
            return back()->with('error', 'Konfigurasi Midtrans belum lengkap. Silakan masukkan Server Key yang valid di file .env.');
        }

        try {
            $params = [
                'transaction_details' => [
                    'order_id' => $invoice,
                    'gross_amount' => (int) $total_price,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
                'item_details' => [
                    [
                        'id' => $course->id,
                        'price' => (int) $course->price,
                        'quantity' => 1,
                        'name' => $course->name,
                    ],
                    [
                        'id' => 'admin_' . $course->id,
                        'price' => (int) ($course->admin_tax ?? 0),
                        'quantity' => 1,
                        'name' => 'Biaya Administrasi',
                    ],
                    [
                        'id' => $transport->id,
                        'price' => (int) $transport->price,
                        'quantity' => 1,
                        'name' => 'Transport: ' . $transport->name,
                    ]
                ]
            ];

            $snapToken = Snap::getSnapToken($params);
            $registration->update(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal terhubung ke Midtrans: ' . $e->getMessage());
        }

        return redirect()->route('lihat_status')->with('success', 'Checkout berhasil. Silakan selesaikan pembayaran.');
    }

    // ================= DIRECT POS (QUICK REGISTRATION) =================

    public function showDirectPOS()
    {
        $courses = Course::all();
        $periods = Period::all();
        $transports = Transport::where('status', 'active')->get();
        return view('admission.direct_pos', compact('courses', 'periods', 'transports'));
    }

    public function processDirectPOS(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required',
            'course_id' => 'required|exists:courses,id',
            'period_id' => 'required|exists:periods,id',
            'transport_id' => 'required|exists:transports,id',
        ]);

        // 1. Auto Account Creation (Semi-login)
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('password'),
                'role' => 'student',
                'Status' => 'Aktif',
            ]);
        }

        // 2. Log in automatically
        Auth::login($user);

        // 3. Save Student Detail
        StudentDetail::updateOrCreate(
            ['user_id' => $user->id],
            ['phone' => $request->phone, 'address' => 'Belum diisi']
        );

        $course = Course::findOrFail($request->course_id);
        $transport = Transport::findOrFail($request->transport_id);
        $total_price = $course->price + ($course->admin_tax ?? 0) + $transport->price;
        $invoice = 'INV-' . strtoupper(Str::random(8));

        // 4. Create Registration
        $registration = Registration::create([
            'user_id' => $user->id,
            'course_id' => $request->course_id,
            'period_id' => $request->period_id,
            'transport_id' => $request->transport_id,
            'invoice_number' => $invoice,
            'payment_status' => 'unpaid',
            'status' => 'pending'
        ]);

        // 5. Generate Midtrans Snap Token
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        if (str_contains(Config::$serverKey, 'placeholder')) {
            return redirect()->route('lihat_status')->with('error', 'Konfigurasi Midtrans belum lengkap. Silakan masukkan Server Key yang valid di file .env.');
        }

        try {
            $params = [
                'transaction_details' => [
                    'order_id' => $invoice,
                    'gross_amount' => (int) $total_price,
                ],
                'customer_details' => [
                    'first_name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                ],
                'item_details' => [
                    [
                        'id' => $course->id,
                        'price' => (int) $course->price,
                        'quantity' => 1,
                        'name' => $course->name,
                    ],
                    [
                        'id' => 'admin_' . $course->id,
                        'price' => (int) ($course->admin_tax ?? 0),
                        'quantity' => 1,
                        'name' => 'Biaya Administrasi',
                    ],
                    [
                        'id' => $transport->id,
                        'price' => (int) $transport->price,
                        'quantity' => 1,
                        'name' => 'Transport: ' . $transport->name,
                    ]
                ]
            ];

            $snapToken = Snap::getSnapToken($params);
            $registration->update(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return redirect()->route('lihat_status')->with('error', 'Gagal terhubung ke Midtrans: ' . $e->getMessage());
        }

        return redirect()->route('lihat_status')->with('success', 'Pendaftaran Berhasil! Silakan selesaikan pembayaran.');
    }

    public function showInvoice($id)
    {
        $registration = Registration::with(['user', 'course', 'period', 'transport'])->findOrFail($id);
        
        // Security check
        if (Auth::user()->role !== 'admin' && $registration->user_id !== Auth::id()) {
            abort(403);
        }

        return view('admission.invoice', compact('registration'));
    }

    public function midtransCallback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            $registration = Registration::where('invoice_number', $request->order_id)->first();
            if ($registration) {
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    $registration->update([
                        'payment_status' => 'paid',
                        'status' => 'completed',
                        'payment_type' => $request->payment_type
                    ]);
                } else if ($request->transaction_status == 'pending') {
                    $registration->update(['payment_status' => 'pending_validation']);
                } else if ($request->transaction_status == 'deny' || $request->transaction_status == 'expire' || $request->transaction_status == 'cancel') {
                    $registration->update(['payment_status' => 'unpaid']);
                }
            }
        }
        return response()->json(['status' => 'success']);
    }

    public function showRegisterPOS()
    {
        $courses = Course::all();
        $periods = Period::all();
        $transports = Transport::where('status', 'active')->get();
        return view('admission.register_pos', compact('courses', 'periods', 'transports'));
    }

    public function processRegisterPOS(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string',
            'course_id' => 'required|exists:courses,id',
            'period_id' => 'required|exists:periods,id',
            'transport_id' => 'required|exists:transports,id',
        ]);

        // 1. Semi-login: Cari atau buat akun user
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('password'), // default password untuk semi-login
                'role' => 'student',
                'Status' => 'Aktif',
            ]);
        }

        // 2. Simpan detail no HP siswa
        StudentDetail::updateOrCreate(
            ['user_id' => $user->id],
            ['phone' => $request->phone, 'address' => 'Belum diisi']
        );

        // 3. Login otomatis
        Auth::login($user);

        // 4. Buat data pendaftaran dengan status pending / unpaid
        $invoice = 'INV-' . strtoupper(Str::random(8));
        $registration = Registration::create([
            'user_id' => $user->id,
            'course_id' => $request->course_id,
            'period_id' => $request->period_id,
            'transport_id' => $request->transport_id,
            'invoice_number' => $invoice,
            'payment_status' => 'unpaid',
            'status' => 'pending'
        ]);

        return redirect()->route('checkout.index')->with('success', 'Registrasi berhasil! Silakan selesaikan pembayaran.');
    }

    public function showCheckout()
    {
        $registration = Registration::with(['course', 'period', 'transport'])
            ->where('user_id', Auth::id())
            ->latest()
            ->first();

        if (!$registration) {
            return redirect()->route('register.pos')->with('error', 'Silakan pilih program terlebih dahulu.');
        }

        return view('admission.checkout', compact('registration'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|in:Transfer Bank,E-Wallet,QRIS',
        ]);

        $registration = Registration::where('user_id', Auth::id())->latest()->first();

        if (!$registration) {
            return redirect()->route('register.pos')->with('error', 'Pendaftaran tidak ditemukan.');
        }

        // Update status pendaftaran
        $registration->update([
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending_validation', // status pending menunggu konfirmasi admin
            'status' => 'pending'
        ]);

        return redirect()->route('pos.invoice', $registration->id)->with('success', 'Transaksi berhasil diajukan! Bukti invoice Anda telah digenerate.');
    }
}