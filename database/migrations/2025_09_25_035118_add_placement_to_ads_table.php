<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            // قيم مقترحة: home, catalog, services, rfq, all
            $table->string('placement', 20)->nullable()->after('is_active');
            $table->index('placement');
        });
    }

    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropIndex(['placement']);
            $table->dropColumn('placement');
        });
    }
};