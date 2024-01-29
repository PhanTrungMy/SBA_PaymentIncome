<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class RequestExportPL implements FromView, WithEvents
{
    protected $data;
    protected $year;

    public function __construct($data, $year)
    {
        $this->data = $data;
        $this->year = $year;
    }

    public function view(): View
    {
        return view('CustomPL', [
            'data' => $this->data,
            'year' => $this->year,
        ]);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A6:N6')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);

                $event->sheet->getDelegate()->getStyle('A12:N12')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A27:N27')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A32:N32')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A42:N42')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);

                $event->sheet->getDelegate()->getStyle('A46:N46')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A47:N47')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A49:N49')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A50:N50')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A54:N54')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A4:N4')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'ADD8E6',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle("A4:A54")->getFont()->setBold(True);

                $event->sheet->getDelegate()->getStyle("A4:N4")->getFont()->setBold(True);

                $event->sheet->getDelegate()->getStyle("A4:A54")->getBorders()->getAllBorders()->setBorderStyle("medium");
                $event->sheet->getDelegate()->getStyle("B4:B54")->getBorders()->getAllBorders()->setBorderStyle("medium");
                $event->sheet->getDelegate()->getStyle("C4:C54")->getBorders()->getAllBorders()->setBorderStyle("medium");
                $event->sheet->getDelegate()->getStyle("D4:D54")->getBorders()->getAllBorders()->setBorderStyle("medium");
                $event->sheet->getDelegate()->getStyle("E4:E54")->getBorders()->getAllBorders()->setBorderStyle("medium");
                $event->sheet->getDelegate()->getStyle("F4:F54")->getBorders()->getAllBorders()->setBorderStyle("medium");
                $event->sheet->getDelegate()->getStyle("G4:G54")->getBorders()->getAllBorders()->setBorderStyle("medium");
                $event->sheet->getDelegate()->getStyle("H4:H54")->getBorders()->getAllBorders()->setBorderStyle("medium");
                $event->sheet->getDelegate()->getStyle("I4:I54")->getBorders()->getAllBorders()->setBorderStyle("medium");
                $event->sheet->getDelegate()->getStyle("J4:J54")->getBorders()->getAllBorders()->setBorderStyle("medium");
                $event->sheet->getDelegate()->getStyle("K4:K54")->getBorders()->getAllBorders()->setBorderStyle("medium");
                $event->sheet->getDelegate()->getStyle("L4:L54")->getBorders()->getAllBorders()->setBorderStyle("medium");
                $event->sheet->getDelegate()->getStyle("M4:M54")->getBorders()->getAllBorders()->setBorderStyle("medium");
                $event->sheet->getDelegate()->getStyle("N4:N54")->getBorders()->getAllBorders()->setBorderStyle("medium");
                $event->sheet->getDelegate()->mergeCells("A1:N1");

                $event->sheet->getDelegate()->mergeCells("A2:N2");
                $event->sheet->getDelegate()->getStyle("A1:N1")->getFont()->setSize(20)->setName('MS Mincho');
                $event->sheet->getDelegate()->getStyle("A2:N2")->getFont()->setSize(15)->setName('MS Mincho');
                $event->sheet->getDelegate()->getStyle("A4:A54")->getFont()->setSize(12)->setName('MS Mincho');
                $event->sheet->getDelegate()->getStyle("A4:N4")->getFont()->setSize(12)->setName('MS Mincho');

                $event->sheet->getDelegate()->getStyle("A1:O1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle("A2:O2")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getColumnDimension("A")->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension("N")->setWidth(15);

                for ($i = 4; $i < 55; $i++) {
                    $event->sheet->getDelegate()->getRowDimension("{$i}")->setRowHeight(20);
                }
            },
        ];
    }
}
