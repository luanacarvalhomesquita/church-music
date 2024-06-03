<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('music_repertoire', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('repertoire_id');
            $table->unsignedBigInteger('music_id');
            $table->string('music_name');
            $table->string('tone')->nullable();

            $table->unique(['repertoire_id', 'music_id']);
            $table->index(['repertoire_id', 'music_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('music_repertoire');
    }
};
