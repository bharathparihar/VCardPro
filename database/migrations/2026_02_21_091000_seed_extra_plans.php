<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Run the ExtraPlansSeeder to populate the plans table
        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\ExtraPlansSeeder',
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optional: remove plans added by seeder
    }
};
