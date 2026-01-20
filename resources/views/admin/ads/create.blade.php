@extends('layouts.admin')
@push('styles') @vite('resources/css/entries/admin.css') @endpush

@section('content')
  <h1 class="page-title">{{ __('app.ads_create_title') }}</h1>
  @include('components.flash')

  <form method="POST" action="{{ route('admin.ads.store') }}" enctype="multipart/form-data" class="form-card">
    @csrf

    <div class="grid-2">
      <div>
        <label>{{ __('app.title_ar') }}</label>
        <input class="input" name="title_ar" value="{{ old('title_ar') }}">
      </div>
      <div>
        <label>{{ __('app.title_en') }}</label>
        <input class="input" name="title_en" value="{{ old('title_en') }}">
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
        <textarea class="input" name="desc_ar" rows="3">{{ old('desc_ar') }}</textarea>
      </div>
      <div>
        <label>{{ __('app.short_desc_en') }}</label>
        <textarea class="input" name="desc_en" rows="3">{{ old('desc_en') }}</textarea>
      </div>
    </div>

    <div class="grid-2">
      <div>
        <label>{{ __('app.location_title_optional') }}</label>
        <input class="input" name="location_title" value="{{ old('location_title') }}">
      </div>
      <div class="row-start">
        <label>{{ __('app.status') }}</label>
        <label class="switch">
          <input type="checkbox" name="is_active" value="1" checked>
          <span>{{ __('app.active') }}</span>
        </label>
      </div>
    </div>

    <div>
      <label>{{ __('app.ad_images_label') }}</label>
      <input class="input" type="file" name="images[]" multiple accept=".jpg,.jpeg,.png,.webp">
      <p class="small muted">{{ __('app.ad_images_hint', ['size' => '8MB']) }}</p>
    </div>

    <div class="row-end gap-6">
      <a class="btn" href="{{ route('admin.ads.index') }}">{{ __('app.cancel') }}</a>
      <button class="btn btn-primary">{{ __('app.save') }}</button>
    </div>
  </form>
@endsection
