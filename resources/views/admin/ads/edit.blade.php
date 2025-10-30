@extends('layouts.admin')
@push('styles') @vite('resources/css/entries/admin.css') @endpush

@section('content')
  <h1 class="page-title">تعديل إعلان</h1>
  @include('components.flash')

  <form method="POST" action="{{ route('admin.ads.update',$ad) }}" enctype="multipart/form-data" class="form-card">
    @csrf @method('PUT')

    <div class="grid-2">
      <div>
        <label>العنوان (AR)</label>
        <input class="input" name="title_ar" value="{{ old('title_ar', $ad->title_ar) }}">
      </div>
      <div>
        <label>Title (EN)</label>
        <input class="input" name="title_en" value="{{ old('title_en', $ad->title_en) }}">
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
        <textarea class="input" name="desc_ar" rows="3">{{ old('desc_ar', $ad->desc_ar) }}</textarea>
      </div>
      <div>
        <label>Short Description (EN)</label>
        <textarea class="input" name="desc_en" rows="3">{{ old('desc_en', $ad->desc_en) }}</textarea>
      </div>
    </div>

    <div class="grid-2">
      <div>
        <label>عنوان المكان</label>
        <input class="input" name="location_title" value="{{ old('location_title', $ad->location_title) }}">
      </div>
      <div class="row-start">
        <label>الحالة</label>
        <label class="switch">
          <input type="checkbox" name="is_active" value="1" @checked(old('is_active',$ad->is_active))>
          <span>فعّال</span>
        </label>
      </div>
    </div>

    {{-- الصور الحالية مع خيار الإبقاء --}}
    @if(!empty($images))
      <div>
        <label>الصور الحالية (علِّم للإبقاء عليها)</label>
        <div class="thumbs">
          @foreach($images as $img)
            <label class="thumb">
              <input type="checkbox" name="keep[]" value="{{ $img }}" checked>
              <img src="{{ asset($img) }}" alt="">
            </label>
          @endforeach
        </div>
      </div>
    @endif

    <div>
      <label>إضافة صور جديدة</label>
      <input class="input" type="file" name="images[]" multiple accept=".jpg,.jpeg,.png,.webp">
    </div>

    <div class="row-end gap-6">
      <a class="btn" href="{{ route('admin.ads.index') }}">إلغاء</a>
      <button class="btn btn-primary">تحديث</button>
    </div>
  </form>
@endsection