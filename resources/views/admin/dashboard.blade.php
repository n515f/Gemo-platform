{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.site')
@push('styles')
  @vite('resources/css/admin.css')
@endpush
@section('content')
  <h1 class="page-title">لوحة التحكم</h1>

  <div class="grid-3">
    <a class="card link" href="{{ route('admin.products.index') }}">
      <h3>المنتجات</h3>
      <p>إدارة الكتالوج (إضافة/تعديل/حذف)</p>
    </a>

    <a class="card link" href="{{ route('admin.projects.index') }}">
      <h3>المشاريع</h3>
      <p>متابعة الأعمال الجارية والمنجزة</p>
    </a>

    <a class="card link" href="{{ route('admin.reports.create') }}">
      <h3>تقارير الفنيين</h3>
      <p>رفع التقارير والمهام اليومية</p>
    </a>
  </div>
@endsection