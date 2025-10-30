{{-- إدارة المنتجات --}}
@extends('layouts.admin')

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
<section class="admin-page">
  <header class="page-head">
    <div class="lhs">
      <h1 class="title">{{ __('إدارة المنتجات') }}</h1>
      <p class="muted">{{ __('أضف/عدّل/احذف منتجات الكتالوج، وابحث بسرعة.') }}</p>
    </div>

    <form method="GET" action="{{ route('admin.products.index') }}" class="toolbar">
      <input class="input" type="text" name="q" value="{{ $q ?? '' }}" placeholder="{{ __('ابحث بالاسم/الكود/المعرف') }}">

      {{-- فلتر بالفئة (اختياري إن تم تمريره من الكنترولر) --}}
      @isset($categories)
        <select class="select" name="category_id">
          <option value="">{{ __('كل الفئات') }}</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ (isset($catId) && (int)$catId === (int)$cat->id) ? 'selected':'' }}>
              {{ app()->getLocale()==='ar' ? $cat->name_ar : ($cat->name_en ?: $cat->name_ar) }}
            </option>
          @endforeach
        </select>
      @endisset

      <select class="select" name="order">
        <option value="id"         {{ ($order ?? '')==='id' ? 'selected':'' }}>ID</option>
        <option value="name_ar"    {{ ($order ?? '')==='name_ar' ? 'selected':'' }}>{{ __('الاسم AR') }}</option>
        <option value="name_en"    {{ ($order ?? '')==='name_en' ? 'selected':'' }}>{{ __('الاسم EN') }}</option>
        <option value="code"       {{ ($order ?? '')==='code' ? 'selected':'' }}>{{ __('الكود') }}</option>
        <option value="price"      {{ ($order ?? '')==='price' ? 'selected':'' }}>{{ __('السعر') }}</option>
        <option value="sort_order" {{ ($order ?? '')==='sort_order' ? 'selected':'' }}>{{ __('الترتيب') }}</option>
        <option value="updated_at" {{ ($order ?? '')==='updated_at' ? 'selected':'' }}>{{ __('آخر تحديث') }}</option>
      </select>

      <select class="select" name="dir">
        <option value="asc"  {{ ($dir ?? '')==='asc'  ? 'selected':'' }}>{{ __('تصاعدي') }}</option>
        <option value="desc" {{ ($dir ?? '')==='desc' ? 'selected':'' }}>{{ __('تنازلي') }}</option>
      </select>

      <button class="btn btn-light" type="submit">{{ __('تصفية') }}</button>
      <a class="btn btn-primary" href="{{ route('admin.products.create') }}">+ {{ __('منتج جديد') }}</a>
    </form>
  </header>

  @if(session('ok'))      <div class="flash success">{{ session('ok') }}</div>@endif
  @if(session('success')) <div class="flash success">{{ session('success') }}</div>@endif

  <div class="card">
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th style="width:64px">{{ __('الصورة') }}</th>
            <th>{{ __('الاسم') }}</th>
            <th>{{ __('الكود') }}</th>
            <th class="num">{{ __('السعر') }}</th>
            <th class="num">{{ __('عدد الصور') }}</th>
            <th>{{ __('الفئات') }}</th>
            <th>{{ __('الحالة') }}</th>
            <th>{{ __('آخر تحديث') }}</th>
            <th class="actions-col">{{ __('إجراءات') }}</th>
          </tr>
        </thead>

        <tbody>
        @forelse($products as $p)
          <tr>
            <td>
              @php
                $thumb = method_exists($p,'first_image_url') ? $p->first_image_url : optional($p->images->first())->path;
              @endphp
              <img class="thumb" src="{{ $thumb ? asset($thumb) : asset('images/placeholder.png') }}" alt="" width="56" height="56">
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
                <span class="badge ok">{{ __('مفعل') }}</span>
              @else
                <span class="badge">{{ __('معطل') }}</span>
              @endif
            </td>

            <td>{{ optional($p->updated_at)->format('Y-m-d H:i') }}</td>

            <td class="actions">
              <a class="btn btn-soft small"  href="{{ route('admin.products.show', $p) }}">{{ __('عرض') }}</a>
              <a class="btn btn-light small" href="{{ route('admin.products.edit', $p) }}">{{ __('تعديل') }}</a>

              <form method="POST" action="{{ route('admin.products.destroy', $p) }}"
                    class="needs-confirm" data-confirm="{{ __('حذف هذا المنتج؟') }}"
                    style="display:inline-block">
                @csrf @method('DELETE')
                <button class="btn btn-danger small" type="submit">{{ __('حذف') }}</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="9" class="empty">{{ __('لا توجد بيانات') }}</td></tr>
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