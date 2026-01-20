{{-- resources/views/admin/products/create.blade.php --}}
{{-- إضافة منتج --}}
@extends('layouts.admin')

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
<section class="admin-page">
  <header class="page-head">
    <div class="lhs">
      <h1 class="title">{{ __('app.product_create_title') }}</h1>
      <p class="muted">{{ __('app.product_create_subtitle') }}</p>
    </div>
    <div class="rhs">
      <a class="btn btn-light" href="{{ route('admin.products.index') }}">{{ __('app.back_to_list') }}</a>
    </div>
  </header>

  @if (session('success'))
    <div class="flash success">
      {{ session('success') }}
    </div>
  @endif

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
        <label>{{ __('app.name_ar') }}</label>
        <input type="text" name="name_ar" value="{{ old('name_ar') }}" required>
      </div>

      <div class="field">
        <label>{{ __('app.name_en') }}</label>
        <input type="text" name="name_en" value="{{ old('name_en') }}">
      </div>

      {{-- الكود والسُّـلَغ --}}
      <div class="field">
        <label>{{ __('app.code') }}</label>
        <input type="text" name="code" value="{{ old('code') }}" placeholder="{{ __('app.example_code') }}">
        <small class="muted">{{ __('app.leave_blank_auto') }}</small>
      </div>

      <div class="field">
        <label>{{ __('app.slug') }}</label>
        <input type="text" name="slug" value="{{ old('slug') }}" placeholder="{{ __('app.example_slug') }}">
        <small class="muted">{{ __('app.leave_blank_auto') }}</small>
      </div>

      {{-- السعر والحالة والترتيب --}}
      <div class="field">
        <label>{{ __('app.price') }}</label>
        <input type="number" step="0.01" name="price" value="{{ old('price') }}">
      </div>

      <div class="field">
        <label class="block">{{ __('app.status') }}</label>
        <label class="switch">
          <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
          <span>{{ __('app.enabled_question') }}</span>
        </label>
      </div>

      <div class="field">
        <label>{{ __('app.sort_order') }}</label>
        <input type="number" min="0" name="sort_order" value="{{ old('sort_order', 0) }}">
      </div>

      {{-- الفئات (متعددة) --}}
      <div class="field wide">
        <label>{{ __('app.categories') }}</label>
        <select name="category_ids[]" class="input" multiple size="6">
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ app()->getLocale()==='ar' ? $cat->name_ar : ($cat->name_en ?: $cat->name_ar) }}</option>
          @endforeach
        </select>
        <small class="muted">{{ __('app.multi_select_hint') }}</small>
      </div>

      {{-- موجز المواصفات والوصف المختصر --}}
      <div class="field wide">
        <label>{{ __('app.short_desc_ar') }}</label>
        <textarea name="short_desc_ar" rows="3">{{ old('short_desc_ar') }}</textarea>
      </div>

      <div class="field wide">
        <label>{{ __('app.short_desc_en') }}</label>
        <textarea name="short_desc_en" rows="3">{{ old('short_desc_en') }}</textarea>
      </div>

      {{-- مواصفات تفصيلية --}}
      <div class="field wide">
        <label>{{ __('app.specs_ar') }}</label>
        <textarea name="specs_ar" rows="6">{{ old('specs_ar') }}</textarea>
      </div>

      <div class="field wide">
        <label>{{ __('app.specs_en') }}</label>
        <textarea name="specs_en" rows="6">{{ old('specs_en') }}</textarea>
      </div>

      {{-- صور متعددة --}}
      <div class="field wide">
        <label>{{ __('app.product_images') }}</label>
        <input type="file" name="images[]" accept="image/*" multiple>
        <small class="muted">{{ __('app.images_hint') }}</small>
      </div>
    </div>

    <div class="form-actions">
      <button class="btn btn-primary" type="submit">{{ __('app.save') }}</button>
      <button class="btn btn-soft" type="submit" name="cancel" value="1">{{ __('app.cancel') }}</button>
    </div>
  </form>
</section>
@endsection
