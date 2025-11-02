<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $table = 'site_settings';

    protected $fillable = [
        'company_name_ar','company_name_en','company_tagline_ar','company_tagline_en',
        'company_description_ar','company_description_en','company_address_ar','company_address_en',
        'company_phone','company_whatsapp_number','company_email','company_email_alt','company_website_url',
        'social_whatsapp_url','social_instagram_url','social_facebook_url','social_twitter_url','social_linkedin_url',
        'social_youtube_url','social_tiktok_url',
        'theme_primary_color','theme_secondary_color','theme_background_color','theme_logo_path','theme_logo_dark_path',
        'theme_favicon_path','theme_dark_mode_enabled',
        'default_locale','supported_locales_json','rtl_locales_json',
        'seo_meta_title_ar','seo_meta_title_en','seo_meta_description_ar','seo_meta_description_en','seo_og_image_path',
        'maintenance_mode','registration_enabled','max_upload_size_kb','timezone',
        'contact_page_enabled','contact_recipient_email',
        'mail_notifications_enabled','mail_from_name','mail_from_address',
        'whatsapp_api_enabled','whatsapp_api_phone','whatsapp_api_token',
        'rfq_notify_via_whatsapp','rfq_notify_via_email','rfq_default_recipient_email',
        'ads_auto_rotate_enabled','ads_rotate_interval_sec',
        'catalog_card_image_rotate_interval_sec','catalog_show_out_of_stock',
        'categories_marquee_auto_move_enabled','categories_marquee_speed','categories_marquee_direction_strategy',
        'password_policy_min_length','password_policy_require_symbols',
        'analytics_enabled','analytics_google_tag_id',
        'ceo_name_ar','ceo_name_en','ceo_image_path',
        'company_logo_path',
        'extra_json',
    ];

    protected $casts = [
        'theme_dark_mode_enabled'                => 'boolean',
        'supported_locales_json'                 => 'array',
        'rtl_locales_json'                       => 'array',
        'maintenance_mode'                       => 'boolean',
        'registration_enabled'                   => 'boolean',
        'contact_page_enabled'                   => 'boolean',
        'mail_notifications_enabled'             => 'boolean',
        'whatsapp_api_enabled'                   => 'boolean',
        'rfq_notify_via_whatsapp'                => 'boolean',
        'rfq_notify_via_email'                   => 'boolean',
        'ads_auto_rotate_enabled'                => 'boolean',
        'catalog_show_out_of_stock'              => 'boolean',
        'categories_marquee_auto_move_enabled'   => 'boolean',
        'password_policy_require_symbols'        => 'boolean',
        'analytics_enabled'                      => 'boolean',
        'extra_json'                             => 'array',
    ];

    public function ceoCertificates()
    {
        return $this->hasMany(CeoCertificate::class, 'site_setting_id');
    }
}