<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

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
        Artisan::call('db:seed', ['--class' => 'SubTextSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'DefaultCountryCode', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'DefaultCurrencySeeder', '--force' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
