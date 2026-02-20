<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Disable migrations transaction for this file to see the real Postgres error.
     */
    public $withinTransaction = false;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $setting = Setting::where('key', 'currency_after_amount')->first();
        if ($setting) {
            return;
        }
        Setting::create([
            'key' => 'currency_after_amount',
            'value' => '0',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
