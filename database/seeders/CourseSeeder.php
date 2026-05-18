<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            // === OFFLINE ===
            [
                'name'       => 'SHORT LEARNING 1',
                'language'   => 'Inggris',
                'type'       => 'Offline',
                'duration'   => '7 Hari',
                'price'      => 495000,
                'admin_tax'  => 125000,
                'description' => "Fasilitas:\n- Free Voucher Brilliant Health Care\n- Tempat Tinggal / Camp\n- Modul, Competence & Gelang\n- Sertifikat\n- Bonus Materi Psychotraining & Enterpreneurship\n- Pendidikan Budi Pekerti Luhur Etika Sopan Santun Budaya Jawa\n- Program 6 Kelas/Hari X 75 Menit\n- T-Shirt",
            ],
            [
                'name'       => 'SHORT LEARNING 2',
                'language'   => 'Inggris',
                'type'       => 'Offline',
                'duration'   => '14 Hari',
                'price'      => 850000,
                'admin_tax'  => 125000,
                'description' => "Fasilitas:\n- Free Voucher Brilliant Health Care\n- Tempat Tinggal / Camp\n- Modul, Competence & Gelang\n- Sertifikat\n- Bonus Materi Psychotraining & Enterpreneurship\n- Pendidikan Budi Pekerti Luhur Etika Sopan Santun Budaya Jawa\n- Program 6 Kelas/Hari X 75 Menit\n- T-Shirt",
            ],
            [
                'name'       => 'REGULER 1',
                'language'   => 'Inggris',
                'type'       => 'Offline',
                'duration'   => '30 Hari',
                'price'      => 1399000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- Free Voucher Brilliant Health Care\n- Tempat Tinggal / Camp\n- Modul, Competence & Gelang\n- Sertifikat\n- Bonus Materi Psychotraining & Enterpreneurship\n- Pendidikan Budi Pekerti Luhur Etika Sopan Santun Budaya Jawa\n- Program 6 Kelas/Hari X 75 Menit\n- T-Shirt",
            ],
            [
                'name'       => 'REGULER 2',
                'language'   => 'Inggris',
                'type'       => 'Offline',
                'duration'   => '60 Hari',
                'price'      => 2599000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- Free Voucher Brilliant Health Care\n- Tempat Tinggal / Camp\n- Modul, Competence & Gelang\n- Sertifikat\n- Bonus Materi Psychotraining & Enterpreneurship\n- Pendidikan Budi Pekerti Luhur Etika Sopan Santun Budaya Jawa\n- Program 6 Kelas/Hari X 75 Menit\n- T-Shirt",
            ],
            [
                'name'       => 'MASTER',
                'language'   => 'Inggris',
                'type'       => 'Offline',
                'duration'   => '90 Hari',
                'price'      => 3867000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- BONUS TOEFL Preparation\n- Free Voucher Brilliant Health Care\n- Tempat Tinggal / Camp\n- Modul, Competence & Gelang\n- Sertifikat\n- Bonus Materi Psychotraining & Enterpreneurship\n- Pendidikan Budi Pekerti Luhur Etika Sopan Santun Budaya Jawa\n- Program 6 Kelas/Hari X 75 Menit\n- T-Shirt",
            ],
            // === ONLINE ===
            [
                'name'       => 'SHORT LEARNING ONLINE',
                'language'   => 'Inggris',
                'type'       => 'Online',
                'duration'   => '14 Hari',
                'price'      => 400000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- 20x Meeting\n- 60 Menit/Pertemuan\n- 2x Ujian\n- E-Certificate",
            ],
            [
                'name'       => 'REGULER 1 ONLINE',
                'language'   => 'Inggris',
                'type'       => 'Online',
                'duration'   => '30 Hari',
                'price'      => 650000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- 20x Meeting\n- 60 Menit/Pertemuan\n- 2x Ujian\n- E-Certificate",
            ],
            [
                'name'       => 'REGULER 2 ONLINE',
                'language'   => 'Inggris',
                'type'       => 'Online',
                'duration'   => '60 Hari',
                'price'      => 1200000,
                'admin_tax'  => 0,
                'description' => "Fasilitas:\n- 20x Meeting\n- 60 Menit/Pertemuan\n- 2x Ujian\n- E-Certificate",
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
