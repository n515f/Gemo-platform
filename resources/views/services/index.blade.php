{{-- resources/views/services/index.blade.php --}}
@extends('layouts.site')

@section('title', __('app.services'))

@push('styles')
  @vite(['resources/css/entries/site.css','resources/css/pages/services.css'])
@endpush

@section('content')
  {{-- Hero تعريفي خفيف --}}
  <section class="svc-hero reveal">
    <div class="shell">
      <div class="svc-hero__head">
        <img class="svc-hero__icon" src="{{ asset('images/icons/services.png') }}" alt="">
        <div>
          <h1 class="svc-hero__title">{{ __('app.services_hero_title') }}</h1>
          <p class="svc-hero__sub">{{ __('app.services_hero_text') }}</p>
        </div>
      </div>
      <div class="svc-hero__cta">
        <a class="btn btn-gradient" href="{{ route('rfq.create') }}">{{ __('app.cta_rfq') }}</a>
        <a class="btn outline" href="{{ route('contact') }}">{{ __('app.contact_us') }}</a>
      </div>
    </div>
  </section>

  {{-- عنوان عام للقسم --}}
  <section class="svc-section reveal">
    <div class="sec-head">
      <h2 class="sec-title">{{ __('app.our_services') }}</h2>
      <p class="sec-sub">
        {{ __('app.services_intro', ['brand' => config('app.name')]) /* ضف المفتاح في lang */ }}
      </p>
    </div>

    {{-- بطاقات الخدمات — دخول متناوب يمين/يسار + نبض على الهوفر --}}
    <div class="svc-grid">
      <a class="svc-card reveal" href="{{ route('services.index') }}">
        <img class="svc-ico" src="{{ asset('images/services/procurement.png') }}" alt="">
        <h3 class="svc-title">{{ __('app.service_procurement') }}</h3>
        <p class="svc-desc">{{ __('app.service_procurement_desc') }}</p>
      </a>

      <a class="svc-card reveal" href="{{ route('services.index') }}">
        <img class="svc-ico" src="{{ asset('images/services/customs.png') }}" alt="">
        <h3 class="svc-title">{{ __('app.service_customs') }}</h3>
        <p class="svc-desc">{{ __('app.service_customs_desc') }}</p>
      </a>

      <a class="svc-card reveal" href="{{ route('services.index') }}">
        <img class="svc-ico" src="{{ asset('images/services/installation.png') }}" alt="">
        <h3 class="svc-title">{{ __('app.service_installation') }}</h3>
        <p class="svc-desc">{{ __('app.service_installation_desc') }}</p>
      </a>

      <a class="svc-card reveal" href="{{ route('services.index') }}">
        <img class="svc-ico" src="{{ asset('images/services/training.png') }}" alt="">
        <h3 class="svc-title">{{ __('app.service_training') }}</h3>
        <p class="svc-desc">{{ __('app.service_training_desc') }}</p>
      </a>

      <a class="svc-card reveal" href="{{ route('services.index') }}">
        <img class="svc-ico" src="{{ asset('images/services/full-line.png') }}" alt="">
        <h3 class="svc-title">{{ __('app.service_full_line') }}</h3>
        <p class="svc-desc">{{ __('app.service_full_line_desc') }}</p>
      </a>
    </div>
  </section>

  {{-- القطاعات التي نخدمها --}}
  <section class="svc-section reveal">
    <div class="sec-head">
      <h2 class="sec-title">{{ __('app.sectors_we_serve') }}</h2>
      <p class="sec-sub">{{ __('app.sectors_hero_text') /* ضف المفتاح */ }}</p>
    </div>

    <div class="sectors-grid">
      <div class="sector-card reveal">
        <img class="sector-ico" src="{{ asset('images/sectors/Food .png') }}" alt="">
        <h3 class="sector-title">{{ __('app.sector_food') }}</h3>
        <p class="sector-desc">{{ __('app.sector_food_desc') }}</p>
      </div>

      <div class="sector-card reveal">
        <img class="sector-ico" src="{{ asset('images/sectors/packaging.png') }}" alt="">
        <h3 class="sector-title">{{ __('app.sector_packaging') }}</h3>
        <p class="sector-desc">{{ __('app.sector_packaging_desc') }}</p>
      </div>

      <div class="sector-card reveal">
        <img class="sector-ico" src="{{ asset('images/sectors/pharmaceuticals.png') }}" alt="">
        <h3 class="sector-title">{{ __('app.sector_pharma') }}</h3>
        <p class="sector-desc">{{ __('app.sector_pharma_desc') }}</p>
      </div>

      <div class="sector-card reveal">
        <img class="sector-ico" src="{{ asset('images/sectors/logistics.png') }}" alt="">
        <h3 class="sector-title">{{ __('app.sector_logistics') }}</h3>
        <p class="sector-desc">{{ __('app.sector_logistics_desc') }}</p>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  {{-- لو كان عندك initRevealOnScroll في app.js سيعمل تلقائياً.
       هذا فقط كنسخ احتياطي إن لم يكن موجوداً. --}}
  <script>
    (function(){
      const els = document.querySelectorAll('.reveal');
      if(!('IntersectionObserver' in window)){ els.forEach(e=>e.classList.add('in')); return; }
      const io = new IntersectionObserver((entries)=>{
        entries.forEach(en=>{
          if(en.isIntersecting){ en.target.classList.add('in'); io.unobserve(en.target); }
        });
      }, {threshold: .08});
      els.forEach(e=>io.observe(e));
    })();
  </script>
@endpush