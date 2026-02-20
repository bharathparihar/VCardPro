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
        if (Schema::hasTable('whatsapp_store_email_subscriptions')) {
            return;
        }

        Schema::create('whatsapp_store_email_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('whatsapp_store_id');
            $table->string('email');
            $table->timestamps();

            $table->foreign('whatsapp_store_id')->references('id')->on('whatsapp_stores')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_store_email_subscriptions');
    }
};
