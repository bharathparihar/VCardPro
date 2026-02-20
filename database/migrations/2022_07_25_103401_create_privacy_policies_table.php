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
        if (Schema::hasTable('privacy_policies')) {
            return;
        }

        Schema::create('privacy_policies', function (Blueprint $table) {
            $table->id();
            $table->longText('privacy_policy');
            $table->unsignedBigInteger('vcard_id');
            $table->timestamps();

            $table->foreign('vcard_id')
                ->references('id')
                ->on('vcards');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('privacy_policies');
    }
};
