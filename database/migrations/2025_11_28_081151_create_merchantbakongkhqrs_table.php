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
        Schema::create('merchantbakongkhqrs', function (Blueprint $table) {
            $table->id();
            $table->string('merchantType')->nullable();
            $table->string('bakongAccountID')->nullable();
            $table->string('transactionCurrency')->nullable();
            $table->string('countryCode')->nullable();
            $table->string('merchantName')->nullable();
            $table->string('merchantCity')->nullable();
            $table->string('crc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchantbakongkhqrs');
    }
};
