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
        if (Schema::hasTable('social_icon')) {
            return;
        }

        Schema::create('social_icon', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('social_link_id')->nullable();
            $table->string('link');
            $table->timestamps();

            $table->foreign('social_link_id')->references('id')->on('social_links')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_icon');
    }
};
