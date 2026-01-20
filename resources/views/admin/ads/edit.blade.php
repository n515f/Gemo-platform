@extends('layouts.admin')
@push('styles') @vite('resources/css/entries/admin.css') @endpush

@section('content')
  <h1 class="page-title">{{ __('app.edit_ad') }}</h1>
  @include('components.flash')

  <form method="POST" action="{{ route('admin.ads.update',$ad) }}" enctype="multipart/form-data" class="form-card">
    @csrf @method('PUT')

    <div class="grid-2">
      <div>
        <label>{{ __('app.title_ar') }}</label>
        <input class="input" name="title_ar" value="{{ old('title_ar', $ad->title_ar) }}">
      </div>
      <div>
        <label>{{ __('app.title_en') }}</label>
        <input class="input" name="title_en" value="{{ old('title_en', $ad->title_en) }}">
      </div>
    </div>

    <div class="field">
      <label>{{ __('app.placement') }}</label>
      <select name="placement" class="input">
        <option value="">{{ __('app.placement_none') }}</option>
        <option value="home"       @selected(old('placement', $ad->placement ?? '')==='home')>{{ __('app.placement_home') }}</option>
        <option value="catalog"    @selected(old('placement', $ad->placement ?? '')==='catalog')>{{ __('app.placement_catalog') }}</option>
        <option value="categories" @selected(old('placement', $ad->placement ?? '')==='categories')>{{ __('app.placement_categories') }}</option>
        <option value="services"   @selected(old('placement', $ad->placement ?? '')==='services')>{{ __('app.placement_services') }}</option>
        <option value="rfq"        @selected(old('placement', $ad->placement ?? '')==='rfq')>{{ __('app.placement_rfq') }}</option>
        <option value="all"        @selected(old('placement', $ad->placement ?? '')==='all')>{{ __('app.placement_all') }}</option>
      </select>
    </div>

    <div class="grid-2">
      <div>
        <label>{{ __('app.short_desc_ar') }}</label>
        <textarea class="input" name="desc_ar" rows="3">{{ old('desc_ar', $ad->desc_ar) }}</textarea>
      </div>
      <div>
        <label>{{ __('app.short_desc_en') }}</label>
        <textarea class="input" name="desc_en" rows="3">{{ old('desc_en', $ad->desc_en) }}</textarea>
      </div>
    </div>

    <div class="grid-2">
      <div>
        <label>{{ __('app.location_title') }}</label>
        <input class="input" name="location_title" value="{{ old('location_title', $ad->location_title) }}">
      </div>
      <div class="row-start">
        <label>{{ __('app.status') }}</label>
        <label class="switch">
          <input type="checkbox" name="is_active" value="1" @checked(old('is_active',$ad->is_active))>
          <span>{{ __('app.active') }}</span>
        </label>
      </div>
    </div>

    {{-- الصور الحالية مع خيار الإبقاء --}}
    @if(!empty($images))
      <div>
        <label>{{ __('app.current_images_keep') }}</label>
        <div class="thumbs">
          @foreach($images as $img)
            <label class="thumb">
              <input type="checkbox" name="keep[]" value="{{ $img }}" checked>
              <img src="{{ asset($img) }}" alt="{{ __('app.ad_image') }}">
            </label>
          @endforeach
        </div>
      </div>
    @endif

    <div>
      <label>{{ __('app.add_new_images') }}</label>
      <input class="input" type="file" name="images[]" multiple accept=".jpg,.jpeg,.png,.webp">
    </div>

    <div class="row-end gap-6">
        <a class="btn btn--search btn--outline" href="{{ route('admin.ads.index') }}">
            <svg class="ico" viewBox="0 0 24 24">
                <path d="M19 12H5M12 19l-7-7 7-7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            {{ __('app.cancel') }}
        </a>
        <button class="btn btn--edit">
            <svg class="ico" viewBox="0 0 24 24">
                <path d="M14.5 3.5a2.5 2.5 0 0 1 3.5 3.5L8 17l-4 1 1-4 9.5-10.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
            {{ __('app.update') }}
        </button>
    </div>
  </form>
@endsection
