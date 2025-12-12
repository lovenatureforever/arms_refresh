<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant\CosecTemplate;
use Illuminate\Support\Facades\File;

class CosecTemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            [
                'name' => 'Appointment of Director',
                'description' => 'Director\'s Circular Resolution for appointing new director with consent form (Section 201)',
                'form_type' => 1,
                'signature_type' => 'all_directors',
                'credit_cost' => 100,
                'template_file' => 'appointment-of-director',
                'is_active' => true
            ],
            [
                'name' => 'Resignation of Director',
                'description' => 'Director\'s Circular Resolution for accepting director resignation (Paragraph 15)',
                'form_type' => 2,
                'signature_type' => 'all_directors',
                'credit_cost' => 100,
                'template_file' => 'resignation-of-director',
                'is_active' => true
            ],
            [
                'name' => 'Appointment & Resignation of Director',
                'description' => 'Combined Director\'s Circular Resolution for appointing new director and accepting resignation',
                'form_type' => 3,
                'signature_type' => 'all_directors',
                'credit_cost' => 150,
                'template_file' => 'appointment-resignation-of-director',
                'is_active' => true
            ],
            [
                'name' => 'Transfer of Shares',
                'description' => 'Director\'s Circular Resolution for approving share transfer (Section 105)',
                'form_type' => 4,
                'signature_type' => 'all_directors',
                'credit_cost' => 200,
                'template_file' => 'transfer-of-shares',
                'is_active' => true
            ],
            [
                'name' => 'Increase Paid Up Capital',
                'description' => 'Director\'s Circular Resolution for allotment and issuance of new ordinary shares (Section 290)',
                'form_type' => 5,
                'signature_type' => 'all_directors',
                'credit_cost' => 250,
                'template_file' => 'increase-paid-up-capital',
                'is_active' => true
            ],
            [
                'name' => 'Change of Business Address',
                'description' => 'Directors\' Circular Resolution for changing business address and accounting records location (Section 245)',
                'form_type' => 6,
                'signature_type' => 'all_directors',
                'credit_cost' => 100,
                'template_file' => 'change-business-address',
                'is_active' => true
            ],
            [
                'name' => 'Closure of Branch Address',
                'description' => 'Directors\' Circular Resolution for closing branch office address (Clause 121)',
                'form_type' => 7,
                'signature_type' => 'all_directors',
                'credit_cost' => 100,
                'template_file' => 'closure-of-branch-address',
                'is_active' => true
            ],
            [
                'name' => 'Registration of Branch Address',
                'description' => 'Directors\' Circular Resolution for registering new branch office address (Clause 121)',
                'form_type' => 8,
                'signature_type' => 'all_directors',
                'credit_cost' => 100,
                'template_file' => 'registration-of-branch-address',
                'is_active' => true
            ],
            [
                'name' => 'Registration of Business Address & Financial Records',
                'description' => 'Director\'s Circular Resolution for registering business address and accounting records location (Section 245)',
                'form_type' => 9,
                'signature_type' => 'default',
                'credit_cost' => 100,
                'template_file' => 'registration-business-address-financial-records',
                'is_active' => true
            ],
            [
                'name' => 'Change of Registered Office Address',
                'description' => 'Directors\' Circular Resolution for changing registered office address (Clause 45)',
                'form_type' => 10,
                'signature_type' => 'all_directors',
                'credit_cost' => 100,
                'template_file' => 'change-registered-address',
                'is_active' => true
            ],
        ];

        $templatePath = resource_path('templates/cosec');

        foreach ($templates as $template) {
            $htmlFile = $templatePath . '/' . $template['template_file'] . '.html';
            $cssFile = $templatePath . '/' . $template['template_file'] . '.css';

            $htmlContent = File::exists($htmlFile) ? File::get($htmlFile) : '';
            $cssContent = File::exists($cssFile) ? File::get($cssFile) : '';

            // Combine CSS and HTML into content
            $content = '';
            if ($cssContent) {
                $content = "<style>\n{$cssContent}\n</style>\n";
            }
            $content .= $htmlContent;

            CosecTemplate::updateOrCreate(
                ['form_type' => $template['form_type']],
                [
                    'name' => $template['name'],
                    'description' => $template['description'],
                    'signature_type' => $template['signature_type'],
                    'credit_cost' => $template['credit_cost'],
                    'content' => $content,
                    'is_active' => $template['is_active']
                ]
            );
        }
    }
}
