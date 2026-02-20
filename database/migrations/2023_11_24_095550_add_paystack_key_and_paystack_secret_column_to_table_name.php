<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
        Setting::create([
            'key' => 'paystack_key',
            'value' => '',
        ]);
        Setting::create([
            'key' => 'paystack_secret',
            'value' => '',
        ]);
    }
};
