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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->string('slug')->unique();
            $table->unsignedSmallInteger('year')->nullable();
            $table->decimal('daily_price', 12, 2)->default(0);
            $table->string('currency', 3)->default('AED');
            $table->string('engine')->nullable();
            $table->string('transmission')->nullable();
            $table->unsignedTinyInteger('seats')->nullable();
            $table->string('body_type')->nullable();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->boolean('is_featured')->default(false);
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
        Schema::dropIfExists('rentals');
    }
};
