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
                'name'       => "I'dad",
                'language'   => 'Arab',
                'type'       => 'Offline',
                'duration'   => '22 Hari',
                'price'      => 165000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- Pelajaran dasar bahasa Arab\n- Qowaid\n- Qira'ah\n- Muhadatsah",
            ],
            [
                'name'       => 'Mustawa Awwal',
                'language'   => 'Arab',
                'type'       => 'Offline',
                'duration'   => '22 Hari',
                'price'      => 460000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- Latihan percakapan\n- Penyusunan kalimat sederhana\n- Target hingga 1500 kosakata\n- Kelas dan suasana belajar yang nyaman",
            ],
            [
                'name'       => 'Mustawa Tsani',
                'language'   => 'Arab',
                'type'       => 'Offline',
                'duration'   => '22 Hari',
                'price'      => 460000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- Pendalaman kaidah\n- Percakapan lancar\n- Presentasi topik\n- Target hingga 2100 kosakata",
            ],
            [
                'name'       => 'Mustawa Tsalist',
                'language'   => 'Arab',
                'type'       => 'Offline',
                'duration'   => '22 Hari',
                'price'      => 460000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- Penekanan dalam fashohah\n- Insya' (mengarang)\n- Latihan alih bahasa\n- Target hingga 3000 lebih kosakata",
            ],
        ];

        foreach ($courses as $course) {
            \App\Models\Course::updateOrCreate(
                ['name' => $course['name']],
                $course
            );
        }
    }
}
