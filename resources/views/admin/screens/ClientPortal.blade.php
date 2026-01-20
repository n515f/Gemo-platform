{{-- resources/views/admin/screens/ClientPortal.blade.php --}}
@extends('layouts.admin')

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
  @php
    // بطاقات شاشات الواجهة
    $cards = [
      [
        'title' => __('app.portal_home'),
        'desc'  => __('app.portal_home_desc'),
        'href'  => route('home'),
        'icon'  => asset('images/icons/home.png'),
      ],
      [
        'title' => __('app.portal_categories'),
        'desc'  => __('app.portal_categories_desc'),
        'href'  => route('categories.index', [], false) ?? url('/categories'),
        'icon'  => asset('images/icons/categories.png'),
      ],
      [
        'title' => __('app.portal_services'),
        'desc'  => __('app.portal_services_desc'),
        'href'  => route('services.index', [], false) ?? url('/services'),
        'icon'  => asset('images/icons/services.png'),
      ],
      [
        'title' => __('app.portal_catalog'),
        'desc'  => __('app.portal_catalog_desc'),
        'href'  => route('catalog.index', [], false) ?? url('/catalog'),
        'icon'  => asset('images/icons/catalog.png'),
      ],
      [
        'title' => __('app.portal_rfq'),
        'desc'  => __('app.portal_rfq_desc'),
        'href'  => route('rfq.create', [], false) ?? url('/rfq/create'),
        'icon'  => asset('images/icons/rfq.png'),
      ],
      [
        'title' => __('app.portal_contact'),
        'desc'  => __('app.portal_contact_desc'),
        'href'  => route('contact', [], false) ?? url('/contact'),
        'icon'  => asset('images/icons/contact.png'),
      ],
    ];
  @endphp

  <section class="client-portal">
    <h1 class="cp-title">{{ __('app.client_portal_title') }}</h1>
    <p class="cp-sub">
      {{ __('app.client_portal_subtitle') }}
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
            <span class="btn btn-go">{{ __('app.go') }}</span>
          </div>
        </a>
      @endforeach
    </div>
  </section>
@endsection
