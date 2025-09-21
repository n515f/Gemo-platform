@extends('layouts.site')

@section('content')
  <link rel="stylesheet" href="{{ asset('css/catalog.css') }}">

  <h1 class="page-title">{{ __('app.catalog') }}</h1>

  <form method="GET" action="{{ route('catalog.index') }}" class="search-bar">
    <input type="text" name="q" value="{{ $q }}" placeholder="{{ __('app.search') }}">
    <button type="submit">{{ __('app.search_btn') }}</button>
  </form>

  @if ($products->count() === 0)
    <div class="empty">{{ __('app.no_products') }}</div>
  @else
    <div class="pro-grid">
      @foreach ($products as $p)
        @php
          $img  = optional($p->images->first())->path;
          $src  = $img ? asset($img) : 'https://picsum.photos/seed/'.($p->id).'/900/600';

          $desc  = trim((string) $p->trans_short_desc);
          $lines = collect(preg_split('/\r\n|\r|\n/', $desc))
                    ->map(fn($l)=>trim($l))->filter()->values();
          $showAsBullets = $lines->count() >= 2;
        @endphp

        <article class="pro-card">
          <div class="media">
            <img
  src="{{ $src }}"
  alt="{{ $p->trans_name }}"
  data-fallback="{{ asset('images/no-image.png') }}"
  onerror="this.onerror=null; this.src=this.dataset.fallback;"
  loading="lazy"
  decoding="async"

/>
            {{-- لا توجد شارة فوق الصورة --}}
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
              <a class="btn btn-gradient" href="{{ route('rfq.create', ['product' => $p->id]) }}">
                {{ __('app.ask_quote') }}
              </a>
              
            </div>
          </div>
        </article>
      @endforeach
    </div>

    <div class="pagination">{{ $products->links() }}</div>
  @endif
@endsection
