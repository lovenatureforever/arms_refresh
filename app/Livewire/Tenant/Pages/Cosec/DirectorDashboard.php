<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyDirector;
use App\Models\Tenant\DirectorSignature;

class DirectorDashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        // Find director linked to current user by user_id
        $director = CompanyDirector::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        $hasSignature = false;
        $currentSignature = null;
        $company = null;

        if ($director) {
            // Check if director has uploaded a signature
            $currentSignature = DirectorSignature::where('director_id', $director->id)
                ->where('is_default', true)
                ->first();
            $hasSignature = $currentSignature !== null;

            // Get company info
            $company = Company::find($director->company_id);
        }

        return view('livewire.tenant.pages.cosec.director-dashboard', [
            'director' => $director,
            'hasSignature' => $hasSignature,
            'currentSignature' => $currentSignature,
            'company' => $company,
        ]);
    }
}
