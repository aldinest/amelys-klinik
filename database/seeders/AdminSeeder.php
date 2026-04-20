<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminEmail = 'admin@reservasidokter.com';

        // cek admin sudah ada atau belum
        $admin = User::where('email', $adminEmail)->first();

        if (!$admin) {
            User::create([
                'name'     => 'Super Admin',
                'email'    => $adminEmail,
                'password' => Hash::make('admin123'),
                'role'     => 'admin', // enum
            ]);
        }
    }
}
