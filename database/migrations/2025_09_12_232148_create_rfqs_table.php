<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rfqs', function (Blueprint $table) {
            $table->id();
            // بيانات العميل (يمكن أن يكون ضيف بدون حساب)
            $table->string('client_name');
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();

            // المنتج والكمية
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('quantity')->default(1);

            $table->text('notes')->nullable();

            // حالة الطلب
            $table->enum('status', ['pending','contacted','quoted','closed'])->default('pending');

            // مسار PDF إن وُلد
            $table->string('pdf_path')->nullable();

            // إن أردت ربطه بمستخدم ادمن قام بإدخاله من لوحة الإدارة
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rfqs');
    }
};
