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
        Schema::table('rfqs', function (Blueprint $table) {
            $table->string('location', 190)->nullable()->after('phone');
            $table->string('service', 100)->nullable()->after('location');
            $table->string('budget', 100)->nullable()->after('service');
            $table->text('brief')->nullable()->after('budget');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rfqs', function (Blueprint $table) {
            $table->dropColumn(['location', 'service', 'budget', 'brief']);
        });
    }
};
