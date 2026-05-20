<?php

namespace App\Http\Controllers;

use App\Models\TutorialStat;
use App\Models\TutorialComment;
use Illuminate\Http\Request;

class TutorialController extends Controller
{
    public function getData()
    {
        $stats = TutorialStat::firstOrCreate(['id' => 1], ['views' => 0, 'likes' => 0]);
        $comments = TutorialComment::orderBy('created_at', 'asc')->get();

        return response()->json([
            'views' => $stats->views,
            'likes' => $stats->likes,
            'comments' => $comments
        ]);
    }

    public function incrementView()
    {
        $stats = TutorialStat::firstOrCreate(['id' => 1], ['views' => 0, 'likes' => 0]);
        $stats->increment('views');
        return response()->json(['success' => true, 'views' => $stats->views]);
    }

    public function toggleLike(Request $request)
    {
        $isLiked = $request->input('is_liked');
        $stats = TutorialStat::firstOrCreate(['id' => 1], ['views' => 0, 'likes' => 0]);
        
        if ($isLiked) {
            $stats->increment('likes');
        } else {
            if ($stats->likes > 0) {
                $stats->decrement('likes');
            }
        }

        return response()->json(['success' => true, 'likes' => $stats->likes]);
    }

    public function addComment(Request $request)
    {
        $request->validate([
            'comment_text' => 'required|string',
        ]);

        $userName = auth()->check() ? auth()->user()->name : 'Sobat Brilliant';
        
        $userComment = TutorialComment::create([
            'user_name' => $userName,
            'comment_text' => $request->comment_text,
            'is_admin' => false,
        ]);

        // Smart Context-Aware Logic for Admin Reply
        $lower = strtolower($request->comment_text);
        $reply = "";
        
        // Randomized Greeting/Prefix
        $prefixes = [
            "Halo Kak! ", "Siap Kak, ", "Wah, pertanyaan bagus nih! ", 
            "Senang sekali Kakak tanya ini. ", "Berikut infonya ya Kak: ",
            "Terima kasih atas pertanyaannya! ", "Mari saya bantu jelaskan ya. "
        ];
        $prefix = $prefixes[array_rand($prefixes)];

        if (preg_match('/(cara|bagaimana|gimana|tutor|langkah|proses).*(daftar|registrasi|masuk|gabung)/i', $lower) || str_contains($lower, 'daftar') || str_contains($lower, 'registrasi')) {
            $reply = $prefix . "Untuk mendaftar, Kakak cukup klik tombol 'DAFTAR SEKARANG' di pojok kanan bawah layar ini. Nanti isi formulirnya, pilih program, dan selesai! Semuanya bisa dilakukan online tanpa ribet. 👍";
        } else if (preg_match('/(biaya|harga|tarif|bayar|mahal|murah|duit|dana|uang)/i', $lower)) {
            $reply = $prefix . "Biaya program di BEC sangat terjangkau, mulai dari Rp 150.000 saja tergantung program yang dipilih. Detail lengkap rincian biaya akan muncul secara otomatis saat Kakak mengisi formulir pendaftaran ya!";
        } else if (preg_match('/(lokasi|alamat|dimana|mana|kediri|pare|tempat|gedung)/i', $lower)) {
            $reply = $prefix . "Kampus Brilliant English Course (BEC) berlokasi di Jl. Anggrek No. 1, Kampung Inggris Pare, Kediri, Jawa Timur. Lokasinya sangat asri dan strategis, pas banget buat fokus belajar bahasa Inggris!";
        } else if (preg_match('/(kelas|program|kursus|belajar|offline|online|asrama|camp|mulai)/i', $lower)) {
            $reply = $prefix . "Kami menyediakan kelas offline dengan fasilitas asrama (camp) dan juga kelas online interaktif. Pendaftaran kelas baru dibuka setiap tanggal 10 dan 25 setiap bulannya. Kakak tertarik program yang mana nih?";
        } else if (preg_match('/(fasilitas|foto|ruang|kamar|gazebo|lihat|tampil)/i', $lower)) {
            $reply = $prefix . "Di Virtual Tour ini Kakak bisa melihat langsung semua fasilitas kami. Coba klik 'Daftar Ruangan' di menu samping kiri untuk melihat foto 360 derajat dari setiap sudut asrama dan kelas kami. Keren kan?";
        } else if (preg_match('/(sertifikat|ijazah|hasil|lulus|nilai)/i', $lower)) {
            $reply = $prefix . "Tentu saja Kak! Setiap pendaftar yang menyelesaikan program akan mendapatkan Sertifikat Resmi dari BEC yang diakui dan sangat berguna untuk melamar kerja atau beasiswa.";
        } else if (preg_match('/(makan|lapar|warung|kuliner|hidup)/i', $lower)) {
            $reply = $prefix . "Biaya hidup di Pare sangat murah, Kak! Di sekitar kampus banyak sekali warung makan enak dengan harga ramah di kantong pelajar. Dijamin betah deh tinggal di sini.";
        } else if (preg_match('/(halo|hi|p|assalamu|salam|pagi|siang|malam)/i', $lower)) {
            $reply = "Halo juga Sobat Brilliant! Ada yang bisa saya bantu terkait informasi pendaftaran atau fasilitas di BEC?";
        } else if (preg_match('/(makasih|terima kasih|thanks|tq|sip|oke|ok)/i', $lower)) {
            $reply = "Sama-sama Kak! Senang bisa membantu. Kalau ada pertanyaan lain jangan ragu buat tanya lagi ya. Sampai ketemu di BEC! 🔥";
        } else {
            $reply = "Pertanyaan yang menarik! Untuk respon lebih detail dan cepat, Kakak bisa langsung klik tombol daftar lalu hubungi Admin via WhatsApp yang tertera di sana ya. Tim kami siap membantu 24/7! 😊";
        }

        $adminComment = TutorialComment::create([
            'user_name' => 'Admin',
            'comment_text' => $reply,
            'is_admin' => true,
        ]);

        return response()->json([
            'success' => true, 
            'user_comment' => $userComment,
            'admin_comment' => $adminComment
        ]);
    }

    public function demoIncrement()
    {
        $stats = TutorialStat::firstOrCreate(['id' => 1], ['views' => 0, 'likes' => 0]);
        $stats->increment('views');

        return response()->json([
            'success' => true,
            'views' => $stats->views,
            'likes' => $stats->likes,
            'comment_count' => TutorialComment::count()
        ]);
    }

    public function resetData()
    {
        TutorialStat::truncate();
        TutorialComment::truncate();
        return response()->json(['success' => true]);
    }
}
