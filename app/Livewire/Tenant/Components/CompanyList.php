<?php
namespace App\Livewire\Tenant\Components;

use Exception;
use App\Models\Tenant\v2\Company;
use App\Models\Tenant\CompanyDirector;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyList extends Component
{
    // use WithPagination;

    public function render()
    {
        $user = auth()->user();

        if ($user->isCosecAdmin()) {
            // Admin sees all companies
            $companies = Company::all();
        } elseif ($user->isCosecSubscriber()) {
            // Subscriber/Secretary sees only companies they created
            $companies = Company::where('created_by', $user->id)->get();
        } elseif ($user->isCosecDirector()) {
            // Director sees only their linked company
            $directorCompanyIds = CompanyDirector::where('user_id', $user->id)
                ->pluck('company_id')
                ->toArray();
            $companies = Company::whereIn('id', $directorCompanyIds)->get();
        } else {
            $companies = collect();
        }

        return view('livewire.tenant.v2.components.company-list', [
            'companies' => $companies
        ]);
    }

    public function deleteCompany($id)
    {
        try {
            Company::findOrFail($id)->delete();
        } catch (Exception $e) {
            info('delete company - ' . $e->getMessage());
        }
    }

    public function showCompany($id)
    {
        redirect()->route('show.company', $id);
    }
}
