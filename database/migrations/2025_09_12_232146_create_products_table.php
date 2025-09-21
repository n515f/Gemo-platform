<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();                 // مثل PKG-220
            $table->string('slug')->unique();                 // للرابط
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('short_desc_ar', 500)->nullable();
            $table->string('short_desc_en', 500)->nullable();
            $table->json('specs_ar')->nullable();             // قائمة مواصفات بالعربية (JSON)
            $table->json('specs_en')->nullable();             // قائمة مواصفات بالإنجليزية
            $table->decimal('price', 12, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
