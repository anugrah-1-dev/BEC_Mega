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
                'name'        => 'Program Offline Level A1 (Periode 15)',
                'price'       => 3500000,
                'description' => "Bahasa: Jerman | Tipe: Offline | Kategori: A1 | Durasi: 15 Hari\n\nFasilitas:\n- Level Dasar Pemula\n- Pembelajaran dengan Modul Khusus\n- Gratis Camp Reguler\n- Sertifikat",
            ],
            [
                'name'        => 'Program Offline Level A1 (Periode 30)',
                'price'       => 3500000,
                'description' => "Bahasa: Jerman | Tipe: Offline | Kategori: A1 | Durasi: 30 Hari\n\nFasilitas:\n- Level Dasar Pemula\n- Pembelajaran dengan Modul Khusus\n- Gratis Camp Reguler\n- Sertifikat",
            ],
            [
                'name'        => 'Program Offline Level A2 (Periode 15)',
                'price'       => 4000000,
                'description' => "Bahasa: Jerman | Tipe: Offline | Kategori: A2 | Durasi: 15 Hari\n\nFasilitas:\n- Level Dasar Lanjutan\n- Pembelajaran dengan Modul Khusus\n- Gratis Camp Reguler\n- Sertifikat",
            ],
            [
                'name'        => 'Program Offline Level A2 (Periode 30)',
                'price'       => 4000000,
                'description' => "Bahasa: Jerman | Tipe: Offline | Kategori: A2 | Durasi: 30 Hari\n\nFasilitas:\n- Level Dasar Lanjutan\n- Pembelajaran dengan Modul Khusus\n- Gratis Camp Reguler\n- Sertifikat",
            ],
            [
                'name'        => 'Program Offline Level B1 (Periode 15)',
                'price'       => 4500000,
                'description' => "Bahasa: Jerman | Tipe: Offline | Kategori: B1 | Durasi: 15 Hari\n\nFasilitas:\n- Level Menengah\n- Pembelajaran dengan Modul Khusus\n- Program Presentasi Khusus (Hari Sabtu)\n- Gratis Camp Reguler",
            ],
            [
                'name'        => 'Program Offline Level B1 (Periode 30)',
                'price'       => 4500000,
                'description' => "Bahasa: Jerman | Tipe: Offline | Kategori: B1 | Durasi: 30 Hari\n\nFasilitas:\n- Level Menengah\n- Pembelajaran dengan Modul Khusus\n- Program Presentasi Khusus (Hari Sabtu)\n- Gratis Camp Reguler",
            ],
            // === ONLINE ===
            [
                'name'        => 'Kelas Private Online Bahasa Jerman',
                'price'       => 3960000,
                'description' => "Bahasa: Jerman | Tipe: Online | Kategori: Private | Durasi: Kelas Satuan Online\n\nFasilitas:\n- Buku\n- Sertifikat\n- Zoom Meeting",
            ],
        ];

        foreach ($courses as $course) {
            \App\Models\Course::firstOrCreate(
                ['name' => $course['name']],
                ['price' => $course['price'], 'description' => $course['description']]
            );
        }
    }
}
