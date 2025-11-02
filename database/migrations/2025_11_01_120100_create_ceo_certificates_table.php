<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ceo_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_setting_id')->constrained('site_settings')->onDelete('cascade');
            $table->string('image_path')->nullable();
            $table->string('title_ar')->nullable();
            $table->string('title_en')->nullable();
            $table->string('issuer_ar')->nullable();
            $table->string('issuer_en')->nullable();
            $table->string('issued_at')->nullable(); // نص مرن (يمكن تحويله لتاريخ لاحقاً)
            $table->integer('sort_order')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ceo_certificates');
    }
};