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
{{-- ===== إعلانات الفئات ===== --}}
@if(($categoryAds ?? collect())->count())
  <section class="svc-section reveal">
    <div class="sec-head">
      <h2>{{ app()->getLocale()==='ar' ? 'إعلانات الفئات' : 'Categories Ads' }}</h2>
    </div>

    <div class="ads-rotator" data-interval-sec="20" data-fade="500" data-fit="cover">
      @foreach($categoryAds as $ad)
        @php
          $imgs  = is_string($ad->images) ? json_decode($ad->images, true) : ($ad->images ?? []);
          $imgs  = is_array($imgs) ? $imgs : [];
          $first = $imgs[0] ?? 'images/services/full-line.jpg';
        @endphp

        <article class="ad" data-images='@json($imgs, JSON_UNESCAPED_UNICODE)'>
          <div class="ad-visual">
            <img src="{{ asset($first) }}" alt="">
          </div>
          <div class="ad-overlay">
            @if($ad->title_ar || $ad->title_en)
              <h3 class="ad-title">
                {{ app()->getLocale()==='ar' ? ($ad->title_ar ?: $ad->title_en) : ($ad->title_en ?: $ad->title_ar) }}
              </h3>
            @endif

            @if($ad->desc_ar || $ad->desc_en)
              <p class="ad-desc">
                {{ app()->getLocale()==='ar' ? ($ad->desc_ar ?: $ad->desc_en) : ($ad->desc_en ?: $ad->desc_ar) }}
              </p>
            @endif

            @if($ad->location_title)
              <div class="ad-loc">{{ $ad->location_title }}</div>
            @endif
          </div>
        </article>
      @endforeach
    </div>
  </section>
@endif
  {{-- ===== فئات الخدمات ===== --}}
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