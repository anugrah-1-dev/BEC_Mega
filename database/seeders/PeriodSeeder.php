<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periods = [
            ['name' => 'Periode 04 Mei 2026',          'date' => '04-05-2026', 'start_date' => '2026-05-04'],
            ['name' => 'Periode 10 Mei 2026',          'date' => '10-05-2026', 'start_date' => '2026-05-10'],
            ['name' => 'Periode 11 Mei 2026',          'date' => '11-05-2026', 'start_date' => '2026-05-11'],
            ['name' => 'Periode 18 Mei 2026',          'date' => '18-05-2026', 'start_date' => '2026-05-18'],
            ['name' => 'Periode 25 Mei 2026',          'date' => '25-05-2026', 'start_date' => '2026-05-25'],
            ['name' => 'Periode 01 Juni 2026',         'date' => '01-06-2026', 'start_date' => '2026-06-01'],
            ['name' => 'Periode 08 Juni 2026',         'date' => '08-06-2026', 'start_date' => '2026-06-08'],
            ['name' => 'Periode 10 Juni 2026',         'date' => '10-06-2026', 'start_date' => '2026-06-10'],
            ['name' => 'Periode 15 Juni 2026',         'date' => '15-06-2026', 'start_date' => '2026-06-15'],
            ['name' => 'Periode 22 Juni 2026',         'date' => '22-06-2026', 'start_date' => '2026-06-22'],
            ['name' => 'Periode 25 Juni 2026',         'date' => '25-06-2026', 'start_date' => '2026-06-25'],
            ['name' => 'Periode 29 Juni 2026',         'date' => '29-06-2026', 'start_date' => '2026-06-29'],
            ['name' => 'Periode 01 Juli 2026',         'date' => '01-07-2026', 'start_date' => '2026-07-01'],
            ['name' => 'Periode 06 Juli 2026',         'date' => '06-07-2026', 'start_date' => '2026-07-06'],
            ['name' => 'Periode 10 Juli 2026',         'date' => '10-07-2026', 'start_date' => '2026-07-10'],
            ['name' => 'Periode 13 Juli 2026',         'date' => '13-07-2026', 'start_date' => '2026-07-13'],
            ['name' => 'Periode 20 Juli 2026',         'date' => '20-07-2026', 'start_date' => '2026-07-20'],
            ['name' => 'Periode 25 Juli 2026',         'date' => '25-07-2026', 'start_date' => '2026-07-25'],
            ['name' => 'Periode 27 Juli 2026',         'date' => '27-07-2026', 'start_date' => '2026-07-27'],
            ['name' => 'Periode 31 Juli 2026',         'date' => '31-07-2026', 'start_date' => '2026-07-31'],
            ['name' => 'Periode 03 Agustus 2026',      'date' => '03-08-2026', 'start_date' => '2026-08-03'],
            ['name' => 'Periode 10 Agustus 2026',      'date' => '10-08-2026', 'start_date' => '2026-08-10'],
            ['name' => 'Periode 17 Agustus 2026',      'date' => '17-08-2026', 'start_date' => '2026-08-17'],
            ['name' => 'Periode 18 Agustus 2026',      'date' => '18-08-2026', 'start_date' => '2026-08-18'],
            ['name' => 'Periode 24 Agustus 2026',      'date' => '24-08-2026', 'start_date' => '2026-08-24'],
            ['name' => 'Periode 25 Agustus 2026',      'date' => '25-08-2026', 'start_date' => '2026-08-25'],
            ['name' => 'Periode 31 Agustus 2026',      'date' => '31-08-2026', 'start_date' => '2026-08-31'],
            ['name' => 'Periode 07 September 2026',    'date' => '07-09-2026', 'start_date' => '2026-09-07'],
            ['name' => 'Periode 10 September 2026',    'date' => '10-09-2026', 'start_date' => '2026-09-10'],
            ['name' => 'Periode 14 September 2026',    'date' => '14-09-2026', 'start_date' => '2026-09-14'],
            ['name' => 'Periode 21 September 2026',    'date' => '21-09-2026', 'start_date' => '2026-09-21'],
            ['name' => 'Periode 25 September 2026',    'date' => '25-09-2026', 'start_date' => '2026-09-25'],
            ['name' => 'Periode 28 September 2026',    'date' => '28-09-2026', 'start_date' => '2026-09-28'],
            ['name' => 'Periode 05 Oktober 2026',      'date' => '05-10-2026', 'start_date' => '2026-10-05'],
            ['name' => 'Periode 10 Oktober 2026',      'date' => '10-10-2026', 'start_date' => '2026-10-10'],
            ['name' => 'Periode 12 Oktober 2026',      'date' => '12-10-2026', 'start_date' => '2026-10-12'],
            ['name' => 'Periode 19 Oktober 2026',      'date' => '19-10-2026', 'start_date' => '2026-10-19'],
            ['name' => 'Periode 25 Oktober 2026',      'date' => '25-10-2026', 'start_date' => '2026-10-25'],
            ['name' => 'Periode 26 Oktober 2026',      'date' => '26-10-2026', 'start_date' => '2026-10-26'],
            ['name' => 'Periode 02 November 2026',     'date' => '02-11-2026', 'start_date' => '2026-11-02'],
            ['name' => 'Periode 10 November 2026',     'date' => '10-11-2026', 'start_date' => '2026-11-10'],
            ['name' => 'Periode 16 November 2026',     'date' => '16-11-2026', 'start_date' => '2026-11-16'],
            ['name' => 'Periode 23 November 2026',     'date' => '23-11-2026', 'start_date' => '2026-11-23'],
            ['name' => 'Periode 25 November 2026',     'date' => '25-11-2026', 'start_date' => '2026-11-25'],
            ['name' => 'Periode 30 November 2026',     'date' => '30-11-2026', 'start_date' => '2026-11-30'],
            ['name' => 'Periode 07 Desember 2026',     'date' => '07-12-2026', 'start_date' => '2026-12-07'],
            ['name' => 'Periode 10 Desember 2026',     'date' => '10-12-2026', 'start_date' => '2026-12-10'],
            ['name' => 'Periode 14 Desember 2026',     'date' => '14-12-2026', 'start_date' => '2026-12-14'],
            ['name' => 'Periode 18 Desember 2026',     'date' => '18-12-2026', 'start_date' => '2026-12-18'],
            ['name' => 'Periode 21 Desember 2026',     'date' => '21-12-2026', 'start_date' => '2026-12-21'],
            ['name' => 'Periode 25 Desember 2026',     'date' => '25-12-2026', 'start_date' => '2026-12-25'],
            ['name' => 'Periode 28 Desember 2026',     'date' => '28-12-2026', 'start_date' => '2026-12-28'],
        ];

        foreach ($periods as $period) {
            \App\Models\Period::firstOrCreate(
                ['start_date' => $period['start_date']],
                ['name' => $period['name'], 'date' => $period['date']]
            );
        }
    }
}
