@extends('layouts.admin')
@push('styles') @vite('resources/css/entries/admin.css') @endpush

@section('content')
  <h1 class="page-title">{{ __('app.category_edit_title') }}</h1>
  @include('components.flash')

  <form method="POST" action="{{ route('admin.categories.update',$category) }}" enctype="multipart/form-data" class="card form">
    @csrf @method('PUT')

    <div class="grid-2">
      <div>
        <label class="label">{{ __('app.name_ar') }}</label>
        <input class="input" type="text" name="name_ar" value="{{ old('name_ar', $category->name_ar) }}" required>
      </div>
      <div>
        <label class="label">{{ __('app.name_en') }}</label>
        <input class="input" type="text" name="name_en" value="{{ old('name_en', $category->name_en) }}" required>
      </div>
    </div>

    <div class="grid-2">
      <div>
        <label class="label">{{ __('app.description_ar') }}</label>
        <textarea class="input" name="description_ar" rows="4">{{ old('description_ar', $category->description_ar) }}</textarea>
      </div>
      <div>
        <label class="label">{{ __('app.description_en') }}</label>
        <textarea class="input" name="description_en" rows="4">{{ old('description_en', $category->description_en) }}</textarea>
      </div>
    </div>

    <div class="grid-2">
      <div>
        <label class="label">{{ __('app.current_icon') }}</label>
        @if($category->icon)
          <img src="{{ asset($category->icon) }}" alt="" style="max-width:120px;display:block;margin-bottom:8px">
          <label class="checkbox">
            <input type="checkbox" name="remove_icon" value="1"> {{ __('app.remove_icon') }}
          </label>
        @else
          <div class="muted">{{ __('app.no_icon') }}</div>
        @endif
      </div>
      <div>
        <label class="label">{{ __('app.new_icon_optional') }}</label>
        <input class="input" type="file" name="icon" accept="image/*">
      </div>
    </div>

    <div class="grid-2">
      <div>
        <label class="label">{{ __('app.status') }}</label>
        <select class="input" name="is_active">
          <option value="1" @selected($category->is_active)>{{ __('app.active') }}</option>
          <option value="0" @selected(!$category->is_active)>{{ __('app.archived') }}</option>
        </select>
      </div>
    </div>

    <div class="row-end">
      <a class="btn btn--search btn--outline" href="{{ route('admin.categories.index') }}">
          <svg class="ico" viewBox="0 0 24 24">
              <path d="M19 12H5M12 19l-7-7 7-7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          {{ __('app.back') }}
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
