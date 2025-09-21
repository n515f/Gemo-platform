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
      <ul>
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="card form-card" x-data="{ preview: null }">
    @csrf

    <div class="grid-2">
      <div class="field">
        <label>{{ __('الاسم (عربي)') }}</label>
        <input type="text" name="name_ar" value="{{ old('name_ar') }}" required>
      </div>

      <div class="field">
        <label>{{ __('الاسم (English)') }}</label>
        <input type="text" name="name_en" value="{{ old('name_en') }}">
      </div>

      <div class="field">
        <label>{{ __('الكود (SKU)') }}</label>
        <input type="text" name="sku" value="{{ old('sku') }}">
      </div>

      <div class="field">
        <label>{{ __('السعر') }}</label>
        <input type="number" step="0.01" name="price" value="{{ old('price') }}">
      </div>

      <div class="field">
        <label>{{ __('الحالة') }}</label>
        <select name="status">
          <option value="active">{{ __('نشط') }}</option>
          <option value="hidden">{{ __('مخفي') }}</option>
          <option value="draft">{{ __('مسودة') }}</option>
        </select>
      </div>

      <div class="field">
        <label>{{ __('الصورة') }}</label>
        <input type="file" name="image" accept="image/*" @change="preview = URL.createObjectURL($event.target.files[0])">
        <div class="img-preview" x-show="preview"><img :src="preview" alt=""></div>
      </div>

      <div class="field wide">
        <label>{{ __('الوصف (عربي)') }}</label>
        <textarea name="description_ar" rows="4">{{ old('description_ar') }}</textarea>
      </div>

      <div class="field wide">
        <label>{{ __('الوصف (English)') }}</label>
        <textarea name="description_en" rows="4">{{ old('description_en') }}</textarea>
      </div>
    </div>

    <div class="form-actions">
      <button class="btn btn-primary" type="submit">{{ __('حفظ') }}</button>
      <a class="btn btn-soft" href="{{ route('admin.products.index') }}">{{ __('إلغاء') }}</a>
    </div>
  </form>
</section>
@endsection