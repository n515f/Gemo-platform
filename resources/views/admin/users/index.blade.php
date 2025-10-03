{{-- resources/views/admin/rfqs/index.blade.php --}}
@extends('layouts.site')

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
  <h1 class="page-title">المستخدمون</h1>
  @include('components.flash')

  <form method="get" class="searchbar">
    <input type="text" name="q" value="{{ $q }}" placeholder="بحث بالاسم أو البريد...">
    <button class="btn">بحث</button>
  </form>

  <div class="users-grid">
    @foreach($users as $u)
      @php
        $role = $u->primary_role ?? 'client';
        $badge = $role === 'admin' ? 'admin' : ($role === 'technician' ? 'technician' : 'client');
      @endphp
      <div class="user-card">
        <img class="avatar" src="{{ $u->profile_photo_url }}" alt="avatar">

        <div>
          <div style="display:flex;justify-content:space-between;align-items:center;gap:8px">
            <h3 class="name">{{ $u->name }}</h3>
            <span class="badge {{ $badge }}">{{ $role }}</span>
          </div>
          <div class="email">{{ $u->email }}</div>

          <div class="user-actions">
            {{-- تعديل الدور --}}
            <form method="POST" action="{{ route('admin.users.role',$u) }}">
              @csrf
              <select name="role" class="role" onchange="this.form.submit()">
                @foreach($roles as $rid => $rname)
                  <option value="{{ $rname }}" @selected($u->hasRole($rname))>{{ $rname }}</option>
                @endforeach
              </select>
            </form>

            {{-- رفع صورة --}}
            <form method="POST" action="{{ route('admin.users.avatar',$u) }}" enctype="multipart/form-data" class="upload">
              @csrf
              <label class="btn">
                رفع صورة
                <input type="file" name="avatar" accept="image/*" onchange="this.form.submit()">
              </label>
            </form>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="mt-12">{{ $users->links() }}</div>
@endsection