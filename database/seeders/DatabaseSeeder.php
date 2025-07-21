<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\Outlet;
use App\Models\User;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $petugasRole = Role::create(['name' => 'petugas']);
        $ownerRole = Role::create(['name' => 'owner']);
        
        // Create outlets
        $outlet1 = Outlet::create([
            'name' => 'Outlet Pusat',
            'address' => 'Jl. Sudirman No. 123',
            'phone' => '021-1234567'
        ]);
        
        $outlet2 = Outlet::create([
            'name' => 'Outlet Cabang',
            'address' => 'Jl. Thamrin No. 456',
            'phone' => '021-7654321'
        ]);
        
        // Create users
        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'branch_id' => $outlet1->id,
        ]);

        User::create([
            'name' => 'Petugas User',
            'username' => 'petugas',
            'email' => 'petugas@example.com',
            'password' => Hash::make('password'),
            'role_id' => $petugasRole->id,
            'branch_id' => $outlet1->id,
        ]);

        User::create([
            'name' => 'Owner User',
            'username' => 'owner',
            'email' => 'owner@example.com',
            'password' => Hash::make('password'),
            'role_id' => $ownerRole->id,
            'branch_id' => $outlet1->id,
        ]);

        // Create products
        Product::create([
            'name' => 'Cuci Kering Kiloan',
            'price' => 5000,
            'type' => 'kiloan',
            'outlet_id' => $outlet1->id,
        ]);
        
        Product::create([
            'name' => 'Cuci Sepatu',
            'price' => 15000,
            'type' => 'satuan',
            'outlet_id' => $outlet1->id,
        ]);
    }
}