<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::whereNull('api_key')->each(function (Company $company): void {
            $company->api_key = 'pk_live_' . Str::random(24);
            $company->save();
        });

        Company::create([
            'name' => 'Dariv',
        ]);

        User::updateOrCreate(
            ['email' => 'admin@dariv.com'],
            [
                'name'              => 'Admin User',
                'password'          => 'password', 
                'role'              => User::ROLE_ADMIN,
                'company_id'        => 1,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'mae.s@dariv.com'],
            [
                'name'              => 'Mae S.',
                'password'          => 'password',
                'role'              => User::ROLE_OPERATOR,
                'company_id'        => 1,
                'email_verified_at' => now(),
            ]
        );

    }
}
