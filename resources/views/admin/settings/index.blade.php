@extends('layouts.admin')
@section('title', 'إعدادات الموقع')

@push('styles')
  {{-- ادخل CSS العام + صفحة الداشبورد --}}
  @vite([
    'resources/css/entries/admin.css'
  ])
@endpush

@section('content')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">إعدادات الموقع</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.updateAll') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- اسم الشركة --}}
            <div>
                <label class="block text-sm font-medium mb-1">اسم الشركة (عربي)</label>
                <input type="text" name="company.name_ar" value="{{ $settings['company.name_ar'] ?? '' }}" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">اسم الشركة (إنجليزي)</label>
                <input type="text" name="company.name_en" value="{{ $settings['company.name_en'] ?? '' }}" class="w-full border rounded px-3 py-2">
            </div>

            {{-- البريد الإلكتروني --}}
            <div>
                <label class="block text-sm font-medium mb-1">البريد الإلكتروني</label>
                <input type="email" name="company.email" value="{{ $settings['company.email'] ?? '' }}" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">البريد الإلكتروني البديل</label>
                <input type="email" name="company.email_alt" value="{{ $settings['company.email_alt'] ?? '' }}" class="w-full border rounded px-3 py-2">
            </div>

            {{-- الهاتف والعنوان --}}
            <div>
                <label class="block text-sm font-medium mb-1">رقم الهاتف</label>
                <input type="text" name="company.phone" value="{{ $settings['company.phone'] ?? '' }}" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">العنوان</label>
                <input type="text" name="company.address_ar" value="{{ $settings['company.address_ar'] ?? '' }}" class="w-full border rounded px-3 py-2">
            </div>

            {{-- الروابط الاجتماعية --}}
            <div>
                <label class="block text-sm font-medium mb-1">واتساب</label>
                <input type="text" name="social.whatsapp" value="{{ $settings['social.whatsapp'] ?? '' }}" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">انستقرام</label>
                <input type="text" name="social.instagram" value="{{ $settings['social.instagram'] ?? '' }}" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">فيسبوك</label>
                <input type="text" name="social.facebook" value="{{ $settings['social.facebook'] ?? '' }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div class="text-end mt-6">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">حفظ التغييرات</button>
        </div>
    </form>
</div>
@endsection
