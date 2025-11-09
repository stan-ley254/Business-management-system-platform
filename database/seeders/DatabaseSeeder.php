<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\{User, Role, Business, Supplier, SupplierProduct};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1️⃣ Roles first
        $this->call(RoleSeeder::class);

        // 2️⃣ Create superadmin (not tied to any business)
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'System Super Admin',
                'password' => Hash::make('1234567890'),
                'is_superadmin' => true,
                'business_id' => null,
                'role_id' => null,
            ]
        );

        // 3️⃣ Fetch roles
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        // 4️⃣ Create main business
        $business = Business::create([
            'name' => 'Justart Technologies',
            'type' => 'pos',
            'mpesa_short_code' => '247247',
            'mpesa_consumer_key' => 'consumer-key',
            'mpesa_consumer_secret' => 'consumer_secret',
            'mpesa_passkey' => 'passkey',
            'mpesa_initiator_name' => 'admin',
            'mpesa_security_credential' => '1234567890',
            'is_active' => true,
        ]);

        // 5️⃣ Create business admin
        $adminUser = User::factory()->create([
            'name' => 'Justart Tech Admin',
            'email' => 'justarttech@gmail.com',
            'password' => bcrypt('techjustart'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'role_id' => $adminRole->id,
            'business_id' => $business->id,
        ]);

        // 6️⃣ Create a regular employee
        $employee = User::factory()->create([
            'name' => 'Employee One',
            'email' => 'employeeone@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'role_id' => $userRole->id,
            'business_id' => $business->id,
        ]);

        // 7️⃣ Create suppliers for Justart Technologies
        $suppliers = Supplier::factory(10)->create([
            'business_id' => $business->id,
        ]);

        // 8️⃣ Create supplier products tied to those suppliers
        foreach ($suppliers as $supplier) {
            SupplierProduct::factory(5)->create([
                'supplier_id' => $supplier->id,
                'business_id' => $business->id,
            ]);
        }

        
    }
}
