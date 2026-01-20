@extends('layouts.site')

@push('styles')
  @vite(['resources/css/admin.css','resources/css/rfq.css'])
@endpush

@section('content')
<div class="container" style="max-width:1100px">
  <h1 class="page-title">{{ __('app.rfq_requests') }}</h1>

  <form method="GET" class="search-bar" style="margin-bottom:12px">
    <input type="text" name="q" value="{{ $q }}" placeholder="{{ __('app.search_name_email_phone') }}">
    <select name="status" class="rfq-select" style="max-width:220px">
      <option value="">{{ __('app.all_statuses') }}</option>
      @foreach (['pending'=>__('app.under_review'),'reviewed'=>__('app.reviewed'),'quoted'=>__('app.quoted'),'won'=>__('app.winner'),'lost'=>__('app.loser')] as $k=>$v)
        <option value="{{ $k }}" @selected($status===$k)>{{ $v }}</option>
      @endforeach
    </select>
    <button class="btn primary">{{ __('app.filter') }}</button>
  </form>

  <div class="card" style="padding:0">
    <div class="table-responsive" style="overflow:auto">
      <table class="table table-striped align-middle mb-0" style="width:100%; border-collapse:separate; border-spacing:0">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ __('app.client') }}</th>
            <th>{{ __('app.phone') }}</th>
            <th>{{ __('app.service') }}</th>
            <th>{{ __('app.budget') }}</th>
            <th>{{ __('app.status') }}</th>
            <th>{{ __('app.date') }}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($rfqs as $r)
            <tr>
              <td>{{ $r->id }}</td>
              <td>
                {{ $r->client_name }}
                <div class="text-muted small" style="opacity:.8">{{ $r->email }}</div>
              </td>
              <td>{{ $r->phone }}</td>
              <td>{{ $r->service }}</td>
              <td>{{ $r->budget }}</td>
              <td>{{ $r->status }}</td>
              <td class="text-muted small">{{ $r->created_at?->format('Y-m-d H:i') }}</td>
              <td>
                <a class="btn outline" href="{{ route('admin.rfqs.show',$r) }}">{{ __('app.open') }}</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center py-4">{{ __('app.no_requests') }}</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card-footer" style="padding:12px 16px">
      {{ $rfqs->links() }}
    </div>
  </div>
</div>
@endsection