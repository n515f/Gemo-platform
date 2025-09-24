@extends('layouts.site')
@push('styles') @vite('resources/css/entries/site.css') @endpush

@section('content')
  <section class="svc-hero reveal">
    <div class="shell">
      <div class="svc-hero__head">
        <img class="svc-hero__icon" src="{{ asset('images/icons/catalog.png') }}" alt="">
        <div>
          <h1 class="svc-hero__title">{{ __('app.sectors_we_serve') }}</h1>
          <p class="svc-hero__sub">{{ __('app.sectors_hero_text') }}</p>
        </div>
      </div>
    </div>
  </section>

  <section class="svc-section reveal">
    <div class="sec-head"><h2 class="sec-title">{{ __('app.categories') }}</h2></div>

    <div class="services-grid">
      @forelse($categories as $cat)
        <a class="svc-card reveal" href="{{ route('categories.show', $cat) }}">
          @if($cat->icon)
            <img src="{{ asset($cat->icon) }}" alt="" class="ico">
          @else
            <img src="{{ asset('images/services/service.png') }}" alt="" class="ico">
          @endif
          <h3>{{ app()->getLocale() === 'ar' ? $cat->name_ar : $cat->name_en }}</h3>
          <p>{{ app()->getLocale() === 'ar' ? ($cat->description_ar ?? '') : ($cat->description_en ?? '') }}</p>
        </a>
      @empty
        <div class="empty">{{ __('app.no_items') }}</div>
      @endforelse
    </div>

    <div class="mt-12">{{ $categories->links() }}</div>
  </section>
@endsection