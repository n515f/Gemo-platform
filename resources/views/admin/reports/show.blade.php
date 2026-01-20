@extends('layouts.admin')

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
<section class="admin-page">
  <header class="page-head">
    <div class="lhs">
      <h1 class="title">{{ __('app.report_hash_id', ['id' => $report->id]) }}</h1>
      <p class="muted">
        {{ optional($report->project)->title ?? __('app.no_project') }}
        — {{ __('app.by') }} {{ optional($report->user)->name ?? '—' }}
      </p>
    </div>
    <div class="rhs">
      <a class="btn btn-light"   href="{{ route('admin.reports.edit', $report) }}">{{ __('app.edit') }}</a>
      <a class="btn btn-primary" href="{{ route('admin.reports.index') }}">{{ __('app.back_to_list') }}</a>
    </div>
  </header>

  @include('components.flash')

  <div class="card">
    <h3 class="card-title">{{ $report->title }}</h3>
    <div class="stack">
      <div class="muted">
        {{ __('app.created_at_label') }}: {{ optional($report->created_at)->format('Y-m-d H:i') }}
        — {{ __('app.last_updated_label') }}: {{ optional($report->updated_at)->format('Y-m-d H:i') }}
      </div>
      <div class="mt">{!! nl2br(e($report->notes)) !!}</div>
    </div>
  </div>

  @if($report->attachments)
    @php
      $atts = is_array($report->attachments)
        ? $report->attachments
        : (json_decode($report->attachments, true) ?: []);
    @endphp

    <div class="card">
      <h3 class="card-title">{{ __('app.attachments') }}</h3>
      <div class="attachments">
        @forelse($atts as $p)
          <a class="att" href="{{ asset($p) }}" target="_blank">{{ __('app.open') }}</a>
        @empty
          <div class="muted">{{ __('app.no_attachments') }}</div>
        @endforelse
      </div>
    </div>
  @endif
</section>
@endsection
