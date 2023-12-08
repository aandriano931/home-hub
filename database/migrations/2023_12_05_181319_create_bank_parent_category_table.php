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
        Schema::create('bank_parent_category', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 255);
            $table->string('picto', 255)->nullable();
            $table->string('color', 30)->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->enum('type', ['débit','crédit'])->default('débit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_parent_category');
    }
};
