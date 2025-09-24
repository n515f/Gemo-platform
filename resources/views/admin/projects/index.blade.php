{{-- resources/views/admin/projects/index.blade.php --}}
@extends('layouts.site')

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
<section class="admin-page">

  <header class="page-head">
    <div class="lhs">
      <h1 class="title">{{ __('متابعة المشاريع') }}</h1>
      <p class="muted">{{ __('إدارة المشاريع وتتبّع حالتها ومواعيدها.') }}</p>
    </div>

    <form method="GET" class="toolbar">
      <input class="input" type="text" name="q" value="{{ $q ?? '' }}" placeholder="{{ __('ابحث بالعنوان/العميل/المعرف') }}">
      <select class="select" name="order">
        @php $order = $order ?? 'id'; @endphp
        <option value="id"         {{ $order==='id'?'selected':'' }}>ID</option>
        <option value="title"      {{ $order==='title'?'selected':'' }}>{{ __('العنوان') }}</option>
        <option value="client_name"{{ $order==='client_name'?'selected':'' }}>{{ __('العميل') }}</option>
        <option value="status"     {{ $order==='status'?'selected':'' }}>{{ __('الحالة') }}</option>
        <option value="start_date" {{ $order==='start_date'?'selected':'' }}>{{ __('البداية') }}</option>
        <option value="due_date"   {{ $order==='due_date'?'selected':'' }}>{{ __('الانتهاء') }}</option>
        <option value="updated_at" {{ $order==='updated_at'?'selected':'' }}>{{ __('آخر تحديث') }}</option>
      </select>
      <select class="select" name="dir">
        @php $dir = $dir ?? 'desc'; @endphp
        <option value="asc"  {{ $dir==='asc'?'selected':'' }}>{{ __('تصاعدي') }}</option>
        <option value="desc" {{ $dir==='desc'?'selected':'' }}>{{ __('تنازلي') }}</option>
      </select>
      <button class="btn btn-light" type="submit">{{ __('تصفية') }}</button>
      <a class="btn btn-primary" href="{{ route('admin.projects.create') }}">+ {{ __('مشروع جديد') }}</a>
    </form>
  </header>

  @include('components.flash')

  <div class="card">
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ __('العنوان') }}</th>
            <th>{{ __('العميل') }}</th>
            <th>{{ __('الحالة') }}</th>
            <th>{{ __('البداية') }}</th>
            <th>{{ __('الانتهاء') }}</th>
            <th>{{ __('آخر تحديث') }}</th>
            <th class="actions-col">{{ __('إجراءات') }}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rows as $p)
            <tr>
              <td>{{ $p->id }}</td>
              <td class="strong"><a href="{{ route('admin.projects.show', $p) }}">{{ $p->title }}</a></td>
              <td>{{ $p->client_name }}</td>
              <td><span class="badge">{{ __($p->status) }}</span></td>
              <td>{{ $p->start_date ?: '—' }}</td>
              <td>{{ $p->due_date   ?: '—' }}</td>
              <td>{{ optional($p->updated_at)->format('Y-m-d H:i') }}</td>
              <td class="actions">
                <a class="btn btn-soft small"  href="{{ route('admin.projects.show', $p) }}">{{ __('عرض') }}</a>
                <a class="btn btn-light small" href="{{ route('admin.projects.edit', $p) }}">{{ __('تعديل') }}</a>
                <form method="POST" action="{{ route('admin.projects.destroy', $p) }}" onsubmit="return confirm('حذف المشروع؟')" style="display:inline-block">
                  @csrf @method('DELETE')
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
      {{ $rows->withQueryString()->links() }}
    </div>
  </div>
</section>
@endsection