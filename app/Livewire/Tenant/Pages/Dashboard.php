<?php

namespace App\Livewire\Tenant\Pages;

use App\Models\User;
use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyDirector;
use App\Models\Tenant\DirectorSignature;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        // For directors, show their company info and director dashboard
        if ($user->user_type === User::USER_TYPE_DIRECTOR) {
            $director = CompanyDirector::where('user_id', $user->id)
                ->where('is_active', true)
                ->first();

            $hasSignature = false;
            $currentSignature = null;
            $company = null;

            if ($director) {
                $currentSignature = DirectorSignature::where('director_id', $director->id)
                    ->where('is_default', true)
                    ->first();
                $hasSignature = $currentSignature !== null;
                $company = Company::find($director->company_id);
            }

            return view('livewire.tenant.pages.dashboard', [
                'isDirector' => true,
                'director' => $director,
                'hasSignature' => $hasSignature,
                'currentSignature' => $currentSignature,
                'company' => $company,
            ]);
        }

        // For admin/subscriber, show normal company list
        return view('livewire.tenant.pages.dashboard', [
            'isDirector' => false,
        ]);
    }
}
