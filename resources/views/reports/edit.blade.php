{{-- resources/views/reports/edit.blade.php --}}
@extends('layouts.site')

@section('title', __('app.edit_report'))

@push('styles')
  @vite(['resources/css/entries/site.css','resources/js/app.js'])
@endpush

@section('content')
<div class="container mx-auto py-6">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-6">

        <h1 class="text-2xl font-bold mb-6 text-gray-800">{{ __('app.edit_report') }}</h1>

        @include('components.flash')

        <form method="POST" action="{{ route('reports.update', $report) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            {{-- المشروع --}}
            <div class="mb-4">
                <label for="project_id" class="block text-gray-700 font-medium mb-1">
                    {{ __('app.project') }}
                </label>
                <select name="project_id" id="project_id" class="w-full border rounded p-2">
                    <option value="">{{ __('app.choose_project') }}</option>
                    @foreach($projects as $proj)
                        <option value="{{ $proj->id }}" {{ old('project_id', $report->project_id) == $proj->id ? 'selected' : '' }}>
                            {{ $proj->title ?? ('#'.$proj->id) }}
                        </option>
                    @endforeach
                </select>
                @error('project_id') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            {{-- العنوان --}}
            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-medium mb-1">
                    {{ __('app.report_title') }}
                </label>
                <input type="text" name="title" id="title"
                       value="{{ old('title', $report->title) }}"
                       class="w-full border rounded p-2" required>
                @error('title') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            {{-- الملاحظات --}}
            <div class="mb-4">
                <label for="notes" class="block text-gray-700 font-medium mb-1">
                    {{ __('app.notes') }}
                </label>
                <textarea name="notes" id="notes" rows="5" class="w-full border rounded p-2">{{ old('notes', $report->notes) }}</textarea>
                @error('notes') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            {{-- المرفقات --}}
            <div class="mb-4">
                <label for="attachments" class="block text-gray-700 font-medium mb-1">
                    {{ __('app.add_attachments') }}
                </label>
                <input type="file" name="attachments[]" id="attachments" multiple class="w-full border rounded p-2">
                @error('attachments.*') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="flex items-center gap-2 justify-end">
                <a href="{{ route('reports.index') }}" class="px-4 py-2 rounded border hover:bg-gray-50">
                    {{ __('app.cancel') }}
                </a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    {{ __('app.save_changes') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection