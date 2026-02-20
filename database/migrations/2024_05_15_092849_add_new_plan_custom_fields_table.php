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
        if (Schema::hasTable('plan_custom_fields')) {
            return;
        }

        Schema::create('plan_custom_fields', function (Blueprint $table) {
                  $table->id();
                  $table->foreignId('plan_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
                  $table->string('custom_vcard_number');
                  $table->string('custom_vcard_price');
                  $table->timestamps();
                 });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
