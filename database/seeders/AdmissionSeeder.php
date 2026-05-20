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
        ]);

        \App\Models\User::firstOrCreate(['email' => 'siswa@mail.com'], [
            'name' => 'Siswa Test',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'student',
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
