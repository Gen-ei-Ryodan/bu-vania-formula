<?php

namespace App\Services;

use App\Models\Production;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProductionExcelExportService
{
    public function export(Production $production): Xlsx
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Program Formula');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $sheet->setCellValue('A2', $production->production_type === 'treatment' ? 'FORMULIR PENGOBATAN' : 'LAPORAN PRODUKSI');
        $sheet->mergeCells('A2:F2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);

        $row = 4;
        $sheet->setCellValue("A{$row}", 'Nama Produksi:');
        $sheet->setCellValue("B{$row}", $production->name);
        $sheet->setCellValue("D{$row}", 'Resep:');
        $sheet->setCellValue("E{$row}", $production->concept?->name ?? '-');
        $row++;

        $sheet->setCellValue("A{$row}", 'Lokasi:');
        $sheet->setCellValue("B{$row}", $production->location ?? '-');
        $sheet->setCellValue("D{$row}", 'Kandang:');
        $sheet->setCellValue("E{$row}", $production->cage ?? '-');
        $row++;

        $sheet->setCellValue("A{$row}", 'Target Berat:');
        $sheet->setCellValue("B{$row}", $production->target_weight_kg . ' kg');
        $sheet->setCellValue("D{$row}", 'Tanggal Campur:');
        $sheet->setCellValue("E{$row}", $production->mix_date?->format('d-m-Y') ?? '-');
        $row++;

        if ($production->production_type === 'treatment') {
            $sheet->setCellValue("A{$row}", 'Pengobatan Hari Ke:');
            $sheet->setCellValue("B{$row}", $production->treatment_day ?? '-');
            $sheet->setCellValue("D{$row}", 'Waktu:');
            $sheet->setCellValue("E{$row}", $production->treatment_time ?? '-');
            $row++;
        }

        $sheet->setCellValue("A{$row}", 'Mulai Pakai Konsep:');
        $sheet->setCellValue("B{$row}", $production->start_date?->format('d-m-Y') ?? '-');
        $sheet->setCellValue("D{$row}", 'Durasi:');
        $sheet->setCellValue("E{$row}", $production->is_forever ? 'Selamanya' : ($production->duration_days ? $production->duration_days . ' hari' : '-'));
        $row++;

        if ($production->notes) {
            $row++;
            $sheet->setCellValue("A{$row}", 'Catatan:');
            $sheet->setCellValue("B{$row}", $production->notes);
            $row++;
        }

        $row += 2;
        $headerRow = $row;

        $sheet->setCellValue("A{$row}", 'No');
        $sheet->setCellValue("B{$row}", 'Item');
        $sheet->setCellValue("C{$row}", 'Berat (kg)');
        $sheet->setCellValue("D{$row}", 'Sumber');
        $sheet->getStyle("A{$row}:D{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:D{$row}")->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $row++;

        foreach ($production->items as $i => $item) {
            $sheet->setCellValue("A{$row}", $i + 1);
            $sheet->setCellValue("B{$row}", $item->item?->name ?? '-');
            $sheet->setCellValue("C{$row}", (float) $item->weight_kg);
            $sheet->setCellValue("D{$row}", $item->source);
            $sheet->getStyle("A{$row}:D{$row}")->getBorders()->getAllBorders()
                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $row++;
        }

        // Groups section
        if ($production->groups->isNotEmpty()) {
            $row++;
            $sheet->setCellValue("A{$row}", 'GOLONGAN');
            $sheet->getStyle("A{$row}")->getFont()->setBold(true);
            $row++;

            foreach ($production->groups as $group) {
                $sheet->setCellValue("A{$row}", $group->name);
                $sheet->getStyle("A{$row}")->getFont()->setBold(true);
                $row++;

                $sheet->setCellValue("A{$row}", 'No');
                $sheet->setCellValue("B{$row}", 'Item');
                $sheet->setCellValue("C{$row}", 'Berat');
                $sheet->setCellValue("D{$row}", 'Dosis');
                $sheet->getStyle("A{$row}:D{$row}")->getFont()->setBold(true);
                $sheet->getStyle("A{$row}:D{$row}")->getBorders()->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $row++;

                $no = 1;
            foreach ($group->items as $gi) {
                    $dw = $gi->weight_input_value && $gi->inputUnit
                        ? formatWeight($gi->weight_input_value) . ' ' . $gi->inputUnit->name
                        : formatWeight($gi->weight_kg) . ' kg';
                    $sheet->setCellValue("A{$row}", $no++);
                    $sheet->setCellValue("B{$row}", $gi->item?->name ?? '-');
                    $sheet->setCellValue("C{$row}", $dw);
                    $sheet->setCellValue("D{$row}", $gi->is_dosis ? 'Dosis' : '-');
                    $sheet->getStyle("A{$row}:D{$row}")->getBorders()->getAllBorders()
                        ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $row++;
                }
            }
        }

        // Tabs section
        if ($production->tabs->isNotEmpty()) {
            $row++;
            $sheet->setCellValue("A{$row}", 'TAB (SPLIT BATCH)');
            $sheet->getStyle("A{$row}")->getFont()->setBold(true);
            $row++;

            foreach ($production->tabs as $tab) {
                $sheet->setCellValue("A{$row}", $tab->name . ' (Ambil: ' . formatWeight($tab->input_weight_kg) . ' kg, Sisa: ' . formatWeight($tab->remaining_weight_kg) . ' kg)');
                $sheet->getStyle("A{$row}")->getFont()->setBold(true);
                $row++;

                $sheet->setCellValue("A{$row}", 'No');
                $sheet->setCellValue("B{$row}", 'Item');
                $sheet->setCellValue("C{$row}", 'Berat');
                $sheet->setCellValue("D{$row}", 'Dosis');
                $sheet->getStyle("A{$row}:D{$row}")->getFont()->setBold(true);
                $sheet->getStyle("A{$row}:D{$row}")->getBorders()->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $row++;

            $no = 1;
            foreach ($tab->items as $ti) {
                    $dw = $ti->weight_input_value && $ti->inputUnit
                        ? formatWeight($ti->weight_input_value) . ' ' . $ti->inputUnit->name
                        : formatWeight($ti->weight_kg) . ' kg';
                    $sheet->setCellValue("A{$row}", $no++);
                    $sheet->setCellValue("B{$row}", $ti->item?->name ?? '-');
                    $sheet->setCellValue("C{$row}", $dw);
                    $sheet->setCellValue("D{$row}", $ti->is_dosis ? 'Dosis' : '-');
                    $sheet->getStyle("A{$row}:D{$row}")->getBorders()->getAllBorders()
                        ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $row++;
                }
            }
        }

        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(18);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);

        $writer = new Xlsx($spreadsheet);
        return $writer;
    }
}
