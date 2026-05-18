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
        \App\Models\User::firstOrCreate(['email' => 'adminBec@gmail.com'], [
            'name' => 'Admin BEC',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
            'Status' => 'Aktif',
        ]);

        \App\Models\User::firstOrCreate(['email' => 'siswa@mail.com'], [
            'name' => 'Siswa Test',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'student',
            'Status' => 'Aktif',
        ]);

        // Courses
        \App\Models\Course::firstOrCreate(['name' => 'Basic English Course'], [
            'price' => 750000,
            'description' => 'Kursus dasar untuk pemula yang ingin belajar bahasa Inggris dari nol.'
        ]);

        \App\Models\Course::firstOrCreate(['name' => 'Intermediate English'], [
            'price' => 950000,
            'description' => 'Kelas lanjutan untuk mengasah kemampuan percakapan dan grammar.'
        ]);

        \App\Models\Course::firstOrCreate(['name' => 'Short Program (2 Minggu)'], [
            'price' => 500000,
            'description' => 'Program singkat intensif selama 2 minggu.'
        ]);

        // Periods
        \App\Models\Period::firstOrCreate(['name' => 'Periode April 2026'], [
            'date' => '10-04-2026',
            'start_date' => '2026-04-10'
        ]);

        \App\Models\Period::firstOrCreate(['name' => 'Periode Mei 2026'], [
            'date' => '10-05-2026',
            'start_date' => '2026-05-10'
        ]);

        \App\Models\Period::firstOrCreate(['name' => 'Periode Juni 2026'], [
            'date' => '10-06-2026',
            'start_date' => '2026-06-10'
        ]);

        // Transports
        \App\Models\Transport::firstOrCreate(['name' => 'Antar Jemput'], [
            'price' => 150000,
            'status' => 'active',
        ]);

        \App\Models\Transport::firstOrCreate(['name' => 'Mandiri (Tanpa Transport)'], [
            'price' => 0,
            'status' => 'active',
        ]);
    }
}
