@csrf
<div class="grid two">
  <div>
    <label class="lbl">{{ __('app.title') }}</label>
    <input class="input" type="text" name="title" value="{{ old('title', $project->title ?? '') }}" required>
  </div>
  <div>
    <label class="lbl">{{ __('app.client_name') }}</label>
    <input class="input" type="text" name="client_name" value="{{ old('client_name', $project->client_name ?? '') }}" required>
  </div>
</div>

<div class="grid two">
  <div>
    <label class="lbl">{{ __('app.product_optional') }}</label>
    <select class="select" name="product_id">
      <option value="">{{ __('app.none') }}</option>
      @foreach($products as $prod)
        <option value="{{ $prod->id }}" @selected(old('product_id', $project->product_id ?? null)==$prod->id)>
          {{ $prod->name_ar ?? $prod->name_en }}
        </option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="lbl">{{ __('app.status') }}</label>
    <select class="select" name="status" required>
      @foreach($statuses as $st)
        <option value="{{ $st }}" @selected(old('status', $project->status ?? 'supply')===$st)>
          {{ __('app.statuses.' . $st) }}
        </option>
      @endforeach
    </select>
  </div>
</div>

<div class="grid two">
  <div>
    <label class="lbl">{{ __('app.start_date') }}</label>
    <input class="input" type="date" name="start_date" value="{{ old('start_date', $project->start_date ?? '') }}">
  </div>
  <div>
    <label class="lbl">{{ __('app.due_date') }}</label>
    <input class="input" type="date" name="due_date" value="{{ old('due_date', $project->due_date ?? '') }}">
  </div>
</div>

<div>
  <label class="lbl">{{ __('app.notes') }}</label>
  <textarea class="textarea" name="notes" rows="4">{{ old('notes', $project->notes ?? '') }}</textarea>
</div>

<div class="actions mt">
  <button class="btn btn-primary" type="submit">{{ $submit ?? __('app.save') }}</button>
  <button form="cancelForm" class="btn btn-light" type="submit">{{ __('app.cancel') }}</button>
</div>
