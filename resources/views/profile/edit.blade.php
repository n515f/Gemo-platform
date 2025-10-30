@php
  $layout = ($isAdmin ?? false) ? 'layouts.admin' : 'layouts.site';
@endphp
@extends($layout)

@php
  use Illuminate\Support\Facades\Storage;

  $hasAvatar = !empty($user->profile_image);
  $avatarUrl = $hasAvatar ? Storage::url($user->profile_image) : null;

  $isRtl = app()->getLocale() === 'ar';
  $dir   = $isRtl ? 'rtl' : 'ltr';
@endphp
@push('styles')
  @vite(['resources/css/entries/admin.css','resources/css/entries/site.css','resources/js/app.js'])
@endpush
@section('content')
<div class="pf-wrap" dir="{{ $dir }}">

  {{-- عنوان الصفحة (بدون أزرار) --}}
  <div class="pf-toolbar">
    <h1 class="pf-title">{{ __('app.profile') }}</h1>
  </div>

  {{-- رسائل النظام --}}
  @if (session('status'))
    <div class="pf-alert pf-alert--success">{{ session('status') }}</div>
  @endif
  @if ($errors->any())
    <div class="pf-alert pf-alert--danger">
      <ul class="pf-list">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="pf-card">
    {{-- صف الصورة + إجراءات رفع/حذف --}}
    <div class="pf-avatarRow">
      @if($hasAvatar)
        <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="pf-avatar" />
      @else
        <div class="pf-avatar pf-avatar--empty" aria-hidden="true">
          <svg class="pf-avatarIcon" viewBox="0 0 24 24" fill="none">
            <circle cx="12" cy="7.5" r="3.5" stroke="currentColor" stroke-width="1.6"/>
            <path d="M4 19.2a8 8 0 0 1 16 0" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
          </svg>
        </div>
      @endif

      <form method="POST" action="{{ route('profile.avatar.store') }}" enctype="multipart/form-data" class="pf-upload">
        @csrf
        <input class="pf-file" type="file" name="avatar" accept="image/*" required />
        <button type="submit" class="pf-btn pf-btn--primary">{{ __('app.upload') }}</button>
      </form>

      @if($hasAvatar)
  <form method="POST"
        action="{{ route('profile.avatar.destroy') }}"
        onsubmit="return confirm('هل أنت متأكد من حذف ')"
        class="pf-delete">
    @csrf
    <input type="hidden" name="_method" value="DELETE">
    <button type="submit" class="pf-btn pf-btn--danger">
      {{ __('Delete') }}
    </button>
  </form>
@endif

    </div>

    {{-- الأقسام (تستجيب للثيم/الاتجاه عبر الكلاسات) --}}
    <section class="pf-section">
      @include('profile.partials.update-profile-information-form')
    </section>

    <section class="pf-section">
      @include('profile.partials.update-password-form')
    </section>

    <section class="pf-section">
      @include('profile.partials.delete-user-form')
    </section>

  </div>
</div>
@endsection
