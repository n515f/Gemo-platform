@extends('layouts.admin')
@push('styles') @vite('resources/css/entries/admin.css') @endpush

@section('content')
  <h1 class="page-title">فئة جديدة</h1>
  @include('components.flash')

  <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="card form">
    @csrf

    <div class="grid-2">
      <div>
        <label class="label">الاسم (عربي)</label>
        <input class="input" type="text" name="name_ar" value="{{ old('name_ar') }}" required>
      </div>
      <div>
        <label class="label">Name (English)</label>
        <input class="input" type="text" name="name_en" value="{{ old('name_en') }}" required>
      </div>
    </div>

    <div class="grid-2">
      <div>
        <label class="label">الوصف (عربي)</label>
        <textarea class="input" name="description_ar" rows="4">{{ old('description_ar') }}</textarea>
      </div>
      <div>
        <label class="label">Description (English)</label>
        <textarea class="input" name="description_en" rows="4">{{ old('description_en') }}</textarea>
      </div>
    </div>

    <div class="grid-2">
      <div>
        <label class="label">الأيقونة (اختياري)</label>
        <input class="input" type="file" name="icon" accept="image/*">
      </div>
      <div>
        <label class="label">الحالة</label>
        <select class="input" name="is_active">
          <option value="1" selected>فعّالة</option>
          <option value="0">مؤرشفة</option>
        </select>
      </div>
    </div>

    <div class="row-end">
      <a class="btn" href="{{ route('admin.categories.index') }}">إلغاء</a>
      <button class="btn btn-primary">حفظ</button>
    </div>
  </form>
@endsection