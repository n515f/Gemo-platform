{{-- resources/views/admin/categories/index.blade.php --}}
@extends('layouts.admin')

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
      <button class="btn btn--search" type="submit">
        <svg class="ico" viewBox="0 0 24 24">
          <circle cx="11" cy="11" r="8" fill="none" stroke="currentColor" stroke-width="2"/>
          <path d="m21 21-4.35-4.35" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        بحث
      </button>
    </form>

    <a class="btn btn--add" href="{{ route('admin.categories.create') }}">
      <svg class="ico" viewBox="0 0 24 24">
        <path d="M12 5v14M5 12h14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      فئة جديدة
    </a>
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
          <a class="btn btn--edit btn--sm" href="{{ route('admin.categories.edit', $cat) }}">
            <svg class="ico" viewBox="0 0 24 24">
              <path d="M14.5 3.5a2.5 2.5 0 0 1 3.5 3.5L8 17l-4 1 1-4 9.5-10.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
            تعديل
          </a>

          <form method="POST"
                action="{{ route('admin.categories.destroy', $cat) }}"
                onsubmit="return confirm('حذف الفئة؟');">
            @csrf @method('DELETE')
            <button class="btn btn--delete btn--sm" type="submit">
              <svg class="ico" viewBox="0 0 24 24">
                <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6M10 11v6M14 11v6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              حذف
            </button>
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