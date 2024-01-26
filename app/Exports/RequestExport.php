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

class RequestExport implements FromView, WithEvents
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
        return view('Custom', [
            'data' => $this->data,
            'year' => $this->year,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $cellRange = 'A4:A37';
                $event->sheet->getDelegate()->getColumnDimension("A")->setWidth(30);
                for ($i=4; $i < 38; $i++) {
                    $event->sheet->getDelegate()->getRowDimension("{$i}")->setRowHeight(20);
                }
                $event->sheet->getDelegate()->getRowDimension("2")->setRowHeight(20);

                $event->sheet->getDelegate()->getColumnDimension("B")->setWidth(15);

                $event->sheet->getDelegate()->getStyle($cellRange)->getBorders()->getAllBorders()->setBorderStyle("medium");

                $event->sheet->getDelegate()->getStyle("B4:B37")->getBorders()->getAllBorders()->setBorderStyle("medium");

                $event->sheet->getDelegate()->getStyle("C4:C37")->getBorders()->getAllBorders()->setBorderStyle("medium");

                $event->sheet->getDelegate()->getStyle("D4:D37")->getBorders()->getAllBorders()->setBorderStyle("medium");

                $event->sheet->getDelegate()->getStyle("E4:E37")->getBorders()->getAllBorders()->setBorderStyle("medium");

                $event->sheet->getDelegate()->getStyle("F4:F37")->getBorders()->getAllBorders()->setBorderStyle("medium");

                $event->sheet->getDelegate()->getStyle("G4:G37")->getBorders()->getAllBorders()->setBorderStyle("medium");

                $event->sheet->getDelegate()->getStyle("H4:H37")->getBorders()->getAllBorders()->setBorderStyle("medium");

                $event->sheet->getDelegate()->getStyle("I4:I37")->getBorders()->getAllBorders()->setBorderStyle("medium");

                $event->sheet->getDelegate()->getStyle("J4:J37")->getBorders()->getAllBorders()->setBorderStyle("medium");

                $event->sheet->getDelegate()->getStyle("K4:K37")->getBorders()->getAllBorders()->setBorderStyle("medium");

                $event->sheet->getDelegate()->getStyle("L4:L37")->getBorders()->getAllBorders()->setBorderStyle("medium");

                $event->sheet->getDelegate()->getStyle("M4:M37")->getBorders()->getAllBorders()->setBorderStyle("medium");

                $event->sheet->getDelegate()->getStyle("N4:N37")->getBorders()->getAllBorders()->setBorderStyle("medium");

                $event->sheet->getDelegate()->getStyle("O4:O37")->getBorders()->getAllBorders()->setBorderStyle("medium");

                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(True);

                $event->sheet->getDelegate()->getStyle("B4")->getFont()->setBold(True);

                $event->sheet->getDelegate()->getStyle("O4")->getFont()->setBold(True);

                $event->sheet->getDelegate()->getStyle("A1:O1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->mergeCells("A1:N1");

                $event->sheet->getDelegate()->mergeCells("A2:N2");

                $event->sheet->getDelegate()->getStyle("A2:N2")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A4:O4')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'ADD8E6',
                        ],
                    ],
                ]);

                $event->sheet->getDelegate()->getStyle('A8:O8')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A10:O10')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A11:O11')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A13:O13')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A14:O14')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A18:O18')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);

                $event->sheet->getDelegate()->getStyle('A19:O19')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A20:O20')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A25:O25')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A26:O26')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A27:O27')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A29:O29')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A31:O31')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A32:O32')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A33:O33')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A34:O34')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A35:O35')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A36:O36')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A37:O37')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'DDDDDD',
                        ],
                    ],
                ]);
            },
        ];
    }
}
