<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // لو الجدول غير موجود، أنشئه بالسكيمة النهائية
        if (!Schema::hasTable('technician_reports')) {
            Schema::create('technician_reports', function (Blueprint $t) {
                $t->id();
                $t->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
                $t->string('title', 255);
                $t->text('notes')->nullable();
                $t->json('attachments')->nullable(); // JSON: ["storage/..."]
                $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $t->timestamps();
            });
            return;
        }

        // الجدول موجود: تأكد من الأعمدة الأساسية
        Schema::table('technician_reports', function (Blueprint $t) {
            if (!Schema::hasColumn('technician_reports','project_id')) {
                $t->foreignId('project_id')->nullable()->after('id')->constrained('projects')->nullOnDelete();
            }
            if (!Schema::hasColumn('technician_reports','title')) {
                $t->string('title',255)->after('project_id');
            }
            if (!Schema::hasColumn('technician_reports','notes')) {
                $t->text('notes')->nullable()->after('title');
            }
            if (!Schema::hasColumn('technician_reports','created_by')) {
                $t->foreignId('created_by')->nullable()->after('attachments')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('technician_reports','created_at')) {
                $t->timestamps();
            }
        });

        // تطبيع نوع attachments: لو كان LongText بدل JSON، حوّله إلى JSON بدون DBAL
        // الاستراتيجية: أنشئ عمود مؤقت JSON ثم انقل البيانات النصية كما هي (لو كانت JSON صالح)
        if (Schema::hasColumn('technician_reports', 'attachments')) {
            // إذا كان العمود الحالي ليس JSON، أضف عمود JSON مؤقت
            // ملاحظة: لا توجد طريقة محمولة 100% لاكتشاف النوع هنا، لذلك نستخدم عمودًا مؤقتًا بذكاء.
            if (!Schema::hasColumn('technician_reports', 'attachments_json_tmp')) {
                Schema::table('technician_reports', function (Blueprint $t) {
                    $t->json('attachments_json_tmp')->nullable()->after('notes');
                });
            }

            // انقل القيم النصية كما هي؛ MySQL سيرفض غير الـJSON الصالح تلقائيًا.
            // لو لديك PostgreSQL، هذا الحقل json سيحاول التحويل أيضًا.
            DB::table('technician_reports')
                ->whereNotNull('attachments')
                ->orderBy('id')
                ->chunkById(500, function ($rows) {
                    foreach ($rows as $r) {
                        try {
                            // إن كانت القيمة نص JSON صالح ضعها كما هي، وإلا حوّلها إلى مصفوفة بسلسلة وحيدة
                            $value = $r->attachments;
                            $decoded = json_decode($value, true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                DB::table('technician_reports')
                                  ->where('id', $r->id)
                                  ->update(['attachments_json_tmp' => $decoded]);
                            } else {
                                DB::table('technician_reports')
                                  ->where('id', $r->id)
                                  ->update(['attachments_json_tmp' => [$value]]);
                            }
                        } catch (\Throwable $e) {
                            // تجاهل السجل السيء: خزّنه كمصفوفة تحتوي النص الأصلي
                            DB::table('technician_reports')
                              ->where('id', $r->id)
                              ->update(['attachments_json_tmp' => [$r->attachments]]);
                        }
                    }
                });

            // استبدل العمود القديم بالجديد
            Schema::table('technician_reports', function (Blueprint $t) {
                $t->dropColumn('attachments');
            });
            Schema::table('technician_reports', function (Blueprint $t) {
                $t->renameColumn('attachments_json_tmp', 'attachments');
            });
        }
    }

    public function down(): void
    {
        // رجوع آمن: لا نسقط الجدول، لكن يمكننا التراجع عن تحويل العمود إذا لزم
        // (نتركه كما هو لتجنّب فقدان بيانات)
    }
};
