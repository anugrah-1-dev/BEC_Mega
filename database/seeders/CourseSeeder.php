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
                'name'        => 'SHORT LEARNING 1',
                'price'       => 495000,
                'description' => "Tipe: Offline | Kategori: Short Learning | Durasi: 7 Hari\n\nFasilitas:\n- Biaya Admin: Rp 125.000\n- Free Voucher Brilliant Health Care\n- Tempat Tinggal / Camp\n- Modul, Competence & Gelang\n- Sertifikat\n- Bonus Materi Psychotraining & Enterpreneurship\n- Pendidikan Budi Pekerti Luhur Etika Sopan Santun Budaya Jawa\n- Program 6 Kelas/Hari X 75 Menit\n- T-Shirt",
            ],
            [
                'name'        => 'SHORT LEARNING 2',
                'price'       => 850000,
                'description' => "Tipe: Offline | Kategori: Short Learning | Durasi: 14 Hari\n\nFasilitas:\n- Biaya Admin: Rp 125.000\n- Free Voucher Brilliant Health Care\n- Tempat Tinggal / Camp\n- Modul, Competence & Gelang\n- Sertifikat\n- Bonus Materi Psychotraining & Enterpreneurship\n- Pendidikan Budi Pekerti Luhur Etika Sopan Santun Budaya Jawa\n- Program 6 Kelas/Hari X 75 Menit\n- T-Shirt",
            ],
            [
                'name'        => 'REGULER 1',
                'price'       => 1399000,
                'description' => "Tipe: Offline | Kategori: Reguler | Durasi: 30 Hari\n\nFasilitas:\n- Biaya Admin: Gratis\n- Free Voucher Brilliant Health Care\n- Tempat Tinggal / Camp\n- Modul, Competence & Gelang\n- Sertifikat\n- Bonus Materi Psychotraining & Enterpreneurship\n- Pendidikan Budi Pekerti Luhur Etika Sopan Santun Budaya Jawa\n- Program 6 Kelas/Hari X 75 Menit\n- T-Shirt",
            ],
            [
                'name'        => 'REGULER 2',
                'price'       => 2599000,
                'description' => "Tipe: Offline | Kategori: Reguler | Durasi: 60 Hari\n\nFasilitas:\n- Biaya Admin: Gratis\n- Free Voucher Brilliant Health Care\n- Tempat Tinggal / Camp\n- Modul, Competence & Gelang\n- Sertifikat\n- Bonus Materi Psychotraining & Enterpreneurship\n- Pendidikan Budi Pekerti Luhur Etika Sopan Santun Budaya Jawa\n- Program 6 Kelas/Hari X 75 Menit\n- T-Shirt",
            ],
            [
                'name'        => 'MASTER',
                'price'       => 3867000,
                'description' => "Tipe: Offline | Kategori: Master | Durasi: 90 Hari\n\nFasilitas:\n- Biaya Admin: Gratis\n- BONUS TOEFL Preparation\n- Free Voucher Brilliant Health Care\n- Tempat Tinggal / Camp\n- Modul, Competence & Gelang\n- Sertifikat\n- Bonus Materi Psychotraining & Enterpreneurship\n- Pendidikan Budi Pekerti Luhur Etika Sopan Santun Budaya Jawa\n- Program 6 Kelas/Hari X 75 Menit\n- T-Shirt",
            ],
            // === ONLINE ===
            [
                'name'        => 'SHORT LEARNING ONLINE',
                'price'       => 400000,
                'description' => "Tipe: Online | Kategori: Short Learning | Durasi: 14 Hari\n\nFasilitas:\n- Biaya Admin: Gratis\n- 20x Meeting\n- 60 Menit/Pertemuan\n- 2x Ujian\n- E-Certificate",
            ],
            [
                'name'        => 'REGULER 1 ONLINE',
                'price'       => 650000,
                'description' => "Tipe: Online | Kategori: Reguler | Durasi: 30 Hari\n\nFasilitas:\n- Biaya Admin: Gratis\n- 20x Meeting\n- 60 Menit/Pertemuan\n- 2x Ujian\n- E-Certificate",
            ],
            [
                'name'        => 'REGULER 2 ONLINE',
                'price'       => 1200000,
                'description' => "Tipe: Online | Kategori: Reguler | Durasi: 60 Hari\n\nFasilitas:\n- Biaya Admin: Gratis\n- 20x Meeting\n- 60 Menit/Pertemuan\n- 2x Ujian\n- E-Certificate",
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
