{{-- resources/views/admin/projects/index.blade.php --}}
@extends('layouts.admin')

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
<section class="admin-page">

  <header class="page-head">
    <div class="lhs">
      <h1 class="title">{{ __('app.projects_track_title') }}</h1>
      <p class="muted">{{ __('app.projects_track_subtitle') }}</p>
    </div>

    <form method="GET" class="toolbar">
      <input class="input" type="text" name="q" value="{{ $q ?? '' }}" placeholder="{{ __('app.search_projects_ph') }}">
      <select class="select" name="order">
        @php $order = $order ?? 'id'; @endphp
        <option value="id"         {{ $order==='id'?'selected':'' }}>{{ __('app.id') }}</option>
        <option value="title"      {{ $order==='title'?'selected':'' }}>{{ __('app.title') }}</option>
        <option value="client_name"{{ $order==='client_name'?'selected':'' }}>{{ __('app.client') }}</option>
        <option value="status"     {{ $order==='status'?'selected':'' }}>{{ __('app.status') }}</option>
        <option value="start_date" {{ $order==='start_date'?'selected':'' }}>{{ __('app.start_short') }}</option>
        <option value="due_date"   {{ $order==='due_date'?'selected':'' }}>{{ __('app.due_short') }}</option>
        <option value="updated_at" {{ $order==='updated_at'?'selected':'' }}>{{ __('app.updated_at') }}</option>
      </select>
      <select class="select" name="dir">
        @php $dir = $dir ?? 'desc'; @endphp
        <option value="asc"  {{ $dir==='asc'?'selected':'' }}>{{ __('app.asc') }}</option>
        <option value="desc" {{ $dir==='desc'?'selected':'' }}>{{ __('app.desc') }}</option>
      </select>
      <button class="btn btn-light" type="submit">{{ __('app.filter') }}</button>
      <a class="btn btn-primary" href="{{ route('admin.projects.create') }}">+ {{ __('app.new_project') }}</a>
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
            <th>{{ __('app.client') }}</th>
            <th>{{ __('app.status') }}</th>
            <th>{{ __('app.start_short') }}</th>
            <th>{{ __('app.due_short') }}</th>
            <th>{{ __('app.updated_at') }}</th>
            <th class="actions-col">{{ __('app.actions') }}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rows as $p)
            <tr>
              <td>{{ $p->id }}</td>
              <td class="strong"><a href="{{ route('admin.projects.show', $p) }}">{{ $p->title }}</a></td>
              <td>{{ $p->client_name }}</td>
              <td><span class="badge">{{ __('app.statuses.' . $p->status) }}</span></td>
              <td>{{ $p->start_date ?: '—' }}</td>
              <td>{{ $p->due_date   ?: '—' }}</td>
              <td>{{ optional($p->updated_at)->format('Y-m-d H:i') }}</td>
              <td class="actions">
                <a class="btn btn-soft small"  href="{{ route('admin.projects.show', $p) }}">{{ __('app.view') }}</a>
                <a class="btn btn-light small" href="{{ route('admin.projects.edit', $p) }}">{{ __('app.edit') }}</a>
                <form method="POST"
                      action="{{ route('admin.projects.destroy', $p) }}"
                      class="needs-confirm" data-confirm="{{ __('app.confirm_delete_project') }}"
                      style="display:inline-block">
                  @csrf @method('DELETE')
                  <button class="btn btn-danger small" type="submit">{{ __('app.delete') }}</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="8" class="empty">{{ __('app.no_data') }}</td></tr>
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
