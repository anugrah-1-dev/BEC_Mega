<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $registration->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #1e293b;
            background: #ffffff;
            padding: 40px;
        }

        /* ===== HEADER ===== */
        .header {
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 20px;
            margin-bottom: 24px;
            overflow: hidden;
        }
        .header-left { float: left; }
        .header-left .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #4f46e5;
        }
        .header-left .company-sub {
            font-size: 11px;
            color: #64748b;
            margin-top: 4px;
        }
        .header-right { float: right; text-align: right; }
        .header-right .invoice-label {
            font-size: 32px;
            font-weight: bold;
            color: #e2e8f0;
            line-height: 1;
        }
        .header-right .invoice-number {
            font-size: 15px;
            font-weight: bold;
            color: #4f46e5;
            margin-top: 4px;
        }
        .header-right .invoice-date {
            font-size: 11px;
            color: #64748b;
            margin-top: 3px;
        }
        .clearfix { clear: both; }

        /* ===== INFO GRID ===== */
        .info-section {
            margin-bottom: 24px;
            overflow: hidden;
        }
        .info-box {
            float: left;
            width: 48%;
        }
        .info-box:last-child { float: right; }
        .info-label {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .info-value-name {
            font-size: 15px;
            font-weight: bold;
            color: #1e293b;
        }
        .info-value {
            font-size: 11px;
            color: #64748b;
            margin-top: 3px;
        }
        .payment-badge {
            display: inline-block;
            background: #f1f5f9;
            padding: 4px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 11px;
            color: #475569;
        }
        .status-paid {
            display: inline-block;
            font-size: 11px;
            font-weight: bold;
            color: #059669;
            background: #ecfdf5;
            border: 1.5px solid #a7f3d0;
            padding: 3px 7px;
            border-radius: 4px;
        }
        .status-pending {
            display: inline-block;
            font-size: 11px;
            font-weight: bold;
            color: #d97706;
            background: #fffbeb;
            border: 1.5px solid #fde68a;
            padding: 3px 7px;
            border-radius: 4px;
        }

        /* ===== TABLE ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        thead th {
            background: #f8fafc;
            padding: 12px 16px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
        }
        thead th.text-right { text-align: right; }
        tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 12px;
        }
        tbody td.text-right { text-align: right; }
        tbody td .item-name { font-weight: bold; color: #1e293b; }
        tbody td .item-sub { font-size: 10px; color: #94a3b8; margin-top: 3px; }
        tfoot td {
            padding: 14px 16px;
            font-weight: bold;
        }
        tfoot td.text-right { text-align: right; }
        .total-amount {
            font-size: 18px;
            font-weight: bold;
            color: #4f46e5;
        }

        /* ===== FOOTER ===== */
        .footer-section {
            overflow: hidden;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }
        .notes { float: left; width: 55%; }
        .notes h4 { font-size: 12px; font-weight: bold; margin-bottom: 8px; }
        .notes ul { padding-left: 16px; }
        .notes li { font-size: 10px; color: #64748b; margin-bottom: 4px; }
        .signature { float: right; width: 180px; text-align: center; }
        .signature .city-date { font-size: 11px; color: #64748b; margin-bottom: 40px; }
        .signature .sign-line {
            border-top: 1px solid #1e293b;
            padding-top: 8px;
            font-size: 12px;
            font-weight: bold;
        }

        /* ===== STAMP ===== */
        .stamp-container {
            position: relative;
            height: 0;
            text-align: center;
        }
        .stamp {
            display: inline-block;
            padding: 6px 14px;
            font-weight: bold;
            font-size: 14px;
            transform: rotate(-15deg);
            margin-top: -35px;
            margin-bottom: 35px;
        }
        .stamp-paid-style {
            border: 3px solid #10b981;
            color: #10b981;
        }
        .stamp-pending-style {
            border: 3px solid #d97706;
            color: #d97706;
        }

        /* ===== WATERMARK ===== */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 80px;
            font-weight: bold;
            opacity: 0.04;
            color: #4f46e5;
            white-space: nowrap;
            z-index: -1;
        }
    </style>
</head>
<body>

<div class="watermark">BEC</div>

<!-- HEADER -->
<div class="header">
    <div class="header-left">
        <div class="company-name">Brilliant English Course</div>
        <div class="company-sub">Kampung Inggris Pare, Kediri, Jawa Timur</div>
        <div class="company-sub">WA: 0812-3456-7890 | brilliantenglishcourse.com</div>
    </div>
    <div class="header-right">
        <div class="invoice-label">INVOICE</div>
        <div class="invoice-number">{{ $registration->invoice_number }}</div>
        <div class="invoice-date">Tanggal: {{ $registration->created_at->format('d M Y') }}</div>
    </div>
    <div class="clearfix"></div>
</div>

<!-- INFO -->
<div class="info-section">
    <div class="info-box">
        <div class="info-label">Tagihan Untuk:</div>
        <div class="info-value-name">{{ $registration->user->name }}</div>
        <div class="info-value">{{ $registration->user->email }}</div>
        <div class="info-value">{{ $registration->user->studentDetail->phone ?? '-' }}</div>
    </div>
    <div class="info-box">
        <div class="info-label">Metode Pembayaran:</div>
        <div style="margin-top: 4px;">
            <span class="payment-badge">{{ $registration->payment_method ?? 'Transfer Bank' }}</span>
        </div>
        <div class="info-value" style="margin-top: 8px;">
            Status:
            @if($registration->payment_status === 'paid')
                <span class="status-paid">LUNAS</span>
            @else
                <span class="status-pending">PENDING</span>
            @endif
        </div>
    </div>
    <div class="clearfix"></div>
</div>

<!-- TABLE -->
<table>
    <thead>
        <tr>
            <th>Deskripsi Program</th>
            <th class="text-right">Harga</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <div class="item-name">{{ $registration->course->name }}</div>
                <div class="item-sub">Periode: {{ $registration->period->name }}</div>
            </td>
            <td class="text-right">Rp {{ number_format($registration->course->price, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>
                <div class="item-name">Biaya Pendaftaran / Admin</div>
                <div class="item-sub">Registrasi awal siswa</div>
            </td>
            <td class="text-right">
                @if($registration->course->admin_tax > 0)
                    Rp {{ number_format($registration->course->admin_tax, 0, ',', '.') }}
                @else
                    Gratis
                @endif
            </td>
        </tr>
        @if($registration->transport)
        <tr>
            <td>
                <div class="item-name">Layanan Penjemputan</div>
                <div class="item-sub">{{ $registration->transport->name }}</div>
            </td>
            <td class="text-right">Rp {{ number_format($registration->transport->price, 0, ',', '.') }}</td>
        </tr>
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td class="text-right"><strong>TOTAL PEMBAYARAN</strong></td>
            <td class="text-right total-amount">
                Rp {{ number_format(
                    ($registration->course->price ?? 0)
                    + ($registration->course->admin_tax ?? 0)
                    + ($registration->transport->price ?? 0),
                    0, ',', '.'
                ) }}
            </td>
        </tr>
    </tfoot>
</table>

<!-- FOOTER -->
<div class="footer-section">
    <div class="notes">
        <h4>Catatan:</h4>
        <ul>
            <li>Simpan invoice ini sebagai bukti pendaftaran resmi.</li>
            <li>Silakan lakukan pembayaran sesuai metode yang dipilih.</li>
            <li>Konfirmasi pembayaran akan diproses dalam 1x24 jam.</li>
        </ul>
    </div>
    <div class="signature">
        <div class="city-date">Kediri, {{ date('d M Y') }}</div>
        <div class="stamp-container">
            <div class="stamp {{ $registration->payment_status === 'paid' ? 'stamp-paid-style' : 'stamp-pending-style' }}">
                {{ $registration->payment_status === 'paid' ? 'PAID / LUNAS' : 'PENDING' }}
            </div>
        </div>
        <div class="sign-line">Administrasi BEC</div>
    </div>
    <div class="clearfix"></div>
</div>

</body>
</html>
