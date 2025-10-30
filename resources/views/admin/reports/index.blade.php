@extends('layouts.admin')

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
<section class="admin-page">
  <header class="page-head">
    <div class="lhs">
      <h1 class="title">إدارة التقارير</h1>
      <p class="muted">مراجعة وإنشاء تقارير الفنيين.</p>
    </div>
    <form method="GET" action="{{ route('admin.reports.index') }}" class="toolbar">
      <input class="input" type="text" name="q" value="{{ $q ?? '' }}" placeholder="ابحث بالعنوان/المشروع/الفني">
      <select class="select" name="order">
        @php $o=$order??'id'; @endphp
        <option value="id"         {{ $o==='id'?'selected':'' }}>ID</option>
        <option value="title"      {{ $o==='title'?'selected':'' }}>العنوان</option>
        <option value="updated_at" {{ $o==='updated_at'?'selected':'' }}>آخر تحديث</option>
      </select>
      <select class="select" name="dir">
        @php $d=$dir??'desc'; @endphp
        <option value="asc"  {{ $d==='asc'?'selected':'' }}>تصاعدي</option>
        <option value="desc" {{ $d==='desc'?'selected':'' }}>تنازلي</option>
      </select>
      <button class="btn btn-light" type="submit">تصفية</button>
      <a class="btn btn-primary" href="{{ route('admin.reports.create') }}">+ تقرير جديد</a>
    </form>
  </header>

  @include('components.flash')

  <div class="card">
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>العنوان</th>
            <th>المشروع</th>
            <th>الفني</th>
            <th>آخر تحديث</th>
            <th class="actions-col">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rows as $r)
            <tr>
              <td>{{ $r->id }}</td>
              <td class="strong"><a href="{{ route('admin.reports.show',$r) }}">{{ $r->title }}</a></td>
              <td>{{ optional($r->project)->title ?? '—' }}</td>
              <td>{{ optional($r->user)->name ?? '—' }}</td>
              <td>{{ optional($r->updated_at)->format('Y-m-d H:i') }}</td>
              <td class="actions">
                <a class="btn btn-soft small"  href="{{ route('admin.reports.show',$r) }}">عرض</a>
                <a class="btn btn-light small" href="{{ route('admin.reports.edit',$r) }}">تعديل</a>
                <form method="POST" action="{{ route('admin.reports.destroy',$r) }}"
                      onsubmit="return confirm('حذف هذا التقرير؟')" style="display:inline-block">
                  @csrf @method('DELETE')
                  <button class="btn btn-danger small" type="submit">حذف</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="empty">لا توجد بيانات</td></tr>
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