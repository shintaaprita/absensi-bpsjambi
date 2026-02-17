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
        Schema::table('users', function (Blueprint $table) {
            $table->string('fullname')->nullable()->after('id');
            $table->string('username')->unique()->nullable()->after('fullname')->index();
            $table->string('nip_lama')->nullable()->after('email')->index();
            $table->string('nip_baru')->nullable()->after('nip_lama')->index();
            $table->string('satker_kd')->nullable()->after('nip_baru');
            $table->string('jabatan')->nullable()->after('satker_kd');
            $table->json('roles_json')->nullable()->after('jabatan'); // For compatibility with SSO logic
            $table->boolean('is_active')->default(true)->after('roles_json');
            $table->softDeletes();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('satkers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('method', ['location', 'share_qr', 'scan_qr']);
            $table->string('location_name')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('radius')->default(100); // meters
            $table->string('qr_token')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('attendance_session_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('present');
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->string('method_used')->nullable();
            $table->timestamp('captured_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('attendance_sessions');
        Schema::dropIfExists('satkers');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['fullname', 'username', 'nip_lama', 'nip_baru', 'satker_kd', 'jabatan', 'roles_json', 'is_active']);
            $table->dropSoftDeletes();
        });
    }
};
