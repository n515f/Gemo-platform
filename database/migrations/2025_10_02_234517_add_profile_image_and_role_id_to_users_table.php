<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users','profile_image')) {
                $table->string('profile_image')->nullable()->after('email');
            }
            // لو حاب تربط role_id مباشرةً، وإلا اتركه واعتمد على spatie فقط
            if (!Schema::hasColumn('users','role_id')) {
                $table->unsignedBigInteger('role_id')->nullable()->after('profile_image')->index();
            }
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users','role_id'))     $table->dropColumn('role_id');
            if (Schema::hasColumn('users','profile_image')) $table->dropColumn('profile_image');
        });
    }
};