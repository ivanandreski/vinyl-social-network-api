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
            $table->string('discogs_id');
            $table->string('discogs_resource_url');
            $table->string('discogs_release_url');
            $table->string('title');
            $table->string('artist_name');
            $table->string('image_url');
            $table->integer('length_in_seconds');
            $table->string('column_hash');
            // hash albumName, imageUrl, lengthInSeconds, and urls to determine wether the release has changed
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
