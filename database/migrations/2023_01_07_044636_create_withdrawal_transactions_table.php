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
        if (Schema::hasTable('withdrawal_transactions')) {
            return;
        }

        Schema::create('withdrawal_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('withdrawal_id');
            $table->integer('amount');
            $table->integer('paid_by');
            $table->json('payment_meta')->nullable();
            $table->timestamps();

            $table->foreign('withdrawal_id')->references('id')->on('withdrawals');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawal_transactions');
    }
};
