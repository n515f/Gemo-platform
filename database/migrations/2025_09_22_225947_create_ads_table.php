<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('ads')) {
            Schema::create('ads', function (Blueprint $t) {
                $t->id();
                $t->string('title_ar', 255)->nullable();
                $t->string('title_en', 255)->nullable();
                $t->text('desc_ar')->nullable();
                $t->text('desc_en')->nullable();
                $t->string('location_title', 255)->nullable(); // عنوان المكان (اختياري)
                $t->longText('images')->nullable();            // JSON: ["storage/...","storage/..."]
                $t->boolean('is_active')->default(true);
                $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $t->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};