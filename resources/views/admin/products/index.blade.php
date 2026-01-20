{{-- resources/views/admin/products/index.blade.php --}}
{{-- إدارة المنتجات --}}
@extends('layouts.admin')

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
<section class="admin-page">
  <header class="page-head">
    <div class="lhs">
      <h1 class="title">{{ __('app.products_manage_title') }}</h1>
      <p class="muted">{{ __('app.products_manage_subtitle') }}</p>
    </div>

    <form method="GET" action="{{ route('admin.products.index') }}" class="toolbar">
      <input class="input" type="text" name="q" value="{{ $q ?? '' }}" placeholder="{{ __('app.search_products_ph') }}">

      @isset($categories)
        <select class="select" name="category_id">
          <option value="">{{ __('app.all_categories') }}</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ (isset($catId) && (int)$catId === (int)$cat->id) ? 'selected':'' }}>
              {{ app()->getLocale()==='ar' ? $cat->name_ar : ($cat->name_en ?: $cat->name_ar) }}
            </option>
          @endforeach
        </select>
      @endisset

      <select class="select" name="order">
        <option value="id"         {{ ($order ?? '')==='id' ? 'selected':'' }}>{{ __('app.id') }}</option>
        <option value="name_ar"    {{ ($order ?? '')==='name_ar' ? 'selected':'' }}>{{ __('app.name_ar_short') }}</option>
        <option value="name_en"    {{ ($order ?? '')==='name_en' ? 'selected':'' }}>{{ __('app.name_en_short') }}</option>
        <option value="code"       {{ ($order ?? '')==='code' ? 'selected':'' }}>{{ __('app.code') }}</option>
        <option value="price"      {{ ($order ?? '')==='price' ? 'selected':'' }}>{{ __('app.price') }}</option>
        <option value="sort_order" {{ ($order ?? '')==='sort_order' ? 'selected':'' }}>{{ __('app.sort_order_short') }}</option>
        <option value="updated_at" {{ ($order ?? '')==='updated_at' ? 'selected':'' }}>{{ __('app.updated_at') }}</option>
      </select>

      <select class="select" name="dir">
        <option value="asc"  {{ ($dir ?? '')==='asc'  ? 'selected':'' }}>{{ __('app.asc') }}</option>
        <option value="desc" {{ ($dir ?? '')==='desc' ? 'selected':'' }}>{{ __('app.desc') }}</option>
      </select>

      <button class="btn btn--search" type="submit">
        <svg class="ico" viewBox="0 0 24 24">
          <circle cx="11" cy="11" r="8" fill="none" stroke="currentColor" stroke-width="2"/>
          <path d="m21 21-4.35-4.35" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        {{ __('app.filter') }}
      </button>
      <a class="btn btn--add" href="{{ route('admin.products.create') }}">
        <svg class="ico" viewBox="0 0 24 24">
          <path d="M12 5v14M5 12h14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        {{ __('app.new_product') }}
      </a>
    </form>
  </header>

  @if(session('ok'))      <div class="flash success">{{ session('ok') }}</div>@endif
  @if(session('success')) <div class="flash success">{{ session('success') }}</div>@endif

  <div class="card">
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th style="width:64px">{{ __('app.image') }}</th>
            <th>{{ __('app.name') }}</th>
            <th>{{ __('app.code') }}</th>
            <th class="num">{{ __('app.price') }}</th>
            <th class="num">{{ __('app.images_count') }}</th>
            <th>{{ __('app.categories') }}</th>
            <th>{{ __('app.status') }}</th>
            <th>{{ __('app.updated_at') }}</th>
            <th class="actions-col">{{ __('app.actions') }}</th>
          </tr>
        </thead>

        <tbody>
        @forelse($products as $p)
          <tr>
            <td>
              @php
                $thumbPath = optional($p->images->first())->path;
                $src = $p->first_image_url ?: ($thumbPath ? asset($thumbPath) : asset('images/no-image.png'));
              @endphp
              <img class="thumb" src="{{ $src }}" alt="" width="56" height="56">
            </td>

            <td class="stack">
              <strong>{{ $p->name_ar ?? '—' }}</strong>
              <small class="muted">{{ $p->name_en ?? '—' }}</small>
            </td>

            <td>{{ $p->code ?? '—' }}</td>

            <td class="num">
              {{ isset($p->price) ? number_format($p->price, 2) : '—' }}
            </td>

            <td class="num">{{ $p->images_count ?? ($p->images->count() ?? 0) }}</td>

            <td class="wrap">
              @if(!empty($p->categories))
                @foreach($p->categories as $cat)
                  <span class="badge soft">{{ app()->getLocale()==='ar' ? $cat->name_ar : ($cat->name_en ?: $cat->name_ar) }}</span>
                @endforeach
              @endif
            </td>

            <td>
              @if($p->is_active)
                <span class="badge ok">{{ __('app.enabled') }}</span>
              @else
                <span class="badge">{{ __('app.disabled') }}</span>
              @endif
            </td>

            <td>{{ optional($p->updated_at)->format('Y-m-d H:i') }}</td>

            <td class="actions">
              <a class="btn btn--search btn--sm" href="{{ route('admin.products.show', $p) }}">
                <svg class="ico" viewBox="0 0 24 24">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" fill="none" stroke="currentColor" stroke-width="2"/>
                  <circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="2"/>
                </svg>
                {{ __('app.view') }}
              </a>
              <a class="btn btn--edit btn--sm" href="{{ route('admin.products.edit', $p) }}">
                <svg class="ico" viewBox="0 0 24 24">
                  <path d="M14.5 3.5a2.5 2.5 0 0 1 3.5 3.5L8 17l-4 1 1-4 9.5-10.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                </svg>
                {{ __('app.edit') }}
              </a>

              <form method="POST" action="{{ route('admin.products.destroy', $p) }}"
                    class="needs-confirm" data-confirm="{{ __('app.confirm_delete_product') }}"
                    style="display:inline-block">
                @csrf @method('DELETE')
                <button class="btn btn--delete btn--sm" type="submit">
                  <svg class="ico" viewBox="0 0 24 24">
                    <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6M10 11v6M14 11v6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  {{ __('app.delete') }}
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="9" class="empty">{{ __('app.no_data') }}</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>

    <div class="pagination">
      {{ $products->withQueryString()->links() }}
    </div>
  </div>
</section>
@endsection
