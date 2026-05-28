<?php

namespace database\seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin (Kepala Desa) default account
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama' => 'Lukman Hakim',
                'password' => 'admin123', // Akan di-hash otomatis via User Model Cast
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // 2. Operator (Staf Desa) default account
        User::updateOrCreate(
            ['username' => 'operator'],
            [
                'nama' => 'Indra Pramudya',
                'password' => 'operator123', // Akan di-hash otomatis via User Model Cast
                'role' => 'operator',
                'is_active' => true,
            ]
        );
    }
}
