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
        if (Schema::hasTable('whatsapp_stores')) {
            return;
        }

        Schema::create('whatsapp_stores', function (Blueprint $table) {
            $table->id();
            $table->string('url_alias')->unique();
            $table->string('store_name');
            $table->string('region_code');
            $table->string('whatsapp_no');
            $table->unsignedBigInteger('template_id')->nullable();
            $table->string('tenant_id');
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('template_id')->references('id')->on('wp_store_templates')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_stores');
    }
};
