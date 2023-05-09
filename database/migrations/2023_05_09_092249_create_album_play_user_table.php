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
        Schema::create('album_play_user', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('user_id')
                ->unsigned();
            $table->bigInteger('album_cache_id')
                ->unsigned();
            $table->bigInteger('user_stylus_id')
                ->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('album_cache_id')
                ->references('id')
                ->on('albums_cache')
                ->onDelete('cascade');
            $table->foreign('user_stylus_id')
                ->references('id')
                ->on('user_styluses')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('album_play_user');
    }
};
