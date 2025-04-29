<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('account_type');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->enum('account_type', ['employer', 'job_seeker']);
    });
}

};
