<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();

            // بيانات الشركة
            $table->string('company_name_ar')->nullable();
            $table->string('company_name_en')->nullable();
            $table->string('company_tagline_ar')->nullable();
            $table->string('company_tagline_en')->nullable();
            $table->text('company_description_ar')->nullable();
            $table->text('company_description_en')->nullable();
            $table->text('company_address_ar')->nullable();
            $table->text('company_address_en')->nullable();
            $table->string('company_phone', 80)->nullable();
            $table->string('company_whatsapp_number', 80)->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_email_alt')->nullable();
            $table->string('company_website_url')->nullable();

            // روابط التواصل
            $table->string('social_whatsapp_url')->nullable();
            $table->string('social_instagram_url')->nullable();
            $table->string('social_facebook_url')->nullable();
            $table->string('social_twitter_url')->nullable();
            $table->string('social_linkedin_url')->nullable();
            $table->string('social_youtube_url')->nullable();
            $table->string('social_tiktok_url')->nullable();

            // الواجهة والهوية
            $table->string('theme_primary_color', 10)->default('#2563eb');
            $table->string('theme_secondary_color', 10)->nullable();
            $table->string('theme_background_color', 10)->nullable();
            $table->string('theme_logo_path')->nullable();
            $table->string('theme_logo_dark_path')->nullable();
            $table->string('theme_favicon_path')->nullable();
            $table->boolean('theme_dark_mode_enabled')->default(false);

            // اللغة والتوطين
            $table->string('default_locale', 5)->default('ar');
            $table->json('supported_locales_json')->nullable(); // مثال: ["ar","en"]
            $table->json('rtl_locales_json')->nullable();       // مثال: ["ar"]

            // SEO
            $table->string('seo_meta_title_ar')->nullable();
            $table->string('seo_meta_title_en')->nullable();
            $table->text('seo_meta_description_ar')->nullable();
            $table->text('seo_meta_description_en')->nullable();
            $table->string('seo_og_image_path')->nullable();

            // تشغيل النظام
            $table->boolean('maintenance_mode')->default(false);
            $table->boolean('registration_enabled')->default(true);
            $table->integer('max_upload_size_kb')->default(10240);
            $table->string('timezone', 64)->nullable()->default('Asia/Riyadh');

            // الاتصال والدعم
            $table->boolean('contact_page_enabled')->default(true);
            $table->string('contact_recipient_email')->nullable();

            // البريد والإشعارات
            $table->boolean('mail_notifications_enabled')->default(true);
            $table->string('mail_from_name')->nullable();
            $table->string('mail_from_address')->nullable();

            // تكامل واتساب
            $table->boolean('whatsapp_api_enabled')->default(false);
            $table->string('whatsapp_api_phone', 80)->nullable();
            $table->string('whatsapp_api_token')->nullable();

            // إعدادات RFQ
            $table->boolean('rfq_notify_via_whatsapp')->default(true);
            $table->boolean('rfq_notify_via_email')->default(true);
            $table->string('rfq_default_recipient_email')->nullable();

            // الإعلانات
            $table->boolean('ads_auto_rotate_enabled')->default(true);
            $table->integer('ads_rotate_interval_sec')->default(60);

            // الكتالوج
            $table->integer('catalog_card_image_rotate_interval_sec')->default(300);
            $table->boolean('catalog_show_out_of_stock')->default(false);

            // الماركيه/التصنيفات
            $table->boolean('categories_marquee_auto_move_enabled')->default(true);
            $table->integer('categories_marquee_speed')->default(1);
            $table->string('categories_marquee_direction_strategy')->default('locale_based');

            // الأمان
            $table->integer('password_policy_min_length')->default(8);
            $table->boolean('password_policy_require_symbols')->default(true);

            // التحليلات
            $table->boolean('analytics_enabled')->default(false);
            $table->string('analytics_google_tag_id')->nullable();

            // المدير التنفيذي
            $table->string('ceo_name_ar')->nullable();
            $table->string('ceo_name_en')->nullable();
            $table->string('ceo_image_path')->nullable();

            // مرونة مستقبلية
            $table->json('extra_json')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};