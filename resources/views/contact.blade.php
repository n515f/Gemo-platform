{{-- resources/views/contact.blade.php --}}
@extends('layouts.site')

@section('title', __('app.contact_us'))

@push('styles')
  @vite(['resources/css/entries/site.css'])
@endpush

@section('content')
  {{-- Hero --}}
  <section class="contact-hero reveal" data-reveal="up">
    <div class="shell">
      <div class="contact-hero__head">
        <img class="contact-hero__icon" src="{{ asset('images/icons/contact.png') }}" alt="">
        <div>
          <h1 class="contact-hero__title">{{ __('app.contact_hero_title') }}</h1>
          <p class="contact-hero__sub">{{ __('app.contact_hero_text') }}</p>
        </div>
      </div>

      @php
        // ====== القيم القادمة من الإعدادات ======
        $brandName = $settings['company.name_'.(app()->getLocale()==='ar'?'ar':'en')] ?? __('app.brand');
        $email     = $settings['company.email']      ?? 'info@example.com';   // بريد رئيسي (Gmail)
        $emailAlt  = $settings['company.email_alt']  ?? null;                 // بريد بديل (Yahoo)
        $phone     = $settings['company.phone']      ?? '+000000000';
        $insta     = $settings['social.instagram']   ?? '#';
        $address   = $settings['company.address_'.(app()->getLocale()==='ar'?'ar':'en')] ?? '';

        // ====== WhatsApp ======
        $whRaw    = $settings['social.whatsapp'] ?? null;                     // قد يكون رقم أو رابط wa.me
        $whNumber = $whRaw ? preg_replace('/\D+/', '', $whRaw) : null;
        $waMsg    = __('app.whatsapp_prefill', ['brand' => $brandName]);      // أضف المفتاح في lang
        $waLink   = $whNumber ? ('https://wa.me/'.$whNumber.'?text='.rawurlencode($waMsg)) : null;

        // ====== زر اتصل بنا (tel:) ======
        $phoneDigits = preg_replace('/\D+/', '', $phone ?? '');
        // نحاول إضافة + في البداية إن لم يكن موجودًا
        $telLink = $phoneDigits ? 'tel:+'.$phoneDigits : null;

        // ====== Gmail/Yahoo mailto مع subject/body ======
        $mailSubject = __('app.contact_subject', ['brand' => $brandName]);    // أضف المفتاح
        $mailBody    = __('app.contact_body',    ['brand' => $brandName]);    // أضف المفتاح

        $gmailAddr = $email;
        $yahooAddr = $emailAlt ?: 'example@yahoo.com';

        $gmailLink = 'mailto:'.$gmailAddr.'?subject='.rawurlencode($mailSubject).'&body='.rawurlencode($mailBody);
        $yahooLink = 'mailto:'.$yahooAddr.'?subject='.rawurlencode($mailSubject).'&body='.rawurlencode($mailBody);
      @endphp

      <div class="contact-hero__cta">
        {{-- واتساب --}}
        <a class="btn btn-gradient {{ $waLink ? '' : 'is-disabled' }}"
           href="{{ $waLink ?? 'javascript:void(0)' }}"
           @if($waLink) target="_blank" rel="noopener" @endif>
          {{ __('app.whatsapp_chat') }}
        </a>

        {{-- اتصل بنا (بدلاً من راسلنا عبر البريد) --}}
        <a class="btn outline {{ $telLink ? '' : 'is-disabled' }}"
           href="{{ $telLink ?? 'javascript:void(0)' }}">
          {{ __('app.call_us') }}
        </a>
      </div>
    </div>
  </section>

  {{-- قنوات التواصل (بطائق شفافة + نبض) --}}
  <section class="contact-grid shell">

    {{-- WhatsApp --}}
    <a class="channel-card glass pulse-whatsapp reveal {{ $waLink ? '' : 'is-disabled' }}"
       href="{{ $waLink ?? 'javascript:void(0)' }}"
       @if($waLink) target="_blank" rel="noopener" @endif
       aria-label="WhatsApp">
      <div class="channel-icon"><img src="{{ asset('images/whatsapp.png') }}" alt="WhatsApp"></div>
      <div class="channel-body">
        <h3>WhatsApp</h3>
        <p class="muted">{{ __('app.whatsapp_desc') }}</p>
        <div class="pill ltr">{{ $whNumber ?? '—' }}</div>
      </div>
    </a>

    {{-- Gmail (يفتح برنامج البريد لإرسال رسالة للعنوان المخزن) --}}
    <a class="channel-card glass pulse-gmail reveal"
       href="{{ $gmailLink }}"
       aria-label="Gmail">
      <div class="channel-icon"><img src="{{ asset('images/icons/gmail.png') }}" alt="Gmail"></div>
      <div class="channel-body">
        <h3>Gmail</h3>
        <p class="muted">{{ __('app.gmail_desc') }}</p>
        <div class="pill ltr">{{ $gmailAddr }}</div>
      </div>
    </a>

    {{-- Yahoo Mail (mailto للعنوان البديل) --}}
    <a class="channel-card glass pulse-yahoo reveal"
       href="{{ $yahooLink }}"
       aria-label="Yahoo Mail">
      <div class="channel-icon"><img src="{{ asset('images/icons/yahoo.png') }}" alt="Yahoo"></div>
      <div class="channel-body">
        <h3>Yahoo Mail</h3>
        <p class="muted">{{ __('app.yahoo_desc') }}</p>
        <div class="pill ltr">{{ $yahooAddr }}</div>
      </div>
    </a>

    {{-- WeChat (رابط النظام) --}}
    <a class="channel-card glass pulse-wechat reveal"
       href="weixin://dl/chat?yourid"
       target="_blank" rel="noopener"
       aria-label="WeChat">
      <div class="channel-icon"><img src="{{ asset('images/icons/wechat.png') }}" alt="WeChat"></div>
      <div class="channel-body">
        <h3>WeChat</h3>
        <p class="muted">{{ __('app.wechat_desc') }}</p>
        <div class="pill ltr">@yourid</div>
      </div>
    </a>

    {{-- Facebook --}}
    <a class="channel-card glass pulse-facebook reveal"
       href="{{ $settings['social.facebook'] ?? 'https://facebook.com' }}"
       target="_blank" rel="noopener"
       aria-label="Facebook">
      <div class="channel-icon"><img src="{{ asset('images/icons/facebook.png') }}" alt="Facebook"></div>
      <div class="channel-body">
        <h3>Facebook</h3>
        <p class="muted">{{ __('app.facebook_desc') }}</p>
        <div class="pill ltr">Adel Saeed</div>
      </div>
    </a>

    {{-- Instagram --}}
    <a class="channel-card glass pulse-instagram reveal"
       href="{{ $insta }}"
       target="_blank" rel="noopener"
       aria-label="Instagram">
      <div class="channel-icon"><img src="{{ asset('images/instagram.png') }}" alt="Instagram"></div>
      <div class="channel-body">
        <h3>Instagram</h3>
        <p class="muted">{{ __('app.instagram_desc') }}</p>
        <div class="pill ltr">@adelsaeed_</div>
      </div>
    </a>

  </section>

  {{-- خريطة (اختياري) --}}
  @if(!empty($settings['company.map_embed']))
    <section class="map-wrap reveal" data-reveal="up">
      <div class="shell">
        <div class="map-card">
          {!! $settings['company.map_embed'] !!}
        </div>
      </div>
    </section>
  @endif
@endsection