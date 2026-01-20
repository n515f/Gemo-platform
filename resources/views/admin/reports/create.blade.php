@extends('layouts.admin')

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
<section class="admin-page">
  <header class="page-head">
    <div class="lhs">
      <h1 class="title">{{ __('app.new_report') }}</h1>
    </div>
    <div class="rhs">
      <a class="btn btn-light" href="{{ route('admin.reports.index') }}">{{ __('app.back') }}</a>
    </div>
  </header>

  @include('components.flash')

  <div class="card">
    @include('admin.reports._form', ['projects' => $projects])
  </div>
</section>
@endsection
