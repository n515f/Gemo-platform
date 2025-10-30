{{-- resources/views/admin/rfqs/index.blade.php --}}
@extends('layouts.admin')
@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
<section class="admin-page">

  {{-- رأس + فلاتر --}}
  <header class="page-head">
    <div>
      <h1 class="title">{{ __('طلبات عرض السعر') }}</h1>
      <p class="muted">
        {{ __('استعرض الطلبات وتواصل سريع عبر واتساب وغيّر الحالة بنقرة.') }}
      </p>
    </div>

    <form class="toolbar" method="get" action="{{ route('admin.rfqs.index') }}">
      <input class="input" type="text" name="q" value="{{ $q ?? '' }}" placeholder="{{ __('بحث بالاسم/الهاتف/البريد/الموقع...') }}">
      <select class="select" name="status">
        <option value="">{{ __('كل الحالات') }}</option>
        @foreach(\App\Http\Controllers\Admin\RfqAdminController::STATUSES as $s)
          <option value="{{ $s }}" {{ ($status ?? '')===$s ? 'selected':'' }}>
            {{ __($s==='pending'?'بانتظار الرد':($s==='quoted'?'تم إرسال عرض':'مكتمل')) }}
          </option>
        @endforeach
      </select>
      <input class="input" type="text" name="service" value="{{ $service ?? '' }}" placeholder="{{ __('الخدمة (اختياري)') }}">
      <button class="btn btn-light" type="submit">{{ __('تصفية') }}</button>
    </form>
  </header>

  {{-- فلاش --}}
  @if(session('ok'))   <div class="flash success">{{ session('ok') }}</div> @endif
  @if(session('info')) <div class="flash info">{{ session('info') }}</div> @endif
  @if($errors->any())  <div class="flash error">{{ implode(' • ', $errors->all()) }}</div> @endif

  {{-- شبكة البطاقات --}}
  <div class="rfq-grid">
    @forelse($rfqs as $r)
      @php
        $isDone   = in_array($r->status, ['quoted','won'], true);
        $waText   = "مرحباً {$r->client_name}، بخصوص طلب عرض السعر رقم #{$r->id}";
        $waLink   = \App\Http\Controllers\Admin\RfqAdminController::whatsappUrl($r->phone, $waText);
        $created  = optional($r->created_at)->format('Y-m-d H:i');
      @endphp

      <div class="rfq-card {{ $isDone ? 'done' : 'pending' }}">
        <div class="rfq-head">
          <div class="left">
            <span class="rfq-id">#{{ $r->id }}</span>
            <strong class="client">{{ $r->client_name }}</strong>
            <span class="badge st-{{ $r->status }}">{{ __($r->status==='pending'?'بانتظار الرد':($r->status==='quoted'?'تم إرسال عرض':'مكتمل')) }}</span>
          </div>
          <div class="right">
            <a class="btn btn-wa small" target="_blank" href="{{ $waLink }}">{{ __('واتساب') }}</a>
            <a class="btn btn-soft small" href="{{ route('admin.rfqs.show',$r) }}">{{ __('عرض') }}</a>
          </div>
        </div>

        <div class="rfq-body">
          <div class="row">
            <div><b>{{ __('الهاتف') }}:</b> <span dir="ltr">{{ $r->phone ?: '—' }}</span></div>
            <div><b>{{ __('البريد') }}:</b> <span dir="ltr">{{ $r->email ?: '—' }}</span></div>
          </div>
          <div class="row">
            <div><b>{{ __('الخدمة') }}:</b> {{ $r->service ?: '—' }}</div>
            <div><b>{{ __('الميزانية') }}:</b> {{ $r->budget ?: '—' }}</div>
          </div>
          <div class="row">
            <div><b>{{ __('الكمية') }}:</b> {{ $r->quantity ?? 1 }}</div>
            <div><b>{{ __('الموقع') }}:</b> {{ $r->location ?: '—' }}</div>
          </div>
          <div class="brief">{{ Str::limit($r->brief ?? '', 180) }}</div>
          <div class="meta muted">{{ __('أُنشئ') }}: {{ $created }}</div>
        </div>

        <div class="rfq-actions">
          <form method="post" action="{{ route('admin.rfqs.status',$r) }}">
            @csrf @method('patch')
            <input type="hidden" name="status" value="{{ $r->status==='pending' ? 'quoted' : 'won' }}">
            <button class="btn {{ $r->status==='pending' ? 'btn-primary' : 'btn-success' }}" type="submit">
              {{ $r->status==='pending' ? __('أرسلت العرض') : __('تم الإقفال') }}
            </button>
          </form>

          <form method="post" action="{{ route('admin.rfqs.destroy',$r) }}" onsubmit="return confirm('حذف الطلب؟')">
            @csrf @method('delete')
            <button class="btn btn-danger" type="submit">{{ __('حذف') }}</button>
          </form>
        </div>
      </div>
    @empty
      <div class="empty">{{ __('لا توجد طلبات حالياً') }}</div>
    @endforelse
  </div>

  <div class="pagination">
    {{ $rfqs->withQueryString()->links() }}
  </div>
</section>
@endsection