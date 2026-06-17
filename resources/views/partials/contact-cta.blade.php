{{-- Bize Ulaşın hızlı iletişim kartları — Telefon / WhatsApp / E-posta (Site Ayarları'ndan) --}}
@if (setting('phone') || setting('whatsapp') || setting('email'))
  <div class="contact-cta">
    @if (setting('phone'))
      <a class="cc-card" href="tel:{{ preg_replace('/[^0-9+]/', '', setting('phone')) }}">
        <span class="cc-ic">✆</span>
        <span class="cc-t">
          <b>@lang('site.call_us')</b>
          <small>{{ setting('phone') }}</small>
        </span>
      </a>
    @endif

    @if (setting('whatsapp'))
      <a class="cc-card wa" href="https://wa.me/{{ preg_replace('/\D/', '', setting('whatsapp')) }}?text={{ rawurlencode(__('site.wa_text')) }}" target="_blank" rel="noopener">
        <span class="cc-ic">
          <svg viewBox="0 0 32 32" width="22" height="22" fill="currentColor" aria-hidden="true"><path d="M16.04 3.2C8.94 3.2 3.18 8.96 3.18 16.06c0 2.27.6 4.48 1.73 6.43L3.1 28.8l6.5-1.7a12.8 12.8 0 0 0 6.43 1.64c7.1 0 12.86-5.76 12.86-12.86S23.14 3.2 16.04 3.2zm0 23.5c-1.98 0-3.92-.53-5.6-1.53l-.4-.24-3.86 1.01 1.03-3.76-.26-.39a10.6 10.6 0 0 1-1.63-5.66c0-5.9 4.8-10.7 10.72-10.7 5.9 0 10.7 4.8 10.7 10.7s-4.8 10.7-10.7 10.7zm5.88-8.02c-.32-.16-1.9-.94-2.2-1.05-.3-.11-.51-.16-.73.16-.21.32-.83 1.05-1.02 1.26-.19.21-.37.24-.69.08-.32-.16-1.36-.5-2.59-1.6-.96-.85-1.6-1.9-1.79-2.22-.19-.32-.02-.49.14-.65.14-.14.32-.37.48-.56.16-.19.21-.32.32-.53.11-.21.05-.4-.03-.56-.08-.16-.73-1.76-1-2.4-.26-.62-.53-.54-.73-.55l-.62-.01c-.21 0-.56.08-.85.4s-1.12 1.1-1.12 2.66c0 1.57 1.14 3.08 1.3 3.3.16.21 2.24 3.42 5.43 4.8.76.33 1.35.52 1.81.67.76.24 1.45.21 2 .13.61-.09 1.9-.78 2.17-1.53.27-.75.27-1.4.19-1.53-.08-.13-.29-.21-.61-.37z"/></svg>
        </span>
        <span class="cc-t">
          <b>@lang('site.wa_line')</b>
          <small>{{ setting('whatsapp') }}</small>
        </span>
      </a>
    @endif

    @if (setting('email'))
      <a class="cc-card" href="mailto:{{ setting('email') }}">
        <span class="cc-ic">✉</span>
        <span class="cc-t">
          <b>@lang('site.email_us')</b>
          <small>{{ setting('email') }}</small>
        </span>
      </a>
    @endif
  </div>
@endif
