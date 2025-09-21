{{-- resources/views/admin/projects/create.blade.php --}}
@extends('layouts.site')
@push('styles') @vite(['resources/css/pages/admin.projects.css']) @endpush

@section('content')
<section class="admin-page">
  <h1 class="title">{{ __('مشروع جديد') }}</h1>

  @include('components.flash')
  <div class="card">
    <form method="POST" action="{{ route('admin.projects.store') }}">
      @include('admin.projects.form', ['submit'=>_('إنشاء')])
    </form>
    <form id="cancelForm" method="GET" action="{{ route('admin.projects.index') }}"></form>
  </div>
</section>
@endsection