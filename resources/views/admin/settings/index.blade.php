@extends('layouts.admin')
@section('title', __('app.page_title'))

@push('styles')
  @vite([
    'resources/css/entries/admin.css',
    'resources/css/pages/admin.settings.css',
  ])
@endpush

@section('content')
<!-- Sprite داخلي للأيقونات — ضروري لعرض الأيقونات -->
<svg aria-hidden="true" style="position:absolute;width:0;height:0;overflow:hidden">
  <symbol id="i-briefcase" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
    <path d="M9 6h6V4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v2Z"></path>
    <rect x="3" y="6" width="18" height="14" rx="2"></rect>
    <path d="M3 12h18"></path>
  </symbol>
  <symbol id="i-link" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
    <path d="M10 13a5 5 0 0 1 0-7l1.5-1.5a5 5 0 0 1 7 7L17 13"></path>
    <path d="M7 17l-1.5 1.5a5 5 0 1 0 7 7L14 24"></path>
  </symbol>
  <symbol id="i-palette" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
    <path d="M12 3a9 9 0 1 0 9 9c0-2.2-1.8-2-3.2-2-1.3 0-2.3-1.2-2.3-2.6A4.4 4.4 0 0 0 12 3Z"></path>
    <circle cx="7.5" cy="13.5" r="1.2"></circle>
    <circle cx="9.5" cy="17" r="1.2"></circle>
    <circle cx="14.5" cy="17" r="1.2"></circle>
    <circle cx="16.5" cy="13.5" r="1.2"></circle>
  </symbol>
  <symbol id="i-admin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
    <circle cx="12" cy="7.5" r="3.5"></circle>
    <path d="M4 21a8 8 0 0 1 16 0"></path>
  </symbol>
  <symbol id="i-award" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
    <circle cx="12" cy="8" r="4"></circle>
    <path d="M8 13l-2 7 6-3 6 3-2-7"></path>
  </symbol>
  <symbol id="i-chevron-down" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
    <path d="M6 9l6 6 6-6"></path>
  </symbol>
  <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
    <path d="M6 6l12 12M18 6L6 18"></path>
  </symbol>
</svg>

<div class="settings-page" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="settings-header">
        <h1 class="title">{{ __('app.page_title') }}</h1>
        <p class="subtitle">{{ __('app.page_subtitle') }}</p>
    </div>

    @if(session('success'))
        <div class="alert success">
            {{ session('success') }}
        </div>
    @endif

    <div class="cards-grid">
        <div class="settings-card" data-settings-card data-open="0" tabindex="0" aria-expanded="false" role="button">
            <div class="card-head">
                <div class="card-icon">
                    <svg class="icon"><use href="#i-briefcase" xlink:href="#i-briefcase"></use></svg>
                </div>
                <h2 class="card-title">{{ __('app.company_section') }}</h2>
                <button type="button" class="card-toggle" data-card-toggle aria-expanded="false" aria-label="{{ __('app.open_card') }}">
                    <svg class="icon" data-toggle-icon><use href="#i-chevron-down" xlink:href="#i-chevron-down"></use></svg>
                </button>
            </div>
            <div class="card-body">
                <form class="card-form" data-card-form action="{{ route('admin.settings.updateAll') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-grid">
                        <div class="field">
                            <label class="lbl">{{ __('app.company_name_ar') }}</label>
                            <input type="text" name="company_name_ar" value="{{ $site->company_name_ar }}" class="input">
                        </div>
                        <div class="field">
                            <label class="lbl">{{ __('app.company_name_en') }}</label>
                            <input type="text" name="company_name_en" value="{{ $site->company_name_en }}" class="input">
                        </div>
                        <div class="field">
                            <label class="lbl">{{ __('app.company_tagline_ar') }}</label>
                            <input type="text" name="company_tagline_ar" value="{{ $site->company_tagline_ar }}" class="input">
                        </div>
                        <div class="field">
                            <label class="lbl">{{ __('app.company_tagline_en') }}</label>
                            <input type="text" name="company_tagline_en" value="{{ $site->company_tagline_en }}" class="input">
                        </div>
                        <div class="field">
                            <label class="lbl">{{ __('app.company_email') }}</label>
                            <input type="email" name="company_email" value="{{ $site->company_email }}" class="input">
                        </div>
                        <div class="field">
                            <label class="lbl">{{ __('app.company_email_alt') }}</label>
                            <input type="email" name="company_email_alt" value="{{ $site->company_email_alt }}" class="input">
                        </div>
                        <div class="field">
                            <label class="lbl">{{ __('app.company_phone') }}</label>
                            <input type="text" name="company_phone" value="{{ $site->company_phone }}" class="input">
                        </div>
                        <div class="field">
                            <label class="lbl">{{ __('app.company_whatsapp_number') }}</label>
                            <input type="text" name="company_whatsapp_number" value="{{ $site->company_whatsapp_number }}" class="input">
                        </div>
                        <div class="field col-span-2">
                            <label class="lbl">{{ __('app.company_address_ar') }}</label>
                            <input type="text" name="company_address_ar" value="{{ $site->company_address_ar }}" class="input">
                        </div>
                        <div class="field col-span-2">
                            <label class="lbl">{{ __('app.company_address_en') }}</label>
                            <input type="text" name="company_address_en" value="{{ $site->company_address_en }}" class="input">
                        </div>
                        <div class="field col-span-2">
                            <label class="lbl">{{ __('app.upload_logo') }}</label>
                            <input type="file" name="company_logo" accept="image/*" class="input">
                            @if($site->company_logo_path)
                              <div class="logo-preview">
                                <img src="{{ asset($site->company_logo_path) }}" alt="{{ __('app.logo_preview') }}">
                              </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn primary" data-card-save>{{ __('app.save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- روابط التواصل -->
        <div class="settings-card" data-settings-card data-open="0" tabindex="0" aria-expanded="false" role="button">
            <div class="card-head">
                <div class="card-icon">
                    <svg class="icon"><use href="#i-link" xlink:href="#i-link"></use></svg>
                </div>
                <h2 class="card-title">{{ __('app.social_section') }}</h2>
                <button type="button" class="card-toggle" data-card-toggle aria-expanded="false" aria-label="{{ __('app.open_card') }}">
                    <svg class="icon" data-toggle-icon><use href="#i-chevron-down" xlink:href="#i-chevron-down"></use></svg>
                </button>
            </div>
            <div class="card-body">
                <form class="card-form" data-card-form action="{{ route('admin.settings.updateAll') }}" method="POST">
                    @csrf
                    <div class="form-grid">
                        <div class="field">
                            <label class="lbl">{{ __('app.whatsapp') }}</label>
                            <input type="url" name="social_whatsapp_url" value="{{ $site->social_whatsapp_url }}" class="input">
                        </div>
                        <div class="field">
                            <label class="lbl">{{ __('app.instagram') }}</label>
                            <input type="url" name="social_instagram_url" value="{{ $site->social_instagram_url }}" class="input">
                        </div>
                        <div class="field">
                            <label class="lbl">{{ __('app.facebook') }}</label>
                            <input type="url" name="social_facebook_url" value="{{ $site->social_facebook_url }}" class="input">
                        </div>
                        <div class="field">
                            <label class="lbl">{{ __('app.twitter') }}</label>
                            <input type="url" name="social_twitter_url" value="{{ $site->social_twitter_url }}" class="input">
                        </div>
                        <div class="field">
                            <label class="lbl">{{ __('app.linkedin') }}</label>
                            <input type="url" name="social_linkedin_url" value="{{ $site->social_linkedin_url }}" class="input">
                        </div>
                        <div class="field">
                            <label class="lbl">{{ __('app.youtube') }}</label>
                            <input type="url" name="social_youtube_url" value="{{ $site->social_youtube_url }}" class="input">
                        </div>
                        <div class="field">
                            <label class="lbl">{{ __('app.tiktok') }}</label>
                            <input type="url" name="social_tiktok_url" value="{{ $site->social_tiktok_url }}" class="input">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn primary" data-card-save>{{ __('app.save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ألوان الموقع -->
        <div class="settings-card" data-settings-card data-open="0" tabindex="0" aria-expanded="false" role="button">
            <div class="card-head">
                <div class="card-icon">
                    <svg class="icon"><use href="#i-palette" xlink:href="#i-palette"></use></svg>
                </div>
                <h2 class="card-title">{{ __('app.site_colors') }}</h2>
                <button type="button" class="card-toggle" data-card-toggle aria-expanded="false" aria-label="{{ __('app.open_card') }}">
                    <svg class="icon" data-toggle-icon><use href="#i-chevron-down" xlink:href="#i-chevron-down"></use></svg>
                </button>
            </div>
            <div class="card-body">
                <form class="card-form" data-card-form action="{{ route('admin.settings.updateAll') }}" method="POST">
                    @csrf
                    <div class="form-grid">
                        <div class="field">
                            <label class="lbl">{{ __('app.primary_color') }}</label>
                            <input type="color" name="theme_primary_color" value="{{ $site->theme_primary_color ?? '#2563eb' }}" class="input">
                        </div>
                        <div class="field">
                            <label class="lbl">{{ __('app.secondary_color') }}</label>
                            <input type="color" name="theme_secondary_color" value="{{ $site->theme_secondary_color ?? '#16a34a' }}" class="input">
                        </div>
                        <div class="field">
                            <label class="lbl">{{ __('app.background_color') }}</label>
                            <input type="color" name="theme_background_color" value="{{ $site->theme_background_color ?? '#f6f7fb' }}" class="input">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn primary" data-card-save>{{ __('app.save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- المدير التنفيذي -->
        <div class="settings-card" data-settings-card data-open="0" tabindex="0" aria-expanded="false" role="button">
            <div class="card-head">
                <div class="card-icon">
                    <svg class="icon"><use href="#i-admin" xlink:href="#i-admin"></use></svg>
                </div>
                <h2 class="card-title">{{ __('app.ceo_section') }}</h2>
                <button type="button" class="card-toggle" data-card-toggle aria-expanded="false" aria-label="{{ __('app.open_card') }}">
                    <svg class="icon" data-toggle-icon><use href="#i-chevron-down" xlink:href="#i-chevron-down"></use></svg>
                </button>
            </div>
            <div class="card-body">
                <form class="card-form" data-card-form action="{{ route('admin.settings.updateAll') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-grid">
                        <div class="field">
                            <label class="lbl">{{ __('app.ceo_name_ar') }}</label>
                            <input type="text" name="ceo_name_ar" value="{{ $site->ceo_name_ar }}" class="input">
                        </div>
                        <div class="field">
                            <label class="lbl">{{ __('app.ceo_name_en') }}</label>
                            <input type="text" name="ceo_name_en" value="{{ $site->ceo_name_en }}" class="input">
                        </div>
                        <div class="field col-span-2">
                            <label class="lbl">{{ __('app.ceo_image') }}</label>
                            <input type="file" name="ceo_image" accept="image/*" class="input">
                            @if($site->ceo_image_path)
                              <div style="margin-top:8px">
                                <img src="{{ asset($site->ceo_image_path) }}" alt="CEO" style="max-width:160px;border-radius:10px">
                              </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn primary" data-card-save>{{ __('app.save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- شهادات المدير التنفيذي -->
        <div class="settings-card" data-settings-card data-open="0" tabindex="0" aria-expanded="false" role="button">
            <div class="card-head">
                <div class="card-icon">
                    <svg class="icon"><use href="#i-award"></use></svg>
                </div>
                <h2 class="card-title">{{ __('app.ceo_certificates') }}</h2>
                <button type="button" class="card-toggle" data-card-toggle aria-expanded="false" aria-label="{{ __('app.open_card') }}">
                    <svg class="icon" data-toggle-icon><use href="#i-chevron-down"></use></svg>
                </button>
            </div>
            <div class="card-body">
                <form class="card-form" data-card-form action="{{ route('admin.settings.updateAll') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <p class="card-desc">@lang('يمكنك إضافة عدة شهادات مع صورة وعنوان وجهة الإصدار وتاريخ الإصدار.')</p>

                    <div id="cert-list" class="kv-list">
                        @php $certs = $site->ceoCertificates; @endphp
                        @forelse($certs as $i => $c)
                            <div class="kv-row">
                                <div class="kv-key">
                                    <span class="key-label">@lang('شهادة #'){{ $i+1 }}</span>
                                </div>
                                <div class="kv-value">
                                    <div class="form-grid">
                                        <div class="field">
                                            <label class="lbl">@lang('العنوان (عربي)')</label>
                                            <input type="text" name="cert_title_ar[]" value="{{ $c->title_ar }}" class="input">
                                        </div>
                                        <div class="field">
                                            <label class="lbl">@lang('العنوان (إنجليزي)')</label>
                                            <input type="text" name="cert_title_en[]" value="{{ $c->title_en }}" class="input">
                                        </div>
                                        <div class="field">
                                            <label class="lbl">@lang('الجهة (عربي)')</label>
                                            <input type="text" name="cert_issuer_ar[]" value="{{ $c->issuer_ar }}" class="input">
                                        </div>
                                        <div class="field">
                                            <label class="lbl">@lang('الجهة (إنجليزي)')</label>
                                            <input type="text" name="cert_issuer_en[]" value="{{ $c->issuer_en }}" class="input">
                                        </div>
                                        <div class="field">
                                            <label class="lbl">@lang('تاريخ الإصدار')</label>
                                            <input type="text" name="cert_issued_at[]" value="{{ $c->issued_at }}" class="input" placeholder="YYYY-MM | 2020-05">
                                        </div>
                                        <div class="field">
                                            <label class="lbl">@lang('ترتيب العرض')</label>
                                            <input type="number" name="cert_sort_order[]" value="{{ $c->sort_order ?? ($i+1) }}" class="input" min="1">
                                        </div>
                                        <div class="field col-span-2">
                                            <label class="lbl">@lang('صورة الشهادة')</label>
                                            <input type="file" name="cert_image[]" accept="image/*" class="input">
                                            @if($c->image_path)
                                              <div style="margin-top:8px">
                                                <img src="{{ asset($c->image_path) }}" alt="Cert" style="max-width:160px;border-radius:10px">
                                              </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            {{-- صف فارغ جاهز للإضافة --}}
                            <div class="kv-row">
                                <div class="kv-key">
                                    <span class="key-label">@lang('شهادة #1')</span>
                                </div>
                                <div class="kv-value">
                                    <div class="form-grid">
                                        <div class="field">
                                            <label class="lbl">@lang('العنوان (عربي)')</label>
                                            <input type="text" name="cert_title_ar[]" class="input">
                                        </div>
                                        <div class="field">
                                            <label class="lbl">@lang('العنوان (إنجليزي)')</label>
                                            <input type="text" name="cert_title_en[]" class="input">
                                        </div>
                                        <div class="field">
                                            <label class="lbl">@lang('الجهة (عربي)')</label>
                                            <input type="text" name="cert_issuer_ar[]" class="input">
                                        </div>
                                        <div class="field">
                                            <label class="lbl">@lang('الجهة (إنجليزي)')</label>
                                            <input type="text" name="cert_issuer_en[]" class="input">
                                        </div>
                                        <div class="field">
                                            <label class="lbl">@lang('تاريخ الإصدار')</label>
                                            <input type="text" name="cert_issued_at[]" class="input" placeholder="YYYY-MM | 2020-05">
                                        </div>
                                        <div class="field">
                                            <label class="lbl">@lang('ترتيب العرض')</label>
                                            <input type="number" name="cert_sort_order[]" class="input" min="1" value="1">
                                        </div>
                                        <div class="field col-span-2">
                                            <label class="lbl">@lang('صورة الشهادة')</label>
                                            <input type="file" name="cert_image[]" accept="image/*" class="input">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="form-actions" style="justify-content:flex-start">
                        <button type="button" class="btn btn--add" onclick="addCertRow()">
                            <svg class="ico" viewBox="0 0 24 24">
                                <path d="M12 5v14M5 12h14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            @lang('إضافة شهادة')
                        </button>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn--add" data-card-save>
                            <svg class="ico" viewBox="0 0 24 24">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" fill="none" stroke="currentColor" stroke-width="2"/>
                                <polyline points="17,21 17,13 7,13 7,21" fill="none" stroke="currentColor" stroke-width="2"/>
                                <polyline points="7,3 7,8 15,8" fill="none" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            {{ __('app.save_changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- أزيلت أزرار الحفظ العامة في الأسفل -->
</div>

<script>
function addCertRow(){
  const list = document.getElementById('cert-list');
  const idx  = list.querySelectorAll('.kv-row').length + 1;
  const tpl = `
    <div class="kv-row">
      <div class="kv-key">
        <span class="key-label">{{ __('شهادة #') }}${idx}</span>
      </div>
      <div class="kv-value">
        <div class="form-grid">
          <div class="field">
            <label class="lbl">{{ __('العنوان (عربي)') }}</label>
            <input type="text" name="cert_title_ar[]" class="input">
          </div>
          <div class="field">
            <label class="lbl">{{ __('العنوان (إنجليزي)') }}</label>
            <input type="text" name="cert_title_en[]" class="input">
          </div>
          <div class="field">
            <label class="lbl">{{ __('الجهة (عربي)') }}</label>
            <input type="text" name="cert_issuer_ar[]" class="input">
          </div>
          <div class="field">
            <label class="lbl">{{ __('الجهة (إنجليزي)') }}</label>
            <input type="text" name="cert_issuer_en[]" class="input">
          </div>
          <div class="field">
            <label class="lbl">{{ __('تاريخ الإصدار') }}</label>
            <input type="text" name="cert_issued_at[]" class="input" placeholder="YYYY-MM | 2020-05">
          </div>
          <div class="field">
            <label class="lbl">{{ __('ترتيب العرض') }}</label>
            <input type="number" name="cert_sort_order[]" class="input" min="1" value="${idx}">
          </div>
          <div class="field col-span-2">
            <label class="lbl">{{ __('صورة الشهادة') }}</label>
            <input type="file" name="cert_image[]" accept="image/*" class="input">
          </div>
        </div>
      </div>
    </div>`;
  list.insertAdjacentHTML('beforeend', tpl);
}
</script>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('[data-settings-card]').forEach(function (card) {
    const toggleBtn = card.querySelector('[data-card-toggle]');
    const form = card.querySelector('[data-card-form]');
    const iconUse = card.querySelector('[data-toggle-icon] use');

    function setIcon(id) {
      if (!iconUse) return;
      iconUse.setAttribute('href', id);
      iconUse.setAttribute('xlink:href', id);
    }
    function setOpen(open) {
      card.dataset.open = open ? '1' : '0';
      card.setAttribute('aria-expanded', open ? 'true' : 'false');
      if (toggleBtn) toggleBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
      setIcon(open ? '#i-x' : '#i-chevron-down');
    }

    function isEditingTarget(el) {
      return !!(el.closest('.card-body') ||
                el.closest('.card-form') ||
                el.closest('button') ||
                el.closest('input, select, textarea'));
    }

    // علّم النموذج بأنه "متسخ" عند التغيير
    if (form) {
      form.querySelectorAll('input, select, textarea').forEach(function (ctrl) {
        ctrl.addEventListener('change', () => { form.dataset.dirty = '1'; });
        ctrl.addEventListener('input',  () => { form.dataset.dirty = '1'; });
      });
    }

    card.addEventListener('click', function (e) {
      if (isEditingTarget(e.target)) return; // لا تغلق أثناء التحرير
      const willOpen = card.dataset.open !== '1';
      // إذا سنغلق وبداخله تغييرات غير محفوظة، اطلب التأكيد
      if (!willOpen && form && form.dataset.dirty === '1') {
        const confirmSave = confirm('لديك تغييرات غير محفوظة. هل تريد حفظها الآن؟');
        if (confirmSave) {
          form.requestSubmit();
          return;
        } else {
          form.reset();
          form.dataset.dirty = '0';
        }
      }
      setOpen(willOpen);
    });

    // زر التبديل ما زال يعمل ويمنع انتشار الحدث
    if (toggleBtn) {
      toggleBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        const willOpen = card.dataset.open !== '1';
        if (!willOpen && form && form.dataset.dirty === '1') {
          const confirmSave = confirm('لديك تغييرات غير محفوظة. هل تريد حفظها الآن؟');
          if (confirmSave) { form.requestSubmit(); return; }
          else { form.reset(); form.dataset.dirty = '0'; }
        }
        setOpen(willOpen);
      });
    }

    // فتح باستخدام Enter/Space عند التركيز على البطاقة
    card.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        card.click();
      }
    });
  });
});
</script>
@endpush
