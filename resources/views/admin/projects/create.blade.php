{{-- resources/views/admin/projects/create.blade.php --}}
@extends('layouts.admin')

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
<section class="admin-page">
  <h1 class="title">{{ __('app.new_project') }}</h1>

  @include('components.flash')
  <div class="card">
    <form method="POST" action="{{ route('admin.projects.store') }}">
      @include('admin.projects.form', ['submit' => __('app.create')])
    </form>

    <form id="cancelForm" method="GET" action="{{ route('admin.projects.index') }}"></form>
  </div>
</section>
@endsection
