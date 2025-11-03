{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')
@section('title', __('app.admin'))

@push('styles')
  @vite(['resources/css/entries/admin.css'])
@endpush

@section('content')
<div class="dash">
  {{-- الرأس + أزرار سريعة --}}
  <div class="dash-head">
    <div>
      <h1 class="dash-title">{{ __('app.dashboard_title') }}</h1>
      <p class="dash-sub">{{ __('app.dashboard_subtitle') }}</p>
    </div>
    <div class="dash-actions">
      <a href="{{ route('admin.projects.index') }}" class="btn btn--search">
        <svg class="ico" viewBox="0 0 24 24">
            <path d="M3 7h18M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z" fill="none" stroke="currentColor" stroke-width="2"/>
        </svg>
        {{ __('app.projects') }}
      </a>
      <a href="{{ route('admin.products.index') }}" class="btn btn--search">
        <svg class="ico" viewBox="0 0 24 24">
            <path d="M4 6h16M4 12h16M4 18h16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        {{ __('app.catalog') }}
      </a>
      <a href="{{ route('admin.rfqs.index') }}" class="btn btn--search">
        <svg class="ico" viewBox="0 0 24 24">
            <rect x="3" y="4" width="18" height="16" rx="2" ry="2" fill="none" stroke="currentColor" stroke-width="2"/>
            <path d="M7 8h10M7 12h10M7 16h6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        {{ __('app.rfqs') }}
      </a>
    </div>
  </div>

  {{-- بطائق KPI مُحسّنة --}}
  <div class="kpi-grid">
    <a class="kpi-card k1" href="{{ route('admin.products.index') }}">
      <div class="kpi-top">
        <span class="kpi-label">{{ __('app.products_count') }}</span>
        <div class="kpi-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
            <path d="m1 1 4 4 5.5 11H21l-3-7H6.5"/>
          </svg>
        </div>
      </div>
      <div class="kpi-value">{{ number_format($stats['products']) }}</div>
      <div class="kpi-footer">{{ __('app.catalog_management') }}</div>
    </a>

    <a class="kpi-card k2" href="{{ route('admin.projects.index') }}">
      <div class="kpi-top">
        <span class="kpi-label">{{ __('app.projects_count') }}</span>
        <div class="kpi-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
          </svg>
        </div>
      </div>
      <div class="kpi-value">{{ number_format($stats['projects']) }}</div>
      <div class="kpi-footer">{{ __('app.total_active', ['active' => number_format($stats['active_projects'])]) }}</div>
    </a>

    <a class="kpi-card k3" href="{{ route('admin.rfqs.index') }}">
      <div class="kpi-top">
        <span class="kpi-label">{{ __('app.rfqs_count') }}</span>
        <div class="kpi-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
          </svg>
        </div>
      </div>
      <div class="kpi-value">{{ number_format($stats['rfqs']) }}</div>
      <div class="kpi-footer">{{ __('app.since_beginning') }}</div>
    </a>

    <a class="kpi-card k4" href="{{ route('admin.reports.index') }}">
      <div class="kpi-top">
        <span class="kpi-label">{{ __('app.today_reports') }}</span>
        <div class="kpi-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/><polyline points="10,9 9,9 8,9"/>
          </svg>
        </div>
      </div>
      <div class="kpi-value">{{ number_format($stats['reports_today']) }}</div>
      <div class="kpi-footer">{{ __('app.uploaded_today') }}</div>
    </a>
  </div>

  {{-- شبكة المخططات الحديثة --}}
  <section class="charts-grid">
    {{-- خطي: نشاط طلبات عرض السعر (آخر 14 يوم) --}}
    <div class="chart-card line" id="rfqsTrend"
         data-dates='@json($recentRfqs->pluck("created_at")->map->format("Y-m-d"))'>
      <div class="chart-header">
        <div class="chart-title">{{ __('app.rfq_daily_activity') }}</div>
        <div class="chart-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M3 3v18h18"/>
            <path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/>
          </svg>
        </div>
      </div>
      <svg width="560" height="220" viewBox="0 0 560 220" preserveAspectRatio="none"></svg>
      <div class="chart-legend">
        <span class="dot dot-primary"></span> {{ __('app.daily_requests_count') }}
      </div>
    </div>

    {{-- أعمدة: تقارير الفنيين (آخر 14 يوم) --}}
    <div class="chart-card bar" id="reportsBar"
         data-dates='@json($recentReports->pluck("created_at")->map->format("Y-m-d"))'>
      <div class="chart-header">
        <div class="chart-title">{{ __('app.technician_reports_chart') }}</div>
        <div class="chart-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="20" x2="12" y2="10"/>
            <line x1="18" y1="20" x2="18" y2="4"/>
            <line x1="6" y1="20" x2="6" y2="16"/>
          </svg>
        </div>
      </div>
      <svg width="560" height="220" viewBox="0 0 560 220" preserveAspectRatio="none"></svg>
      <div class="chart-legend">
        <span class="dot dot-secondary"></span> {{ __('app.daily_reports_count') }}
      </div>
    </div>

    {{-- دونت: المشاريع النشطة مقابل الإجمالي --}}
    <div class="chart-card donut" id="projectsDonut"
         data-active="{{ (int)($stats['active_projects'] ?? 0) }}"
         data-total="{{ (int)($stats['projects'] ?? 0) }}">
      <div class="chart-header">
        <div class="chart-title">{{ __('app.active_projects_ratio') }}</div>
        <div class="chart-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21.21 15.89A10 10 0 1 1 8 2.83"/>
            <path d="M22 12A10 10 0 0 0 12 2v10z"/>
          </svg>
        </div>
      </div>
      <svg width="280" height="220" viewBox="0 0 280 220" preserveAspectRatio="xMidYMid meet"></svg>
      <div class="chart-legend">
        <span class="dot dot-success"></span> {{ __('app.active') }}
        <span class="dot dot-muted"></span> {{ __('app.inactive') }}
      </div>
    </div>
  </section>

  {{-- شبكة وسط الصفحة: تايملاين --}}
  <div class="dash-grid">
    <div class="card activity">
      <div class="card-header">
        <div class="card-title-group">
          <div class="card-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14,2 14,8 20,8"/>
              <line x1="16" y1="13" x2="8" y2="13"/>
              <line x1="16" y1="17" x2="8" y2="17"/>
            </svg>
          </div>
          <h3 class="card-title">{{ __('app.recent_technician_reports') }}</h3>
        </div>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-light btn-sm">{{ __('app.view_all') }}</a>
      </div>
      @if($recentReports->isEmpty())
        <div class="empty">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14,2 14,8 20,8"/>
          </svg>
          {{ __('app.no_recent_reports') }}
        </div>
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
  </div>

  {{-- جدول مختصر لطلبات عروض الأسعار --}}
  <div class="card">
    <div class="card-header">
      <div class="card-title-group">
        <div class="card-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
          </svg>
        </div>
        <h3 class="card-title">{{ __('app.recent_rfqs') }}</h3>
      </div>
      <a href="{{ route('admin.rfqs.index') }}" class="btn btn-light btn-sm">{{ __('app.view_all') }}</a>
    </div>
    @if($recentRfqs->isEmpty())
      <div class="empty">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
          <polyline points="22,6 12,13 2,6"/>
        </svg>
        {{ __('app.no_recent_rfqs') }}
      </div>
    @else
      <div class="table-responsive">
        <table class="table modern-table">
          <thead>
            <tr>
              <th>{{ __('app.id') }}</th>
              <th>{{ __('app.client') }}</th>
              <th>{{ __('app.service') }}</th>
              <th>{{ __('app.budget') }}</th>
              <th>{{ __('app.phone') }}</th>
              <th>{{ __('app.date') }}</th>
              <th>{{ __('app.actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recentRfqs as $r)
              <tr>
                <td class="table-id">{{ $r->id }}</td>
                <td class="table-name">{{ $r->client_name }}</td>
                <td class="table-service">{{ $r->service }}</td>
                <td class="table-budget">{{ $r->budget }}</td>
                <td class="table-phone">{{ $r->phone }}</td>
                <td class="table-date">{{ $r->created_at->format('Y-m-d H:i') }}</td>
                <td class="table-actions">
                  <a href="{{ route('admin.rfqs.show',$r) }}" class="btn btn-soft btn-sm">
                    {{ __('app.details') }}
                  </a>
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

@push('scripts')
<script>
(function(){
  const fmtDate = d => new Date(d);
  const lastNDays = (n) => {
    const days = [];
    const today = new Date(); today.setHours(0,0,0,0);
    for (let i = n-1; i >= 0; i--) {
      const d = new Date(today); d.setDate(today.getDate() - i);
      days.push(d.toISOString().slice(0,10));
    }
    return days;
  };
  const countByDay = (dates, n = 14) => {
    const days = lastNDays(n);
    const set = Object.create(null);
    (dates || []).forEach(d => { const k = String(d).slice(0,10); set[k] = (set[k]||0)+1; });
    return { days, values: days.map(k => set[k]||0) };
  };

  // Line chart
  const drawLineChart = (el, values, labels) => {
    const svg = el.querySelector('svg');
    const w = svg.viewBox.baseVal.width || 560;
    const h = svg.viewBox.baseVal.height || 220;
    const pad = { t: 20, r: 20, b: 26, l: 26 };
    const max = Math.max(1, ...values);
    const xs = (w - pad.l - pad.r) / (values.length - 1);
    const ys = (h - pad.t - pad.b) / max;

    // grid
    const grid = document.createElementNS('http://www.w3.org/2000/svg','g');
    grid.setAttribute('opacity','0.18');
    for(let i=0;i<=4;i++){
      const y = pad.t + (h - pad.t - pad.b) * (i/4);
      const ln = document.createElementNS(grid.namespaceURI,'line');
      ln.setAttribute('x1', pad.l); ln.setAttribute('x2', w - pad.r);
      ln.setAttribute('y1', y); ln.setAttribute('y2', y);
      ln.setAttribute('stroke', 'var(--ring)');
      grid.appendChild(ln);
    }
    svg.appendChild(grid);

    // path
    const path = document.createElementNS(svg.namespaceURI,'path');
    let d = `M ${pad.l} ${h-pad.b - values[0]*ys}`;
    values.forEach((v,i) => {
      const x = pad.l + i*xs;
      const y = h-pad.b - v*ys;
      d += ` L ${x} ${y}`;
    });
    path.setAttribute('d', d);
    path.setAttribute('fill','none');
    path.setAttribute('stroke','url(#gradLine)');
    path.setAttribute('stroke-width','3');
    svg.appendChild(path);

    // area fill
    const area = document.createElementNS(svg.namespaceURI,'path');
    const base = `${d} L ${pad.l + (values.length-1)*xs} ${h-pad.b} L ${pad.l} ${h-pad.b} Z`;
    area.setAttribute('d', base);
    area.setAttribute('fill','url(#gradArea)');
    area.setAttribute('opacity','0.25');
    svg.appendChild(area);

    // x labels (every 2)
    const xg = document.createElementNS(svg.namespaceURI,'g');
    labels.forEach((lb,i)=>{
      if(i%2!==0) return;
      const x = pad.l + i*xs;
      const t = document.createElementNS(svg.namespaceURI,'text');
      t.setAttribute('x', x); t.setAttribute('y', h-6);
      t.setAttribute('text-anchor','middle'); t.setAttribute('class','axis-label');
      t.textContent = lb.slice(5); // mm-dd
      xg.appendChild(t);
    });
    svg.appendChild(xg);

    // defs gradients
    const defs = document.createElementNS(svg.namespaceURI,'defs');
    const gradLine = document.createElementNS(svg.namespaceURI,'linearGradient');
    gradLine.id = 'gradLine'; gradLine.setAttribute('x1','0'); gradLine.setAttribute('x2','1');
    gradLine.innerHTML = '<stop offset="0%" stop-color="#60a5fa"/><stop offset="100%" stop-color="#a78bfa"/>';
    const gradArea = document.createElementNS(svg.namespaceURI,'linearGradient');
    gradArea.id = 'gradArea'; gradArea.setAttribute('x1','0'); gradArea.setAttribute('x2','1');
    gradArea.innerHTML = '<stop offset="0%" stop-color="#60a5fa"/><stop offset="100%" stop-color="#a78bfa"/>';
    defs.appendChild(gradLine); defs.appendChild(gradArea); svg.insertBefore(defs, svg.firstChild);
  };

  // Bar chart
  const drawBarChart = (el, values, labels) => {
    const svg = el.querySelector('svg');
    const w = svg.viewBox.baseVal.width || 560, h = svg.viewBox.baseVal.height || 220;
    const pad = { t: 20, r: 20, b: 26, l: 26 };
    const max = Math.max(1, ...values);
    const bw = (w - pad.l - pad.r) / values.length * 0.65;
    const xs = (w - pad.l - pad.r) / values.length;

    // grid
    const grid = document.createElementNS('http://www.w3.org/2000/svg','g');
    grid.setAttribute('opacity','0.18');
    for(let i=0;i<=4;i++){
      const y = pad.t + (h - pad.t - pad.b) * (i/4);
      const ln = document.createElementNS(grid.namespaceURI,'line');
      ln.setAttribute('x1', pad.l); ln.setAttribute('x2', w - pad.r);
      ln.setAttribute('y1', y); ln.setAttribute('y2', y);
      ln.setAttribute('stroke', 'var(--ring)');
      grid.appendChild(ln);
    }
    svg.appendChild(grid);

    // bars
    values.forEach((v,i)=>{
      const x = pad.l + i*xs + (xs-bw)/2;
      const bh = (h - pad.t - pad.b) * (v/max);
      const y = h - pad.b - bh;
      const rect = document.createElementNS(svg.namespaceURI,'rect');
      rect.setAttribute('x', x); rect.setAttribute('y', y);
      rect.setAttribute('width', bw); rect.setAttribute('height', bh);
      rect.setAttribute('rx','6'); rect.setAttribute('fill','url(#gradBars)');
      rect.setAttribute('class','bar-item');
      svg.appendChild(rect);
    });

    // x labels
    const xg = document.createElementNS(svg.namespaceURI,'g');
    labels.forEach((lb,i)=>{
      if(i%2!==0) return;
      const x = pad.l + i*xs + xs/2;
      const t = document.createElementNS(svg.namespaceURI,'text');
      t.setAttribute('x', x); t.setAttribute('y', h-6);
      t.setAttribute('text-anchor','middle'); t.setAttribute('class','axis-label');
      t.textContent = lb.slice(5);
      xg.appendChild(t);
    });
    svg.appendChild(xg);

    // defs
    const defs = document.createElementNS(svg.namespaceURI,'defs');
    const grad = document.createElementNS(svg.namespaceURI,'linearGradient');
    grad.id = 'gradBars'; grad.setAttribute('x1','0'); grad.setAttribute('x2','1');
    grad.innerHTML = '<stop offset="0%" stop-color="#34d399"/><stop offset="100%" stop-color="#60a5fa"/>';
    defs.appendChild(grad); svg.insertBefore(defs, svg.firstChild);
  };

  // Donut chart
  const drawDonut = (el, active, total) => {
    const svg = el.querySelector('svg');
    const cx = 140, cy = 100, r = 64;
    const circle = (color, dash) => {
      const c = document.createElementNS(svg.namespaceURI,'circle');
      c.setAttribute('cx', cx); c.setAttribute('cy', cy); c.setAttribute('r', r);
      c.setAttribute('fill','none'); c.setAttribute('stroke', color); c.setAttribute('stroke-width','16');
      if(dash){ c.setAttribute('stroke-dasharray', dash); c.setAttribute('stroke-linecap','round'); c.setAttribute('transform',`rotate(-90 ${cx} ${cy})`); }
      return c;
    };
    const bg = circle('var(--ring)');
    const circ = 2*Math.PI*r;
    const val = Math.min(total>0 ? (active/total) : 0, 1) * circ;
    const donut = circle('url(#gradDonut)', `${val} ${circ}`);
    svg.appendChild(bg); svg.appendChild(donut);
    const defs = document.createElementNS(svg.namespaceURI,'defs');
    const grad = document.createElementNS(svg.namespaceURI,'linearGradient');
    grad.id = 'gradDonut'; grad.setAttribute('x1','0'); grad.setAttribute('x2','1');
    grad.innerHTML = '<stop offset="0%" stop-color="#a78bfa"/><stop offset="100%" stop-color="#60a5fa"/>';
    defs.appendChild(grad); svg.insertBefore(defs, svg.firstChild);

    const label = document.createElementNS(svg.namespaceURI,'text');
    label.setAttribute('x', cx); label.setAttribute('y', cy+6);
    label.setAttribute('text-anchor','middle'); label.setAttribute('class','donut-label');
    label.textContent = `${Math.round((active/Math.max(1,total))*100)}%`;
    svg.appendChild(label);
  };

  // Build data and draw
  const rfqsEl = document.getElementById('rfqsTrend');
  const reportsEl = document.getElementById('reportsBar');
  const donutEl = document.getElementById('projectsDonut');

  const rfqsDates = JSON.parse(rfqsEl.dataset.dates || '[]');
  const reportsDates = JSON.parse(reportsEl.dataset.dates || '[]');

  const rfqs = countByDay(rfqsDates, 14);
  const reps  = countByDay(reportsDates, 14);

  drawLineChart(rfqsEl, rfqs.values, rfqs.days);
  drawBarChart(reportsEl, reps.values, reps.days);
  drawDonut(donutEl, Number(donutEl.dataset.active||0), Number(donutEl.dataset.total||0));
})();
</script>
@endpush