<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Fund;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criar usuário admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'profile' => 'admin'
        ]);

        // Criar fundos
        Fund::create([
            'name' => 'Fundo Alpha',
            'cnpj' => '12345678000101',
            'razao_social' => 'Fundo de Investimentos Alpha',
            'logradouro' => 'Rua das Árvores',
            'numero' => '123'
        ]);

        Fund::create([
            'name' => 'Fundo Beta',
            'cnpj' => '98765432000102',
            'razao_social' => 'Fundo Beta Capital',
            'logradouro' => 'Av. Brasil',
            'numero' => '456'
        ]);

        Fund::create([
            'name' => 'Fundo Gama',
            'cnpj' => '11222333000103',
            'razao_social' => 'Gama Investimentos Financeiros',
            'logradouro' => 'Alameda Santos',
            'numero' => '789'
        ]);
    }
}
