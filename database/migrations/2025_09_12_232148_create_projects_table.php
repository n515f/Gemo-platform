<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');                       // اسم العميل
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title')->nullable();                 // اسم المشروع إن رغبت
            $table->enum('status', ['supply','install','operate','maintenance'])
                  ->default('supply');                           // توريد/تركيب/تشغيل/صيانة
            $table->text('notes')->nullable();
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
