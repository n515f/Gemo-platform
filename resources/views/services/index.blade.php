@extends('layouts.site')
@push('styles')
  @vite('resources/css/app.css')
@section('content')
  <h1 class="page-title">{{ __('app.services') }}</h1>

  {{-- هيرو خفيف --}}
  <section class="hero">
    <h1>{{ __('app.services_hero_title') }}</h1>
    <p>{{ __('app.services_hero_text') }}</p>
    <div class="cta">
      <a class="btn btn-gradient" href="{{ route('rfq.create') }}">{{ __('app.cta_rfq') }}</a>
      <a class="btn outline" href="{{ route('contact') }}">{{ __('app.contact_us') }}</a>
    </div>
  </section>

  {{-- خدماتنا --}}
  <h2 class="page-title" style="margin-top:22px">{{ __('app.our_services') }}</h2>
  <div class="grid" style="margin-bottom:14px">
    <div class="card">
      <img src="{{ asset('images/services/procurement.png') }}" alt="">
      <div class="card-body">
        <h3 class="card-title">{{ __('app.service_procurement') }}</h3>
        <p class="desc">{{ __('app.service_procurement_desc') }}</p>
      </div>
    </div>
    <div class="card">
      <img src="{{ asset('images/services/customs.png') }}" alt="">
      <div class="card-body">
        <h3 class="card-title">{{ __('app.service_customs') }}</h3>
        <p class="desc">{{ __('app.service_customs_desc') }}</p>
      </div>
    </div>
    <div class="card">
      <img src="{{ asset('images/services/installation.png') }}" alt="">
      <div class="card-body">
        <h3 class="card-title">{{ __('app.service_installation') }}</h3>
        <p class="desc">{{ __('app.service_installation_desc') }}</p>
      </div>
    </div>
    <div class="card">
      <img src="{{ asset('images/services/training.png') }}" alt="">
      <div class="card-body">
        <h3 class="card-title">{{ __('app.service_training') }}</h3>
        <p class="desc">{{ __('app.service_training_desc') }}</p>
      </div>
    </div>
    <div class="card">
      <img src="{{ asset('images/services/full-line.png') }}" alt="">
      <div class="card-body">
        <h3 class="card-title">{{ __('app.service_full_line') }}</h3>
        <p class="desc">{{ __('app.service_full_line_desc') }}</p>
      </div>
    </div>
  </div>

  {{-- القطاعات التي نخدمها --}}
  <h2 class="page-title">{{ __('app.sectors_we_serve') }}</h2>
  <div class="grid">
    <div class="card"><div class="card-body"><h3 class="card-title">{{ __('app.sector_food') }}</h3><p class="desc">{{ __('app.sector_food_desc') }}</p></div></div>
    <div class="card"><div class="card-body"><h3 class="card-title">{{ __('app.sector_beverage') }}</h3><p class="desc">{{ __('app.sector_beverage_desc') }}</p></div></div>
    <div class="card"><div class="card-body"><h3 class="card-title">{{ __('app.sector_packaging') }}</h3><p class="desc">{{ __('app.sector_packaging_desc') }}</p></div></div>
    <div class="card"><div class="card-body"><h3 class="card-title">{{ __('app.sector_pharma') }}</h3><p class="desc">{{ __('app.sector_pharma_desc') }}</p></div></div>
  </div>
@endsection
