{{-- resources/views/partials/icons-sprite.blade.php --}}
<svg xmlns="http://www.w3.org/2000/svg" style="display:none">

  <!-- ====== أساسية ====== -->
  <symbol id="i-menu" viewBox="0 0 24 24">
    <path d="M4 7h16M4 12h12M4 17h16"
          fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
  </symbol>

  <symbol id="i-close" viewBox="0 0 24 24">
    <path d="M6 6l12 12M18 6L6 18"
          fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
  </symbol>

  <symbol id="i-collapse" viewBox="0 0 24 24">
    <path d="M15 6L9 12l6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
  </symbol>

  <symbol id="i-globe" viewBox="0 0 24 24">
    <circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="2"/>
    <path d="M3 12h18M12 3a16 16 0 0 1 0 18M12 3a16 16 0 0 0 0 18"
          fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
  </symbol>

  <symbol id="i-edit" viewBox="0 0 24 24">
    <path d="M14.5 3.5a2.5 2.5 0 0 1 3.5 3.5L8 17l-4 1 1-4 9.5-10.5Z"
          fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
    <path d="M13 20h8" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>
  </symbol>

  <symbol id="i-logout" viewBox="0 0 24 24">
    <path d="M15 8l4 4-4 4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    <path d="M19 12H9" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    <path d="M11 4H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
  </symbol>

  <!-- ====== عناصر السايدبار ====== -->

  <!-- لوحة التحكم: مخطط + بطاقة -->
  <symbol id="i-dashboard" viewBox="0 0 24 24">
    <rect x="3" y="3" width="10" height="8" rx="2" fill="none" stroke="currentColor" stroke-width="2"/>
    <path d="M13 13h8v8h-8z" fill="none" stroke="currentColor" stroke-width="2" />
    <path d="M4.5 9.5l2.8-2.8 2 1.8 2.2-2.2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
  </symbol>

  <!-- الفئات: شبكة مرنة مع زوايا دائرية -->
  <symbol id="i-categories" viewBox="0 0 24 24">
    <rect x="3" y="3" width="8" height="8" rx="2" fill="none" stroke="currentColor" stroke-width="2"/>
    <rect x="13" y="3" width="8" height="8" rx="2" fill="none" stroke="currentColor" stroke-width="2"/>
    <rect x="3" y="13" width="8" height="8" rx="2" fill="none" stroke="currentColor" stroke-width="2"/>
    <rect x="13" y="13" width="8" height="8" rx="2" fill="none" stroke="currentColor" stroke-width="2"/>
  </symbol>

  <!-- الكتالوج: قائمة بسطور + غلاف -->
  <symbol id="i-catalog" viewBox="0 0 24 24">
    <rect x="3" y="4" width="18" height="16" rx="2" fill="none" stroke="currentColor" stroke-width="2"/>
    <path d="M7 9h10M7 13h8M7 17h5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
  </symbol>

  <!-- المشاريع: لوحة مع تبويب ووسائط -->
  <symbol id="i-projects" viewBox="0 0 24 24">
    <rect x="3" y="6" width="18" height="12" rx="2" fill="none" stroke="currentColor" stroke-width="2"/>
    <path d="M7 6v4h10V6" fill="none" stroke="currentColor" stroke-width="2"/>
    <path d="M7 14h4M13 14h4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
  </symbol>

  <!-- التقارير: عمودان + خط اتجاه -->
  <symbol id="i-reports" viewBox="0 0 24 24">
    <path d="M4 20V10m6 10V7m6 13V4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    <path d="M3 20h18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
  </symbol>

  <!-- RFQ: ورقة مع زاوية مطوية وفقاعة طلب -->
  <symbol id="i-rfq" viewBox="0 0 24 24">
    <path d="M6 3h9l4 4v14H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" fill="none" stroke="currentColor" stroke-width="2"/>
    <path d="M15 3v5h5" fill="none" stroke="currentColor" stroke-width="2"/>
    <path d="M8 13h6M8 17h8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
  </symbol>

  <!-- الإعلانات: مكبر صوت حديث -->
  <symbol id="i-ads" viewBox="0 0 24 24">
    <path d="M4 12h4l6-4v8l-6-4H4z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
    <path d="M18 10.5a3 3 0 0 1 0 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
  </symbol>

  <!-- المستخدمون: رأسين + أساس -->
  <symbol id="i-users" viewBox="0 0 24 24">
    <circle cx="9" cy="8" r="3.5" fill="none" stroke="currentColor" stroke-width="2"/>
    <circle cx="17" cy="9.5" r="3" fill="none" stroke="currentColor" stroke-width="2"/>
    <path d="M3 20v-1.5A4.5 4.5 0 0 1 7.5 14h3A4.5 4.5 0 0 1 15 18.5V20"
          fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    <path d="M14.5 20v-1a4 4 0 0 1 4-4h2.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
  </symbol>

  <!-- شاشات/عرض -->
  <symbol id="i-screens" viewBox="0 0 24 24">
    <rect x="3" y="4" width="18" height="12" rx="2" fill="none" stroke="currentColor" stroke-width="2"/>
    <path d="M8 20h8" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>
  </symbol>

  <!-- الثيم: شمس/قمر -->
  <symbol id="i-sun" viewBox="0 0 24 24">
    <circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/>
    <path d="M12 2v2M12 20v2M4 12H2M22 12h-2M5.5 5.5l1.4 1.4M17.1 17.1l1.4 1.4M5.5 18.5l1.4-1.4M17.1 6.9l1.4-1.4"
          fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
  </symbol>

  <symbol id="i-moon" viewBox="0 0 24 24">
    <path d="M21 12.5A8.5 8.5 0 1 1 11.5 3a6.7 6.7 0 0 0 9.5 9.5Z"
          fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
  </symbol>

  <!-- الإعدادات: ترس ناعم -->
  <symbol id="i-settings" viewBox="0 0 24 24">
    <circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="2"/>
    <path d="M19.4 12a7.4 7.4 0 0 0-.1-1l2-1.5-2-3.5-2.5 1a7.6 7.6 0 0 0-1.6-1l-.4-2.7H11l-.4 2.7a7.6 7.6 0 0 0-1.6 1l-2.5-1-2 3.5L6.5 11a7.4 7.4 0 0 0 0 2l-2 1.5 2 3.5 2.5-1a7.6 7.6 0 0 0 1.6 1l.4 2.7h3.8l.4-2.7a7.6 7.6 0 0 0 1.6-1l2.5 1 2-3.5-2-1.5c.07-.33.1-.67.1-1Z"
          fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
  </symbol>

  <!-- بوابة العملاء -->
  <symbol id="i-portal" viewBox="0 0 24 24">
    <path d="M6 3h8l4 4v14H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" fill="none" stroke="currentColor" stroke-width="2"/>
    <path d="M14 3v4h4" fill="none" stroke="currentColor" stroke-width="2"/>
    <path d="M9 12h6M9 16h8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
  </symbol>

  <!-- رجوع -->
  <symbol id="i-arrow-left" viewBox="0 0 24 24">
    <path d="M15 18 9 12l6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
  </symbol>

</svg>
