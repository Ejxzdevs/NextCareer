<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
        $table->boolean('is_read')->default(false)->after('application_status');
        });
    }

    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
        $table->dropColumn('is_read');
        });
    }
};
