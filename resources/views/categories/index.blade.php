@extends('layouts.site')

@push('styles')
  @vite('resources/css/entries/site.css')
@endpush
@push('scripts')
  @vite('resources/js/app.js')
@endpush

@section('content')
<section class="svc-section reveal">

  {{-- رأس الصفحة + شريط بحث --}}
  <div class="sec-head">
    <h2 class="sec-title">{{ __('app.categories') }}</h2>
    <form class="cat-search" method="GET" action="{{ route('categories.index') }}">
      <input type="search" name="q" value="{{ $q ?? '' }}" placeholder="{{ app()->getLocale()==='ar' ? 'ابحث عن فئة...' : 'Search categories...' }}">
      @if(!empty($q))
        <a class="clear" href="{{ route('categories.index') }}" aria-label="Clear">×</a>
      @endif
      <button class="btn-search" type="submit">{{ app()->getLocale()==='ar' ? 'بحث' : 'Search' }}</button>
    </form>
  </div>

  @php
    $list = $allCategories ?? collect();

    // دالة محلية لتطبيع مسار صورة الإعلان لأي صيغة مدخلة
    $adImg = function ($raw) {
      // Fallback لو فاضي
      $fallback = asset('images/services/full-line.jpg');

      if (!$raw) return $fallback;

      // لو رابط كامل http/https
      if (\Illuminate\Support\Str::startsWith($raw, ['http://','https://'])) return $raw;

      // إزالة أي سلاشات زائدة بالبداية
      $p = ltrim($raw, '/');

      // لو جاي بصيغة "storage/..." نُعيده كما هو لكن بسلاش بالبداية
      if (\Illuminate\Support\Str::startsWith($p, 'storage/')) {
        return '/'.$p; // مثال: /storage/ads/x.jpg
      }

      // لو الملف موجود على قرص public (storage/app/public)
      if (\Illuminate\Support\Facades\Storage::disk('public')->exists($p)) {
        return \Illuminate\Support\Facades\Storage::url($p); // /storage/...
      }

      // آخر حل: نحاول عبر asset (public/)
      return asset($p);
    };
  @endphp

  {{-- الشريط الأول --}}
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
              @if($desc)<p>{{ \Illuminate\Support\Str::limit($desc, 80) }}</p>@endif
            </div>
          </a>
        @endforeach
        {{-- تكرار المحتوى لتمرير لا نهائي --}}
        @foreach ($list as $cat)
          <a class="cat-card" href="{{ route('categories.show', $cat) }}">
            <div class="thumb">
              <img src="{{ asset($cat->icon ?: 'images/services/service.png') }}" alt="">
            </div>
            <div class="meta">
              <h3>{{ app()->getLocale() === 'ar' ? $cat->name_ar : $cat->name_en }}</h3>
              @php $desc = app()->getLocale()==='ar' ? ($cat->description_ar ?? '') : ($cat->description_en ?? ''); @endphp
              @if($desc)<p>{{ \Illuminate\Support\Str::limit($desc, 80) }}</p>@endif
            </div>
          </a>
        @endforeach
      </div>
    </div>
  @endif

  {{-- ===== إعلانات الفئات ===== --}}
  @if(($categoryAds ?? collect())->count())
    <section class="svc-section reveal">
      <div class="sec-head center">
        <h2>{{ app()->getLocale()==='ar' ? 'إعلانات الفئات' : 'Categories Ads' }}</h2>
      </div>

      <div class="ads-rotator" data-interval-sec="20" data-fit="cover">
        @foreach($categoryAds as $ad)
          @php
            // استخرج أول صورة غير فارغة
            $imgs = is_string($ad->images) ? json_decode($ad->images, true) : ($ad->images ?? []);
            $imgs = is_array($imgs) ? $imgs : [];
            $firstRaw = collect($imgs)->first(fn($p) => !empty($p));
            $first = $adImg($firstRaw);
          @endphp

        <article class="ad {{ $loop->first ? 'active' : '' }}"
         data-images='@json($imgs, JSON_UNESCAPED_UNICODE)'>
  <div class="ad-visual">
    <img src="{{ $first }}" alt="">
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

        <button class="ads-nav prev" aria-label="Prev">‹</button>
        <button class="ads-nav next" aria-label="Next">›</button>
      </div>
    </section>
  @endif

  {{-- الشريط الثاني (عكسي) --}}
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
              @if($desc)<p>{{ \Illuminate\Support\Str::limit($desc, 80) }}</p>@endif
            </div>
          </a>
        @endforeach
        @foreach ($list as $cat)
          <a class="cat-card" href="{{ route('categories.show', $cat) }}">
            <div class="thumb">
              <img src="{{ asset($cat->icon ?: 'images/services/service.png') }}" alt="">
            </div>
            <div class="meta">
              <h3>{{ app()->getLocale() === 'ar' ? $cat->name_ar : $cat->name_en }}</h3>
              @php $desc = app()->getLocale()==='ar' ? ($cat->description_ar ?? '') : ($cat->description_en ?? ''); @endphp
              @if($desc)<p>{{ \Illuminate\Support\Str::limit($desc, 80) }}</p>@endif
            </div>
          </a>
        @endforeach
      </div>
    </div>
  @endif

</section>
@endsection
