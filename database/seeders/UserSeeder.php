<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    User::create([
        'company_id' => 1,
        'cpf' => '12345678901',
        'name' => 'Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'phone' => '11987654322',
        'is_verified' => true,
        'role' => 'admin',
    ]);
}

}
