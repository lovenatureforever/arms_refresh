<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant\CosecTemplate;

class CosecTemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            [
                'name' => 'Appointment of Director',
                'form_type' => 1,
                'signature_type' => CosecTemplate::SIGNATURE_DEFAULT,
                'credit_cost' => 100
            ],
            [
                'name' => 'Resignation of Director', 
                'form_type' => 2,
                'signature_type' => CosecTemplate::SIGNATURE_ALL_DIRECTORS,
                'credit_cost' => 150
            ],
            [
                'name' => 'Transfer of Shares',
                'form_type' => 3,
                'signature_type' => CosecTemplate::SIGNATURE_ALL_DIRECTORS,
                'credit_cost' => 200
            ],
            [
                'name' => 'Increase Paid-up Capital',
                'form_type' => 4,
                'signature_type' => CosecTemplate::SIGNATURE_ALL_DIRECTORS,
                'credit_cost' => 250
            ],
            [
                'name' => 'Change Business Address',
                'form_type' => 5,
                'signature_type' => CosecTemplate::SIGNATURE_DEFAULT,
                'credit_cost' => 100
            ],
            [
                'name' => 'Register Branch Address',
                'form_type' => 6,
                'signature_type' => CosecTemplate::SIGNATURE_DEFAULT,
                'credit_cost' => 100
            ],
            [
                'name' => 'Bank Account Opening',
                'form_type' => 7,
                'signature_type' => CosecTemplate::SIGNATURE_ALL_DIRECTORS,
                'credit_cost' => 150
            ],
            [
                'name' => 'Bank Signatories',
                'form_type' => 8,
                'signature_type' => CosecTemplate::SIGNATURE_ALL_DIRECTORS,
                'credit_cost' => 150
            ],
            [
                'name' => 'Maker or Checker',
                'form_type' => 9,
                'signature_type' => CosecTemplate::SIGNATURE_DEFAULT,
                'credit_cost' => 100
            ]
        ];

        foreach ($templates as $template) {
            CosecTemplate::create($template);
        }
    }
}