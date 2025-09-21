@extends('layouts.site')
@push('styles')
  @vite('resources/css/app.css')
@section('content')
  <section class="hero">
    <div class="hero-card">
      <h1>app.welcome</h1>
      <p>{{ __('app.tagline') }}</p>
      <div class="hero-cta">
        <a class="btn btn-outline" href="{{ route('catalog.index') }}">{{ __('app.catalog') }}</a>
        <a class="btn btn-primary" href="{{ route('rfq.create') }}">{{ __('app.cta_rfq') }}</a>
      </div>
    </div>
  </section>
@endsection