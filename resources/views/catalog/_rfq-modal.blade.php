{{-- resources/views/catalog/_rfq-modal.blade.php --}}

{{-- Backdrop --}}
<div id="rfqBackdrop" class="rfq-backdrop" hidden></div>

{{-- Modal --}}
<div
  id="rfqModal"
  class="rfq-modal"
  hidden
  role="dialog"
  aria-modal="true"
  data-whats="{{ trim((string) config('services.whatsapp.phone', '')) }}"
  data-lbl-ask="{{ __('app.ask_quote') }}"
  data-lbl-product="{{ __('app.product') }}"
  data-lbl-qty="{{ __('app.quantity') ?? 'Quantity' }}"
  data-lbl-name="{{ __('app.name') }}"
  data-lbl-phone="{{ __('app.phone') }}"
  data-lbl-email="{{ __('app.email') }}"
>
  <div class="sheet">
    <div class="rfq-header">
      <h3 class="rfq-title">{{ __('app.ask_quote') }}</h3>
      <button class="rfq-close" type="button" id="rfqClose" aria-label="Close">
        <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
          <path d="M18.3 5.7 12 12l6.3 6.3-1.4 1.4L10.6 13.4 4.3 19.7 2.9 18.3 9.2 12 2.9 5.7 4.3 4.3 10.6 10.6 16.9 4.3z"/>
        </svg>
      </button>
    </div>

    <div class="rfq-grid">
      <div class="rfq-summary">
        <div class="card" style="border:1px solid var(--ring);border-radius:14px;padding:12px">
          <strong style="display:block;margin-bottom:6px">{{ __('app.product') }}</strong>
          <div id="rfqProdName" style="font-weight:900"></div>
          <div id="rfqProdMeta" style="color:var(--muted);font-weight:800"></div>
        </div>
      </div>

      <form id="rfqForm" class="rfq-form" method="POST" action="{{ route('rfq.store') }}">
        @csrf
        <input type="hidden" name="product_id" id="rfqProductId">
        <div class="row">
          <div>
            <label class="lbl">{{ __('app.name') }}</label>
            <input type="text" name="name" required>
          </div>
          <div>
            <label class="lbl">{{ __('app.phone') }}</label>
            <input type="tel" name="phone" required>
          </div>
          <div>
            <label class="lbl">{{ __('app.email') }}</label>
            <input type="email" name="email">
          </div>
          <div>
            <label class="lbl">{{ __('app.quantity') ?? 'Quantity' }}</label>
            <input type="number" name="quantity" min="1" value="1">
          </div>
          <div class="full">
            <label class="lbl">{{ __('app.message') }}</label>
            <textarea name="message" rows="4" placeholder="{{ __('app.write_here') ?? 'Write details here…' }}"></textarea>
          </div>
        </div>

        <div class="rfq-actions">
          <a id="rfqWhats" class="btn btn-whats" target="_blank" rel="noopener">
            {{ __('app.send_whatsapp') ?? 'Send on WhatsApp' }}
          </a>
          <button type="submit" class="btn btn-primary">
            {{ __('app.send_email') ?? 'Send by Email' }}
          </button>
          <button type="button" class="btn btn-ghost" id="rfqCancel">{{ __('app.cancel') ?? 'Cancel' }}</button>
        </div>
      </form>
    </div>
  </div>
</div>
