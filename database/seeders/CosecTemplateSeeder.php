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
                'credit_cost' => 100,
                'content' => '<p><strong>APPOINTMENT OF DIRECTOR</strong></p><p>This is the default template content for director appointment. Please customize as needed.</p>',
                'is_active' => true
            ],
            [
                'name' => 'Resignation of Director',
                'form_type' => 2,
                'signature_type' => CosecTemplate::SIGNATURE_ALL_DIRECTORS,
                'credit_cost' => 150,
                'content' => '<p><strong>RESIGNATION OF DIRECTOR</strong></p><p>This is the default template content for director resignation. Please customize as needed.</p>',
                'is_active' => true
            ],
            [
                'name' => 'Transfer of Shares',
                'form_type' => 3,
                'signature_type' => CosecTemplate::SIGNATURE_ALL_DIRECTORS,
                'credit_cost' => 200,
                'content' => '<p><strong>TRANSFER OF SHARES</strong></p><p>This is the default template content for share transfer. Please customize as needed.</p>',
                'is_active' => true
            ],
            [
                'name' => 'Increase Paid-up Capital',
                'form_type' => 4,
                'signature_type' => CosecTemplate::SIGNATURE_ALL_DIRECTORS,
                'credit_cost' => 250,
                'content' => '<p><strong>INCREASE OF PAID-UP CAPITAL</strong></p><p>This is the default template content for capital increase. Please customize as needed.</p>',
                'is_active' => true
            ],
            [
                'name' => 'Change Business Address',
                'form_type' => 5,
                'signature_type' => CosecTemplate::SIGNATURE_DEFAULT,
                'credit_cost' => 100,
                'content' => '<p><strong>CHANGE BUSINESS ADDRESS</strong></p><p>This is the default template content for business address change. Please customize as needed.</p>',
                'is_active' => true
            ],
            [
                'name' => 'Register Branch Address',
                'form_type' => 6,
                'signature_type' => CosecTemplate::SIGNATURE_DEFAULT,
                'credit_cost' => 100,
                'content' => '<p><strong>REGISTER BRANCH ADDRESS</strong></p><p>This is the default template content for branch address registration. Please customize as needed.</p>',
                'is_active' => true
            ],
            [
                'name' => 'Bank Account Opening',
                'form_type' => 7,
                'signature_type' => CosecTemplate::SIGNATURE_ALL_DIRECTORS,
                'credit_cost' => 150,
                'content' => '<p><strong>BANK ACCOUNT OPENING</strong></p><p>This is the default template content for bank account opening. Please customize as needed.</p>',
                'is_active' => true
            ],
            [
                'name' => 'Bank Signatories',
                'form_type' => 8,
                'signature_type' => CosecTemplate::SIGNATURE_ALL_DIRECTORS,
                'credit_cost' => 150,
                'content' => '<p><strong>CHANGE OF BANK SIGNATORIES</strong></p><p>This is the default template content for bank signatories change. Please customize as needed.</p>',
                'is_active' => true
            ],
            [
                'name' => 'Maker or Checker',
                'form_type' => 9,
                'signature_type' => CosecTemplate::SIGNATURE_DEFAULT,
                'credit_cost' => 100,
                'content' => '<p><strong>CHANGE OF MAKER OR CHECKER</strong></p><p>This is the default template content for maker/checker change. Please customize as needed.</p>',
                'is_active' => true
            ]
        ];

        foreach ($templates as $template) {
            CosecTemplate::create($template);
        }
    }
}
