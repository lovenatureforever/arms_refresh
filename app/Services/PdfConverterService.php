<?php

namespace App\Services;

use Illuminate\Support\Facades\Process;

class PdfConverterService
{
    public static function convertToPdf($docxPath, $suffix = '')
    {
        $tempDir = dirname($docxPath);
        $originalPdfPath = str_replace('.docx', '.pdf', $docxPath);

        $result = Process::timeout(120)->run([
            'libreoffice',
            '--headless',
            '--convert-to',
            'pdf',
            '--outdir',
            $tempDir,
            '-env:UserInstallation=file:///tmp/libreoffice_profile',
            $docxPath
        ]);

        if (!$result->successful()) {
            throw new \Exception('Failed to convert document to PDF: ' . $result->errorOutput());
        }

        if ($suffix) {
            $newPdfPath = str_replace('.pdf', $suffix . '.pdf', $originalPdfPath);
            rename($originalPdfPath, $newPdfPath);
            return $newPdfPath;
        }

        return $originalPdfPath;
    }
}