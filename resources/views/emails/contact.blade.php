<!DOCTYPE html>
<html lang="tr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"></head>
<body style="margin:0;background:#0e0d0a;font-family:Arial,Helvetica,sans-serif;color:#e6e0d2">
  <div style="max-width:600px;margin:0 auto;padding:28px">
    <div style="text-align:center;padding:18px 0;border-bottom:1px solid rgba(200,164,77,.3)">
      <div style="font-size:22px;letter-spacing:6px;color:#e9d6a0;font-weight:bold">DEMİRBEY</div>
      <div style="font-size:10px;letter-spacing:4px;color:#c8a44d">HOLDING LUXURY AUTOMOTIVE</div>
    </div>

    <h2 style="color:#e9d6a0;font-weight:normal;font-size:18px;margin:26px 0 16px">Yeni İletişim Mesajı</h2>

    <table style="width:100%;border-collapse:collapse;font-size:14px">
      <tr><td style="padding:9px 0;color:#8d8470;width:120px">Ad Soyad</td><td style="padding:9px 0;color:#fff">{{ $d['name'] ?? '—' }}</td></tr>
      <tr><td style="padding:9px 0;color:#8d8470">E-posta</td><td style="padding:9px 0"><a href="mailto:{{ $d['email'] ?? '' }}" style="color:#c8a44d">{{ $d['email'] ?? '—' }}</a></td></tr>
      @if (!empty($d['phone']))
        <tr><td style="padding:9px 0;color:#8d8470">Telefon</td><td style="padding:9px 0;color:#fff">{{ $d['phone'] }}</td></tr>
      @endif
      @if (!empty($d['vehicle']))
        <tr><td style="padding:9px 0;color:#8d8470">İlgilenilen Araç</td><td style="padding:9px 0;color:#fff">{{ $d['vehicle'] }}</td></tr>
      @endif
    </table>

    <div style="margin-top:18px;padding:16px;background:rgba(255,255,255,.04);border:1px solid rgba(200,164,77,.2);border-radius:6px">
      <div style="color:#8d8470;font-size:12px;margin-bottom:8px">MESAJ</div>
      <div style="color:#e6e0d2;line-height:1.7;white-space:pre-wrap">{{ $d['message'] ?? '' }}</div>
    </div>

    <p style="color:#6f6857;font-size:11px;margin-top:24px;text-align:center">
      Bu mesaj {{ config('app.name') }} web sitesi iletişim formundan gönderilmiştir.
    </p>
  </div>
</body>
</html>
