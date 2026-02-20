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
        Schema::table('whatsapp_store_products', function (Blueprint $table) {
            $table->decimal('net_price', 12, 2)->nullable()->change();
            $table->decimal('selling_price', 12, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_store_products', function (Blueprint $table) {
            $table->decimal('net_price',12,2)->nullable()->change();
            $table->decimal('selling_price',12,2)->change();
        });
    }
};
