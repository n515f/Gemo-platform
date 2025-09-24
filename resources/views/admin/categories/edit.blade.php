@extends('layouts.site')
@push('styles') @vite('resources/css/entries/admin.css') @endpush

@section('content')
  <h1 class="page-title">تعديل فئة</h1>
  @include('components.flash')

  <form method="POST" action="{{ route('admin.categories.update',$category) }}" enctype="multipart/form-data" class="card form">
    @csrf @method('PUT')

    <div class="grid-2">
      <div>
        <label class="label">الاسم (عربي)</label>
        <input class="input" type="text" name="name_ar" value="{{ old('name_ar', $category->name_ar) }}" required>
      </div>
      <div>
        <label class="label">Name (English)</label>
        <input class="input" type="text" name="name_en" value="{{ old('name_en', $category->name_en) }}" required>
      </div>
    </div>

    <div class="grid-2">
      <div>
        <label class="label">الوصف (عربي)</label>
        <textarea class="input" name="description_ar" rows="4">{{ old('description_ar', $category->description_ar) }}</textarea>
      </div>
      <div>
        <label class="label">Description (English)</label>
        <textarea class="input" name="description_en" rows="4">{{ old('description_en', $category->description_en) }}</textarea>
      </div>
    </div>

    <div class="grid-2">
      <div>
        <label class="label">الأيقونة الحالية</label>
        @if($category->icon)
          <img src="{{ asset($category->icon) }}" alt="" style="max-width:120px;display:block;margin-bottom:8px">
          <label class="checkbox">
            <input type="checkbox" name="remove_icon" value="1"> إزالة الأيقونة
          </label>
        @else
          <div class="muted">لا توجد أيقونة</div>
        @endif
      </div>
      <div>
        <label class="label">أيقونة جديدة (اختياري)</label>
        <input class="input" type="file" name="icon" accept="image/*">
      </div>
    </div>

    <div class="grid-2">
      <div>
        <label class="label">الحالة</label>
        <select class="input" name="is_active">
          <option value="1" @selected($category->is_active)>فعّالة</option>
          <option value="0" @selected(!$category->is_active)>مؤرشفة</option>
        </select>
      </div>
    </div>

    <div class="row-end">
      <a class="btn" href="{{ route('admin.categories.index') }}">رجوع</a>
      <button class="btn btn-primary">تحديث</button>
    </div>
  </form>
@endsection