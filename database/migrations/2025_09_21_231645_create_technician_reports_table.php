<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('technician_reports')) {
            Schema::create('technician_reports', function (Blueprint $t) {
                $t->id();
                $t->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
                $t->string('title', 255);
                $t->text('notes')->nullable();
                $t->longText('attachments')->nullable(); // JSON: ["storage/..."]
                $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $t->timestamps();
            });
            return;
        }

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
            if (!Schema::hasColumn('technician_reports','attachments')) {
                $t->longText('attachments')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('technician_reports','created_by')) {
                $t->foreignId('created_by')->nullable()->after('attachments')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('technician_reports','created_at')) {
                $t->timestamps();
            }
        });
    }

    public function down(): void
    {
        // لا نسقط الجدول هنا حتى لا نفقد بياناتك
    }
};