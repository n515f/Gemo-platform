@extends('layouts.admin')
@push('styles') @vite('resources/css/entries/admin.css') @endpush

@section('content')
  <h1 class="page-title">{{ __('app.category_create_title') }}</h1>
  @include('components.flash')

  <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="card form">
    @csrf

    <div class="grid-2">
      <div>
        <label class="label">{{ __('app.name_ar') }}</label>
        <input class="input" type="text" name="name_ar" value="{{ old('name_ar') }}" required>
      </div>
      <div>
        <label class="label">{{ __('app.name_en') }}</label>
        <input class="input" type="text" name="name_en" value="{{ old('name_en') }}" required>
      </div>
    </div>

    <div class="grid-2">
      <div>
        <label class="label">{{ __('app.description_ar') }}</label>
        <textarea class="input" name="description_ar" rows="4">{{ old('description_ar') }}</textarea>
      </div>
      <div>
        <label class="label">{{ __('app.description_en') }}</label>
        <textarea class="input" name="description_en" rows="4">{{ old('description_en') }}</textarea>
      </div>
    </div>

    <div class="grid-2">
      <div>
        <label class="label">{{ __('app.icon_optional') }}</label>
        <input class="input" type="file" name="icon" accept="image/*">
      </div>
      <div>
        <label class="label">{{ __('app.status') }}</label>
        <select class="input" name="is_active">
          <option value="1" selected>{{ __('app.active') }}</option>
          <option value="0">{{ __('app.archived') }}</option>
        </select>
      </div>
    </div>

    <div class="row-end">
      <a class="btn" href="{{ route('admin.categories.index') }}">{{ __('app.cancel') }}</a>
      <button class="btn btn-primary">{{ __('app.save') }}</button>
    </div>
  </form>
@endsection
