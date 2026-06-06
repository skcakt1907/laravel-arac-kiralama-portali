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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            // çoklu dil başlık / özet / içerik
            $table->string('title_tr')->nullable();
            $table->string('title_en')->nullable();
            $table->string('title_ar')->nullable();
            $table->string('excerpt_tr', 500)->nullable();
            $table->string('excerpt_en', 500)->nullable();
            $table->string('excerpt_ar', 500)->nullable();
            $table->longText('body_tr')->nullable();
            $table->longText('body_en')->nullable();
            $table->longText('body_ar')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['is_published', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
