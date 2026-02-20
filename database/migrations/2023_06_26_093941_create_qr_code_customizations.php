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
        if (Schema::hasTable('qr_code_customizations')) {
            return;
        }

        Schema::create('qr_code_customizations', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id', 191);
            $table->foreign('tenant_id')->references('id')->on('tenants')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('key');
            $table->string('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_code_customizations');
    }
};
