<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('application_status', 20)
                  ->default('pending');
        });

        // Add check constraint
        DB::statement("ALTER TABLE applications ADD CONSTRAINT check_application_status CHECK (application_status IN ('pending', 'viewed'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('application_status');
        });
    }
};
