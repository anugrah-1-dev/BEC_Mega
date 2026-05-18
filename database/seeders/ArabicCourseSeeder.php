<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ArabicCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Program Bahasa Arab - Brilliant English Course (BEC)
     */
    public function run(): void
    {
        $courses = [
            [
                'name'        => "I'dad",
                'price'       => 165000,
                'description' => "Bahasa: Arab | Tipe: Offline | Kategori: I'dad | Durasi: 22 Hari\n\nFasilitas:\n- Pelajaran dasar bahasa Arab\n- Qowaid\n- Qira'ah\n- Muhadatsah",
            ],
            [
                'name'        => 'Mustawa Awwal',
                'price'       => 460000,
                'description' => "Bahasa: Arab | Tipe: Offline | Kategori: Mustawa 1 | Durasi: 22 Hari\n\nFasilitas:\n- Latihan percakapan\n- Penyusunan kalimat sederhana\n- Target hingga 1500 kosakata\n- Kelas dan suasana belajar yang nyaman",
            ],
            [
                'name'        => 'Mustawa Tsani',
                'price'       => 460000,
                'description' => "Bahasa: Arab | Tipe: Offline | Kategori: Mustawa 2 | Durasi: 22 Hari\n\nFasilitas:\n- Pendalaman kaidah\n- Percakapan lancar\n- Presentasi topik\n- Target hingga 2100 kosakata",
            ],
            [
                'name'        => 'Mustawa Tsalist',
                'price'       => 460000,
                'description' => "Bahasa: Arab | Tipe: Offline | Kategori: Mustawa 3 | Durasi: 22 Hari\n\nFasilitas:\n- Penekanan dalam fashohah\n- Insya' (mengarang)\n- Latihan alih bahasa\n- Target hingga 3000 lebih kosakata",
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
