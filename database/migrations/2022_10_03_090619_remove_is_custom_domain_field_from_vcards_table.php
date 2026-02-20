<?php

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
        if (Schema::hasColumn('vcards', 'is_custom_domain')) {
            Schema::table('vcards', function (Blueprint $table) {
                $table->dropColumn('is_custom_domain');
            });
        }

        Schema::dropIfExists('custom_domain_request');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
