<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Adapters\Persistence\UserEloquentModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $username = env('ADMIN_USERNAME', 'admin');
        $password = env('ADMIN_PASSWORD', 'admin_password_seguro');

        UserEloquentModel::updateOrCreate(
            ['username' => strtolower(trim($username))],
            [
                'id' => (string) Str::uuid(),
                'password_hash' => Hash::make($password),
                'role' => 'admin',
            ]
        );
    }
}