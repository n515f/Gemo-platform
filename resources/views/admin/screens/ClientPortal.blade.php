{{-- resources/views/admin/screens/ClientPortal.blade.php --}}
@extends('layouts.site')

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
  @php
    // مسارات أيقونات SVG (ضعها في public/images/icons/)
    $cards = [
      [
        'title' => __('الرئيسية'),
        'desc'  => __('الصفحة الافتتاحية والمحتوى التعريفي.'),
        'href'  => route('home'),
        'icon'  => asset('images/icons/home.png'),
      ],
      [
        'title' => __('خدماتنا'),
        'desc'  => __('كل خدمات الشركة ومجالات العمل.'),
        'href'  => route('services.index'),
        'icon'  => asset('images/icons/services.png'),
      ],
      [
        'title' => __('الكتالوج'),
        'desc'  => __('استعراض المنتجات والمواصفات.'),
        'href'  => route('catalog.index'),
        'icon'  => asset('images/icons/catalog.png'),
      ],
      [
        'title' => __('طلب عرض سعر'),
        'desc'  => __('نموذج إرسال طلب عرض السعر.'),
        'href'  => route('rfq.create'),
        'icon'  => asset('images/icons/rfq.png'),
      ],
      [
        'title' => __('تواصل معنا'),
        'desc'  => __('معلومات الاتصال ونموذج المراسلة.'),
        'href'  => route('contact'),
        'icon'  => asset('images/icons/contact.png'),
      ],
    ];
  @endphp

  <section class="client-portal">
    <h1 class="cp-title">{{ __('شاشات العميل') }}</h1>
    <p class="cp-sub">
      {{ __('هذه بطائق مختصرة لصفحات الواجهة العامة — للاطّلاع والتنقّل السريع من قبل الأدمن.') }}
    </p>

    <div class="cp-grid">
      @foreach($cards as $c)
        <a class="cp-card" href="{{ $c['href'] }}">
          <div class="cp-media" aria-hidden="true">
            <img src="{{ $c['icon'] }}" alt="{{ $c['title'] }}" width="64" height="64" loading="lazy">
          </div>

          <div class="cp-body">
            <h3 class="cp-head">{{ $c['title'] }}</h3>
            <p class="cp-desc">{{ $c['desc'] }}</p>
          </div>

          <div class="cp-actions">
            <span class="btn btn-go">{{ __('انتقال') }}</span>
          </div>
        </a>
      @endforeach
    </div>
  </section>
@endsection