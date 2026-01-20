@extends('layouts.admin')

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
  <h1 class="page-title">{{ __('app.users_title') }}</h1>
  @include('components.flash')

  <form method="get" class="searchbar">
    <input type="text" name="q" value="{{ $q }}" placeholder="{{ __('app.search_placeholder_users') }}">
    <button class="btn" type="submit">{{ __('app.search') }}</button>
  </form>

  <div class="users-grid">
    @foreach($users as $u)
      @php
        $role  = $u->primary_role ?? 'client';
        $badge = $role === 'admin' ? 'admin' : ($role === 'technician' ? 'technician' : 'client');
      @endphp

      <div class="user-card">
        <img class="avatar" src="{{ $u->profile_photo_url }}" alt="{{ __('app.avatar_alt') }}">

        <div>
          <div style="display:flex;justify-content:space-between;align-items:center;gap:8px">
            <h3 class="name">{{ $u->name }}</h3>
            <span class="badge {{ $badge }}">{{ __('app.role_'.$role) }}</span>
          </div>
          <div class="email">{{ $u->email }}</div>

          <div class="user-actions">
            {{-- تعديل الدور --}}
            <form method="POST" action="{{ route('admin.users.role', $u) }}">
              @csrf
              <label class="sr-only">{{ __('app.change_role') }}</label>
              <select name="role" class="role" onchange="this.form.submit()">
                @foreach($roles as $rid => $rname)
                  <option value="{{ $rname }}" @selected($u->hasRole($rname))>
                    {{ __('app.role_'.$rname) }}
                  </option>
                @endforeach
              </select>
            </form>

            {{-- رفع صورة --}}
            <form method="POST" action="{{ route('admin.users.avatar', $u) }}" enctype="multipart/form-data" class="upload">
              @csrf
              <label class="btn btn--add btn--sm">
                <svg class="ico" viewBox="0 0 24 24">
                  <path d="M12 5v14M5 12h14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                {{ __('app.upload_avatar') }}
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
