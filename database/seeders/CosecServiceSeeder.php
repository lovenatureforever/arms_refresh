<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant\CosecService;

class CosecServiceSeeder extends Seeder
{
    public function run()
    {
        $services = [
            ['name' => 'APPOINTMENT OF DIRECTOR', 'cost' => 200],
            ['name' => 'RESIGNATION  OF DIRECTOR', 'cost' => 100],
            ['name' => 'TRANSFER OF SHARE', 'cost' => 50],
            ['name' => 'INCREASE OF PAID UP CAPITAL', 'cost' => 300],
            ['name' => 'BUSINESS ADDRESS - CHANGE / NEW', 'cost' => 100],
            ['name' => 'BRANCH ADDRESS - ADD / CLOSE', 'cost' => 100],
            ['name' => 'OPEN BANK ACCOUNT', 'cost' => 150],
            ['name' => 'CHANGE OF BANK SIGNATORIES', 'cost' => 100],
            ['name' => 'CHANGE OF MAKER AND CHECKER', 'cost' => 250],
        ];

        foreach ($services as $service) {
            CosecService::updateOrCreate(
                ['name' => $service['name']],
                ['cost' => $service['cost']]
            );
        }
    }
}
