@if ($errors->any())
  <div class="alert error">
    <div class="emoji">⚠</div>
    <div class="body">
      <strong>{{ __('حدثت أخطاء في التحقق') }}</strong>
      <ul>
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  </div>
@endif

@foreach (['ok' => '✅', 'error' => '⛔', 'warn' => '⚠', 'info' => 'ℹ'] as $key => $emoji)
  @if (session($key))
    <div class="alert {{ $key }}">
      <div class="emoji">{{ $emoji }}</div>
      <div class="body">{{ session($key) }}</div>
    </div>
  @endif
@endforeach

@if(session('ok') || session('err') || $errors->any())
  <div class="flash-wrap">
    @if(session('ok'))
      <div class="flash ok">✅ {{ session('ok') }}</div>
    @endif
    @if(session('err'))
      <div class="flash err">❌ {{ session('err') }}</div>
    @endif>
    @if($errors->any())
      <div class="flash err">
        <div>⚠ {{ __('هناك أخطاء في الإدخال:') }}</div>
        <ul>
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif
  </div>
@endif
{{-- نهاية فلاش الأخطاء --}}

{{-- فلاش نجاح إن وجد --}}