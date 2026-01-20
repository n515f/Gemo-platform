@php
  $action = $action ?? route('admin.reports.store');
  $method = $method ?? 'POST';
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="grid two">
  @csrf
  @if($method !== 'POST') @method($method) @endif

  <div>
    <label class="lbl">{{ __('app.report_title') }}</label>
    <input class="input" type="text" name="title" value="{{ old('title', $report->title ?? '') }}" required>
    @error('title') <div class="flash error">{{ $message }}</div> @enderror
  </div>

  <div>
    <label class="lbl">{{ __('app.project_optional') }}</label>
    <select class="select" name="project_id">
      <option value="">{{ __('app.none') }}</option>
      @foreach($projects as $p)
        <option value="{{ $p->id }}" @selected(old('project_id', $report->project_id ?? '') == $p->id)>
          {{ $p->title }}
        </option>
      @endforeach
    </select>
    @error('project_id') <div class="flash error">{{ $message }}</div> @enderror
  </div>

  <div class="col-span-2" style="grid-column: 1 / -1;">
    <label class="lbl">{{ __('app.notes') }}</label>
    <textarea class="textarea" name="notes" rows="6">{{ old('notes', $report->notes ?? '') }}</textarea>
    @error('notes') <div class="flash error">{{ $message }}</div> @enderror
  </div>

  <div class="col-span-2" style="grid-column: 1 / -1;">
    <label class="lbl">{{ __('app.attachments_multiple') }}</label>
    <input class="input" type="file" name="attachments[]" multiple>
    @error('attachments.*') <div class="flash error">{{ $message }}</div> @enderror
  </div>

  <div class="mt" style="grid-column: 1 / -1; display:flex; gap:8px; flex-wrap:wrap;">
    <button class="btn btn-primary" type="submit">{{ __('app.save') }}</button>
    @if($method !== 'POST')
      <button class="btn btn-light" name="cancel" value="1">{{ __('app.cancel') }}</button>
    @endif
  </div>
</form>
