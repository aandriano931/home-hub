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
        Schema::create('bank_account', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->string('alias', 100);
            $table->string('holder', 255);
            $table->string('number', 30);
            $table->string('label', 100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_account');
    }
};
