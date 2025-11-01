@extends('layouts.site')

@section('content')
<section class="svc-section reveal">

  {{-- ===== رأس الصفحة + شريط البحث ===== --}}
  <div class="sec-head">
    <h2 class="sec-title">{{ __('app.categories') }}</h2>
    <form class="cat-search" method="GET" action="{{ route('categories.index') }}">
      <input type="search" name="q" value="{{ $q ?? '' }}"
        placeholder="{{ app()->getLocale()==='ar' ? 'ابحث عن فئة...' : 'Search categories...' }}">
      @if(!empty($q))
        <a class="clear" href="{{ route('categories.index') }}" aria-label="Clear">×</a>
      @endif
      <button class="btn-search" type="submit">
        {{ app()->getLocale()==='ar' ? 'بحث' : 'Search' }}
      </button>
    </form>
  </div>

  @php
    $list = $allCategories ?? collect();
  @endphp

  {{-- ===== الشريط الأول ===== --}}
  @if($list->count())
    <div class="cat-marquee dir-right" style="--dur: 38s">
      <div class="track">
        @foreach ($list as $cat)
          <a class="cat-card" href="{{ route('categories.show', $cat) }}">
            <div class="thumb">
              <img src="{{ asset($cat->icon ?: 'images/services/service.png') }}" alt="">
            </div>
            <div class="meta">
              <h3>{{ app()->getLocale() === 'ar' ? $cat->name_ar : $cat->name_en }}</h3>
              @php $desc = app()->getLocale()==='ar' ? ($cat->description_ar ?? '') : ($cat->description_en ?? ''); @endphp
              @if($desc)
                <p>{{ \Illuminate\Support\Str::limit($desc, 80) }}</p>
              @endif
            </div>
          </a>
        @endforeach

        {{-- تكرار المحتوى للتمرير المستمر --}}
        @foreach ($list as $cat)
          <a class="cat-card" href="{{ route('categories.show', $cat) }}">
            <div class="thumb">
              <img src="{{ asset($cat->icon ?: 'images/services/service.png') }}" alt="">
            </div>
            <div class="meta">
              <h3>{{ app()->getLocale() === 'ar' ? $cat->name_ar : $cat->name_en }}</h3>
              @php $desc = app()->getLocale()==='ar' ? ($cat->description_ar ?? '') : ($cat->description_en ?? ''); @endphp
              @if($desc)
                <p>{{ \Illuminate\Support\Str::limit($desc, 80) }}</p>
              @endif
            </div>
          </a>
        @endforeach
      </div>
    </div>
  @endif

{{-- ===== قسم الإعلانات ===== --}}
@if(($categoryAds ?? collect())->count())
  <section class="svc-section reveal">
    <div class="sec-head center">
      <h2>{{ app()->getLocale()==='ar' ? 'إعلانات الفئات' : 'Categories Ads' }}</h2>
    </div>

    <div class="ads-rotator" data-interval-sec="8" data-fit="cover">
      @php $isAr = app()->getLocale()==='ar'; @endphp

      @foreach($categoryAds as $i => $ad)
        <article class="{{ $i === 0 ? 'ad active' : 'ad' }}">
          <div class="ad-visual">
            <img
              src="{{ $ad->first_image_url }}"
              alt="{{ $ad->title_ar ?? $ad->title_en ?? 'ad' }}"
              loading="eager"
              decoding="auto"
              fetchpriority="{{ $i === 0 ? 'high' : 'low' }}"
            >
          </div>

          <div class="ad-overlay">
            @if($ad->title_ar || $ad->title_en)
              <h3 class="ad-title">
                {{ $isAr ? ($ad->title_ar ?: $ad->title_en) : ($ad->title_en ?: $ad->title_ar) }}
              </h3>
            @endif

            @if($ad->desc_ar || $ad->desc_en)
              <p class="ad-desc">
                {{ $isAr ? ($ad->desc_ar ?: $ad->desc_en) : ($ad->desc_en ?: $ad->desc_ar) }}
              </p>
            @endif

            @if($ad->location_title)
              <div class="ad-loc">{{ $ad->location_title }}</div>
            @endif
          </div>
        </article>
      @endforeach

      <button class="ads-prev ads-nav" type="button" aria-label="Previous">‹</button>
      <button class="ads-next ads-nav" type="button" aria-label="Next">›</button>
      <div class="ads-dots"></div>
    </div>
  </section>
@endif


  {{-- ===== الشريط الثاني (عكسي) ===== --}}
  @if($list->count())
    <div class="cat-marquee dir-left after-ads" style="--dur: 40s">
      <div class="track">
        @foreach ($list as $cat)
          <a class="cat-card" href="{{ route('categories.show', $cat) }}">
            <div class="thumb">
              <img src="{{ asset($cat->icon ?: 'images/services/service.png') }}" alt="">
            </div>
            <div class="meta">
              <h3>{{ app()->getLocale() === 'ar' ? $cat->name_ar : $cat->name_en }}</h3>
              @php $desc = app()->getLocale()==='ar' ? ($cat->description_ar ?? '') : ($cat->description_en ?? ''); @endphp
              @if($desc)
                <p>{{ \Illuminate\Support\Str::limit($desc, 80) }}</p>
              @endif
            </div>
          </a>
        @endforeach

        {{-- تكرار --}}
        @foreach ($list as $cat)
          <a class="cat-card" href="{{ route('categories.show', $cat) }}">
            <div class="thumb">
              <img src="{{ asset($cat->icon ?: 'images/services/service.png') }}" alt="">
            </div>
            <div class="meta">
              <h3>{{ app()->getLocale() === 'ar' ? $cat->name_ar : $cat->name_en }}</h3>
              @php $desc = app()->getLocale()==='ar' ? ($cat->description_ar ?? '') : ($cat->description_en ?? ''); @endphp
              @if($desc)
                <p>{{ \Illuminate\Support\Str::limit($desc, 80) }}</p>
              @endif
            </div>
          </a>
        @endforeach
      </div>
    </div>
  @endif

</section>
@endsection
