<?php

namespace App\Services;

use App\Models\Tenant\EConfirmationRequest;
use App\Models\Tenant\EConfirmationBankPdf;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EConfirmationPdfService
{
    /**
     * Generate unsigned PDF for a bank confirmation
     */
    public function generateUnsignedPdf(EConfirmationBankPdf $bankPdf): string
    {
        $request = $bankPdf->request;
        $company = $request->company;
        $bankBranch = $bankPdf->bankBranch;
        $bank = $bankBranch->bank;
        $directors = $company->directors()->where('is_active', true)->orderBy('name')->get();

        $data = [
            'request' => $request,
            'company' => $company,
            'bankBranch' => $bankBranch,
            'bank' => $bank,
            'directors' => $directors,
            'tenant' => tenant(),
            'yearEndDate' => $request->year_end_date,
            'accountNo' => $request->account_no,
            'chargeCode' => $request->charge_code,
            'isSigned' => false,
            'signatures' => [],
        ];

        // Generate filename
        $filename = sprintf(
            'econfirmation/%d/%s_%s_unsigned.pdf',
            $request->id,
            str_replace(' ', '_', $bank->bank_name),
            $bankBranch->id
        );

        try {
            $pdf = Pdf::loadView('pdf.econfirmation.bank-confirmation', $data)
                ->setPaper('a4')
                ->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

            // Store the PDF
            Storage::disk('public')->put($filename, $pdf->output());

            // Update the bank PDF record
            $bankPdf->update([
                'unsigned_pdf_path' => $filename,
                'signatures_required' => $directors->count(),
            ]);

            return $filename;
        } catch (\Exception $e) {
            Log::error("PDF generation failed for unsigned PDF, request {$request->id}, bank {$bank->bank_name}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate signed PDF with all director signatures overlaid
     */
    public function generateSignedPdf(EConfirmationBankPdf $bankPdf): string
    {
        $request = $bankPdf->request;
        $company = $request->company;
        $bankBranch = $bankPdf->bankBranch;
        $bank = $bankBranch->bank;
        $directors = $company->directors()->where('is_active', true)->orderBy('name')->get();

        // Get all signatures for this bank PDF
        $signatures = $bankPdf->signatures()
            ->where('status', 'signed')
            ->with('director')
            ->orderBy('director_name')
            ->get();

        $data = [
            'request' => $request,
            'company' => $company,
            'bankBranch' => $bankBranch,
            'bank' => $bank,
            'directors' => $directors,
            'tenant' => tenant(),
            'yearEndDate' => $request->year_end_date,
            'accountNo' => $request->account_no,
            'chargeCode' => $request->charge_code,
            'isSigned' => true,
            'signatures' => $signatures,
        ];

        // Generate filename with version
        $version = $bankPdf->version + 1;
        $filename = sprintf(
            'econfirmation/%d/%s_%s_signed_v%d.pdf',
            $request->id,
            str_replace(' ', '_', $bank->bank_name),
            $bankBranch->id,
            $version
        );

        try {
            $pdf = Pdf::loadView('pdf.econfirmation.bank-confirmation', $data)
                ->setPaper('a4')
                ->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

            // Store the PDF
            Storage::disk('public')->put($filename, $pdf->output());

            // Update the bank PDF record
            $bankPdf->update([
                'signed_pdf_path' => $filename,
                'status' => 'signed',
                'version' => $version,
            ]);

            return $filename;
        } catch (\Exception $e) {
            Log::error("PDF generation failed for signed PDF, request {$request->id}, bank {$bank->bank_name}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate all unsigned PDFs for a request
     */
    public function generateAllUnsignedPdfs(EConfirmationRequest $request): array
    {
        $paths = [];

        foreach ($request->bankPdfs as $bankPdf) {
            $paths[] = $this->generateUnsignedPdf($bankPdf);
        }

        return $paths;
    }

    /**
     * Check if bank PDF is ready for signed PDF generation
     */
    public function isReadyForSignedPdf(EConfirmationBankPdf $bankPdf): bool
    {
        return $bankPdf->signatures_collected >= $bankPdf->signatures_required
            && $bankPdf->signatures_required > 0;
    }

    /**
     * Stream PDF for preview (unsigned)
     */
    public function streamUnsignedPdf(EConfirmationBankPdf $bankPdf)
    {
        $request = $bankPdf->request;
        $company = $request->company;
        $bankBranch = $bankPdf->bankBranch;
        $bank = $bankBranch->bank;
        $directors = $company->directors()->where('is_active', true)->orderBy('name')->get();

        $data = [
            'request' => $request,
            'company' => $company,
            'bankBranch' => $bankBranch,
            'bank' => $bank,
            'directors' => $directors,
            'tenant' => tenant(),
            'yearEndDate' => $request->year_end_date,
            'accountNo' => $request->account_no,
            'chargeCode' => $request->charge_code,
            'isSigned' => false,
            'signatures' => [],
        ];

        $pdf = Pdf::loadView('pdf.econfirmation.bank-confirmation', $data)
            ->setPaper('a4')
            ->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->stream('bank_confirmation_preview.pdf');
    }
}
