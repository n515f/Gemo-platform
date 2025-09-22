{{-- resources/views/reports/index.blade.php --}}
@extends('layouts.site')

@section('title', __('app.reports'))

@push('styles')
  @vite(['resources/css/entries/site.css','resources/js/app.js'])
@endpush

@section('content')
<div class="container mx-auto py-6">
    <div class="max-w-5xl mx-auto bg-white rounded-xl shadow p-6">

        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold text-gray-800">{{ __('app.reports') }}</h1>
            <a href="{{ route('reports.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
               {{ __('app.create_report') }}
            </a>
        </div>

        {{-- فلاش --}}
        @include('components.flash')

        @forelse($reports as $r)
            <a href="{{ route('reports.show', $r) }}"
               class="block border rounded-lg p-4 mb-3 hover:shadow transition">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-bold text-gray-800">{{ $r->title }}</div>
                        <div class="text-sm text-gray-500">
                            {{ __('app.project') }}:
                            {{ optional($r->project)->title ?? '—' }}
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ optional($r->created_at)->format('Y-m-d H:i') }}
                    </div>
                </div>
                @if($r->notes)
                    <p class="mt-2 text-gray-700 line-clamp-2">{{ Str::limit($r->notes, 180) }}</p>
                @endif
            </a>
        @empty
            <div class="text-gray-500 font-medium">{{ __('app.no_data') }}</div>
        @endforelse

        @if(method_exists($reports, 'links'))
            <div class="mt-4">
                {{ $reports->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection