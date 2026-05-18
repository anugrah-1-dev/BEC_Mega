<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\TutorialStat::firstOrCreate(['id' => 1], [
            'views' => 0,
            'likes' => 0
        ]);
        
        // Initial admin comment
        \App\Models\TutorialComment::firstOrCreate(['id' => 1], [
            'user_name' => 'Admin',
            'comment_text' => 'Halo Sobat Brilliant! Silakan berikan tanggapan atau pertanyaan Anda di sini.',
            'is_admin' => true,
        ]);
    }
}
