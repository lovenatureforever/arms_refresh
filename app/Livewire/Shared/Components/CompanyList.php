<?php
namespace App\Livewire\Shared\Components;

use Exception;
use App\Models\Tenant\Company;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyList extends Component
{
    // use WithPagination;

    public function render()
    {
        // $companies = Company::paginate(10);
        $companies = Company::all();

        return view('livewire.shared.components.company-list', [
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
