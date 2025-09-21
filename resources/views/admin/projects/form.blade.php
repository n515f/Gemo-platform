@csrf
<div class="grid two">
  <div>
    <label class="lbl">{{ __('العنوان') }}</label>
    <input class="input" type="text" name="title" value="{{ old('title', $project->title ?? '') }}" required>
  </div>
  <div>
    <label class="lbl">{{ __('اسم العميل') }}</label>
    <input class="input" type="text" name="client_name" value="{{ old('client_name', $project->client_name ?? '') }}" required>
  </div>
</div>

<div class="grid two">
  <div>
    <label class="lbl">{{ __('المنتج (اختياري)') }}</label>
    <select class="select" name="product_id">
      <option value="">{{ __('— لا شيء —') }}</option>
      @foreach($products as $prod)
        <option value="{{ $prod->id }}" @selected(old('product_id', $project->product_id ?? null)==$prod->id)>
          {{ $prod->name_ar ?? $prod->name_en }}
        </option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="lbl">{{ __('الحالة') }}</label>
    <select class="select" name="status" required>
      @foreach($statuses as $st)
        <option value="{{ $st }}" @selected(old('status', $project->status ?? 'supply')===$st)>{{ __($st) }}</option>
      @endforeach
    </select>
  </div>
</div>

<div class="grid two">
  <div>
    <label class="lbl">{{ __('تاريخ البدء') }}</label>
    <input class="input" type="date" name="start_date" value="{{ old('start_date', $project->start_date ?? '') }}">
  </div>
  <div>
    <label class="lbl">{{ __('تاريخ الانتهاء') }}</label>
    <input class="input" type="date" name="due_date" value="{{ old('due_date', $project->due_date ?? '') }}">
  </div>
</div>

<div>
  <label class="lbl">{{ __('ملاحظات') }}</label>
  <textarea class="textarea" name="notes" rows="4">{{ old('notes', $project->notes ?? '') }}</textarea>
</div>

<div class="actions mt">
  <button class="btn btn-primary" type="submit">{{ $submit ?? __('حفظ') }}</button>
  <button form="cancelForm" class="btn btn-light" type="submit">{{ __('إلغاء') }}</button>
</div>