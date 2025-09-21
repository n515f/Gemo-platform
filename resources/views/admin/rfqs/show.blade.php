{{-- resources/views/admin/rfqs/show.blade.php --}}
@extends('layouts.site')

@push('styles')
  @vite(['resources/css/pages/admin.rfqs.css'])
@endpush

@section('content')
<section class="admin-page">
  <header class="page-head">
    <div>
      <h1 class="title">{{ __('تفاصيل طلب #') }}{{ $rfq->id }}</h1>
      <p class="muted">{{ $rfq->client_name }} — <span dir="ltr">{{ $rfq->phone }}</span></p>
    </div>
    <div class="toolbar">
      @php
        $waLink = \App\Http\Controllers\Admin\RfqAdminController::whatsappUrl($rfq->phone, "مرحباً {$rfq->client_name} بخصوص طلبك رقم #{$rfq->id}");
      @endphp
      <a class="btn btn-wa" target="_blank" href="{{ $waLink }}">{{ __('واتساب') }}</a>
      <a class="btn btn-light" href="{{ route('admin.rfqs.index') }}">{{ __('العودة') }}</a>
    </div>
  </header>

  @if(session('ok')) <div class="flash success">{{ session('ok') }}</div> @endif
  @if($errors->any()) <div class="flash error">{{ implode(' • ', $errors->all()) }}</div> @endif

  <div class="grid two">
    <div class="card">
      <h3 class="card-title">{{ __('بيانات الطلب') }}</h3>
      <div class="stack">
        <div><b>{{ __('العميل') }}:</b> {{ $rfq->client_name }}</div>
        <div><b>{{ __('الهاتف') }}:</b> <span dir="ltr">{{ $rfq->phone }}</span></div>
        <div><b>{{ __('البريد') }}:</b> <span dir="ltr">{{ $rfq->email ?: '—' }}</span></div>
        <div><b>{{ __('الموقع') }}:</b> {{ $rfq->location ?: '—' }}</div>
        <div><b>{{ __('الخدمة') }}:</b> {{ $rfq->service ?: '—' }}</div>
        <div><b>{{ __('الميزانية') }}:</b> {{ $rfq->budget ?: '—' }}</div>
        <div><b>{{ __('الكمية') }}:</b> {{ $rfq->quantity ?? 1 }}</div>
        <div><b>{{ __('وصف مختصر') }}:</b> {{ $rfq->brief ?: '—' }}</div>
        <div><b>{{ __('أُنشئ في') }}:</b> {{ optional($rfq->created_at)->format('Y-m-d H:i') }}</div>
        @if($rfq->pdf_path)
          <div><a class="att" target="_blank" href="{{ asset($rfq->pdf_path) }}">{{ __('عرض المرفق (PDF)') }}</a></div>
        @endif
      </div>
    </div>

    <div class="card">
      <h3 class="card-title">{{ __('تحديث الطلب') }}</h3>
      <form method="post" action="{{ route('admin.rfqs.update',$rfq) }}" enctype="multipart/form-data" class="stack">
        @csrf @method('put')

        <label class="lbl">{{ __('الحالة') }}</label>
        <select class="select" name="status">
          @foreach(\App\Http\Controllers\Admin\RfqAdminController::STATUSES as $s)
            <option value="{{ $s }}" {{ $rfq->status===$s ? 'selected':'' }}>
              {{ __($s==='pending'?'بانتظار الرد':($s==='quoted'?'تم إرسال عرض':'مكتمل')) }}
            </option>
          @endforeach
        </select>

        <label class="lbl">{{ __('ربط بمنتج (اختياري)') }}</label>
        <select class="select" name="product_id">
          <option value="">{{ __('— بدون —') }}</option>
          @foreach($products as $p)
            <option value="{{ $p->id }}" {{ $rfq->product_id===$p->id ? 'selected':'' }}>
              {{ $p->name_ar }} / {{ $p->name_en }}
            </option>
          @endforeach
        </select>

        <label class="lbl">{{ __('ملاحظات داخلية') }}</label>
        <textarea class="textarea" name="notes" rows="4">{{ old('notes',$rfq->notes) }}</textarea>

        <label class="lbl">{{ __('رفع عرض السعر PDF (اختياري)') }}</label>
        <input class="input" type="file" name="pdf" accept="application/pdf">

        <div class="actions">
          <button class="btn btn-primary" type="submit">{{ __('حفظ التعديلات') }}</button>
          <a class="btn btn-light" href="{{ route('admin.rfqs.index') }}">{{ __('إلغاء') }}</a>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection