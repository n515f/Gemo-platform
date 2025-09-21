{{-- resources/views/admin/projects/edit.blade.php --}}
@extends('layouts.site')
@push('styles') @vite(['resources/css/pages/admin.projects.css']) @endpush

@section('content')
<section class="admin-page">
  <h1 class="title">{{ __('تعديل مشروع') }} #{{ $project->id }}</h1>

  @include('components.flash')
  <div class="card">
    <form method="POST" action="{{ route('admin.projects.update', $project) }}">
      @method('PUT')
      @include('admin.projects.form', ['submit'=>_('تحديث')])
    </form>
    <form id="cancelForm" method="GET" action="{{ route('admin.projects.index') }}"></form>
  </div>
</section>
@endsection