<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cria o usuário para o administrador
        $user = User::create([
            'name' => 'Admin Master',
            'cpf' => '000.000.000-00', 
            'adress' => 'Rua Admin, 123',
            'telephone' => '(11) 98765-4321',
            'date_of_birth' => '1990-01-01',
            'email' => 'admin@example.com', 
            'password' => Hash::make('password123'), 
            'role_id' => 2, 
        ]);

       
        Admin::create([
            'user_id' => $user->id,
            'position_in_the_company' => 'CEO', 
        ]);
    }
}