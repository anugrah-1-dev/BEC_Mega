<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TourController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\TutorialController;

// =======================
// HALAMAN UTAMA & TOUR
// =======================
Route::get('/', [TourController::class, 'index'])->name('home');
Route::get('/tour', [TourController::class, 'index'])->name('tour.index');

// =======================
// AUTH (REGISTER & LOGIN)
// =======================
Route::get('/register', [AdmissionController::class, 'showRegister'])->name('register');
Route::post('/register', [AdmissionController::class, 'register'])->name('register.store');

Route::get('/login', [AdmissionController::class, 'showLogin'])->name('login');
Route::post('/login', [AdmissionController::class, 'login'])->name('login.store');

// 🔥 FIX: logout pakai POST (lebih aman)
Route::post('/logout', [AdmissionController::class, 'logout'])->name('logout');

// =======================
// REGISTER DENGAN POS (GUEST ACCESSIBLE)
// =======================
Route::get('/daftar', [AdmissionController::class, 'showRegisterPOS'])->name('register.pos');
Route::post('/daftar', [AdmissionController::class, 'processRegisterPOS'])->name('register.pos.process');

// =======================
// USER (STUDENT FLOW)
// =======================
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdmissionController::class, 'dashboard'])->name('dashboard');

    // Isi Data
    Route::get('/isi-data', [AdmissionController::class, 'showIsiData'])->name('isi_data');
    Route::post('/isi-data', [AdmissionController::class, 'storeIsiData'])->name('isi_data.store');

    // Pilih Kursus
    Route::get('/pilih-course', [AdmissionController::class, 'showPilihCourse'])->name('pilih_course');
    Route::post('/pilih-course', [AdmissionController::class, 'storePilihCourse'])->name('pilih_course.store');
    Route::delete('/batal-kursus', [AdmissionController::class, 'destroyPilihCourse'])->name('pilih_course.destroy');

    // Upload Pembayaran
    Route::get('/upload-bayar', [AdmissionController::class, 'showUploadBayar'])->name('upload_bayar');
    Route::post('/upload-bayar', [AdmissionController::class, 'storeUploadBayar'])->name('upload_bayar.store');

    // Lihat Status (🔥 ini yang penting)
    Route::get('/lihat-status', [AdmissionController::class, 'lihatStatus'])->name('lihat_status');

    // Komentar
    Route::post('/tambah-komentar', [AdmissionController::class, 'storeKomentar'])->name('komentar.store');

    // POS System (Student)
    Route::get('/pos', [AdmissionController::class, 'showPOS'])->name('pos.index');
    Route::post('/pos', [AdmissionController::class, 'processPOS'])->name('pos.process');

    // Checkout POS (Student)
    Route::get('/checkout', [AdmissionController::class, 'showCheckout'])->name('checkout.index');
    Route::post('/checkout', [AdmissionController::class, 'processCheckout'])->name('checkout.process');

    // Quick Registration (Direct POS)
    Route::get('/pendaftaran-cepat', [AdmissionController::class, 'showDirectPOS'])->name('pos.direct');
    Route::post('/pendaftaran-cepat', [AdmissionController::class, 'processDirectPOS'])->name('pos.direct.process');
    Route::get('/invoice/{id}', [AdmissionController::class, 'showInvoice'])->name('pos.invoice');
    Route::get('/invoice/{id}/download-pdf', [AdmissionController::class, 'downloadInvoicePDF'])->name('pos.invoice.pdf');

    // =======================
    // ADMIN
    // =======================
    Route::prefix('admin')
        ->middleware('can:is-admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

            Route::get('/pendaftar', [AdminDashboardController::class, 'pendaftar'])->name('pendaftar');
            Route::get('/pendaftar/{id}', [AdminDashboardController::class, 'showPendaftar'])->name('pendaftar.show');

            Route::post('/pendaftar/{id}/verify', [AdminDashboardController::class, 'verify'])->name('pendaftar.verify');

            Route::post('/pendaftar/{id}/validate-payment', [AdminDashboardController::class, 'validatePayment'])->name('pendaftar.validate_payment');

            Route::get('/kelola-data', [AdminDashboardController::class, 'kelolaData'])->name('kelola_data');

            Route::post('/kelola-data/course', [AdminDashboardController::class, 'storeCourse'])->name('course.store');
            Route::delete('/kelola-data/course/{id}', [AdminDashboardController::class, 'destroyCourse'])->name('course.destroy');
            Route::post('/kelola-data/period', [AdminDashboardController::class, 'storePeriod'])->name('period.store');
            Route::delete('/kelola-data/period/{id}', [AdminDashboardController::class, 'destroyPeriod'])->name('period.destroy');
            Route::post('/kelola-data/transport', [AdminDashboardController::class, 'storeTransport'])->name('transport.store');
            Route::delete('/kelola-data/transport/{id}', [AdminDashboardController::class, 'destroyTransport'])->name('transport.destroy');
            Route::post('/kelola-data/bank', [AdminDashboardController::class, 'storeBank'])->name('bank.store');
            Route::delete('/kelola-data/bank/{id}', [AdminDashboardController::class, 'destroyBank'])->name('bank.destroy');

            Route::get('/pos', [AdminDashboardController::class, 'showPOS'])->name('pos');
            Route::post('/pos', [AdminDashboardController::class, 'processPOS'])->name('pos.process');

            Route::get('/laporan', [AdminDashboardController::class, 'laporan'])->name('laporan');
            Route::get('/laporan/export-excel', [AdminDashboardController::class, 'exportExcel'])->name('laporan.export_excel');
            Route::get('/siswa', [AdminDashboardController::class, 'siswa'])->name('siswa');
        });
});

// =======================
// FITUR TUTORIAL VIDEO
// =======================
Route::get('/tutorial-data', [TutorialController::class, 'getData']);
Route::post('/tutorial/view', [TutorialController::class, 'incrementView']);
Route::post('/tutorial/like', [TutorialController::class, 'toggleLike']);
Route::post('/tutorial/comment', [TutorialController::class, 'addComment']);
Route::post('/tutorial/reset', [TutorialController::class, 'resetData']);
Route::post('/tutorial/demo-increment', [TutorialController::class, 'demoIncrement']);