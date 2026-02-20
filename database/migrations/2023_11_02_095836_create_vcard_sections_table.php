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
        if (Schema::hasTable('vcard_sections')) {
            return;
        }

        Schema::create('vcard_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vcard_id');
            $table->string('header')->nullable();
            $table->string('contact_list')->nullable();
            $table->string('services')->nullable();
            $table->string('products')->nullable();
            $table->string('galleries')->nullable();
            $table->string('blogs')->nullable();
            $table->string('map')->nullable();
            $table->string('testimonials')->nullable();
            $table->string('business_hours')->nullable();
            $table->string('appointments')->nullable();
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
        Schema::dropIfExists('vcard__sections');
    }
};
