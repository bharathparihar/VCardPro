<?php

namespace Database\Seeders;

use App\Models\MultiTenant;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //admin
        $adminEmail = 'admin@vcard.com';
        $tenant = MultiTenant::where('tenant_username', 'admin')->first();
        if (!$tenant) {
            $tenant = MultiTenant::create(['tenant_username' => 'admin']);
        }
        
        $adminUser = User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'first_name' => 'Mr.',
                'last_name' => 'Admin',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('123456'),
                'tenant_id' => $tenant->id,
                'is_active' => User::IS_ACTIVE,
            ]
        );

        $plan = Plan::whereIsDefault(true)->first();
        if ($plan) {
            Subscription::updateOrCreate(
                ['tenant_id' => $tenant->id, 'status' => Subscription::ACTIVE],
                [
                    'plan_id' => $plan->id,
                    'plan_amount' => $plan->price,
                    'plan_frequency' => Plan::MONTHLY,
                    'starts_at' => Carbon::now(),
                    'ends_at' => Carbon::now()->addDays($plan->trial_days),
                    'trial_ends_at' => Carbon::now()->addDays($plan->trial_days),
                    'no_of_vcards' => $plan->no_of_vcards,
                ]
            );
        }

        //super admin
        $superAdminEmail = 'sadmin@vcard.com';
        $superTenant = MultiTenant::where('tenant_username', 'super_admin')->first();
        if (!$superTenant) {
            $superTenant = MultiTenant::create(['tenant_username' => 'super_admin']);
        }
        User::updateOrCreate(
            ['email' => $superAdminEmail],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('123456'),
                'tenant_id' => $superTenant->id,
                'is_active' => User::IS_ACTIVE,
            ]
        );
    }
}
