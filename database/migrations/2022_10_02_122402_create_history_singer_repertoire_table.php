<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('singer_repertoire', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('repertoire_id');
            $table->unsignedBigInteger('singer_id');
            $table->string('singer_name');

            $table->unique(['repertoire_id', 'singer_id']);
            $table->index(['repertoire_id', 'singer_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('singer_repertoire');
    }
};
