{{-- resources/views/admin/rfqs/show.blade.php --}}
@section('content')
<section class="admin-page">
  <header class="page-head">
    <div>
      <h1 class="title">{{ __('app.rfq_details_title', ['id' => $rfq->id]) }}</h1>
      <p class="muted">
        {{ $rfq->client_name }} — <span dir="ltr">{{ $rfq->phone }}</span>
      </p>
    </div>
    <div class="toolbar">
      @php
        $waText = __('app.whatsapp_message', ['name' => $rfq->client_name, 'id' => $rfq->id]);
        $waLink = \App\Http\Controllers\Admin\RfqAdminController::whatsappUrl($rfq->phone, $waText);
      @endphp

      <a class="wa-icon" target="_blank" href="{{ $waLink }}" aria-label="{{ __('app.whatsapp') }}">
        <svg class="ico" viewBox="0 0 24 24">
          <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.472-.149-.671.15-.198.297-.767.967-.94 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.131-.607.134-.133.297-.347.446-.52.149-.173.198-.297.298-.495.099-.198.05-.372-.025-.521-.075-.149-.671-1.613-.919-2.208-.242-.58-.487-.501-.671-.51-.173-.009-.372-.011-.571-.011-.198 0-.52.074-.793.372-.273.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.214 3.074.149.198 2.099 3.2 5.08 4.487.711.306 1.265.489 1.697.627.712.227 1.36.195 1.871.118.571-.085 1.758-.718 2.007-1.413.248-.695.248-1.29.173-1.413-.074-.124-.273-.198-.57-.347z" fill="currentColor"/>
          <path d="M12.003 2.001C6.48 2.001 2 6.481 2 12.004c0 2.076.676 4.004 1.826 5.571L2 22l4.5-1.778c1.5.82 3.215 1.29 5.003 1.29 5.523 0 10.003-4.48 10.003-10.003S17.526 2.001 12.003 2.001z" fill="none" stroke="currentColor" stroke-width="2"/>
        </svg>
      </a>

      <a class="btn btn--search btn--outline" href="{{ route('admin.rfqs.index') }}">
        {{ __('app.back_to_list') }}
      </a>
    </div>
  </header>

  @include('components.flash')

  <div class="grid two rfq-form">
    <div class="card">
      <h3 class="card-title">{{ __('app.rfq_data_card_title') }}</h3>
      <div class="stack">
        <div><b>{{ __('app.client') }}:</b> {{ $rfq->client_name }}</div>
        <div><b>{{ __('app.phone') }}:</b> <span dir="ltr">{{ $rfq->phone }}</span></div>
        <div><b>{{ __('app.email') }}:</b> <span dir="ltr">{{ $rfq->email ?: '—' }}</span></div>
        <div><b>{{ __('app.location') }}:</b> {{ $rfq->location ?: '—' }}</div>
        <div><b>{{ __('app.service') }}:</b> {{ $rfq->service ?: '—' }}</div>
        <div><b>{{ __('app.budget') }}:</b> {{ $rfq->budget ?: '—' }}</div>
        <div><b>{{ __('app.quantity') }}:</b> {{ $rfq->quantity ?? 1 }}</div>
        <div><b>{{ __('app.short_description') }}:</b> {{ $rfq->brief ?: '—' }}</div>
        <div><b>{{ __('app.created_at_label') }}:</b> {{ optional($rfq->created_at)->format('Y-m-d H:i') }}</div>

        @if($rfq->product && $rfq->product->images && $rfq->product->images->first())
          <figure class="rfq-product">
            <img src="{{ asset($rfq->product->images->first()->path ?? $rfq->product->images->first()->url ?? '') }}"
                 alt="{{ $rfq->product->name_ar ?? $rfq->product->name_en ?? __('app.product') }}">
            <figcaption>{{ $rfq->product->name_ar ?? $rfq->product->name_en }}</figcaption>
          </figure>
        @endif

        @if($rfq->pdf_path)
          <div>
            <a class="att" target="_blank" href="{{ asset($rfq->pdf_path) }}">{{ __('app.view_attachment_pdf') }}</a>
          </div>
        @endif
      </div>
    </div>

    <div class="card">
      <h3 class="card-title">{{ __('app.update_request') }}</h3>
      <form method="post" action="{{ route('admin.rfqs.update',$rfq) }}" enctype="multipart/form-data" class="stack">
        @csrf @method('put')

        <label class="lbl">{{ __('app.status') }}</label>
        <select class="select" name="status">
          @foreach(\App\Http\Controllers\Admin\RfqAdminController::STATUSES as $s)
            <option value="{{ $s }}" {{ $rfq->status===$s ? 'selected':'' }}>
              {{ $s==='pending' ? __('app.status_pending') : ($s==='quoted' ? __('app.status_quoted') : __('app.status_done')) }}
            </option>
          @endforeach
        </select>

        <label class="lbl">{{ __('app.link_to_product_optional') }}</label>
        <select class="select" name="product_id">
          <option value="">{{ __('app.none_em_dash') }}</option>
          @foreach($products as $p)
            <option value="{{ $p->id }}" {{ $rfq->product_id===$p->id ? 'selected':'' }}>
              {{ $p->name_ar }} / {{ $p->name_en }}
            </option>
          @endforeach
        </select>

        <label class="lbl">{{ __('app.internal_notes') }}</label>
        <textarea class="textarea" name="notes" rows="4">{{ old('notes',$rfq->notes) }}</textarea>

        <label class="lbl">{{ __('app.upload_pdf_offer_optional') }}</label>
        <input class="input" type="file" name="pdf" accept="application/pdf">

        <div class="actions">
          <button class="btn btn--add" type="submit">
            <svg class="ico" viewBox="0 0 24 24">
              <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" fill="none" stroke="currentColor" stroke-width="2"/>
              <polyline points="17,21 17,13 7,13 7,21" fill="none" stroke="currentColor" stroke-width="2"/>
              <polyline points="7,3 7,8 15,8" fill="none" stroke="currentColor" stroke-width="2"/>
            </svg>
            {{ __('app.save_changes') }}
          </button>

          <a class="btn btn--search btn--outline" href="{{ route('admin.rfqs.index') }}">
            {{ __('app.cancel') }}
          </a>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
