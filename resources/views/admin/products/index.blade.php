{{-- resources/views/admin/products/index.blade.php --}}
@extends('layouts.site')

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@push('scripts')
  @vite(['resources/js/admin.js'])
@endpush

@section('content')
<section class="admin-page">
  {{-- رأس الصفحة + شريط أدوات --}}
  <header class="page-head">
    <div class="lhs">
      <h1 class="title">{{ __('إدارة المنتجات') }}</h1>
      <p class="muted">{{ __('أضف/عدّل/احذف منتجات الكتالوج، وابحث بسرعة.') }}</p>
    </div>

    <form method="GET" action="{{ route('admin.products.index') }}" class="toolbar">
      <input class="input" type="text" name="q" value="{{ $q ?? '' }}" placeholder="{{ __('ابحث بالاسم/الكود/المعرف') }}">

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

  {{-- فلاش نجاح إن وجد --}}
  @if(session('ok'))
    <div class="flash success">{{ session('ok') }}</div>
  @endif
  @if(session('success'))
    <div class="flash success">{{ session('success') }}</div>
  @endif

  {{-- الجدول --}}
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
            <th>{{ __('الحالة') }}</th>
            <th>{{ __('آخر تحديث') }}</th>
            <th class="actions-col">{{ __('إجراءات') }}</th>
          </tr>
        </thead>

        <tbody>
        @forelse($products as $p)
          <tr>
            <td>
              <img class="thumb"
                   src="{{ $p->first_image_url ?? asset('images/placeholder.png') }}"
                   alt="" width="56" height="56">
            </td>

            <td class="stack">
              <strong>{{ $p->name_ar ?? '—' }}</strong>
              <small class="muted">{{ $p->name_en ?? '—' }}</small>
            </td>

            <td>{{ $p->code ?? '—' }}</td>

            <td class="num">
              {{ isset($p->price) ? number_format($p->price, 2) : '—' }}
            </td>

            <td class="num">{{ $p->images_count ?? 0 }}</td>

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

              <form method="POST"
      action="{{ route('admin.products.destroy', $p) }}"
      class="needs-confirm"
      data-confirm="{{ __('حذف هذا المنتج؟') }}"
      style="display:inline-block">
  @csrf
  @method('DELETE')
  <button class="btn btn-danger small" type="submit">{{ __('حذف') }}</button>
</form>
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="empty">{{ __('لا توجد بيانات') }}</td></tr>
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