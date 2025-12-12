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

        // Check if template has content (HTML stored in database)
        if ($template->content) {
            return response($template->content, 200)
                ->header('Content-Type', 'text/html');
        }

        // Fallback to template_file if exists (legacy support)
        if ($template->template_file) {
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

        abort(404, 'No template content found');
    }
}
