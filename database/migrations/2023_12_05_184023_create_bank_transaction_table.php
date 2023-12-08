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
        Schema::create('bank_transaction', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->date('operation_date')->nullable(false);
            $table->date('value_date')->nullable(false);
            $table->string('label', 255)->nullable(false);
            $table->decimal('debit', 10, 2);
            $table->decimal('credit', 10, 2);
            $table->foreignUuid('bank_account_id')->constrained('bank_account');
            $table->foreignUuid('bank_category_id')->constrained('bank_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_transaction');
    }
};
