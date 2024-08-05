<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Empty admin table 
        Admin::truncate();

        // Define the admin credentials
        $adminEmail = 'admin@admin.com';
        $adminPassword = 'admin123';

        // Create the admin user using Eloquent
        Admin::create([
            'name' => 'Admin',
            'email' => $adminEmail,
            'password' => Hash::make($adminPassword),
        ]);
    }
}
