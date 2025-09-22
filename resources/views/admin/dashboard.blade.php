{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.site')

@push('styles')
  {{-- ادخل CSS العام + صفحة الداشبورد --}}
  @vite([
    'resources/css/entries/admin.css',
    'resources/css/pages/admin.dashboard.css'
  ])
@endpush

@section('content')
<div class="dash">

  {{-- العنوان + زر سريع --}}
  <div class="dash-head">
    <div>
      <h1 class="dash-title">لوحة التحكم</h1>
      <p class="dash-sub">نظرة سريعة على أهم المؤشرات والأنشطة الأخيرة.</p>
    </div>
    <div class="dash-actions">
      <a href="{{ route('admin.projects.index') }}" class="btn btn-primary">المشاريع</a>
      <a href="{{ route('admin.products.index') }}" class="btn btn-light">الكتالوج</a>
      <a href="{{ route('admin.rfqs.index') }}" class="btn btn-soft">طلبات عروض الأسعار</a>
    </div>
  </div>

  {{-- بطائق KPI --}}
  <div class="kpi-grid">
    <a class="kpi-card k1" href="{{ route('admin.products.index') }}">
      <div class="kpi-top">
        <span class="kpi-label">المنتجات</span>
        <span class="kpi-icon">🛒</span>
      </div>
      <div class="kpi-value">{{ number_format($stats['products']) }}</div>
      <div class="kpi-footer">إدارة الكتالوج</div>
    </a>

    <a class="kpi-card k2" href="{{ route('admin.projects.index') }}">
      <div class="kpi-top">
        <span class="kpi-label">المشاريع</span>
        <span class="kpi-icon">📁</span>
      </div>
      <div class="kpi-value">{{ number_format($stats['projects']) }}</div>
      <div class="kpi-footer">الإجمالي</div>
      <div class="mini-pill">نشط: {{ number_format($stats['active_projects']) }}</div>
    </a>

    <a class="kpi-card k3" href="{{ route('admin.rfqs.index') }}">
      <div class="kpi-top">
        <span class="kpi-label">طلبات عرض السعر</span>
        <span class="kpi-icon">📨</span>
      </div>
      <div class="kpi-value">{{ number_format($stats['rfqs']) }}</div>
      <div class="kpi-footer">منذ البداية</div>
    </a>

    <a class="kpi-card k4" href="{{ route('admin.reports.index') }}">
      <div class="kpi-top">
        <span class="kpi-label">تقارير اليوم</span>
        <span class="kpi-icon">📝</span>
      </div>
      <div class="kpi-value">{{ number_format($stats['reports_today']) }}</div>
      <div class="kpi-footer">مرفوعة اليوم</div>
    </a>
  </div>

  {{-- صفين: أنشطة + “مؤشر بسيط” --}}
  <div class="dash-grid">
    <div class="card activity">
      <h3 class="card-title">آخر تقارير الفنيين</h3>

      @if($recentReports->isEmpty())
        <div class="empty">لا توجد تقارير حديثة.</div>
      @else
        <ul class="timeline">
          @foreach($recentReports as $rep)
            <li class="timeline-item">
              <div class="dot"></div>
              <div class="content">
                <div class="line-1">
                  <a href="{{ route('admin.reports.show', $rep) }}" class="strong">{{ $rep->title }}</a>
                </div>
                <div class="line-2">
                  {{ optional($rep->project)->title ?? '—' }}
                  • <span class="muted">{{ $rep->created_at->diffForHumans() }}</span>
                </div>
              </div>
            </li>
          @endforeach
        </ul>
      @endif
    </div>

    <div class="card gauge">
      <h3 class="card-title">مؤشر التقدم العام</h3>
      <div class="gauge-wrap">
        <div class="gauge-arc">
          <div class="needle"></div>
        </div>
        <div class="gauge-value">
          {{-- نسبة تقديرية بسيطة بين المشاريع النشطة والإجمالي --}}
          @php
            $pct = $stats['projects'] ? round(($stats['active_projects'] / max(1,$stats['projects'])) * 100) : 0;
          @endphp
          <div class="big">{{ $pct }}%</div>
          <div class="small muted">نشاط المشاريع</div>
        </div>
      </div>
      <p class="muted mt-2">مؤشر بصري بسيط بدون مكتبات خارجية.</p>
    </div>
  </div>

  {{-- قائمة مختصرة لطلبات عروض الأسعار --}}
  <div class="card">
    <div class="card-title flex-between">
      <h3>أحدث طلبات عرض السعر</h3>
      <a href="{{ route('admin.rfqs.index') }}" class="btn btn-light small">عرض الكل</a>
    </div>

    @if($recentRfqs->isEmpty())
      <div class="empty">لا توجد طلبات حديثة.</div>
    @else
      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>العميل</th>
              <th>الخدمة</th>
              <th>الميزانية</th>
              <th>الهاتف</th>
              <th>تاريخ</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($recentRfqs as $r)
              <tr>
                <td>{{ $r->id }}</td>
                <td class="strong">{{ $r->client_name }}</td>
                <td>{{ $r->service }}</td>
                <td class="num">{{ $r->budget }}</td>
                <td class="ltr">{{ $r->phone }}</td>
                <td class="muted">{{ $r->created_at->format('Y-m-d H:i') }}</td>
                <td>
                  <a href="{{ route('admin.rfqs.show',$r) }}" class="btn btn-soft small">تفاصيل</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

</div>
@endsection