<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Users
        \App\Models\User::create([
            'name' => 'Admin BEC',
            'email' => 'adminBec@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'name' => 'Siswa Test',
            'email' => 'siswa@mail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'student',
        ]);

        // Courses
        \App\Models\Course::create([
            'name' => 'Basic English Course',
            'price' => 750000,
            'description' => 'Kursus dasar untuk pemula yang ingin belajar bahasa Inggris dari nol.'
        ]);

        \App\Models\Course::create([
            'name' => 'Intermediate English',
            'price' => 950000,
            'description' => 'Kelas lanjutan untuk mengasah kemampuan percakapan dan grammar.'
        ]);

        // Periods
        \App\Models\Period::create([
            'name' => 'Periode April 2026',
            'date' => '10-04-2026',
            'start_date' => '2026-04-10'
        ]);

        \App\Models\Period::create([
            'name' => 'Periode Mei 2026',
            'date' => '10-05-2026',
            'start_date' => '2026-05-10'
        ]);

        // Transports
        \App\Models\Transport::create([
            'name' => 'Antar Jemput',
            'price' => 150000,
            'status' => 'active',
        ]);

        \App\Models\Transport::create([
            'name' => 'Mandiri (Tanpa Transport)',
            'price' => 0,
            'status' => 'active',
        ]);
    }
}
