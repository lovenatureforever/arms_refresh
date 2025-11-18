<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\CosecTemplate;
use App\Services\PdfConverterService;

class CosecTemplateController extends Controller
{
    public function preview($templateId)
    {
        $template = CosecTemplate::findOrFail($templateId);
        
        if (!$template->template_file) {
            abort(404, 'No template file found');
        }

        $filePath = storage_path('app/public/' . $template->template_file);
        
        if (!file_exists($filePath)) {
            abort(404, 'Template file not found');
        }

        try {
            $pdfPath = PdfConverterService::convertToPdf($filePath, '_preview_' . time());
            
            return response()->file($pdfPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="template-preview.pdf"'
            ])->deleteFileAfterSend();
        } catch (\Exception $e) {
            abort(500, 'Error generating preview: ' . $e->getMessage());
        }
    }
}