{{-- resources/views/admin/categories/index.blade.php --}}
@extends('layouts.admin')

@push('styles')
  @vite('resources/css/entries/admin.css')
@endpush

@section('content')
  <h1 class="page-title">{{ __('app.categories') }}</h1>
  @include('components.flash')

  <div class="toolbar">
    <form method="get" class="inline">
      <input class="input" type="text" name="q" value="{{ $q ?? '' }}" placeholder="{{ __('app.search_placeholder') }}" />
      <select class="input" name="active">
        <option value="">{{ __('app.all') }}</option>
        <option value="1" @selected(($active ?? '')==='1' || ($active ?? null)===1)>{{ __('app.active') }}</option>
        <option value="0" @selected(($active ?? '')==='0' || ($active ?? null)===0)>{{ __('app.archived') }}</option>
      </select>
      <button class="btn btn--search" type="submit">
        <svg class="ico" viewBox="0 0 24 24">
          <circle cx="11" cy="11" r="8" fill="none" stroke="currentColor" stroke-width="2"/>
          <path d="m21 21-4.35-4.35" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        {{ __('app.search') }}
      </button>
    </form>

    <a class="btn btn--add" href="{{ route('admin.categories.create') }}">
      <svg class="ico" viewBox="0 0 24 24">
        <path d="M12 5v14M5 12h14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      {{ __('app.new_category') }}
    </a>
  </div>

  <div class="grid-3">
    @forelse($rows as $cat)
      <div class="card">
        <div class="row-between">
          <strong>{{ $cat->name_ar }} / {{ $cat->name_en }}</strong>
          @if($cat->is_active)
            <span class="badge">{{ __('app.active') }}</span>
          @else
            <span class="badge soft">{{ __('app.archived') }}</span>
          @endif
        </div>

        @if($cat->icon)
          <div class="cat-icon-box" role="img" aria-label="{{ __('app.category_icon') }}">
            <img class="cat-icon" src="{{ asset($cat->icon) }}" alt="">
          </div>
        @endif

        <div class="muted">
          {{ \Illuminate\Support\Str::limit($cat->description_ar ?: $cat->description_en, 120) }}
        </div>

        <div class="row-end gap-6 mt-8">
          <a class="btn btn--edit btn--sm" href="{{ route('admin.categories.edit', $cat) }}">
            <svg class="ico" viewBox="0 0 24 24">
              <path d="M14.5 3.5a2.5 2.5 0 0 1 3.5 3.5L8 17l-4 1 1-4 9.5-10.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
            {{ __('app.edit') }}
          </a>

          <form method="POST"
      action="{{ route('admin.categories.destroy', $cat) }}"
      data-confirm="{{ __('app.confirm_delete_category') }}"
      onsubmit="return confirm(this.dataset.confirm)">

            @csrf @method('DELETE')
            <button class="btn btn--delete btn--sm" type="submit">
              <svg class="ico" viewBox="0 0 24 24">
                <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6M10 11v6M14 11v6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              {{ __('app.delete') }}
            </button>
          </form>
        </div>
      </div>
    @empty
      <div class="empty">{{ __('app.no_categories') }}</div>
    @endforelse
  </div>

  <div class="mt-12">
    {{ $rows->withQueryString()->links() }}
  </div>
@endsection
