<?php

namespace Modules\Authentication\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class RootUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('authentication.root.email');
        $password = config('authentication.root.password');

        if (blank($email) || blank($password)) {
            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => config('authentication.root.name', 'Root'),
                'password' => $password,
                'is_root' => true,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );
    }
}
