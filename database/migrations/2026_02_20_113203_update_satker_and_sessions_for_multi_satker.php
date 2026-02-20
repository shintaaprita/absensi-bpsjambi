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
        Schema::table('satkers', function (Blueprint $table) {
            $table->string('email')->nullable()->after('name');
        });

        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->string('satker_code')->nullable()->after('id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->dropColumn('satker_code');
        });
        
        Schema::table('satkers', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
};
