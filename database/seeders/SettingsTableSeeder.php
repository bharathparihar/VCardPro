<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $appLogoUrl = ('/assets/images/infyom-logo.png');
        $faviconUrl = ('/web/media/logos/favicon-infyom.png');
        $registerImage = ('assets/images/default-register.png');

        $settings = [
            ['key' => 'app_name', 'value' => 'VCardPro'],
            ['key' => 'app_logo', 'value' => $appLogoUrl],
            ['key' => 'favicon', 'value' => $faviconUrl],
            ['key' => 'register_image', 'value' => $registerImage],
            ['key' => 'email', 'value' => 'admin@vcardpro.com'],
            ['key' => 'phone', 'value' => '9876543210'],
            ['key' => 'address', 'value' => 'Surat, India.'],
            ['key' => 'prefix_code', 'value' => '91'],
            ['key' => 'plan_expire_notification', 'value' => '5'],
            ['key' => 'is_front_page', 'value' => '1'],
            ['key' => 'home_page_theme', 'value' => '1'],
            ['key' => 'default_currency', 'value' => 'USD'],
            ['key' => 'currency_after_amount', 'value' => '0'],
            ['key' => 'datetime_method', 'value' => '1'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
