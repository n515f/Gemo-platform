{{-- resources/views/admin/categories/index.blade.php --}}
@extends('layouts.site')

@push('styles')
  @vite('resources/css/entries/admin.css')
@endpush

@section('content')
  <h1 class="page-title">الفئات</h1>
  @include('components.flash')

  <div class="toolbar">
    <form method="get" class="inline">
      <input class="input" type="text" name="q" value="{{ $q ?? '' }}" placeholder="بحث بالاسم/الوصف..." />
      <select class="input" name="active">
        <option value="">{{ __('الكل') }}</option>
        <option value="1" @selected(($active ?? '')==='1' || ($active ?? null)===1)>فعّالة</option>
        <option value="0" @selected(($active ?? '')==='0' || ($active ?? null)===0)>مؤرشفة</option>
      </select>
      <button class="btn" type="submit">بحث</button>
    </form>

    <a class="btn btn-primary" href="{{ route('admin.categories.create') }}">فئة جديدة</a>
  </div>

  <div class="grid-3">
    @forelse($rows as $cat)
      <div class="card">
        <div class="row-between">
          <strong>{{ $cat->name_ar }} / {{ $cat->name_en }}</strong>
          @if($cat->is_active)
            <span class="badge">Active</span>
          @else
            <span class="badge soft">Archived</span>
          @endif
        </div>

        {{-- صندوق الأيقونة: يحافظ على النسبة ويعرض الصورة كاملة في الوسط --}}
        @if($cat->icon)
          <div class="cat-icon-box" role="img" aria-label="Category icon">
            <img class="cat-icon" src="{{ asset($cat->icon) }}" alt="">
          </div>
        @endif

        <div class="muted">
          {{ \Illuminate\Support\Str::limit($cat->description_ar ?: $cat->description_en, 120) }}
        </div>

        <div class="row-end gap-6 mt-8">
          <a class="btn" href="{{ route('admin.categories.edit', $cat) }}">تعديل</a>

          <form method="POST"
                action="{{ route('admin.categories.destroy', $cat) }}"
                onsubmit="return confirm('حذف الفئة؟');">
            @csrf @method('DELETE')
            <button class="btn danger" type="submit">حذف</button>
          </form>
        </div>
      </div>
    @empty
      <div class="empty">لا توجد فئات.</div>
    @endforelse
  </div>

  <div class="mt-12">
    {{ $rows->withQueryString()->links() }}
  </div>
@endsection