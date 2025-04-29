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
        // Drop the old 'account_type' column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('account_type');
        });

        // Add the 'account_type' column with the updated enum values
        Schema::table('users', function (Blueprint $table) {
            $table->enum('account_type', ['employer', 'freelance'])->nullable(); // 'freelance' replaces 'seeker'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the process: drop the updated column and add the old one back
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('account_type');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('account_type', ['employer', 'seeker'])->nullable(); // 'seeker' is restored
        });
    }
};
