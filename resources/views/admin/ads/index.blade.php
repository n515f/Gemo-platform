@extends('layouts.site')
@push('styles') @vite('resources/css/entries/admin.css') @endpush

@section('content')
  <h1 class="page-title">الإعلانات</h1>
  @include('components.flash')

  @php
    // نضمن وجود قيمة حتى لو لم يرسل الكنترولر المتغير لأي سبب
    $qInput      = request('q', $q ?? '');
    $activeInput = request()->query('active', $active ?? '');
    $activeStr   = is_null($activeInput) ? '' : (string) $activeInput;
  @endphp

  <div class="toolbar">
    <form method="get" class="inline">
      <input class="input" type="text" name="q" value="{{ $qInput }}" placeholder="بحث..." />
      <select class="input" name="active">
        <option value=""  @selected($activeStr==='')>الكل</option>
        <option value="1" @selected($activeStr==='1')>فعّال</option>
        <option value="0" @selected($activeStr==='0')>مؤرشف</option>
      </select>
      <button class="btn">بحث</button>
    </form>
    <a class="btn btn-primary" href="{{ route('admin.ads.create') }}">إعلان جديد</a>
  </div>

  <div class="grid-3">
    @forelse($rows as $ad)
      @php
        // images قد تكون JSON
        $imgs = is_array($ad->images) ? $ad->images : (json_decode($ad->images, true) ?: []);
        $firstImg = $imgs[0] ?? null;
      @endphp

      <div class="card">
        <div class="row-between">
          <strong>{{ $ad->title_ar ?? $ad->title_en ?? 'بدون عنوان' }}</strong>
          @if($ad->is_active)
            <span class="badge">Active</span>
          @else
            <span class="badge soft">Archived</span>
          @endif
        </div>

        @if($firstImg)
          <img class="cover" src="{{ asset($firstImg) }}" alt="">
        @endif

        <div class="muted">
          {{ \Illuminate\Support\Str::limit($ad->desc_ar ?? $ad->desc_en, 120) }}
        </div>

        @if($ad->location_title)
          <div class="small">📍 {{ $ad->location_title }}</div>
        @endif

        <div class="row-end gap-6 mt-8">
          <a class="btn" href="{{ route('admin.ads.edit', $ad) }}">تعديل</a>
          <form method="POST" action="{{ route('admin.ads.destroy', $ad) }}" onsubmit="return confirm('حذف الإعلان؟')">
            @csrf @method('DELETE')
            <button class="btn danger">حذف</button>
          </form>
        </div>
      </div>
    @empty
      <div class="empty">لا توجد إعلانات.</div>
    @endforelse
  </div>

  <div class="mt-12">{{ $rows->withQueryString()->links() }}</div>
@endsection