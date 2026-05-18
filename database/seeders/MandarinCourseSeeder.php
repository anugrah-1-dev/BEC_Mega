<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MandarinCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Program Bahasa Mandarin - Brilliant English Course (BEC)
     */
    public function run(): void
    {
        $courses = [
            // === OFFLINE ===
            [
                'name'       => 'Paket 1 Minggu',
                'language'   => 'Mandarin',
                'type'       => 'Offline',
                'duration'   => '1 Minggu',
                'price'      => 624000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- Modul\n- Sertifikat\n- Kelas Cozy\n- Tempat Tinggal / Camp Reguler\n- 3 Kelas/Hari\n- Leader Camp\n- Merchandise\n- Ujian",
            ],
            [
                'name'       => 'Paket 2 Minggu',
                'language'   => 'Mandarin',
                'type'       => 'Offline',
                'duration'   => '2 Minggu',
                'price'      => 849000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- Modul\n- Sertifikat\n- Kelas Cozy\n- Tempat Tinggal / Camp Reguler\n- 3 Kelas/Hari\n- Leader Camp\n- Merchandise\n- Ujian",
            ],
            [
                'name'       => 'Paket 1 Bulan',
                'language'   => 'Mandarin',
                'type'       => 'Offline',
                'duration'   => '1 Bulan',
                'price'      => 1124000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- Modul\n- Sertifikat\n- Kelas Cozy\n- Tempat Tinggal / Camp Reguler\n- 3 Kelas/Hari\n- Leader Camp\n- Merchandise\n- Ujian\n- PRE-TEST HSK 1\n- Konsultasi Beasiswa/Kerja",
            ],
            [
                'name'       => 'Paket HSK 1 (1 Bulan)',
                'language'   => 'Mandarin',
                'type'       => 'Offline',
                'duration'   => '1 Bulan',
                'price'      => 1400000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- Modul\n- Sertifikat\n- Kelas Cozy\n- Tempat Tinggal / Camp Reguler\n- 3 Kelas/Hari\n- Leader Camp\n- Merchandise\n- Ujian\n- PRE-TEST HSK 2\n- Konsultasi Beasiswa/Kerja\n- Kelas Mendengar (Tingli 听力)",
            ],
            [
                'name'       => 'Paket HSK 2 (1 Bulan)',
                'language'   => 'Mandarin',
                'type'       => 'Offline',
                'duration'   => '1 Bulan',
                'price'      => 1800000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- Modul\n- Sertifikat\n- Kelas Cozy\n- Tempat Tinggal / Camp\n- 3 Kelas/Hari\n- Leader Camp\n- Merchandise\n- Ujian\n- PRE-TEST HSK 3\n- Konsultasi Beasiswa/Kerja\n- Kelas Mendengar (Tingli 听力)\n- Kelas Entrepreneur & Psychotraining",
            ],
            [
                'name'       => 'Paket HSK 2 (2 Bulan)',
                'language'   => 'Mandarin',
                'type'       => 'Offline',
                'duration'   => '2 Bulan',
                'price'      => 2880000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- E-Modul\n- E-Sertifikat\n- Kelas Cozy\n- Tempat Tinggal / Camp Reguler\n- 3 Kelas/Hari\n- Leader Camp\n- Merchandise\n- Ujian\n- PRE-TEST HSK 3\n- Konsultasi Beasiswa/Kerja\n- Kelas Mendengar (Tingli 听力)\n- Kelas Entrepreneur & Psychotraining",
            ],
            [
                'name'       => 'Paket HSK 3 (2 Bulan)',
                'language'   => 'Mandarin',
                'type'       => 'Offline',
                'duration'   => '2 Bulan',
                'price'      => 2500000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- Sertifikat\n- Kelas Cozy\n- Tempat Tinggal / Camp Reguler\n- 3 Kelas/Hari\n- Leader Camp\n- Merchandise\n- Ujian\n- PRE-TEST HSK 4\n- Konsultasi Beasiswa/Kerja\n- Kelas Mendengar (Tingli 听力)\n- Kelas Entrepreneur & Psychotraining",
            ],
            [
                'name'       => '3 Bulan (Basic-HSK1-HSK2)',
                'language'   => 'Mandarin',
                'type'       => 'Offline',
                'duration'   => '3 Bulan',
                'price'      => 4324000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- Sertifikat\n- Modul\n- Camp Reguler\n- Kelas (Berbicara, Vocab, Menulis, Listening)\n- Senin-Jumat\n- 60-75 Menit/Pertemuan\n- Merchandise",
            ],
            [
                'name'       => '5 Bulan (Basic-HSK1-HSK2-HSK3)',
                'language'   => 'Mandarin',
                'type'       => 'Offline',
                'duration'   => '5 Bulan',
                'price'      => 6724000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- Sertifikat\n- Modul\n- Camp Reguler\n- Kelas (Berbicara, Vocab, Menulis, Listening)\n- Senin-Jumat\n- 60-75 Menit/Pertemuan\n- Merchandise",
            ],
            [
                'name'       => '7 Bulan (Basic-HSK1-HSK2-HSK3-HSK4)',
                'language'   => 'Mandarin',
                'type'       => 'Offline',
                'duration'   => '7 Bulan',
                'price'      => 10975000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- Sertifikat\n- Modul\n- Camp Reguler\n- Kelas (Berbicara, Vocab, Menulis, Listening)\n- Senin-Jumat\n- 60-75 Menit/Pertemuan\n- Merchandise",
            ],
            // === ONLINE ===
            [
                'name'       => 'Program 1 Bulan Mandarin Online',
                'language'   => 'Mandarin',
                'type'       => 'Online',
                'duration'   => '1 Bulan',
                'price'      => 500000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- E-Modul Lengkap\n- E-Sertifikat Resmi\n- 20 Sesi Belajar (60 Menit/Sesi)\n- 5-7 Member/Kelas\n- Tutor Berpengalaman\n- Latihan Speaking Setiap Hari\n- Beragam Tema Pembelajaran\n- Belajar Fleksibel Dari Mana Saja",
            ],
        ];

        foreach ($courses as $course) {
            \App\Models\Course::firstOrCreate(
                ['name' => $course['name']],
                $course
            );
        }
    }
}
