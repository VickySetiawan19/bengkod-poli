<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelExportService
{
    /**
     * Download as XLSX via PhpSpreadsheet, with lightweight CSV fallback.
     */
    public static function download(string $filename, array $headers, array $rows): StreamedResponse
    {
        if (class_exists(Spreadsheet::class)) {
            try {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Data');

                // Set headers
                $colIndex = 1;
                foreach ($headers as $header) {
                    $sheet->setCellValueByColumnAndRow($colIndex, 1, $header);
                    $colIndex++;
                }

                $lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
                $sheet->getStyle("A1:{$lastColLetter}1")->getFont()->setBold(true);

                // Set data rows
                $rowIndex = 2;
                foreach ($rows as $row) {
                    $colIndex = 1;
                    foreach ($row as $cell) {
                        $sheet->setCellValueByColumnAndRow($colIndex, $rowIndex, $cell);
                        $colIndex++;
                    }
                    $rowIndex++;
                }

                for ($col = 1; $col <= count($headers); $col++) {
                    $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
                }

                $xlsxFilename = str_ends_with($filename, '.xlsx') ? $filename : $filename . '.xlsx';

                return response()->streamDownload(function () use ($spreadsheet) {
                    $writer = new Xlsx($spreadsheet);
                    $writer->save('php://output');
                }, $xlsxFilename, [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'Cache-Control' => 'max-age=0',
                ]);
            } catch (\Throwable) {
                // Fallback to native CSV stream
            }
        }

        $csvFilename = str_ends_with($filename, '.csv') ? $filename : $filename . '.csv';
        return response()->streamDownload(function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);
            foreach ($rows as $row) {
                fputcsv($handle, (array) $row);
            }
            fclose($handle);
        }, $csvFilename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
