<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Business;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class); // 1. Create roles first
        $this->call(SuperAdminSeeder::class);
        // 2. Create a business (you can create more as needed)

        User::firstOrCreate([
            'email' => 'superadmin@gmail.com',
        ], [
            'name' => 'System Super Admin',
            'password' => Hash::make('1234567890'),
            'is_superadmin' => true, // this replaces the need for a 'role' column
            'business_id' => null, // not linked to any business
            'role_id' => null, //not linked to any role
           
        ]);

         // 3. Get roles
         $adminRole = Role::where('name', 'admin')->first();
         $userRole = Role::where('name', 'user')->first();
         $user = User::where('email', 'superadmin@gmail.com')->first();
 
 

        $business = Business::create([
            'name' => 'Justart Technologies',
            'mpesa_short_code'=>'247247',
            'mpesa_consumer_key' =>'consumer-key',
            'mpesa_consumer_secret'=>'consumer_secret',
'mpesa_passkey'=>'passkey',
'mpesa_initiator_name'=>'admin',
'mpesa_security_credential'=>'1234567890'
        ]);

       
        // 4. Create an admin user for the business
        User::factory()->create([
            'name' => 'Justart Tech Admin',
            'email' => 'justarttech@gmail.com',
            'password' => bcrypt('techjustart'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'role_id' => $adminRole->id,
            'business_id' => $business->id,
        ]);

        // 5. Create a regular user for the same business
        User::factory()->create([
            'name' => 'Employee One',
            'email' => 'employeeone@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'role_id' => $userRole->id,
            'business_id' => $business->id,
        ]);
    }
}
