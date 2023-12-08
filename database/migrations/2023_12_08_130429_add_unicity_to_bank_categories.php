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
        Schema::table('bank_category', function (Blueprint $table) {
            $table->unique(['name','bank_parent_category_id'], 'bank_category_UQ01');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_category', function (Blueprint $table) {
            $table->dropUnique('bank_category_UQ01');
        });
    }
};
