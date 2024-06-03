<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('pin');

            $expiredDate = Carbon::now()->addMinutes(15);
            $table->dateTime('expired_date')->default($expiredDate);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_resets');
    }
};
