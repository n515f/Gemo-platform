@extends('layouts.admin')

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
<section class="admin-page">
  <header class="page-head">
    <h1 class="title">{{ __('app.new_report') }}</h1>
  </header>

  @include('components.flash')

  <div class="card">
    @include('admin.reports._form', ['projects'=>$projects])
  </div>
</section>
@endsection