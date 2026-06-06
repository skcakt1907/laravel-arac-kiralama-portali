<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('brand');                 // marka (Ferrari, Rolls-Royce…)
            $table->string('model');                 // model
            $table->string('slug')->unique();
            $table->unsignedSmallInteger('year')->nullable();
            $table->unsignedInteger('mileage_km')->nullable();   // km
            $table->string('engine')->nullable();    // V12 6.75L
            $table->string('fuel')->nullable();      // benzin/hybrid/elektrik
            $table->string('transmission')->nullable();
            $table->string('body_type')->nullable(); // sedan, suv, coupe
            $table->string('color')->nullable();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();           // ana görsel yolu
            $table->boolean('is_published')->default(true);
            $table->boolean('is_featured')->default(false);      // ana sayfa vitrini
            $table->string('source_url')->nullable();            // F1RST scrape kaynağı
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['brand', 'is_published']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
