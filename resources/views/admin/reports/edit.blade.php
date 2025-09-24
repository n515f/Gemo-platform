@extends('layouts.site')

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
<section class="admin-page">
  <header class="page-head">
    <h1 class="title">تعديل تقرير #{{ $report->id }}</h1>
  </header>

  @include('components.flash')

  <div class="card">
    @include('admin.reports._form', [
      'projects'=>$projects,
      'report'=>$report,
      'action'=>route('admin.reports.update',$report),
      'method'=>'PUT'
    ])
  </div>

  @if($report->attachments)
    @php $atts = is_array($report->attachments) ? $report->attachments : (json_decode($report->attachments,true) ?: []); @endphp
    <div class="card">
      <h3 class="card-title">المرفقات الحالية</h3>
      <div class="attachments">
        @forelse($atts as $p)
          <div class="att">
            <a href="{{ asset($p) }}" target="_blank">فتح</a>
            <form method="POST" action="{{ route('admin.reports.attachment.destroy',$report) }}" style="display:inline;">
              @csrf @method('DELETE')
              <input type="hidden" name="path" value="{{ $p }}">
              <button class="btn btn-danger small" onclick="return confirm('حذف هذا المرفق؟')">حذف</button>
            </form>
          </div>
        @empty
          <div class="muted">لا توجد مرفقات.</div>
        @endforelse
      </div>
    </div>
  @endif
</section>
@endsection