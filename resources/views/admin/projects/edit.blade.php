{{-- resources/views/admin/projects/edit.blade.php --}}
@extends('layouts.admin')

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
<section class="admin-page">
  <h1 class="title">{{ __('app.edit_project') }} #{{ $project->id }}</h1>

  @include('components.flash')
  <div class="card">
    <form method="POST" action="{{ route('admin.projects.update', $project) }}">
      @method('PUT')
      @include('admin.projects.form', ['submit' => __('app.update')])
    </form>

    <form id="cancelForm" method="GET" action="{{ route('admin.projects.index') }}"></form>
  </div>
</section>
@endsection
