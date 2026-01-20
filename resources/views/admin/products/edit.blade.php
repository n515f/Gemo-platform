{{-- resources/views/admin/products/edit.blade.php --}}
{{-- تعديل منتج --}}
@extends('layouts.admin')

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
<section class="admin-page">
  <header class="page-head">
    <div class="lhs">
      <h1 class="title">{{ __('app.product_edit_title') }}</h1>
      <p class="muted">{{ $product->name_ar ?? $product->name_en ?? '' }}</p>
    </div>
    <div class="rhs">
      <a class="btn btn-light" href="{{ route('admin.products.index') }}">{{ __('app.back_to_list') }}</a>
    </div>
  </header>

  @if ($errors->any())
    <div class="flash warn">
      <ul>@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="card form-card">
    @csrf @method('PUT')

    <div class="grid-2">
      {{-- الاسم --}}
      <div class="field">
        <label>{{ __('app.name_ar') }}</label>
        <input type="text" name="name_ar" value="{{ old('name_ar', $product->name_ar) }}" required>
      </div>

      <div class="field">
        <label>{{ __('app.name_en') }}</label>
        <input type="text" name="name_en" value="{{ old('name_en', $product->name_en) }}">
      </div>

      {{-- الكود والسُّلغ --}}
      <div class="field">
        <label>{{ __('app.code') }}</label>
        <input type="text" name="code" value="{{ old('code', $product->code) }}">
        <small class="muted">{{ __('app.leave_blank_auto') }}</small>
      </div>

      <div class="field">
        <label>{{ __('app.slug') }}</label>
        <input type="text" name="slug" value="{{ old('slug', $product->slug) }}">
        <small class="muted">{{ __('app.leave_blank_auto') }}</small>
      </div>

      {{-- السعر والحالة والترتيب --}}
      <div class="field">
        <label>{{ __('app.price') }}</label>
        <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}">
      </div>

      <div class="field">
        <label class="block">{{ __('app.status') }}</label>
        @php $active = old('is_active', $product->is_active) ? 1 : 0; @endphp
        <label class="switch">
          <input type="checkbox" name="is_active" value="1" {{ $active ? 'checked' : '' }}>
          <span>{{ __('app.enabled_question') }}</span>
        </label>
      </div>

      <div class="field">
        <label>{{ __('app.sort_order') }}</label>
        <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $product->sort_order ?? 0) }}">
      </div>

      {{-- الفئات (متعددة) --}}
      <div class="field wide">
        <label>{{ __('app.categories') }}</label>
        <select name="category_ids[]" class="input" multiple size="6">
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}"
              @selected(in_array($cat->id, $selectedIds ?? []))>
              {{ app()->getLocale()==='ar' ? $cat->name_ar : ($cat->name_en ?: $cat->name_ar) }}
            </option>
          @endforeach
        </select>
        <small class="muted">{{ __('app.multi_select_hint') }}</small>
      </div>

      {{-- وصف مختصر --}}
      <div class="field wide">
        <label>{{ __('app.short_desc_ar') }}</label>
        <textarea name="short_desc_ar" rows="3">{{ old('short_desc_ar', $product->short_desc_ar) }}</textarea>
      </div>

      <div class="field wide">
        <label>{{ __('app.short_desc_en') }}</label>
        <textarea name="short_desc_en" rows="3">{{ old('short_desc_en', $product->short_desc_en) }}</textarea>
      </div>

      {{-- مواصفات تفصيلية --}}
      <div class="field wide">
        <label>{{ __('app.specs_ar') }}</label>
        <textarea name="specs_ar" rows="6">{{ old('specs_ar', $product->specs_ar) }}</textarea>
      </div>

      <div class="field wide">
        <label>{{ __('app.specs_en') }}</label>
        <textarea name="specs_en" rows="6">{{ old('specs_en', $product->specs_en) }}</textarea>
      </div>

      {{-- صور جديدة --}}
      <div class="field wide">
        <label>{{ __('app.add_new_images') }}</label>
        <input type="file" name="images[]" accept="image/*" multiple>
      </div>

      {{-- الصور الحالية + حذف --}}
      @if($product->images->count())
        <div class="field wide">
          <label>{{ __('app.current_images') }}</label>
          <div class="thumbs-grid">
            @foreach($product->images as $img)
              <label class="thumb-item">
                <img src="{{ asset($img->path) }}" alt="">
                <input type="checkbox" name="remove_images[]" value="{{ $img->id }}">
                <span>{{ __('app.delete_q') }}</span>
              </label>
            @endforeach
          </div>
          <small class="muted">{{ __('app.delete_hint_images') }}</small>
        </div>
      @endif
    </div>

    <div class="form-actions">
      <button class="btn btn-primary" type="submit">{{ __('app.update') }}</button>
      <button class="btn btn-danger" type="submit" name="cancel" value="1">{{ __('app.cancel') }}</button>
    </div>
  </form>
</section>
@endsection
