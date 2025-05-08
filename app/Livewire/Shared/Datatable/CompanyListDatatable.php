<?php

namespace App\Livewire\Shared\Datatable;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Tenants\Company;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class CompanyListDatatable extends DataTableComponent
{
    protected $model = Company::class;

    public function configure(): void
    {
        $this->setLoadingPlaceholderEnabled();
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->deselected()
                ->excludeFromColumnSelect()
                ->setColumnLabelStatusDisabled(),
            Column::make("Company name", "company_name")
                ->searchable()
                ->sortable(),
            Column::make("Registration No", "company_registration_no")
                ->searchable()
                ->sortable(),
            Column::make('Year End', 'current_financial_year')
                ->searchable()
                ->sortable(),
            Column::make('Status', 'status')
                ->searchable()
                ->sortable(),
            ButtonGroupColumn::make('Actions')
                ->attributes(function ($row) {
                    return [
                        'class' => 'space-x-2'
                    ];
                })
                ->buttons([
                    LinkColumn::make('View')
                        ->title(fn ($row) => 'View')
                        ->location(function ($row) {
                            return route('show.company', $row->id);
                        })
                        ->attributes(function ($row) {
                            return [
                                'class' => 'underline text-blue-500 hover:no-underline',
                            ];
                        }),
                ])
        ];
    }
}
