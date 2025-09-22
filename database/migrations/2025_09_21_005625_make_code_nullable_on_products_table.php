<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) جعل code يقبل null
        Schema::table('products', function (Blueprint $t) {
            // ملاحظة: يلزم doctrine/dbal لاستخدام change()
            $t->string('code', 100)->nullable()->change();
        });

        // 2) إزالة أي فهرس UNIQUE قديم على code (إن وُجد) ثم إعادة إنشائه مرة واحدة
        // قد تكون الأسماء مختلفة في بعض البيئات؛ نجرب الأكثر شيوعًا بهدوء
        foreach (['products_code_unique', 'code_unique', 'products_code_uindex'] as $idx) {
            try {
                DB::statement("ALTER TABLE products DROP INDEX {$idx}");
            } catch (\Throwable $e) {
                // تجاهل إن لم يكن موجودًا
            }
        }

        // أعد إنشاء UNIQUE واحد فقط
        try {
            Schema::table('products', function (Blueprint $t) {
                $t->unique('code', 'products_code_unique');
            });
        } catch (\Throwable $e) {
            // لو كان موجودًا أصلًا لا نفعل شيئًا
        }
    }

    public function down(): void
    {
        // الرجوع: اجعل code NOT NULL وأزل الفهرس (اختياري)
        try {
            Schema::table('products', function (Blueprint $t) {
                $t->dropUnique('products_code_unique');
            });
        } catch (\Throwable $e) {}

        Schema::table('products', function (Blueprint $t) {
            $t->string('code', 100)->nullable(false)->change();
        });
    }
};