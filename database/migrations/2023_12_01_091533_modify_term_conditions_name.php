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
        Schema::table('term_conditions', function (Blueprint $table) {
            $table->dropForeign(['vcard_id']);
            $table->foreign('vcard_id')->references('id')->on('vcards')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('term_conditions', function (Blueprint $table) {
            $table->dropForeign(['vcard_id']);
            $table->foreign('vcard_id')->references('id')->on('vcards');
        });
    }
};
