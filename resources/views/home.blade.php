{{-- resources/views/home.blade.php --}}
@extends('layouts.site')

@section('title', __('app.home'))

@push('styles')
  @vite(['resources/css/entries/site.css'])
@endpush

@section('content')
  @include('components.flash')

  @php $isAr = app()->getLocale() === 'ar'; @endphp

  {{-- ===== تعريف الشركة + CTA ===== --}}
  <section class="home-hero divider-b reveal">
    <div class="wrap">
      <div class="hero-brand reveal">
        <div class="brand-card">
          <img class="brand-logo" src="{{ asset('images/logo.png') }}" alt="Company Logo">
          <div class="brand-info">
            <div class="brand-name">{{ __('app.brand') }}</div>
            <div class="brand-desc">{{ __('app.tagline') }}</div>
            <div class="brand-loc">{{ __('app.address') }}: {{ __('app.sultanate_oman') }}</div>
          </div>
          <div class="cta">
            <a class="btn btn-light btn-home" href="{{ route('catalog.index') }}">{{ __('app.catalog') }}</a>
            <a class="btn btn-primary btn-home" href="{{ route('rfq.create') }}">{{ __('app.cta_rfq') }}</a>
          </div>
        </div>
      </div>
    </div>
  </section>


  {{-- ===== المدير التنفيذي + شهادات ===== --}}
  <section class="home-section reveal">
    <div class="sec-head icon-head">
      <img class="sec-ico" src="{{ asset('images/icons/manager.png') }}" alt="">
      <div>
        <h2>{{ __('app.ceo') }}</h2>
        <p class="sec-sub">{{ __('app.brief_expertise') }}</p>
      </div>
    </div>

    <div class="ceo-block">
      <div class="ceo-card">
        <img class="ceo-avatar lg" src="{{ asset('images/manager.png') }}" alt="CEO">
        <div class="ceo-info">
          <div class="ceo-name">{{ $isAr ? 'م. عادل سعيد' : 'Eng. Adel Saeed' }}</div>
          <div class="ceo-title">{{ __('app.ceo') }}</div>
          <ul class="ceo-bullets">
            <li>{{ __('app.experience_15_years') }}</li>
            <li>{{ __('app.managed_projects') }}</li>
            <li>{{ __('app.built_high_performing_teams') }}</li>
          </ul>
        </div>
      </div>

      <div class="certs">
        <img src="{{ asset('images/experience/1.png') }}" alt="Certificate">
        <img src="{{ asset('images/experience/2.png') }}" alt="Certificate">
        <img src="{{ asset('images/experience/3.png') }}" alt="Certificate">
        <img src="{{ asset('images/experience/4.png') }}" alt="Certificate">
      </div>
    </div>
  </section>


  {{-- ===== الإعلانات (بعد المدير التنفيذي) ===== --}}
  @if(($homeAds ?? collect())->count())
    <section class="home-section reveal">
      <div class="sec-head">
        <h2>{{ $isAr ? 'إعلانات' : 'Announcements' }}</h2>
        <p class="sec-sub">{{ $isAr ? 'أحدث الإعلانات والصور من الإدارة.' : 'Latest announcements & visuals from management.' }}</p>
      </div>

      <div class="ads-rotator" data-interval-sec="30" data-fade="500">
        @foreach($homeAds as $ad)
          @php
            $imgs   = is_array($ad->images) ? $ad->images : (json_decode($ad->images ?? '[]', true) ?: []);
            $first  = $imgs[0] ?? 'images/services/full-line.jpg';
            $durMin = $ad->duration_min ?? null;
            $durSec = $ad->duration_sec ?? null;
          @endphp

          <article class="ad"
            data-images='@json($imgs, JSON_UNESCAPED_UNICODE)'
            @if($durMin) data-duration-min="{{ $durMin }}" @endif
            @if($durSec) data-duration-sec="{{ $durSec }}" @endif
          >
            <div class="ad-visual">
              <img src="{{ asset($first) }}" alt="">
            </div>
            <div class="ad-overlay">
              @if($ad->title_ar || $ad->title_en)
                <h3 class="ad-title">{{ app()->getLocale()==='ar' ? ($ad->title_ar ?: $ad->title_en) : ($ad->title_en ?: $ad->title_ar) }}</h3>
              @endif
              @if($ad->desc_ar || $ad->desc_en)
                <p class="ad-desc">{{ app()->getLocale()==='ar' ? ($ad->desc_ar ?: $ad->desc_en) : ($ad->desc_en ?: $ad->desc_ar) }}</p>
              @endif
              @if($ad->location_title)
                <div class="ad-loc">{{ $ad->location_title }}</div>
              @endif
            </div>
          </article>
        @endforeach
      </div>
    </section>
  @endif

  {{-- ===== خدماتنا ===== --}}
  <section id="services" class="home-section reveal">
    <div class="sec-head icon-head">
      <img class="sec-ico" src="{{ asset('images/icons/services.png') }}" alt="">
      <div>
        <h2>{{ __('app.services') }}</h2>
        <p class="sec-sub">
          {{ $isAr ? 'نقدّم دورة متكاملة تشمل التوريد، التخليص، التركيب، التشغيل، التدريب والصيانة.' :
                      'End-to-end cycle: supply, clearance, installation, commissioning, training & maintenance.' }}
        </p>
      </div>
    </div>

    <div class="cards-5">
      <a class="svc-card pulse delay-0" href="{{ route('services.index') }}">
        <img src="{{ asset('images/services/S1.png') }}" alt="" class="ico">
        <h3>{{ __('app.service_supply') }}</h3>
        <p>{{ __('app.service_supply_desc') }}</p>
      </a>

      <a class="svc-card pulse delay-1" href="{{ route('services.index') }}">
        <img src="{{ asset('images/services/S2.png') }}" alt="" class="ico">
        <h3>{{ $isAr ? 'التخليص والشحن' : 'Clearance & Shipping' }}</h3>
        <p>{{ $isAr ? 'شحن دولي، تخليص جمركي، وإدارة سلاسل الإمداد.' : 'International shipping, customs & supply chain.' }}</p>
      </a>

      <a class="svc-card pulse delay-2" href="{{ route('services.index') }}">
        <img src="{{ asset('images/services/S3.png') }}" alt="" class="ico">
        <h3>{{ __('app.service_install') }}</h3>
        <p>{{ __('app.service_install_desc') }}</p>
      </a>

      <a class="svc-card pulse delay-3" href="{{ route('services.index') }}">
        <img src="{{ asset('images/services/S4.png') }}" alt="" class="ico">
        <h3>{{ $isAr ? 'التشغيل والتدريب' : 'Commissioning & Training' }}</h3>
        <p>{{ $isAr ? 'تشغيل أولي وتدريب الفريق للوصول لأعلى إنتاجية.' : 'Commissioning & staff training for peak output.' }}</p>
      </a>

      <a class="svc-card pulse delay-4" href="{{ route('services.index') }}">
        <img src="{{ asset('images/services/S5.png') }}" alt="" class="ico">
        <h3>{{ __('app.service_maint') }}</h3>
        <p>{{ __('app.service_maint_desc') }}</p>
      </a>
    </div>
  </section>

  {{-- ===== القطاعات التي نخدمها (ماركيه يتوقف عند المرور) ===== --}}
  <section class="home-section alt reveal">
    <div class="sec-head icon-head">
      <img class="sec-ico" src="{{ asset('images/icons/Sectors.png') }}" alt="">
      <div>
        <h2>{{ __('app.industries_we_serve') }}</h2>
        <p class="sec-sub">{{ __('app.cross_industry_solutions') }}</p>
      </div>
    </div>

    <div class="sectors-marquee" aria-label="sectors">
      <div class="sectors-track">
        <div class="sector"><img src="{{ asset('images/sectors/food.png') }}"          alt=""><span>{{ $isAr ? 'الأغذية والمشروبات' : 'F&B' }}</span></div>
        <div class="sector"><img src="{{ asset('images/sectors/packaging.png') }}"     alt=""><span>{{ $isAr ? 'البلاستيك والتعبئة' : 'Plastics & Packaging' }}</span></div>
        <div class="sector"><img src="{{ asset('images/sectors/pharmaceuticals.png') }}"alt=""><span>{{ $isAr ? 'الأدوية' : 'Pharmaceuticals' }}</span></div>
        <div class="sector"><img src="{{ asset('images/sectors/textiles.png') }}"       alt=""><span>{{ $isAr ? 'المنسوجات' : 'Textiles' }}</span></div>
        <div class="sector"><img src="{{ asset('images/sectors/chemicals.png') }}"      alt=""><span>{{ $isAr ? 'الكيماويات' : 'Chemicals' }}</span></div>
        <div class="sector"><img src="{{ asset('images/sectors/logistics.png') }}"      alt=""><span>{{ $isAr ? 'اللوجستيات' : 'Logistics' }}</span></div>

        {{-- نسخة ثانية لاستمرارية الحركة --}}
        <div class="sector"><img src="{{ asset('images/sectors/food.png') }}"          alt=""><span>{{ $isAr ? 'الأغذية والمشروبات' : 'F&B' }}</span></div>
        <div class="sector"><img src="{{ asset('images/sectors/packaging.png') }}"     alt=""><span>{{ $isAr ? 'البلاستيك والتعبئة' : 'Plastics & Packaging' }}</span></div>
        <div class="sector"><img src="{{ asset('images/sectors/pharmaceuticals.png') }}"alt=""><span>{{ $isAr ? 'الأدوية' : 'Pharmaceuticals' }}</span></div>
        <div class="sector"><img src="{{ asset('images/sectors/textiles.png') }}"       alt=""><span>{{ $isAr ? 'المنسوجات' : 'Textiles' }}</span></div>
        <div class="sector"><img src="{{ asset('images/sectors/chemicals.png') }}"      alt=""><span>{{ $isAr ? 'الكيماويات' : 'Chemicals' }}</span></div>
        <div class="sector"><img src="{{ asset('images/sectors/logistics.png') }}"      alt=""><span>{{ $isAr ? 'اللوجستيات' : 'Logistics' }}</span></div>
      </div>
    </div>
  </section>

  {{-- ===== الكتالوج (بطاقات زجاجية) ===== --}}
  <section id="catalog" class="home-section reveal">
    <div class="sec-head icon-head">
      <img class="sec-ico" src="{{ asset('images/icons/catalog.png') }}" alt="">
      <div>
        <h2>{{ __('app.catalog') }}</h2>
        <p class="sec-sub">{{ __('app.explore_products_lines') }}</p>
      </div>
    </div>

    <div class="gallery-3">
      @isset($featuredProducts)
        @forelse($featuredProducts as $p)
          <a class="glass-card delay-{{ $loop->index }}" href="{{ route('catalog.index') }}">
            <img src="{{ asset($p->images->first()->path ?? 'images/no-image.png') }}" alt="">
            <div class="glass-overlay">
              <div class="name">{{ $p->name_ar ?? $p->name_en }}</div>
              <div class="meta">
                <span class="badge">#{{ $p->code ?? $p->id }}</span>
                @if(!is_null($p->price)) <span class="price">{{ number_format($p->price) }}</span>@endif
              </div>
            </div>
          </a>
        @empty
          <div class="empty">{{ __('app.no_items') }}</div>
        @endforelse
      @else
        <a class="glass-card delay-0" href="{{ route('catalog.index') }}">
          <img src="{{ asset('images/services/full-line.jpg') }}" alt="">
          <div class="glass-overlay"><div class="name">{{ __('app.full_line') }}</div></div>
        </a>
        <a class="glass-card delay-1" href="{{ route('catalog.index') }}">
          <img src="{{ asset('images/services/training.jpg') }}" alt="">
          <div class="glass-overlay"><div class="name">{{ __('app.training') }}</div></div>
        </a>
        <a class="glass-card delay-2" href="{{ route('catalog.index') }}">
          <img src="{{ asset('images/services/customer-service.png') }}" alt="">
          <div class="glass-overlay"><div class="name">{{ __('app.support') }}</div></div>
        </a>
      @endisset
    </div>
  </section>

  {{-- ===== أعمال مختارة ===== --}}
  <section class="home-section alt reveal">
    <div class="sec-head icon-head">
      <img class="sec-ico" src="{{ asset('images/icons/works.png') }}" alt="">
      <div>
        <h2>{{ __('app.selected_works') }}</h2>
        <p class="sec-sub">{{ __('app.samples_installation') }}</p>
      </div>
    </div>

    <div class="gallery-3">
      <div class="work-card delay-0">
        <img src="{{ asset('images/services/installation.jpg') }}" alt="">
        <div class="work-overlay"><div class="name">{{ __('app.packaging_line_installation') }}</div></div>
      </div>
      <div class="work-card delay-1">
        <img src="{{ asset('images/services/full-line.jpg') }}" alt="">
        <div class="work-overlay"><div class="name">{{ __('app.commissioning_full_line') }}</div></div>
      </div>
      <div class="work-card delay-2">
        <img src="{{ asset('images/services/training.jpg') }}" alt="">
        <div class="work-overlay"><div class="name">{{ __('app.operations_team_training') }}</div></div>
      </div>
    </div>
  </section>

  {{-- ===== طلب عرض سعر ===== --}}
  <section id="rfq" class="home-cta reveal divider-t">
    <div class="sec-head icon-head">
      <img class="sec-ico" src="{{ asset('images/icons/rfq.png') }}" alt="">
      <div>
        <h3>{{ __('app.cta_rfq') }}</h3>
        <p class="sec-sub">{{ __('app.tell_us_need') }}</p>
      </div>
    </div>

    <div class="cta-card">
      <div class="copy">
        <ul class="steps">
          <li>{{ __('app.choose_product_service') }}</li>
          <li>{{ __('app.fill_rfq_form') }}</li>
          <li>{{ __('app.confirm_details_pricing') }}</li>
        </ul>
      </div>
      <a class="btn btn-primary btn-lg" href="{{ route('rfq.create') }}">{{ __('app.start_now') }}</a>
    </div>
  </section>

  {{-- ===== تواصل معنا ===== --}}
  <section id="contact" class="home-section reveal">
    <div class="sec-head icon-head">
      <img class="sec-ico" src="{{ asset('images/icons/contact.png') }}" alt="">
      <div>
        <h2>{{ __('app.contact_us') }}</h2>
        <p class="sec-sub">{{ __('app.choose_channel') }}</p>
      </div>
    </div>

    <div class="contact-cards">
      <a class="contact-item grad-whatsapp delay-0" href="https://wa.me/967738742001" target="_blank" rel="noopener">
        <img src="{{ asset('images/icons/whatsapp.png') }}" alt="{{ __('app.whatsapp') }}">
        <div><div class="t">{{ __('app.whatsapp') }}</div><div class="s ltr">{{ __('app.whatsapp_number') }}</div></div>
      </a>

      <a class="contact-item grad-instagram delay-1" href="https://instagram.com/adelsk2002" target="_blank" rel="noopener">
        <img src="{{ asset('images/icons/Instagram.png') }}" alt="{{ __('app.instagram') }}">
        <div><div class="t">{{ __('app.instagram') }}</div><div class="s ltr">{{ __('app.instagram_handle') }}</div></div>
      </a>

      <a class="contact-item grad-facebook delay-2" href="https://facebook.com" target="_blank" rel="noopener">
        <img src="{{ asset('images/icons/facebook.png') }}" alt="{{ __('app.facebook') }}">
        <div><div class="t">{{ __('app.facebook') }}</div><div class="s">{{ __('app.facebook_page') }}</div></div>
      </a>

      <a class="contact-item grad-yahoo delay-3" href="mailto:Adelsk2002@yahoo.com">
        <img src="{{ asset('images/icons/yahoo.png') }}" alt="{{ __('app.yahoo_mail') }}">
        <div><div class="t">{{ __('app.yahoo_mail') }}</div><div class="s ltr">{{ __('app.yahoo_email') }}</div></div>
      </a>

      <a class="contact-item grad-gmail delay-4" href="mailto:Adelsk2002@gmail.com">
        <img src="{{ asset('images/icons/gmail.png') }}" alt="{{ __('app.gmail') }}">
        <div><div class="t">{{ __('app.gmail') }}</div><div class="s ltr">{{ __('app.gmail_email') }}</div></div>
      </a>
    </div>
  </section>
@endsection