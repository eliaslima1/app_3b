<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    Company::create([
        'cnpj' => '12345678000199',
        'company_name' => 'Empresa Exemplo',
        'address' => 'Rua Exemplo, 123',
        'phone' => '11987654321',
    ]);
}

}
