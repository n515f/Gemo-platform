@extends('layouts.site')

@push('styles')
  @vite(['resources/css/admin.css','resources/css/rfq.css'])
@endpush

@section('content')
<div class="container" style="max-width:1000px">
  <h1 class="page-title">طلب #{{ $rfq->id }}</h1>

  <div class="grid-3">
    {{-- بطاقة بيانات العميل --}}
    <div class="card" style="padding:16px">
      <h3 class="card-title" style="margin-bottom:8px">{{ __('app.client_info') }}</h3>
      <div class="desc">
        <div><strong>{{ __('app.name') }}:</strong> {{ $rfq->client_name }}</div>
        <div><strong>{{ __('app.email') }}:</strong> <a href="mailto:{{ $rfq->email }}">{{ $rfq->email }}</a></div>
    <div><strong>{{ __('app.phone') }}:</strong> <a href="tel:{{ $rfq->phone }}">{{ $rfq->phone }}</a></div>
    <div><strong>{{ __('app.country_city') }}:</strong> {{ $rfq->location ?: '—' }}</div>
      </div>

      <div class="actions" style="margin-top:12px">
        <a class="btn outline" href="mailto:{{ $rfq->email }}?subject=Re:%20RFQ%20#{{ $rfq->id }}">{{ __('app.email') }}</a>
        <a class="btn primary" target="_blank"
           href="https://wa.me/{{ preg_replace('/\D+/','',$rfq->phone) }}?text={{ urlencode('مرحباً، بخصوص طلب عرض السعر رقم '.$rfq->id) }}">
           {{ __('app.whatsapp') }}
        </a>
      </div>
    </div>

    {{-- بطاقة الطلب --}}
    <div class="card" style="padding:16px">
      <h3 class="card-title" style="margin-bottom:8px">{{ __('app.order_details') }}</h3>
      <div class="desc">
        <div><strong>{{ __('app.service') }}:</strong> {{ $rfq->service }}</div>
    <div><strong>{{ __('app.budget') }}:</strong> {{ $rfq->budget }}</div>
    <div><strong>{{ __('app.status') }}:</strong> {{ $rfq->status }}</div>
        <div class="mt-2"><strong>الوصف:</strong></div>
        <p style="margin-top:6px">{{ $rfq->brief ?: '—' }}</p>
      </div>
    </div>

    {{-- تحديث الحالة --}}
    <div class="card" style="padding:16px">
      <h3 class="card-title" style="margin-bottom:8px">{{ __('app.update_status') }}</h3>
      <form method="POST" action="{{ route('admin.rfqs.update',$rfq) }}" class="admin-form">
        @csrf
        @method('PUT')

        <label class="rfq-label" for="status">{{ __('app.status') }}</label>
        <select id="status" name="status" class="rfq-select" required>
          @foreach (['pending'=>'قيد المراجعة','reviewed'=>'مُراجع','quoted'=>'تم التسعير','won'=>'رابح','lost'=>'خاسر'] as $k=>$v)
            <option value="{{ $k }}" @selected($rfq->status===$k)>{{ $v }}</option>
          @endforeach
        </select>

        <label class="rfq-label" for="note" style="margin-top:10px">{{ __('app.internal_notes') }}</label>
        <textarea id="note" name="note" rows="4" class="rfq-textarea">{{ old('note',$rfq->note) }}</textarea>

        <button class="rfq-submit" style="margin-top:12px">{{ __('app.save_changes') }}</button>
      </form>
    </div>
  </div>

  <div style="margin-top:14px">
    <a class="btn btn--search btn--outline" href="{{ url()->previous() }}">
        {{ __('app.back') }}
    </a>
</div>
</div>
@endsection