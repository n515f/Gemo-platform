{{-- عرض منتج --}}
@extends('layouts.site')

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
<section class="admin-page">
  <header class="page-head">
    <div class="lhs">
      <h1 class="title">{{ $product->name ?? __('منتج') }}</h1>
      <p class="muted">{{ __('تفاصيل المنتج كما هو مخزّن في النظام.') }}</p>
    </div>
    <div class="rhs">
      <a class="btn btn-light" href="{{ route('admin.products.index') }}">{{ __('رجوع للقائمة') }}</a>
      <a class="btn btn-primary" href="{{ route('admin.products.edit', $product) }}">{{ __('تعديل') }}</a>
    </div>
  </header>

  <div class="card product-view">
    <div class="pv-grid">
      <div class="pv-media">
        <img src="{{ $product->image_url ?? asset('images/placeholder.png') }}" alt="">
      </div>

      <div class="pv-info">
        <dl class="def">
          <div><dt>{{ __('الاسم') }}</dt><dd>{{ $product->name ?? '—' }}</dd></div>
          <div><dt>{{ __('الاسم EN') }}</dt><dd>{{ $product->name_en ?? '—' }}</dd></div>
          <div><dt>{{ __('SKU') }}</dt><dd>{{ $product->sku ?? '—' }}</dd></div>
          <div><dt>{{ __('السعر') }}</dt><dd>{{ isset($product->price) ? number_format($product->price,2) : '—' }}</dd></div>
          <div><dt>{{ __('الحالة') }}</dt><dd>{{ $product->status ?? '—' }}</dd></div>
          <div><dt>{{ __('الوصف (AR)') }}</dt><dd>{{ $product->description_ar ?? '—' }}</dd></div>
          <div><dt>{{ __('الوصف (EN)') }}</dt><dd>{{ $product->description_en ?? '—' }}</dd></div>
          <div><dt>{{ __('آخر تحديث') }}</dt><dd>{{ optional($product->updated_at)->format('Y-m-d H:i') }}</dd></div>
        </dl>
      </div>
    </div>
  </div>
</section>
@endsection