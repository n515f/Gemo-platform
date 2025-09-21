<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'company.name_ar',     'value' => 'شركة عادل سعيد للتجارة والصناعة'],
            ['key' => 'company.name_en',     'value' => 'Adel Saeed Trading & Industry'],
            ['key' => 'company.tagline_ar',  'value' => 'توريد وتركيب خطوط إنتاج ومعدات صناعية'],
            ['key' => 'company.tagline_en',  'value' => 'Supply & Installation of Production Lines'],
            ['key' => 'company.email',       'value' => 'Adelsk2002@gmail.com'],
            ['key' => 'company.email_alt',   'value' => 'Adelsk2002@yahoo.com'],
            ['key' => 'company.phone',       'value' => '+968-0000-0000'],
            ['key' => 'company.address_ar',  'value' => 'مسقط – سلطنة عُمان (الترخيص من مسقط)'],
            ['key' => 'company.address_en',  'value' => 'Muscat – Sultanate of Oman'],

            // السوشيال
            ['key' => 'social.whatsapp',     'value' => 'https://wa.me/96800000000'],
            ['key' => 'social.instagram',    'value' => 'https://instagram.com/yourpage'],

            // واجهة
            ['key' => 'ui.primary_color',    'value' => '#0ea5e9'], // أزرق
            ['key' => 'ui.dark_mode_default','value' => 'true'],
        ];

        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], ['value' => $s['value']]);
        }
    }
}
