<?php

namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LaporanExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths, WithEvents
{
    protected $period;
    protected $reportData;
    protected $total_income;

    public function __construct($period, $reportData, $total_income)
    {
        $this->period      = $period;
        $this->reportData  = $reportData;
        $this->total_income = $total_income;
    }

    public function collection()
    {
        $no = 1;
        return $this->reportData->map(function ($data) use (&$no) {
            $total = ($data->course->price ?? 0)
                   + ($data->transport->price ?? 0)
                   + ($data->course->admin_tax ?? 0);
            return [
                'No'              => $no++,
                'Tanggal Lunas'   => $data->updated_at->format('d/m/Y H:i'),
                'Nama Siswa'      => $data->user->name ?? '-',
                'Email'           => $data->user->email ?? '-',
                'Program'         => $data->course->name ?? '-',
                'Transport'       => $data->transport->name ?? 'Tidak ada',
                'Metode Bayar'    => strtoupper($data->payment_method ?? 'Transfer'),
                'Total Bayar'     => $total,
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Tanggal Lunas', 'Nama Siswa', 'Email', 'Program', 'Transport', 'Metode Bayar', 'Total Bayar (Rp)'];
    }

    public function title(): string
    {
        return $this->period === 'daily' ? 'Laporan Harian' : 'Laporan Bulanan';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 22,
            'C' => 25,
            'D' => 30,
            'E' => 28,
            'F' => 20,
            'G' => 16,
            'H' => 22,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '059669']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet     = $event->sheet->getDelegate();
                $lastRow   = $this->reportData->count() + 1;

                // Border semua data
                $sheet->getStyle('A1:H' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['rgb' => 'D1D5DB'],
                        ],
                    ],
                ]);

                // Format kolom Total Bayar sebagai currency
                $sheet->getStyle('H2:H' . $lastRow)->getNumberFormat()
                    ->setFormatCode('#,##0');

                // Baris total
                $totalRow = $lastRow + 2;
                $sheet->setCellValue('G' . $totalRow, 'TOTAL PEMASUKAN');
                $sheet->setCellValue('H' . $totalRow, $this->total_income);
                $sheet->getStyle('G' . $totalRow . ':H' . $totalRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '059669']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                ]);
                $sheet->getStyle('H' . $totalRow)->getNumberFormat()
                    ->setFormatCode('#,##0');

                // Zebra stripe
                for ($row = 2; $row <= $lastRow; $row++) {
                    if ($row % 2 === 0) {
                        $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0FDF4']],
                        ]);
                    }
                }

                // Freeze header
                $sheet->freezePane('A2');
            },
        ];
    }
}
