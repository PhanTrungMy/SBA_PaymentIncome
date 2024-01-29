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

                $number = 5;
                for ($i = 0; $i < count($this->data); $i++) {
                    for ($j = 0; $j < count($this->data[$i]["categories"]); $j++) {
                        if ($this->data[$i]["categories"][$j] !== null) {
                            $number += 1;
                        } else {
                            $number += 1;
                        }
                    }
                    $number += 1;
                    $Number = $number - 1;

                    $event->sheet->getDelegate()->getStyle("A{$Number}:O{$Number}")->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                    'argb' => 'DDDDDD',
                                ],
                        ],
                    ]);

                    $event->sheet->getDelegate()->getStyle("A4:A{$Number}")->getFont()->setBold(True);
                    $event->sheet->getDelegate()->getStyle("A4:A{$Number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                    $event->sheet->getDelegate()->getStyle("B4:B{$Number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                    $event->sheet->getDelegate()->getStyle("C4:C{$Number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                    $event->sheet->getDelegate()->getStyle("D4:D{$Number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                    $event->sheet->getDelegate()->getStyle("E4:E{$Number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                    $event->sheet->getDelegate()->getStyle("F4:F{$Number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                    $event->sheet->getDelegate()->getStyle("G4:G{$Number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                    $event->sheet->getDelegate()->getStyle("H4:H{$Number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                    $event->sheet->getDelegate()->getStyle("I4:I{$Number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                    $event->sheet->getDelegate()->getStyle("J4:J{$Number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                    $event->sheet->getDelegate()->getStyle("K4:K{$Number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                    $event->sheet->getDelegate()->getStyle("L4:L{$Number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                    $event->sheet->getDelegate()->getStyle("M4:M{$Number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                    $event->sheet->getDelegate()->getStyle("N4:N{$Number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                    $event->sheet->getDelegate()->getStyle("O4:O{$Number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                    $event->sheet->getDelegate()->getStyle("A4:A{$Number}")->getFont()->setSize(12)->setName('MS Mincho');
                    $event->sheet->getDelegate()->getRowDimension("{$Number}")->setRowHeight(20);
                }

                $event->sheet->getDelegate()->getStyle('A4:O4')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                                'argb' => 'ADD8E6',
                            ],
                    ],
                ]);

                $event->sheet->getDelegate()->getStyle("A4:O4")->getFont()->setBold(True);
                $event->sheet->getDelegate()->mergeCells("A1:O1");

                $event->sheet->getDelegate()->mergeCells("A2:O2");
                $event->sheet->getDelegate()->getStyle("A1:O1")->getFont()->setSize(20)->setName('MS Mincho');
                $event->sheet->getDelegate()->getStyle("A2:O2")->getFont()->setSize(15)->setName('MS Mincho');
                $event->sheet->getDelegate()->getStyle("A4:O4")->getFont()->setSize(12)->setName('MS Mincho');

                $event->sheet->getDelegate()->getStyle("A1:O1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle("A2:O2")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getColumnDimension("A")->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension("O")->setWidth(15);
            },
        ];
    }
}
