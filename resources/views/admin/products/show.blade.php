{{-- resources/views/admin/products/show.blade.php --}}
{{-- عرض منتج --}}
@extends('layouts.admin)

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
<section class="admin-page">
  <header class="page-head">
    <div class="lhs">
      <h1 class="title">{{ $product->name_ar ?? $product->name_en ?? __('app.product') }}</h1>
      <p class="muted">{{ __('app.product_details_hint') }}</p>
    </div>
    <div class="rhs">
      <a class="btn btn-light" href="{{ route('admin.products.index') }}">{{ __('app.back_to_list') }}</a>
      <a class="btn btn-primary" href="{{ route('admin.products.edit', $product) }}">{{ __('app.edit') }}</a>
    </div>
  </header>

  <div class="card product-view">
    <div class="pv-grid">
      <div class="pv-media">
        @php
          $first = optional($product->images->first())->path;
        @endphp
        <img src="{{ $first ? asset($first) : asset('images/placeholder.png') }}" alt="">
        @if($product->images->count() > 1)
          <div class="thumbs-grid mt-8">
            @foreach($product->images as $img)
              <img src="{{ asset($img->path) }}" class="thumb" alt="">
            @endforeach
          </div>
        @endif
      </div>

      <div class="pv-info">
        <dl class="def">
          <div><dt>{{ __('app.name_ar_short') }}</dt><dd>{{ $product->name_ar ?? '—' }}</dd></div>
          <div><dt>{{ __('app.name_en_short') }}</dt><dd>{{ $product->name_en ?? '—' }}</dd></div>
          <div><dt>{{ __('app.code') }}</dt><dd>{{ $product->code ?? '—' }}</dd></div>
          <div><dt>{{ __('app.slug') }}</dt><dd>{{ $product->slug ?? '—' }}</dd></div>
          <div><dt>{{ __('app.price') }}</dt><dd>{{ isset($product->price) ? number_format($product->price,2) : '—' }}</dd></div>
          <div><dt>{{ __('app.sort_order') }}</dt><dd>{{ $product->sort_order ?? 0 }}</dd></div>
          <div><dt>{{ __('app.status') }}</dt><dd>{{ $product->is_active ? __('app.enabled') : __('app.disabled') }}</dd></div>

          <div><dt>{{ __('app.categories') }}</dt>
            <dd>
              @forelse($product->categories as $cat)
                <span class="badge soft">{{ app()->getLocale()==='ar' ? $cat->name_ar : ($cat->name_en ?: $cat->name_ar) }}</span>
              @empty — @endforelse
            </dd>
          </div>

          <div><dt>{{ __('app.short_desc_ar') }}</dt><dd>{{ $product->short_desc_ar ?? '—' }}</dd></div>
          <div><dt>{{ __('app.short_desc_en') }}</dt><dd>{{ $product->short_desc_en ?? '—' }}</dd></div>

          <div><dt>{{ __('app.specs_ar') }}</dt><dd><pre class="pre">{{ $product->specs_ar ?? '—' }}</pre></dd></div>
          <div><dt>{{ __('app.specs_en') }}</dt><dd><pre class="pre">{{ $product->specs_en ?? '—' }}</pre></dd></div>

          <div><dt>{{ __('app.updated_at') }}</dt><dd>{{ optional($product->updated_at)->format('Y-m-d H:i') }}</dd></div>
        </dl>
      </div>
    </div>
  </div>
</section>
@endsection
