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
        Schema::create('satker_settings', function (Blueprint $table) {
            $table->id();
            $table->string('satker_code')->index();
            $table->string('key');
            $table->text('value')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
            $table->unique(['satker_code', 'key']);
        });

        Schema::create('working_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('satker_code')->nullable()->index(); // null means global
            $table->string('day_name'); // Monday, Tuesday, etc.
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->time('overtime_limit')->nullable();
            $table->boolean('is_working_day')->default(true);
            $table->timestamps();
        });

        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->date('holiday_date')->unique();
            $table->string('description');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_wfa')->default(false)->after('is_active');
            $table->string('profile_photo')->nullable()->after('is_wfa');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('method_used');
            $table->boolean('is_wfa_at_time')->default(false)->after('photo_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['photo_path', 'is_wfa_at_time']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_wfa', 'profile_photo']);
        });

        Schema::dropIfExists('holidays');
        Schema::dropIfExists('working_schedules');
        Schema::dropIfExists('satker_settings');
    }
};
