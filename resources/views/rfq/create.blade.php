@extends('layouts.site')

@push('styles')
  @vite('resources/css/rfq.css')
@endpush

@section('content')
<div class="rfq-wrap">
  <div class="rfq-card">
    <h1 class="rfq-title">{{ __('app.rfq_title') }} <span>📝</span></h1>

    <form method="POST" action="{{ route('rfq.store') }}" class="rfq-form">
      @csrf

      {{-- الاسم الكامل --}}
      <label class="rfq-label" for="full_name">{{ __('app.full_name') }}</label>
      <input id="full_name" name="full_name" type="text" required
             value="{{ old('full_name') }}"
             placeholder="{{ __('app.ph_full_name') }}"
             class="rfq-input @error('full_name') is-invalid @enderror">
      @error('full_name') <div class="rfq-err">{{ $message }}</div> @enderror

      {{-- البريد --}}
      <label class="rfq-label" for="email">{{ __('app.email') }}</label>
      <input id="email" name="email" type="email" required
             value="{{ old('email') }}"
             placeholder="{{ __('app.ph_email') }}"
             class="rfq-input @error('email') is-invalid @enderror">
      @error('email') <div class="rfq-err">{{ $message }}</div> @enderror

      {{-- الهاتف/واتساب --}}
      <label class="rfq-label" for="phone">{{ __('app.phone') }}</label>
      <input id="phone" name="phone" type="text" required
             value="{{ old('phone') }}"
             placeholder="{{ __('app.ph_phone') }}"
             class="rfq-input @error('phone') is-invalid @enderror">
      @error('phone') <div class="rfq-err">{{ $message }}</div> @enderror

      {{-- الموقع --}}
      <label class="rfq-label" for="location">{{ __('app.location') }}</label>
      <input id="location" name="location" type="text"
             value="{{ old('location') }}"
             placeholder="{{ __('app.ph_location') }}"
             class="rfq-input @error('location') is-invalid @enderror">
      @error('location') <div class="rfq-err">{{ $message }}</div> @enderror

      {{-- الخدمة المطلوبة --}}
      <label class="rfq-label" for="service">{{ __('app.service') }}</label>
      <select id="service" name="service" class="rfq-select @error('service') is-invalid @enderror" required>
        <option value="import"       @selected(old('service')==='import')>{{ __('app.service_import') }}</option>
        <option value="procurement"  @selected(old('service')==='procurement')>{{ __('app.service_procurement') }}</option>
        <option value="customs"      @selected(old('service')==='customs')>{{ __('app.service_customs') }}</option>
        <option value="installation" @selected(old('service')==='installation')>{{ __('app.service_installation') }}</option>
        <option value="training"     @selected(old('service')==='training')>{{ __('app.service_training') }}</option>
        <option value="full_line"    @selected(old('service')==='full_line')>{{ __('app.service_full_line') }}</option>
      </select>
      @error('service') <div class="rfq-err">{{ $message }}</div> @enderror

      {{-- الميزانية --}}
      <label class="rfq-label" for="budget">{{ __('app.budget') }}</label>
      <select id="budget" name="budget" class="rfq-select @error('budget') is-invalid @enderror" required>
        <option value="under_20k"  @selected(old('budget')==='under_20k')>{{ __('app.budget_under_20k') }}</option>
        <option value="20_100k"    @selected(old('budget')==='20_100k')>{{ __('app.budget_20_100k') }}</option>
        <option value="100_500k"   @selected(old('budget')==='100_500k')>{{ __('app.budget_100_500k') }}</option>
        <option value="over_500k"  @selected(old('budget')==='over_500k')>{{ __('app.budget_over_500k') }}</option>
      </select>
      @error('budget') <div class="rfq-err">{{ $message }}</div> @enderror

      {{-- الوصف المختصر --}}
      <label class="rfq-label" for="brief">{{ __('app.brief') }}</label>
      <textarea id="brief" name="brief" rows="5"
                class="rfq-textarea @error('brief') is-invalid @enderror"
                placeholder="{{ __('app.ph_brief') }}">{{ old('brief') }}</textarea>
      @error('brief') <div class="rfq-err">{{ $message }}</div> @enderror

      {{-- زر الإرسال أو التحويل لتسجيل الدخول --}}
      @auth
        <button type="submit" class="rfq-submit">{{ __('app.submit') }}</button>
      @else
        <a class="btn btn-gradient" href="{{ route('login') }}">{{ __('سجّل الدخول لطلب عرض السعر') }}</a>
      @endauth

      <p class="rfq-note">{{ __('app.rfq_note_whatsapp') }}</p>
    </form>
  </div>
</div>
@endsection