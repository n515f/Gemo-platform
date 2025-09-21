<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'code' => 'PKG-220',
                'name_ar' => 'خط تعبئة وتغليف',
                'name_en' => 'Packaging & Filling Line',
                'short_desc_ar' => 'خط متكامل لتعبئة السوائل وتغليف العبوات.',
                'short_desc_en' => 'Integrated line for liquid filling and packaging.',
                'specs_ar' => [
                    'طاقة إنتاجية حتى 2000 عبوة/ساعة',
                    'ستانلس ستيل 304',
                    'تحكم PLC',
                ],
                'specs_en' => [
                    'Up to 2000 bottles/hour',
                    'Stainless steel 304',
                    'PLC control',
                ],
                'price' => null,
                'sort_order' => 1,
            ],
            [
                'code' => 'INJ-550',
                'name_ar' => 'ماكينة قولبة بلاستيك',
                'name_en' => 'Plastic Injection Machine',
                'short_desc_ar' => 'حقن بلاستيك بقدرة متوسطة مع ذراع روبوتي.',
                'short_desc_en' => 'Mid-capacity plastic injection with robotic arm.',
                'specs_ar' => [
                    'قوة قفل 180 طن',
                    'تحكم سيرفو',
                    'تبريد مائي',
                ],
                'specs_en' => [
                    'Clamp force 180T',
                    'Servo control',
                    'Water cooling',
                ],
                'price' => null,
                'sort_order' => 2,
            ],
            [
                'code' => 'BEV-310',
                'name_ar' => 'خط إنتاج عصائر',
                'name_en' => 'Beverage Production Line',
                'short_desc_ar' => 'غسل وتعقيم وتعبئة آلية كاملة.',
                'short_desc_en' => 'Full automatic washing, sterilizing and filling.',
                'specs_ar' => [
                    'CIP/SIP',
                    'شاشة HMI 10',
                    'سعة حتى 3000 لتر/ساعة',
                ],
                'specs_en' => [
                    'CIP/SIP',
                    '10" HMI',
                    'Up to 3000 L/h',
                ],
                'price' => null,
                'sort_order' => 3,
            ],
        ];

        foreach ($items as $it) {
            Product::updateOrCreate(
                ['code' => $it['code']],
                [
                    'slug'           => Str::slug($it['code'].'-'.$it['name_en']),
                    'name_ar'        => $it['name_ar'],
                    'name_en'        => $it['name_en'],
                    'short_desc_ar'  => $it['short_desc_ar'],
                    'short_desc_en'  => $it['short_desc_en'],
                    'specs_ar'       => $it['specs_ar'],
                    'specs_en'       => $it['specs_en'],
                    'price'          => $it['price'],
                    'is_active'      => true,
                    'sort_order'     => $it['sort_order'],
                ]
            );
        }
    }
}
