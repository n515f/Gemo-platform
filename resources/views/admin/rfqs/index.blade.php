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
      <h1 class="title">{{ __('app.rfqs_title') }}</h1>
      <p class="muted">{{ __('app.rfqs_subtitle') }}</p>
    </div>

    <form class="toolbar" method="get" action="{{ route('admin.rfqs.index') }}">
      <input class="input" type="text" name="q" value="{{ $q ?? '' }}" placeholder="{{ __('app.search_placeholder_rfqs') }}">
      <select class="select" name="status">
        <option value="">{{ __('app.all_statuses') }}</option>
        @foreach(\App\Http\Controllers\Admin\RfqAdminController::STATUSES as $s)
          <option value="{{ $s }}" {{ ($status ?? '')===$s ? 'selected':'' }}>
            {{ __($s==='pending' ? __('app.status_pending') : ($s==='quoted' ? __('app.status_quoted') : __('app.status_done'))) }}
          </option>
        @endforeach
      </select>
      <input class="input" type="text" name="service" value="{{ $service ?? '' }}" placeholder="{{ __('app.service_optional') }}">
      <button class="btn btn--search" type="submit">
        <svg class="ico" viewBox="0 0 24 24">
          <circle cx="11" cy="11" r="8" fill="none" stroke="currentColor" stroke-width="2"/>
          <path d="m21 21-4.35-4.35" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        {{ __('app.filter') }}
      </button>
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
        $waText   = __("app.whatsapp_message", ['name' => $r->client_name, 'id' => $r->id]);
        $waLink   = \App\Http\Controllers\Admin\RfqAdminController::whatsappUrl($r->phone, $waText);
        $created  = optional($r->created_at)->format('Y-m-d H:i');
        $product  = optional($r->product);
        $thumb    = $product && $product->images && $product->images->first()
                    ? asset($product->images->first()->path ?? $product->images->first()->url ?? '')
                    : null;
      @endphp

      <div class="rfq-card {{ $isDone ? 'done' : 'pending' }}"
           data-rfq-id="{{ $r->id }}"
           data-show-url="{{ route('admin.rfqs.show', $r) }}"
           aria-label="{{ __('app.rfq_label', ['id' => $r->id]) }}">
        <div class="rfq-head">
          <div class="left">
            <span class="rfq-id">#{{ $r->id }}</span>
            <strong class="client">{{ $r->client_name }}</strong>
            <span class="badge st-{{ $r->status }}">
              {{ $r->status==='pending'
                  ? __('app.status_pending')
                  : ($r->status==='quoted'
                      ? __('app.status_quoted')
                      : __('app.status_done')) }}
            </span>
          </div>
          <div class="right">
            <span class="meta muted">{{ __('app.created_at_label') }}: {{ $created }}</span>
          </div>
        </div>

        <div class="rfq-body">
          @if($thumb)
            <div class="rfq-media">
              <img class="rfq-thumb" src="{{ $thumb }}" alt="{{ $product->name_ar ?? $product->name_en ?? 'Product' }}">
            </div>
          @endif

          <div class="row">
            <div><b>{{ __('app.phone') }}:</b> <span dir="ltr">{{ $r->phone ?: '—' }}</span></div>
            <div><b>{{ __('app.email') }}:</b> <span dir="ltr">{{ $r->email ?: '—' }}</span></div>
          </div>
          <div class="row">
            <div><b>{{ __('app.service') }}:</b> {{ $r->service ?: '—' }}</div>
            <div><b>{{ __('app.budget') }}:</b> {{ $r->budget ?: '—' }}</div>
          </div>
          <div class="row">
            <div><b>{{ __('app.quantity') }}:</b> {{ $r->quantity ?? 1 }}</div>
            <div><b>{{ __('app.location') }}:</b> {{ $r->location ?: '—' }}</div>
          </div>
          <div class="row">
            <div><b>{{ __('app.product') }}:</b> {{ optional($product)->name_ar ?? optional($product)->name_en ?? '—' }}</div>
            <div><b>{{ __('app.code') }}:</b> {{ $product->code ?? '—' }}</div>
          </div>
          <div class="brief">{{ Str::limit($r->brief ?? '', 300) }}</div>
        </div>

        <div class="rfq-actions">
          <a class="wa-icon" target="_blank" href="{{ $waLink }}" aria-label="{{ __('app.whatsapp') }}">
            <svg class="ico" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.472-.149-.671.15-.198.297-.767.967-.94 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.131-.607.134-.133.297-.347.446-.52.149-.173.198-.297.298-.495.099-.198.05-.372-.025-.521-.075-.149-.671-1.613-.919-2.208-.242-.58-.487-.501-.671-.51-.173-.009-.372-.011-.571-.011-.198 0-.52.074-.793.372-.273.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.214 3.074.149.198 2.099 3.2 5.08 4.487.711.306 1.265.489 1.697.627.712.227 1.36.195 1.871.118.571-.085 1.758-.718 2.007-1.413.248-.695.248-1.29.173-1.413-.074-.124-.273-.198-.57-.347z" fill="currentColor"/>
              <path d="M12.003 2.001C6.48 2.001 2 6.481 2 12.004c0 2.076.676 4.004 1.826 5.571L2 22l4.5-1.778c1.5.82 3.215 1.29 5.003 1.29 5.523 0 10.003-4.48 10.003-10.003S17.526 2.001 12.003 2.001z" fill="none" stroke="currentColor" stroke-width="2"/>
            </svg>
          </a>

          <button class="btn btn--edit" type="button" data-rfq-open="{{ $r->id }}">
            <svg class="ico" viewBox="0 0 24 24">
              <path d="M14.5 3.5a2.5 2.5 0 0 1 3.5 3.5L8 17l-4 1 1-4 9.5-10.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
            {{ __('app.edit') }}
          </button>

          <form method="post" action="{{ route('admin.rfqs.status',$r) }}" class="inline">
            @csrf @method('patch')
            <input type="hidden" name="status" value="{{ $r->status==='pending' ? 'quoted' : 'won' }}">
            <button class="btn {{ $r->status==='pending' ? 'btn--add' : 'btn--edit' }}" type="submit">
              <svg class="ico" viewBox="0 0 24 24">
                @if($r->status==='pending')
                  <path d="M20 6L9 17l-5-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                @else
                  <path d="M14.5 3.5a2.5 2.5 0 0 1 3.5 3.5L8 17l-4 1 1-4 9.5-10.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                @endif
              </svg>
              {{ $r->status==='pending' ? __('app.mark_as_quoted') : __('app.revert_previous_status') }}
            </button>
          </form>

          <form method="post" action="{{ route('admin.rfqs.destroy',$r) }}" data-confirm="{{ __('app.confirm_delete_rfqs') }}" onsubmit="return confirm(this.dataset.confirm)" class="inline">
            @csrf @method('delete')
            <button class="btn btn--delete" type="submit">
              <svg class="ico" viewBox="0 0 24 24">
                <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6M10 11v6M14 11v6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              {{ __('app.delete') }}
            </button>
          </form>
        </div>
      </div>
    @empty
      <div class="empty">{{ __('app.no_rfqs') }}</div>
    @endforelse
  </div>

  {{-- مودال فورم RFQ --}}
  <div class="rfq-modal" hidden aria-hidden="true" role="dialog" aria-modal="true" aria-label="{{ __('app.rfq_details') }}">
    <div class="rfq-modal__dialog" role="document">
      <button class="rfq-modal__close" type="button" aria-label="{{ __('app.close') }}">
        <svg class="ico" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12" fill="none" stroke="currentColor" stroke-width="2"/></svg>
      </button>
      <div class="rfq-modal__content">
        <div class="rfq-modal__loading">{{ __('app.loading') }}</div>
      </div>
    </div>
  </div>
</section>
@endsection
