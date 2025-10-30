@extends('layouts.admin')
@push('styles') @vite('resources/css/entries/admin.css') @endpush

@section('content')
  <h1 class="page-title">إعلان جديد</h1>
  @include('components.flash')

  <form method="POST" action="{{ route('admin.ads.store') }}" enctype="multipart/form-data" class="form-card">
    @csrf

    <div class="grid-2">
      <div>
        <label>العنوان (AR)</label>
        <input class="input" name="title_ar" value="{{ old('title_ar') }}">
      </div>
      <div>
        <label>Title (EN)</label>
        <input class="input" name="title_en" value="{{ old('title_en') }}">
      </div>
    </div>
    <div class="field">
  <label>مكان الظهور</label>
  <select name="placement" class="input">
    <option value="">— بدون تحديد —</option>
    <option value="home"    @selected(old('placement', $ad->placement ?? '')==='home')>الصفحة الرئيسية</option>
    <option value="catalog" @selected(old('placement', $ad->placement ?? '')==='catalog')>الكتالوج</option>
    <option value="categories" @selected(old('placement', $ad->placement ?? '')==='categories')>الفئات</option>
    <option value="services"@selected(old('placement', $ad->placement ?? '')==='services')>خدماتنا</option>
    <option value="rfq"     @selected(old('placement', $ad->placement ?? '')==='rfq')>طلب عرض سعر</option>
    <option value="all"     @selected(old('placement', $ad->placement ?? '')==='all')>كل الصفحات</option>
  </select>
</div>
    <div class="grid-2">
      <div>
        <label>وصف قصير (AR)</label>
        <textarea class="input" name="desc_ar" rows="3">{{ old('desc_ar') }}</textarea>
      </div>
      <div>
        <label>Short Description (EN)</label>
        <textarea class="input" name="desc_en" rows="3">{{ old('desc_en') }}</textarea>
      </div>
    </div>

    <div class="grid-2">
      <div>
        <label>عنوان المكان (اختياري)</label>
        <input class="input" name="location_title" value="{{ old('location_title') }}">
      </div>
      <div class="row-start">
        <label>الحالة</label>
        <label class="switch">
          <input type="checkbox" name="is_active" value="1" checked>
          <span>فعّال</span>
        </label>
      </div>
    </div>

    <div>
      <label>صور الإعلان (يمكن اختيار عدة صور)</label>
      <input class="input" type="file" name="images[]" multiple accept=".jpg,.jpeg,.png,.webp">
      <p class="small muted">حتى 8MB للصورة الواحدة.</p>
    </div>

    <div class="row-end gap-6">
      <a class="btn" href="{{ route('admin.ads.index') }}">إلغاء</a>
      <button class="btn btn-primary">حفظ</button>
    </div>
  </form>
@endsection