<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Plan;
use App\Models\PlanFeature;
use App\Models\Template;
use Illuminate\Database\Seeder;

class TrialPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usdCurrency = Currency::where('currency_code', 'USD')->first();
        $usdId = $usdCurrency ? $usdCurrency->id : 1; 

        $plan = Plan::where('name', 'Standard')->first();
        if (!$plan) {
            $input = [
                'name' => 'Standard',
                'currency_id' => $usdId,
                'price' => 10,
                'frequency' => Plan::MONTHLY,
                'is_default' => 1,
                'trial_days' => 7,
                'no_of_vcards' => 7,
                'custom_vcard_number' => 1,
                'custom_vcard_price' => 0,
                'custom_select' => 0,
            ];

            $plan = Plan::create($input);

            PlanFeature::create([
                'plan_id' => $plan->id,
                'products_services' => true,
                'testimonials' => true,
                'social_links' => true,
                'enquiry_form' => true,
                'custom_fonts' => true,
            ]);

            $templateIds = Template::limit(5)->pluck('id')->toArray();
            $plan->templates()->sync($templateIds);
        }
    }
}
