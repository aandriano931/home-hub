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
        Schema::create('budget_line', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('label', 100);
            $table->decimal('debit', 10, 2);
            $table->decimal('credit', 10, 2);
            $table->foreignUuid('budget_id')->constrained('budget')->nullable(false);
            $table->foreignUuid('bank_category_id')->constrained('bank_category')->nullable(true);
            $table->foreignUuid('budget_contributor_id')->constrained('budget_contributor')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_line');
    }
};
