{{-- إضافة منتج --}}
@extends('layouts.site')

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
<section class="admin-page">
  <header class="page-head">
    <div class="lhs">
      <h1 class="title">{{ __('إضافة منتج') }}</h1>
      <p class="muted">{{ __('أدخل معلومات المنتج ثم احفظ.') }}</p>
    </div>
    <div class="rhs">
      <a class="btn btn-light" href="{{ route('admin.products.index') }}">{{ __('رجوع للقائمة') }}</a>
    </div>
  </header>

  @if ($errors->any())
    <div class="flash warn">
      <ul>@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="card form-card">
    @csrf

    <div class="grid-2">
      {{-- الاسم --}}
      <div class="field">
        <label>{{ __('الاسم (عربي)') }}</label>
        <input type="text" name="name_ar" value="{{ old('name_ar') }}" required>
      </div>

      <div class="field">
        <label>{{ __('الاسم (English)') }}</label>
        <input type="text" name="name_en" value="{{ old('name_en') }}">
      </div>

      {{-- الكود والسُّـلَغ --}}
      <div class="field">
        <label>{{ __('الكود (code)') }}</label>
        <input type="text" name="code" value="{{ old('code') }}" placeholder="مثال: PRD-001">
        <small class="muted">{{ __('اتركه فارغًا لتوليد تلقائي') }}</small>
      </div>

      <div class="field">
        <label>{{ __('المعرف النصي (slug)') }}</label>
        <input type="text" name="slug" value="{{ old('slug') }}" placeholder="مثال: packing-line-pro">
        <small class="muted">{{ __('اتركه فارغًا لتوليد تلقائي') }}</small>
      </div>

      {{-- السعر والحالة والترتيب --}}
      <div class="field">
        <label>{{ __('السعر') }}</label>
        <input type="number" step="0.01" name="price" value="{{ old('price') }}">
      </div>

      <div class="field">
        <label class="block">{{ __('الحالة') }}</label>
        <label class="switch">
          <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
          <span>{{ __('مفعل؟') }}</span>
        </label>
      </div>

      <div class="field">
        <label>{{ __('ترتيب العرض') }}</label>
        <input type="number" min="0" name="sort_order" value="{{ old('sort_order', 0) }}">
      </div>

      {{-- الفئات (متعددة) --}}
      <div class="field wide">
        <label>{{ __('الفئات') }}</label>
        <select name="category_ids[]" class="input" multiple size="6">
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ app()->getLocale()==='ar' ? $cat->name_ar : ($cat->name_en ?: $cat->name_ar) }}</option>
          @endforeach
        </select>
        <small class="muted">{{ __('يمكن اختيار أكثر من فئة (CTRL / ⌘)') }}</small>
      </div>

      {{-- موجز المواصفات والوصف المختصر --}}
      <div class="field wide">
        <label>{{ __('وصف مختصر (عربي)') }}</label>
        <textarea name="short_desc_ar" rows="3">{{ old('short_desc_ar') }}</textarea>
      </div>

      <div class="field wide">
        <label>{{ __('وصف مختصر (EN)') }}</label>
        <textarea name="short_desc_en" rows="3">{{ old('short_desc_en') }}</textarea>
      </div>

      {{-- مواصفات تفصيلية (يمكن تنسيقها كنص حر) --}}
      <div class="field wide">
        <label>{{ __('مواصفات تفصيلية (عربي)') }}</label>
        <textarea name="specs_ar" rows="6">{{ old('specs_ar') }}</textarea>
      </div>

      <div class="field wide">
        <label>{{ __('مواصفات تفصيلية (EN)') }}</label>
        <textarea name="specs_en" rows="6">{{ old('specs_en') }}</textarea>
      </div>

      {{-- صور متعددة --}}
      <div class="field wide">
        <label>{{ __('صور المنتج') }}</label>
        <input type="file" name="images[]" accept="image/*" multiple>
        <small class="muted">{{ __('يمكن رفع عدة صور (JPG/PNG/WebP, حتى 4MB للصورة)') }}</small>
      </div>
    </div>

    <div class="form-actions">
      <button class="btn btn-primary" type="submit">{{ __('حفظ') }}</button>
      <button class="btn btn-soft" type="submit" name="cancel" value="1">{{ __('إلغاء') }}</button>
    </div>
  </form>
</section>
@endsection