{{-- resources/views/admin/projects/show.blade.php --}}
@extends('layouts.admin')
@push('styles') @vite(['resources/css/entries/admin.css']) @endpush

@section('content')
<section class="admin-page">
  <header class="page-head">
    <div class="lhs">
      <h1 class="title">{{ $project->title }}</h1>
      <p class="muted">
        {{ __('app.client') }}: {{ $project->client_name }} •
        {{ __('app.status') }}: <span class="badge">{{ __($project->status) }}</span>
      </p>
    </div>
    <div class="rhs">
      <a class="btn btn--search btn--outline" href="{{ route('admin.projects.index') }}">
          <svg class="ico" viewBox="0 0 24 24">
              <path d="M19 12H5M12 19l-7-7 7-7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          {{ __('app.back') }}
      </a>
      <a class="btn btn--edit" href="{{ route('admin.projects.edit', $project) }}">
          <svg class="ico" viewBox="0 0 24 24">
              <path d="M14.5 3.5a2.5 2.5 0 0 1 3.5 3.5L8 17l-4 1 1-4 9.5-10.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
          </svg>
          {{ __('app.edit') }}
      </a>
    </div>
  </header>

  @include('components.flash')

  <div class="grid two">
    <div class="card">
      <h3 class="card-title">{{ __('app.project_data') }}</h3>
      <div class="stack">
        <div><strong>{{ __('المنتج') }}:</strong> {{ optional($project->product)->name_ar ?? '—' }}</div>
        <div><strong>{{ __('البداية') }}:</strong> {{ $project->start_date ?: '—' }}</div>
        <div><strong>{{ __('الانتهاء') }}:</strong> {{ $project->due_date ?: '—' }}</div>
        <div><strong>{{ __('ملاحظات') }}:</strong> {{ $project->notes ?: '—' }}</div>
        <div class="muted">{{ __('آخر تحديث') }}: {{ optional($project->updated_at)->format('Y-m-d H:i') }}</div>
      </div>
    </div>

    <div class="card">
      <h3 class="card-title">{{ __('إضافة تحديث') }}</h3>
      <form method="POST" action="{{ route('admin.projects.updates.store', $project) }}" enctype="multipart/form-data">
        @csrf
        <div class="grid two">
          <div>
            <label class="lbl">{{ __('الحالة (اختياري)') }}</label>
            <select class="select" name="status">
              <option value="">{{ __('بدون تغيير') }}</option>
              @foreach($statuses as $st)
                <option value="{{ $st }}">{{ __($st) }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="lbl">{{ __('مرفقات') }}</label>
            <input type="file" name="attachments[]" multiple>
          </div>
        </div>
        <div>
          <label class="lbl">{{ __('ملاحظة') }}</label>
          <textarea name="note" class="textarea" rows="3"></textarea>
        </div>
        <div class="actions">
          <button class="btn btn--add" type="submit">
              <svg class="ico" viewBox="0 0 24 24">
                  <path d="M12 5v14M5 12h14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              {{ __('إضافة') }}
          </button>
        </div>
      </form>
    </div>
  </div>

  <div class="card">
    <h3 class="card-title">{{ __('سجل التحديثات') }}</h3>
    <div class="updates">
      @forelse($project->updates as $u)
        <div class="update-item">
          <div class="update-head">
            <strong>#{{ $u->id }}</strong>
            <span class="muted">{{ optional($u->created_at)->format('Y-m-d H:i') }}</span>
            @if($u->status)<span class="badge">{{ __($u->status) }}</span>@endif
          </div>
          <div class="update-body">
            <p>{{ $u->note ?: '—' }}</p>
            @php
              $files = $u->attachments ? json_decode($u->attachments, true) : [];
            @endphp
            @if($files && is_array($files))
              <div class="attachments">
                @foreach($files as $fp)
                  <a class="att" href="{{ asset($fp) }}" target="_blank" rel="noopener">{{ basename($fp) }}</a>
                @endforeach
              </div>
            @endif
          </div>
          <form method="POST" action="{{ route('admin.projects.updates.destroy', [$project, $u]) }}" onsubmit="return confirm('حذف هذا التحديث؟')">
            @csrf @method('DELETE')
            <button class="btn btn--delete btn--sm" type="submit">
                <svg class="ico" viewBox="0 0 24 24">
                    <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6M10 11v6M14 11v6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                {{ __('حذف التحديث') }}
            </button>
          </form>
        </div>
      @empty
        <div class="empty">{{ __('لا توجد تحديثات بعد.') }}</div>
      @endforelse
    </div>
  </div>
</section>
@endsection