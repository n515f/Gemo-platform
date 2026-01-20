@extends('layouts.admin')

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
<section class="admin-page">
  <header class="page-head">
    <div class="lhs">
      <h1 class="title">{{ __('app.manage_reports') }}</h1>
      <p class="muted">{{ __('app.reports_subtitle') }}</p>
    </div>

    <form method="GET" action="{{ route('admin.reports.index') }}" class="toolbar">
      <input
        class="input"
        type="text"
        name="q"
        value="{{ $q ?? '' }}"
        placeholder="{{ __('app.search_placeholder') }}"
        aria-label="{{ __('app.search_placeholder') }}"
      >

      @php $o = $order ?? 'id'; @endphp
      <select class="select" name="order" aria-label="{{ __('app.order_by') }}">
        <option value="id"         {{ $o==='id' ? 'selected' : '' }}>{{ __('app.id') }}</option>
        <option value="title"      {{ $o==='title' ? 'selected' : '' }}>{{ __('app.title') }}</option>
        <option value="updated_at" {{ $o==='updated_at' ? 'selected' : '' }}>{{ __('app.updated_at') }}</option>
      </select>

      @php $d = $dir ?? 'desc'; @endphp
      <select class="select" name="dir" aria-label="{{ __('app.direction') }}">
        <option value="asc"  {{ $d==='asc'  ? 'selected' : '' }}>{{ __('app.asc') }}</option>
        <option value="desc" {{ $d==='desc' ? 'selected' : '' }}>{{ __('app.desc') }}</option>
      </select>

      <button class="btn btn-light" type="submit">{{ __('app.filter') }}</button>
      <a class="btn btn-primary" href="{{ route('admin.reports.create') }}">{{ __('app.new_report') }}</a>
    </form>
  </header>

  @include('components.flash')

  <div class="card">
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ __('app.title') }}</th>
            <th>{{ __('app.project') }}</th>
            <th>{{ __('app.technician') }}</th>
            <th>{{ __('app.updated_at') }}</th>
            <th class="actions-col">{{ __('app.actions') }}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rows as $r)
            <tr>
              <td>{{ $r->id }}</td>
              <td class="strong">
                <a href="{{ route('admin.reports.show', $r) }}">{{ $r->title }}</a>
              </td>
              <td>{{ optional($r->project)->title ?? '—' }}</td>
              <td>{{ optional($r->user)->name ?? '—' }}</td>
              <td>{{ optional($r->updated_at)->format('Y-m-d H:i') }}</td>
              <td class="actions">
                <a class="btn btn-soft small"  href="{{ route('admin.reports.show', $r) }}">{{ __('app.show') }}</a>
                <a class="btn btn-light small" href="{{ route('admin.reports.edit', $r) }}">{{ __('app.edit') }}</a>

                <form method="POST"
                      action="{{ route('admin.reports.destroy', $r) }}"
                      class="needs-confirm"
                      data-confirm="{{ __('app.confirm_delete_report') }}"
                      style="display:inline-block">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger small" type="submit">{{ __('app.delete') }}</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="empty">{{ __('app.no_data') }}</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="pagination">
      {{ $rows->withQueryString()->links() }}
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
  // تأكيد الحذف بدون حقن تعبيرات Blade في JS (لتفادي تحذيرات VS Code)
  document.addEventListener('submit', function (e) {
    const form = e.target.closest('form.needs-confirm');
    if (!form) return;
    const msg = form.dataset.confirm || '';
    if (!confirm(msg)) {
      e.preventDefault();
    }
  });
</script>
@endpush
