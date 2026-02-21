<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Plan;
use App\Models\PlanFeature;
use App\Models\Template;
use Illuminate\Database\Seeder;

class ExtraPlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usdCurrency = Currency::where('currency_code', 'USD')->first();
        $usdId = $usdCurrency ? $usdCurrency->id : 1;

        $plans = [
             [
                'name' => 'Silver',
                'currency_id' => $usdId,
                'price' => 50,
                'frequency' => Plan::MONTHLY,
                'is_default' => 0,
                'trial_days' => 0,
                'no_of_vcards' => 20,
                'custom_vcard_number' => 5,
                'custom_vcard_price' => 5,
                'custom_select' => 0,
                'status' => Plan::IS_ACTIVE,
            ],
            [
                'name' => 'Gold',
                'currency_id' => $usdId,
                'price' => 500,
                'frequency' => Plan::YEARLY,
                'is_default' => 0,
                'trial_days' => 0,
                'no_of_vcards' => 100,
                'custom_vcard_number' => 10,
                'custom_vcard_price' => 10,
                'custom_select' => 0,
                'status' => Plan::IS_ACTIVE,
            ],
            [
                'name' => 'Unlimited',
                'currency_id' => $usdId,
                'price' => 1000,
                'frequency' => Plan::UNLIMITED,
                'is_default' => 0,
                'trial_days' => 0,
                'no_of_vcards' => 9999,
                'custom_vcard_number' => 0,
                'custom_vcard_price' => 0,
                'custom_select' => 0,
                'status' => Plan::IS_ACTIVE,
            ],
        ];

        foreach ($plans as $planData) {
            $plan = Plan::where('name', $planData['name'])->first();
            if (!$plan) {
                $plan = Plan::create($planData);

                PlanFeature::create([
                    'plan_id' => $plan->id,
                    'products_services' => true,
                    'testimonials' => true,
                    'social_links' => true,
                    'enquiry_form' => true,
                    'custom_fonts' => true,
                    'appointments' => true,
                    'analytics' => true,
                ]);

                $templateIds = Template::pluck('id')->toArray();
                $plan->templates()->sync($templateIds);
            }
        }
    }
}
