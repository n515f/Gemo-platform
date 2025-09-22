{{-- resources/views/reports/show.blade.php --}}
@extends('layouts.site')

@section('title', $report->title)

@push('styles')
  @vite(['resources/css/entries/site.css','resources/js/app.js'])
@endpush

@section('content')
<div class="container mx-auto py-6">
  <div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-6">

    <div class="flex items-start justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">{{ $report->title }}</h1>
        <div class="text-sm text-gray-500 mt-1">
          {{ __('app.project') }}:
          {{ optional($report->project)->title ?? '—' }}
        </div>
        <div class="text-xs text-gray-400 mt-1">
          {{ __('app.created_at') }}: {{ optional($report->created_at)->format('Y-m-d H:i') }}
        </div>
      </div>

      <div class="flex items-center gap-2">
        <a href="{{ route('reports.edit', $report) }}" class="px-3 py-2 rounded border hover:bg-gray-50">
          {{ __('app.edit') }}
        </a>

       <form method="POST" action="{{ route('reports.destroy', $report) }}"
      data-confirm="{{ __('app.confirm_delete') }}">
  @csrf @method('DELETE')
  <button class="px-3 py-2 rounded bg-red-600 text-white hover:bg-red-700">
    {{ __('app.delete') }}
  </button>
</form>
      </div>
    </div>

    @include('components.flash')

    @if($report->notes)
      <div class="mt-5">
        <h3 class="font-bold mb-1 text-gray-700">{{ __('app.notes') }}</h3>
        <p class="text-gray-800 whitespace-pre-line">{{ $report->notes }}</p>
      </div>
    @endif

    @php
      $files = is_string($report->attachments) ? json_decode($report->attachments, true) : $report->attachments;
    @endphp
    @if(!empty($files))
      <div class="mt-6">
        <h3 class="font-bold mb-2 text-gray-700">{{ __('app.attachments') }}</h3>
        <div class="flex flex-wrap gap-3">
          @foreach($files as $file)
            <a href="{{ asset($file) }}" target="_blank"
               class="inline-flex items-center px-3 py-2 rounded border hover:bg-gray-50">
              📎 {{ basename($file) }}
            </a>
          @endforeach
        </div>
      </div>
    @endif

    <div class="mt-6">
      <a href="{{ route('reports.index') }}" class="px-4 py-2 rounded border hover:bg-gray-50">
        ← {{ __('app.back') }}
      </a>
    </div>
  </div>
</div>
@endsection