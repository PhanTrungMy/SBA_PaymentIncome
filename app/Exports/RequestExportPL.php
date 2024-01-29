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
                $Categories = 5;
                for ($i=0; $i < count($this->data); $i++) {
                    if (count($this->data[$i]["categories"]) == 0) {
                        continue;
                    }
                    else{
                        $Categories = $Categories + count($this->data[$i]["categories"]);
                        $event->sheet->getDelegate()->getStyle("A{$Categories}:N{$Categories}")->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => [
                                    'argb' => 'DDDDDD',
                                ],
                            ],
                        ]);
                        $Categories += 1;
                    }
                }
                $event->sheet->getDelegate()->getStyle('A4:N4')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'ADD8E6',
                        ],
                    ],
                ]);
                $number = 4;
                for ($j = 0; $j < count($this->data); $j ++){
                    for($cate = 0; $cate < count($this->data[$j]["categories"]); $cate ++){
                        if(isset($this->data[$j]["categories"][$cate])){
                            $event->sheet->getDelegate()->getStyle("A4:A{$number}")->getFont()->setBold(True);
                            $event->sheet->getDelegate()->getStyle("A4:A{$number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                            $event->sheet->getDelegate()->getStyle("B4:B{$number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                            $event->sheet->getDelegate()->getStyle("C4:C{$number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                            $event->sheet->getDelegate()->getStyle("D4:D{$number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                            $event->sheet->getDelegate()->getStyle("E4:E{$number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                            $event->sheet->getDelegate()->getStyle("F4:F{$number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                            $event->sheet->getDelegate()->getStyle("G4:G{$number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                            $event->sheet->getDelegate()->getStyle("H4:H{$number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                            $event->sheet->getDelegate()->getStyle("I4:I{$number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                            $event->sheet->getDelegate()->getStyle("J4:J{$number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                            $event->sheet->getDelegate()->getStyle("K4:K{$number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                            $event->sheet->getDelegate()->getStyle("L4:L{$number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                            $event->sheet->getDelegate()->getStyle("M4:M{$number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                            $event->sheet->getDelegate()->getStyle("N4:N{$number}")->getBorders()->getAllBorders()->setBorderStyle("medium");
                            $event->sheet->getDelegate()->getStyle("A4:A{$number}")->getFont()->setSize(12)->setName('MS Mincho');
                            $event->sheet->getDelegate()->getRowDimension("{$number}")->setRowHeight(20);
                        }
                        $number += 1;
                    }
                    $number += 1;                }
                

                $event->sheet->getDelegate()->getStyle("A4:N4")->getFont()->setBold(True);
                $event->sheet->getDelegate()->mergeCells("A1:N1");

                $event->sheet->getDelegate()->mergeCells("A2:N2");
                $event->sheet->getDelegate()->getStyle("A1:N1")->getFont()->setSize(20)->setName('MS Mincho');
                $event->sheet->getDelegate()->getStyle("A2:N2")->getFont()->setSize(15)->setName('MS Mincho');
                $event->sheet->getDelegate()->getStyle("A4:N4")->getFont()->setSize(12)->setName('MS Mincho');

                $event->sheet->getDelegate()->getStyle("A1:O1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle("A2:O2")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getColumnDimension("A")->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension("N")->setWidth(15);
            },
        ];
    }
}
