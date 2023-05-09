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
        Schema::create('albums_cache', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('discogs_release_id');
            $table->string('album_name');
            $table->string('image_url');
            $table->integer('length_in_seconds');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums_cache');
    }
};
