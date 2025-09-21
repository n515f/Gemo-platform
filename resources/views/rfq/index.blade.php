@extends('layouts.site')

@push('styles')
  @vite(['resources/css/admin.css','resources/css/rfq.css'])
@endpush

@section('content')
<div class="container" style="max-width:1100px">
  <h1 class="page-title">{{ __('طلبات عرض السعر') }}</h1>

  <form method="GET" class="search-bar" style="margin-bottom:12px">
    <input type="text" name="q" value="{{ $q }}" placeholder="ابحث بالاسم/البريد/الهاتف">
    <select name="status" class="rfq-select" style="max-width:220px">
      <option value="">{{ __('كل الحالات') }}</option>
      @foreach (['pending'=>'قيد المراجعة','reviewed'=>'مُراجع','quoted'=>'تم التسعير','won'=>'رابح','lost'=>'خاسر'] as $k=>$v)
        <option value="{{ $k }}" @selected($status===$k)>{{ $v }}</option>
      @endforeach
    </select>
    <button class="btn primary">{{ __('تصفية') }}</button>
  </form>

  <div class="card" style="padding:0">
    <div class="table-responsive" style="overflow:auto">
      <table class="table table-striped align-middle mb-0" style="width:100%; border-collapse:separate; border-spacing:0">
        <thead>
          <tr>
            <th>#</th>
            <th>العميل</th>
            <th>الهاتف</th>
            <th>الخدمة</th>
            <th>الميزانية</th>
            <th>الحالة</th>
            <th>التاريخ</th>
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
                <a class="btn outline" href="{{ route('admin.rfqs.show',$r) }}">{{ __('فتح') }}</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center py-4">{{ __('لا توجد طلبات.') }}</td>
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