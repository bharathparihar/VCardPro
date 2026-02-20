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
        if (Schema::hasTable('instagram_embeds')) {
            return;
        }

        Schema::create('instagram_embeds', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->longText('embedtag')->nullable();
            $table->unsignedBigInteger('vcard_id');
            $table->timestamps();
            $table->foreign('vcard_id')->references('id')->on('vcards')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instagram_embeds');
    }
};
