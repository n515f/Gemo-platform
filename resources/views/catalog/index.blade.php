@extends('layouts.site')

@push('styles')
  @vite(['resources/css/entries/site.css'])
@endpush

@section('content')
  <h1 class="page-title">{{ __('app.catalog') }}</h1>

  <form method="GET" action="{{ route('catalog.index') }}" class="search-bar" style="display:flex;gap:8px;margin:10px 0 18px">
    <input type="text" name="q" value="{{ old('q', $q ?? request('q','')) }}" placeholder="{{ __('app.search') }}"
           style="flex:1;padding:12px;border-radius:12px;border:1px solid var(--ring);background:var(--soft);color:var(--fg);font-weight:800">
    <button type="submit" class="btn btn-primary">{{ __('app.search_btn') }}</button>
  </form>

  @if ($products->count() === 0)
    <div class="empty">{{ __('app.no_products') }}</div>
  @else
    <div class="pro-grid">
      @foreach ($products as $p)
        @php
          $imgs = $p->images?->pluck('path')->filter()->map(fn($path)=>asset($path))->values() ?? collect();
          if ($imgs->isEmpty()) $imgs = collect(['https://picsum.photos/seed/'.$p->id.'/900/600']);

          $desc  = trim((string) $p->trans_short_desc);
          $lines = collect(preg_split('/\r\n|\r|\n/', $desc))->map(fn($l)=>trim($l))->filter()->values();
          $showAsBullets = $lines->count() >= 2;
        @endphp

        <article class="pro-card" data-product-id="{{ $p->id }}">
          <div class="media">
            <div class="slider" data-slider data-interval-sec="300">
              <div class="track">
                @foreach($imgs as $src)
                  <img class="slide"
                       src="{{ $src }}"
                       alt="{{ $p->trans_name }}"
                       loading="lazy" decoding="async"
                       onerror="this.onerror=null;this.style.opacity='0';" />
                @endforeach
              </div>
              @if($imgs->count() > 1)
                <button class="nav prev" type="button" aria-label="Prev" data-prev>&lsaquo;</button>
                <button class="nav next" type="button" aria-label="Next" data-next>&rsaquo;</button>
                <div class="slider-dots"></div>
              @endif
            </div>
          </div>

          <div class="body">
            <h3 class="title">
              {{ $p->trans_name }}
              @if(!empty($p->code))
                <span class="code">({{ $p->code }})</span>
              @endif
            </h3>

            @if(!empty($p->sku))
              <span class="sku">{{ __('app.sku') }}: {{ $p->sku }}</span>
            @endif

            @if($showAsBullets)
              <ul class="features">
                @foreach($lines->take(3) as $line)
                  <li>{{ rtrim($line, ' .،') }}.</li>
                @endforeach
              </ul>
            @elseif($desc !== '')
              <p class="lead">{{ \Illuminate\Support\Str::limit($desc, 160) }}</p>
            @endif

            <div class="cta">
              <a class="btn btn-primary js-open-rfq"
                 href="#"
                 data-id="{{ $p->id }}"
                 data-name="{{ $p->trans_name }}"
                 data-code="{{ $p->code ?? '' }}"
                 data-sku="{{ $p->sku ?? '' }}">
                {{ __('app.ask_quote') }}
              </a>
              <a class="btn btn-ghost" href="{{ route('rfq.create', ['product'=>$p->id]) }}">
                {{ __('app.details') ?? 'Details' }}
              </a>
            </div>
          </div>
        </article>
      @endforeach
    </div>

    <div class="pagination" style="margin-top:16px">{{ $products->links() }}</div>
  @endif

  {{-- المودال كـ بارشال مستقل --}}
  @include('catalog._rfq-modal')
@endsection

