<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\CeoCertificate;
use App\Models\Setting; // FIX: import for legacy key-value updates
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $site = SiteSetting::query()->first() ?? SiteSetting::create();
        return view('admin.settings.index', compact('site'));
    }

    public function updateAll(Request $request)
    {
        // صف الإعدادات الصريح الوحيد
        $site = SiteSetting::query()->first() ?? SiteSetting::create();

        // رفع صورة المدير التنفيذي إن وجدت
        if ($request->hasFile('ceo_image')) {
            $path = $request->file('ceo_image')->store('uploads/ceo', 'public');
            $site->ceo_image_path = 'storage/'.$path;
        }

        // رفع شعار الشركة (اللوغو)
        if ($request->hasFile('company_logo')) {
            $path = $request->file('company_logo')->store('uploads/company', 'public');
            $site->company_logo_path = 'storage/'.$path;
        }

        // تحديث الحقول النصية (أعمدة صريحة فقط)
        $site->fill($request->only([
            'company_name_ar','company_name_en','company_tagline_ar','company_tagline_en',
            'company_description_ar','company_description_en','company_address_ar','company_address_en',
            'company_phone','company_whatsapp_number','company_email','company_email_alt','company_website_url',
            'social_whatsapp_url','social_instagram_url','social_facebook_url','social_twitter_url','social_linkedin_url',
            'social_youtube_url','social_tiktok_url',
            'theme_primary_color','theme_secondary_color','theme_background_color','theme_logo_path','theme_logo_dark_path',
            'theme_favicon_path','theme_dark_mode_enabled',
            'default_locale','seo_meta_title_ar','seo_meta_title_en','seo_meta_description_ar','seo_meta_description_en',
            'seo_og_image_path','maintenance_mode','registration_enabled','max_upload_size_kb','timezone',
            'contact_page_enabled','contact_recipient_email',
            'mail_notifications_enabled','mail_from_name','mail_from_address',
            'whatsapp_api_enabled','whatsapp_api_phone','whatsapp_api_token',
            'rfq_notify_via_whatsapp','rfq_notify_via_email','rfq_default_recipient_email',
            'ads_auto_rotate_enabled','ads_rotate_interval_sec',
            'catalog_card_image_rotate_interval_sec','catalog_show_out_of_stock',
            'categories_marquee_auto_move_enabled','categories_marquee_speed','categories_marquee_direction_strategy',
            'password_policy_min_length','password_policy_require_symbols',
            'analytics_enabled','analytics_google_tag_id',
            'ceo_name_ar','ceo_name_en',
        ]));

        $site->save();

        // إدارة شهادات المدير التنفيذي
        $titlesAr   = (array) $request->input('cert_title_ar', []);
        $titlesEn   = (array) $request->input('cert_title_en', []);
        $issuersAr  = (array) $request->input('cert_issuer_ar', []);
        $issuersEn  = (array) $request->input('cert_issuer_en', []);
        $issuedAts  = (array) $request->input('cert_issued_at', []);
        $sortOrders = (array) $request->input('cert_sort_order', []);
        $images     = (array) $request->file('cert_image', []);

        $site->ceoCertificates()->delete();

        $count = max(
            count($titlesAr), count($titlesEn), count($issuersAr),
            count($issuersEn), count($issuedAts), count($images), count($sortOrders)
        );

        for ($i = 0; $i < $count; $i++) {
            $imgPath = null;
            if (isset($images[$i]) && $images[$i] !== null) {
                $stored = $images[$i]->store('uploads/ceo/certificates', 'public');
                $imgPath = 'storage/'.$stored;
            }

            $hasAny = ($titlesAr[$i] ?? null) || ($titlesEn[$i] ?? null) || ($issuersAr[$i] ?? null) || ($issuersEn[$i] ?? null) || $imgPath;
            if (!$hasAny) continue;

            \App\Models\CeoCertificate::create([
                'site_setting_id' => $site->id,
                'image_path'      => $imgPath,
                'title_ar'        => $titlesAr[$i] ?? null,
                'title_en'        => $titlesEn[$i] ?? null,
                'issuer_ar'       => $issuersAr[$i] ?? null,
                'issuer_en'       => $issuersEn[$i] ?? null,
                'issued_at'       => $issuedAts[$i] ?? null,
                'sort_order'      => (int) ($sortOrders[$i] ?? ($i + 1)),
            ]);
        }

        return redirect()->back()->with('success', __('app.saved_success'));
    }
}
