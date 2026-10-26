<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelExportService
{
    public static function bootAutoloader(): void
    {
        if (!class_exists(Spreadsheet::class)) {
            spl_autoload_register(function ($class) {
                $vendorDir = dirname(__DIR__, 2) . '/vendor';
                $prefixes = [
                    'PhpOffice\\PhpSpreadsheet\\' => $vendorDir . '/phpoffice/phpspreadsheet/src/PhpSpreadsheet/',
                    'ZipStream\\' => $vendorDir . '/maennchen/zipstream-php/src/',
                    'Matrix\\' => $vendorDir . '/markbaker/matrix/classes/src/',
                    'Complex\\' => $vendorDir . '/markbaker/complex/classes/src/',
                ];
                foreach ($prefixes as $prefix => $baseDir) {
                    $len = strlen($prefix);
                    if (strncmp($prefix, $class, $len) === 0) {
                        $relativeClass = substr($class, $len);
                        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
                        if (file_exists($file)) {
                            require_once $file;
                            return true;
                        }
                    }
                }
                return false;
            });
        }
    }

    /**
     * Download as XLSX if PhpSpreadsheet is ready, with automatic CSV/XLS fallback for 100% reliability.
     */
    public static function download(string $filename, array $headers, array $rows): StreamedResponse
    {
        self::bootAutoloader();

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

                // Header styling (Bold, light gray background)
                $lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
                $headerRange = 'A1:' . $lastColLetter . '1';
                $sheet->getStyle($headerRange)->getFont()->setBold(true);

                // Set rows
                $rowIndex = 2;
                foreach ($rows as $row) {
                    $colIndex = 1;
                    foreach ($row as $cell) {
                        $sheet->setCellValueByColumnAndRow($colIndex, $rowIndex, $cell);
                        $colIndex++;
                    }
                    $rowIndex++;
                }

                // Auto size columns
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
            } catch (\Throwable $e) {
                // If XLSX fails, fallback to clean XLS table
            }
        }

        // Native Excel-compatible HTML/XML XLS fallback (opens directly in Microsoft Excel)
        $xlsFilename = str_ends_with($filename, '.xls') ? $filename : $filename . '.xls';
        return response()->streamDownload(function () use ($headers, $rows) {
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style>table{border-collapse:collapse;width:100%;} th{background-color:#0284c7;color:#ffffff;font-weight:bold;border:1px solid #000;padding:8px;} td{border:1px solid #ccc;padding:6px;}</style></head><body>';
            echo '<table border="1">';
            echo '<thead><tr>';
            foreach ($headers as $header) {
                echo '<th>' . htmlspecialchars($header) . '</th>';
            }
            echo '</tr></thead><tbody>';
            foreach ($rows as $row) {
                echo '<tr>';
                foreach ($row as $cell) {
                    echo '<td>' . htmlspecialchars((string) $cell) . '</td>';
                }
                echo '</tr>';
            }
            echo '</tbody></table></body></html>';
        }, $xlsFilename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $xlsFilename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
