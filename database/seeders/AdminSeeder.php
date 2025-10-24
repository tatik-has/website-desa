<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\DataTier\Models\Admin; // pastikan ini sesuai lokasi model kamu

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'nama' => 'Admin Desa Pakning Asal',
            'email' => 'admin@pakningasal.com',
            'password' => Hash::make('admin123'),
        ]);
    }
}
