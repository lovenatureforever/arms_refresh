<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\CosecOrder;
use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyDirector;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use App\Services\PdfConverterService;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $order = CosecOrder::with(['template', 'company'])->findOrFail($orderId);

        // Check if order is approved and has document content
        if ((int)$order->status !== CosecOrder::STATUS_APPROVED) {
            return response()->json(['error' => 'Order must be approved before generating PDF'], 400);
        }

        if (empty($order->document_content)) {
            return response()->json(['error' => 'No document content found. Please approve the order first.'], 404);
        }

        // Build HTML document with proper styling for PDF
        $html = $this->buildPdfHtml($order);

        // Generate PDF using DomPDF
        $pdf = Pdf::loadHTML($html)
            ->setOption('dpi', 96)
            ->setOption('defaultFont', 'sans-serif')
            ->setPaper('a4', 'portrait');

        // Generate filename
        $filename = 'COSEC_' . $order->form_name . '_' . ($order->company->registration_no ?? $order->company_no) . '_' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Build HTML document with styling for PDF generation.
     */
    protected function buildPdfHtml(CosecOrder $order): string
    {
        $content = $order->document_content;

        // Convert tenancy asset URLs to absolute file paths for DomPDF
        $content = $this->convertTenancyAssetPaths($content);

        // Remove unfilled placeholders like [Secretary Name], [License No], etc.
        $content = preg_replace('/\[[^\]]+\]/', '', $content);

        // Strip ALL border-related attributes from tables
        $content = preg_replace('/<table([^>]*)\s+border=["\']?\d*["\']?([^>]*)>/i', '<table$1$2>', $content);
        $content = preg_replace('/<table([^>]*)\s+cellspacing=["\']?\d*["\']?([^>]*)>/i', '<table$1$2>', $content);
        $content = preg_replace('/<table([^>]*)\s+cellpadding=["\']?\d*["\']?([^>]*)>/i', '<table$1$2>', $content);
        $content = preg_replace('/<table([^>]*)\s+rules=["\']?[^"\']*["\']?([^>]*)>/i', '<table$1$2>', $content);
        $content = preg_replace('/<table([^>]*)\s+frame=["\']?[^"\']*["\']?([^>]*)>/i', '<table$1$2>', $content);

        // Strip border-related attributes from td and th elements
        $content = preg_replace('/<(td|th)([^>]*)\s+border=["\']?\d*["\']?([^>]*)>/i', '<$1$2$3>', $content);

        // Strip ALL border-related inline styles (comprehensive approach)
        $content = preg_replace('/border(-[a-z-]+)?:\s*[^;]+;?/i', '', $content);

        // Clean up empty style attributes
        $content = preg_replace('/style=["\'][\s;]*["\']/i', '', $content);

        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #000;
            margin: 0px !important;
        }
        h1, h2, h3, h4, h5, h6 {
            margin-top: 20px;
            margin-bottom: 10px;
        }
        p {
            margin-bottom: 10px;
        }
        /* Clean table styling - no borders by default */
        table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-bottom: 15px;
            border: none !important;
        }
        th, td {
            border: none !important;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        /* Only add borders to tables with explicit border class */
        table.bordered, table.bordered th, table.bordered td {
            border: 1px solid #333 !important;
        }
        /* Hide hr elements - they create unwanted lines */
        hr {
            display: none !important;
        }
        img {
            max-width: 150px;
            max-height: 50px;
            width: auto;
            height: auto;
        }
        img.signature {
            max-width: 150px;
            max-height: 50px;
            width: 150px;
            height: 50px;
            object-fit: contain;
        }
        .signature-block {
            margin-top: 30px;
            page-break-inside: avoid;
            text-align: center;
            display: inline-block;
            width: 45%;
            vertical-align: top;
        }
        .signature-area {
            height: 60px;
            margin-bottom: 10px;
        }
        .signature-area img {
            max-height: 50px;
            max-width: 150px;
            object-fit: contain;
        }
        /* DomPDF does not support CSS grid, use inline-block for two-column layout */
        .signatures-grid {
            text-align: center;
            margin-top: 20px;
        }
        .signatures-grid .signature-block {
            display: inline-block;
            width: 45%;
            vertical-align: top;
            margin: 0 2%;
        }
        .certification-signatures {
            text-align: center;
            margin-top: 30px;
        }
        .certification-signatures .signature-block {
            display: inline-block;
            width: 45%;
            vertical-align: top;
            margin: 0 2%;
        }
        /* Signature images styling */
        img[alt="Signature"], img[alt="Secretary Signature"] {
            max-width: 150px !important;
            max-height: 50px !important;
            width: 150px;
            height: 50px;
            object-fit: contain;
            display: inline-block;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .mb-4 {
            margin-bottom: 16px;
        }
        .mt-4 {
            margin-top: 16px;
        }
        /* Utility classes */
        .underline {
            text-decoration: underline;
        }
        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    ' . $content . '
</body>
</html>';
    }

    /**
     * Convert tenancy asset URLs to absolute file paths or base64 for DomPDF.
     */
    protected function convertTenancyAssetPaths(string $content): string
    {
        // Get the current tenant
        $tenant = tenant();
        if (!$tenant) {
            return $content;
        }

        // Pattern to match /tenancy/assets/path
        $pattern = '/src=["\']\/tenancy\/assets\/([^"\']+)["\']/i';

        $content = preg_replace_callback($pattern, function ($matches) use ($tenant) {
            $relativePath = $matches[1];

            // Build absolute path to the tenant's storage
            $absolutePath = storage_path('app/tenant_' . $tenant->id . '/' . $relativePath);

            // If file exists, convert to base64 for reliable PDF rendering
            if (file_exists($absolutePath)) {
                $mimeType = mime_content_type($absolutePath);
                $imageData = base64_encode(file_get_contents($absolutePath));
                return 'src="data:' . $mimeType . ';base64,' . $imageData . '"';
            }

            // Fallback: try public storage path
            $publicPath = storage_path('app/public/' . $relativePath);
            if (file_exists($publicPath)) {
                $mimeType = mime_content_type($publicPath);
                $imageData = base64_encode(file_get_contents($publicPath));
                return 'src="data:' . $mimeType . ';base64,' . $imageData . '"';
            }

            // Return original if file not found
            return $matches[0];
        }, $content);

        // Pattern to match /storage/path (for new signature format)
        $storagePattern = '/src=["\']\/storage\/([^"\']+)["\']/i';

        $content = preg_replace_callback($storagePattern, function ($matches) {
            $relativePath = $matches[1];

            // Build absolute path to public storage
            $publicPath = storage_path('app/public/' . $relativePath);

            // If file exists, convert to base64 for reliable PDF rendering
            if (file_exists($publicPath)) {
                $mimeType = mime_content_type($publicPath);
                $imageData = base64_encode(file_get_contents($publicPath));
                return 'src="data:' . $mimeType . ';base64,' . $imageData . '"';
            }

            // Return original if file not found
            return $matches[0];
        }, $content);

        return $content;
    }
}
