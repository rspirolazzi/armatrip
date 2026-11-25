<?php

use App\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = config('armatrip.seed_users_password');
        if (empty($password)) {
            throw new RuntimeException('Definir SEED_USERS_PASSWORD en .env antes de sembrar usuarios.');
        }

        $count = 1;
        $rows = [
            ['name' => 'Admin', 'email' => 'admin@armatrip.test', 'is_verified' => 1],
            ['name' => 'Admin IDG', 'email' => 'admin@idg.com', 'is_verified' => 1],
            ['name' => 'Editor', 'email' => 'editor@armatrip.test', 'is_verified' => 1],
            ['name' => 'Cotizador', 'email' => 'cotizador@armatrip.test', 'is_verified' => 1],
        ];
        foreach ($rows as $row) {
            $user = Admin::where('email', $row['email'])->first();
            if (!$user) {
                Admin::create([
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'password' => Hash::make($password),
                    'is_verified' => 1]);
                $count++;
            }
        }
    }
}
