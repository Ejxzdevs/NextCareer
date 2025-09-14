<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the old constraint first (replace 'check_application_status' with your actual constraint name)
        DB::statement("ALTER TABLE applications DROP CONSTRAINT check_application_status");

        // Add new constraint with all statuses
        DB::statement("
            ALTER TABLE applications 
            ADD CONSTRAINT check_application_status 
            CHECK (application_status IN ('pending', 'viewed', 'cancelled', 'scheduled', 'hired', 'rejected'))
        ");
    }

    public function down(): void
    {
        // Optional: revert to old constraint if needed
        DB::statement("ALTER TABLE applications DROP CONSTRAINT check_application_status");

        DB::statement("
            ALTER TABLE applications 
            ADD CONSTRAINT check_application_status 
            CHECK (application_status IN ('pending', 'viewed'))
        ");
    }
};
