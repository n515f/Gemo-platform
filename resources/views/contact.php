@extends('layouts.site')


@push('styles')
  @vite(['resources/css/entries/site.css','resources/js/app.js'])
@endpush
  <h1 class="page-title">{{ __('app.contact_us') }}</h1>

  <section class="hero">
    <h1>{{ __('app.contact_hero_title') }}</h1>
    <p>{{ __('app.contact_hero_text') }}</p>
    <div class="cta">
      @php
        $wh = $settings['social.whatsapp'] ?? null; // مثال: 9665xxxxxxx
        $whNumber = $wh ? preg_replace('/\D+/', '', $wh) : null;
        $waLink = $whNumber ? "https://wa.me/{$whNumber}" : '#';
      @endphp
      <a class="btn btn-gradient" href="{{ $waLink }}" target="_blank" rel="noopener">{{ __('app.whatsapp_chat') }}</a>
      <a class="btn outline" href="mailto:{{ $settings['company.email'] ?? 'info@example.com' }}">{{ __('app.email_us') }}</a>
    </div>
  </section>

  <div class="grid" style="margin-top:14px">
    <div class="card">
      <div class="card-body">
        <h3 class="card-title">{{ __('app.contact_info') }}</h3>
        <p class="desc">
          {{ $settings['company.name_'.(app()->getLocale()==='ar'?'ar':'en')] ?? __('app.brand') }}<br>
          {{ $settings['company.email'] ?? 'info@example.com' }}<br>
          {{ $settings['company.phone'] ?? '0000000000' }}<br>
          {{ $settings['company.address_'.(app()->getLocale()==='ar'?'ar':'en')] ?? '' }}
        </p>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <h3 class="card-title">{{ __('app.quick_links') }}</h3>
        <div class="actions">
          <a class="btn" href="{{ route('catalog.index') }}">{{ __('app.catalog') }}</a>
          <a class="btn primary" href="{{ route('rfq.create') }}">{{ __('app.rfq') }}</a>
        </div>
      </div>
    </div>
  </div>
@endsection
