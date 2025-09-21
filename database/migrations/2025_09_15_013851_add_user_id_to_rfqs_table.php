<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_user_id_to_rfqs_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('rfqs', function (Blueprint $t) {
            $t->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
        });
    }
    public function down(): void {
        Schema::table('rfqs', function (Blueprint $t) {
            $t->dropConstrainedForeignId('user_id');
        });
    }
};
