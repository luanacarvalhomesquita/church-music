<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('singer_music', function (Blueprint $table) {
            $table->id();
            $table->string('tone')->nullable();
            $table->string('version')->nullable();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->unsignedBigInteger('music_id');
            $table->foreign('music_id')->references('id')->on('music')->onDelete('CASCADE');
            $table->unsignedBigInteger('singer_id');
            $table->foreign('singer_id')->references('id')->on('singers')->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('singer_music');
    }
};
