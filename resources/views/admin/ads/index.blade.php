@extends('layouts.admin')
@push('styles') @vite('resources/css/entries/admin.css') @endpush

@section('content')
  <h1 class="page-title">{{ __('app.ads') }}</h1>
  @include('components.flash')

  @php
    // نضمن وجود قيمة حتى لو لم يرسل الكنترولر المتغير لأي سبب
    $qInput      = request('q', $q ?? '');
    $activeInput = request()->query('active', $active ?? '');
    $activeStr   = is_null($activeInput) ? '' : (string) $activeInput;
  @endphp

  <div class="toolbar">
    <form method="get" class="inline">
      <input class="input" type="text" name="q" value="{{ $qInput }}" placeholder="{{ __('app.search_placeholder') }}" />
      <select class="input" name="active">
        <option value=""  @selected($activeStr==='')>{{ __('app.all') }}</option>
        <option value="1" @selected($activeStr==='1')>{{ __('app.active') }}</option>
        <option value="0" @selected($activeStr==='0')>{{ __('app.archived') }}</option>
      </select>
      <button class="btn">{{ __('app.search') }}</button>
    </form>
    <a class="btn btn-primary" href="{{ route('admin.ads.create') }}">{{ __('app.new_ad') }}</a>
  </div>

  <div class="grid-3">
    @forelse($rows as $ad)
      @php
        // images قد تكون JSON
        $imgs = is_array($ad->images) ? $ad->images : (json_decode($ad->images, true) ?: []);
        $firstImg = $imgs[0] ?? null;
      @endphp

      <div class="card">
        <div class="row-between">
          <strong>{{ $ad->title_ar ?? $ad->title_en ?? __('app.no_title') }}</strong>
          @if($ad->is_active)
            <span class="badge">{{ __('app.active') }}</span>
          @else
            <span class="badge soft">{{ __('app.archived') }}</span>
          @endif
        </div>

        @if($firstImg)
          <img class="cover" src="{{ asset($firstImg) }}" alt="{{ __('app.ad_image') }}">
        @endif

        <div class="muted">
          {{ \Illuminate\Support\Str::limit($ad->desc_ar ?? $ad->desc_en, 120) }}
        </div>

        @if($ad->location_title)
          <div class="small">📍 {{ $ad->location_title }}</div>
        @endif

        <div class="row-end gap-6 mt-8">
          <a class="btn" href="{{ route('admin.ads.edit', $ad) }}">{{ __('app.edit') }}</a>
<form method="POST"
      action="{{ route('admin.ads.destroy', $ad) }}"
      data-confirm="{{ __('app.confirm_delete_ad') }}"
      onsubmit="return confirm(this.dataset.confirm)">
            @csrf @method('DELETE')
            <button class="btn danger">{{ __('app.delete') }}</button>
          </form>
        </div>
      </div>
    @empty
      <div class="empty">{{ __('app.no_ads') }}</div>
    @endforelse
  </div>

  <div class="mt-12">{{ $rows->withQueryString()->links() }}</div>
@endsection
