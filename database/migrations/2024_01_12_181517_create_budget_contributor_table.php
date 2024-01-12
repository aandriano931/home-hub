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
        Schema::create('budget_contributor', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('label', 255)->nullable(false);
            $table->decimal('available_money', 10, 2);
            $table->foreignUuid('budget_id')->constrained('budget')->nullable(false);
            $table->foreignId('user_id')->constrained('users')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_contributor');
    }
};
