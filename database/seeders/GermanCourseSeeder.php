<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GermanCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Program Bahasa Jerman - Brilliant English Course (BEC)
     */
    public function run(): void
    {
        $courses = [
            // === OFFLINE ===
            [
                'name'       => 'Program Offline Level A1 (Periode 15)',
                'language'   => 'Jerman',
                'type'       => 'Offline',
                'duration'   => '15 Hari',
                'price'      => 3500000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- Level Dasar Pemula\n- Pembelajaran dengan Modul Khusus\n- Gratis Camp Reguler\n- Sertifikat",
            ],
            [
                'name'       => 'Program Offline Level A1 (Periode 30)',
                'language'   => 'Jerman',
                'type'       => 'Offline',
                'duration'   => '15 Hari',
                'price'      => 3500000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- Level Dasar Pemula\n- Pembelajaran dengan Modul Khusus\n- Gratis Camp Reguler\n- Sertifikat",
            ],
            [
                'name'       => 'Program Offline Level A2 (Periode 15)',
                'language'   => 'Jerman',
                'type'       => 'Offline',
                'duration'   => '15 Hari',
                'price'      => 4000000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- Level Dasar Lanjutan\n- Pembelajaran dengan Modul Khusus\n- Gratis Camp Reguler\n- Sertifikat",
            ],
            [
                'name'       => 'Program Offline Level A2 (Periode 30)',
                'language'   => 'Jerman',
                'type'       => 'Offline',
                'duration'   => '15 Hari',
                'price'      => 4000000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- Level Dasar Lanjutan\n- Pembelajaran dengan Modul Khusus\n- Gratis Camp Reguler\n- Sertifikat",
            ],
            [
                'name'       => 'Program Offline Level B1 (Periode 15)',
                'language'   => 'Jerman',
                'type'       => 'Offline',
                'duration'   => '15 Hari',
                'price'      => 4500000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- Level Menengah\n- Pembelajaran dengan Modul Khusus\n- Program Presentasi Khusus (Hari Sabtu)\n- Gratis Camp Reguler",
            ],
            [
                'name'       => 'Program Offline Level B1 (Periode 30)',
                'language'   => 'Jerman',
                'type'       => 'Offline',
                'duration'   => '15 Hari',
                'price'      => 4500000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- Level Menengah\n- Pembelajaran dengan Modul Khusus\n- Program Presentasi Khusus (Hari Sabtu)\n- Gratis Camp Reguler",
            ],
            // === ONLINE ===
            [
                'name'       => 'Kelas Private Online Bahasa Jerman',
                'language'   => 'Jerman',
                'type'       => 'Online',
                'duration'   => 'Kelas Satuan',
                'price'      => 3960000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- Buku\n- Sertifikat\n- Zoom Meeting",
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
