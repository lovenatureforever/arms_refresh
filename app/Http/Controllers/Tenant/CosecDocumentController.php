<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\CosecOrder;
use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyDirector;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use App\Services\PdfConverterService;
use ZipArchive;

class CosecDocumentController extends Controller
{
    public function printWord($orderId)
    {
        $order = CosecOrder::with('template')->findOrFail($orderId);
        $company = Company::findOrFail($order->tenant_company_id);

        if (!$order->template || !$order->template->template_file) {
            return response()->json(['error' => 'No template file found'], 404);
        }

        $templatePath = storage_path('app/public/' . $order->template->template_file);

        if (!file_exists($templatePath)) {
            return response()->json(['error' => 'Template file not found'], 404);
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        // Replace basic placeholders
        $templateProcessor->setValue('company_name', $company->name ?? '');
        $templateProcessor->setValue('company_no', $company->registration_no ?? '');
        $templateProcessor->setValue('company_old_no', $company->registration_no_old ?? '');

        // Get directors based on signature type
        if ($order->template->signature_type === 'all_directors') {
            $directors = CompanyDirector::where('company_id', $company->id)
                ->where('is_active', true)
                ->get();
        } else {
            $directors = CompanyDirector::where('company_id', $company->id)
                ->where('is_default_signer_cosec', true)
                ->where('is_active', true)
                ->get();
        }

        // Replace director placeholders
        if ($directors->count() > 0) {
            $directorNames = $directors->pluck('name')->implode(', ');
            $templateProcessor->setValue('director_name', $directorNames);
            $templateProcessor->setValue('director_names', $directorNames);

            // Handle signatures
            foreach ($directors as $index => $director) {
                $signaturePath = '';
                if ($director->defaultSignature && $director->defaultSignature->signature_path) {
                    $signaturePath = storage_path('app/public/' . $director->defaultSignature->signature_path);
                    if (file_exists($signaturePath)) {
                        $templateProcessor->setImageValue("director_signature_" . ($index + 1), $signaturePath);
                    }
                }
            }
        }

        // Replace order-specific data
        $orderData = json_decode($order->data, true) ?? [];
        foreach ($orderData as $key => $value) {
            if (is_string($value)) {
                $templateProcessor->setValue($key, $value);
            }
        }

        // Generate filename
        $filename = 'COSEC_' . $order->form_name . '_' . $company->registration_no . '_' . date('Y-m-d') . '.docx';

        // Save processed document
        $outputPath = storage_path('app/temp/' . $filename);

        // Create temp directory if it doesn't exist
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $templateProcessor->saveAs($outputPath);

        return response()->download($outputPath, $filename)->deleteFileAfterSend();
    }

    private function generateWordDocument($order, $company, $templatePath)
    {
        $templateProcessor = new TemplateProcessor($templatePath);

        // Replace basic placeholders
        $templateProcessor->setValue('company_name', $company->name ?? '');
        $templateProcessor->setValue('company_no', $company->registration_no ?? '');
        $templateProcessor->setValue('company_old_no', $company->registration_no_old ?? '');

        // Get directors based on signature type
        if ($order->template->signature_type === 'all_directors') {
            $directors = CompanyDirector::where('company_id', $company->id)
                ->where('is_active', true)
                ->get();
        } else {
            $directors = CompanyDirector::where('company_id', $company->id)
                ->where('is_default_signer_cosec', true)
                ->where('is_active', true)
                ->get();
        }

        // Replace director placeholders
        if ($directors->count() > 0) {
            $directorNames = $directors->pluck('name')->implode(', ');
            $templateProcessor->setValue('director_name', $directorNames);
            $templateProcessor->setValue('director_names', $directorNames);

            // Handle signatures
            foreach ($directors as $index => $director) {
                $signaturePath = '';
                if ($director->defaultSignature && $director->defaultSignature->signature_path) {
                    $signaturePath = storage_path('app/public/' . $director->defaultSignature->signature_path);
                    if (file_exists($signaturePath)) {
                        $templateProcessor->setImageValue("director_signature_" . ($index + 1), $signaturePath);
                    }
                }
            }
        }

        // Replace order-specific data
        $orderData = json_decode($order->data, true) ?? [];
        foreach ($orderData as $key => $value) {
            if (is_string($value)) {
                $templateProcessor->setValue($key, $value);
            }
        }

        // Generate filename and save
        $filename = 'COSEC_' . $order->form_name . '_' . $company->registration_no . '_' . date('Y-m-d') . '.docx';
        $outputPath = storage_path('app/temp/' . $filename);

        // Create temp directory if it doesn't exist
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $templateProcessor->saveAs($outputPath);

        return $outputPath;
    }

    private function convertToPdf($docxPath)
    {
        return PdfConverterService::convertToPdf($docxPath);
    }

    private function createZipFile($docxPath, $pdfPath, $order, $company)
    {
        $zipFilename = 'COSEC_' . $order->form_name . '_' . $company->registration_no . '_' . date('Y-m-d') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFilename);

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            $zip->addFile($docxPath, basename($docxPath));
            $zip->addFile($pdfPath, basename($pdfPath));
            $zip->close();
        } else {
            throw new \Exception('Failed to create ZIP file');
        }

        // Clean up individual files
        if (file_exists($docxPath)) {
            unlink($docxPath);
        }
        if (file_exists($pdfPath)) {
            unlink($pdfPath);
        }

        return $zipPath;
    }

    public function printPdf($orderId)
    {
        $order = CosecOrder::with('template')->findOrFail($orderId);
        $company = Company::findOrFail($order->tenant_company_id);

        if (!$order->template || !$order->template->template_file) {
            return response()->json(['error' => 'No template file found'], 404);
        }

        $templatePath = storage_path('app/public/' . $order->template->template_file);

        if (!file_exists($templatePath)) {
            return response()->json(['error' => 'Template file not found'], 404);
        }

        $docxPath = $this->generateWordDocument($order, $company, $templatePath);
        $pdfPath = $this->convertToPdf($docxPath);

        // Clean up DOCX file
        if (file_exists($docxPath)) {
            unlink($docxPath);
        }

        return response()->download($pdfPath, basename($pdfPath))->deleteFileAfterSend();
    }
}
