@extends('layouts.site')
@push('styles') @vite('resources/css/entries/site.css') @endpush

@section('content')
  <section class="svc-hero reveal">
    <div class="shell">
      <div class="svc-hero__head">
        <img class="svc-hero__icon" src="{{ asset($category->icon ?: 'images/icons/catalog.png') }}" alt="">
        <div>
          <h1 class="svc-hero__title">
            {{ app()->getLocale()==='ar' ? $category->name_ar : $category->name_en }}
          </h1>
          <p class="svc-hero__sub">
            {{ app()->getLocale()==='ar' ? ($category->description_ar ?? '') : ($category->description_en ?? '') }}
          </p>
        </div>
      </div>
    </div>
  </section>

  <section class="svc-section reveal">
    <div class="sec-head">
      <h2 class="sec-title">{{ __('app.catalog') }}</h2>
      <a class="link-more" href="{{ route('categories.index') }}">← {{ __('app.categories') }}</a>
    </div>

    <div class="gallery-3">
      @forelse($products as $p)
        <a class="glass-card reveal" href="{{ route('catalog.index') }}">
          <img src="{{ asset($p->images->first()->path ?? 'images/no-image.png') }}" alt="">
          <div class="glass-overlay">
            <div class="name">{{ $p->name_ar ?? $p->name_en }}</div>
            <div class="meta">
              <span class="badge">#{{ $p->code ?? $p->id }}</span>
              @if(!is_null($p->price)) <span class="price">{{ number_format($p->price) }}</span>@endif
            </div>
          </div>
        </a>
      @empty
        <div class="empty">{{ __('app.no_items') }}</div>
      @endforelse
    </div>

    <div class="mt-12">{{ $products->links() }}</div>
  </section>
@endsection