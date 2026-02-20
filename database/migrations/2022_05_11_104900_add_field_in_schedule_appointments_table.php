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
        Schema::table('schedule_appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('appointment_tran_id')->nullable()->after('to_time');
            $table->foreign('appointment_tran_id')->references('id')->on('appointment_transactions')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_appointments', function (Blueprint $table) {
            //
        });
    }
};
