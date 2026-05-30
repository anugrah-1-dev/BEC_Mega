@extends('layouts.admission')

@section('title', 'Invoice - ' . $registration->invoice_number)

@section('content')
<div class="invoice-container">
    <div class="invoice-actions no-print">
        <a href="{{ route('dashboard') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        <div style="display:flex;gap:10px;">
            <a href="{{ route('pos.invoice.pdf', $registration->id) }}"
               class="btn-download-pdf">
                <i class="fas fa-file-pdf"></i> Download PDF
            </a>
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> Cetak Invoice
            </button>
        </div>
    </div>

    <div class="invoice-card" id="printable-invoice">
        <div class="invoice-header">
            <div class="header-left">
                <img src="{{ asset('assets/logo_BEC.png') }}" alt="Logo BEC">
                <div class="bec-info">
                    <h1>Brilliant English Course</h1>
                    <p>Kampung Inggris Pare, Kediri, Jawa Timur</p>
                    <p>WA: 0812-3456-7890 | Website: brilliantenglishcourse.com</p>
                </div>
            </div>
            <div class="header-right">
                <div class="invoice-label">INVOICE</div>
                <div class="invoice-id">{{ $registration->invoice_number }}</div>
                <div class="invoice-date">Tanggal: {{ $registration->created_at->format('d M Y') }}</div>
            </div>
        </div>

        <div class="invoice-body">
            <div class="info-grid">
                <div class="info-box">
                    <h3>Tagihan Untuk:</h3>
                    <strong>{{ $registration->user->name }}</strong>
                    <p>{{ $registration->user->email }}</p>
                    <p>{{ $registration->user->studentDetail->phone ?? '-' }}</p>
                </div>
                <div class="info-box">
                    <h3>Metode Pembayaran:</h3>
                    <div class="payment-badge">{{ $registration->payment_method ?? 'Transfer Bank' }}</div>
                    <p style="margin-top: 10px;">Status: 
                        <span class="status-badge {{ $registration->payment_status === 'paid' ? 'paid' : 'pending' }}">
                            {{ $registration->payment_status === 'paid' ? 'LUNAS' : 'PENDING' }}
                        </span>
                    </p>
                </div>
            </div>

            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Deskripsi Program</th>
                        <th class="text-right">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>{{ $registration->course->name }}</strong><br>
                            <small>Periode: {{ $registration->period->name }}</small>
                        </td>
                        <td class="text-right">Rp {{ number_format($registration->course->price, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Biaya Pendaftaran / Admin</strong><br>
                            <small>Registrasi awal siswa</small>
                        </td>
                        <td class="text-right">
                            @if($registration->course->admin_tax > 0)
                                Rp {{ number_format($registration->course->admin_tax, 0, ',', '.') }}
                            @else
                                <span style="color:#10b981; font-weight:700">Gratis</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Layanan Penjemputan</strong><br>
                            <small>{{ $registration->transport->name }}</small>
                        </td>
                        <td class="text-right">Rp {{ number_format($registration->transport->price, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-right"><strong>TOTAL PEMBAYARAN</strong></td>
                        <td class="text-right total-amount">Rp {{ number_format($registration->course->price + $registration->course->admin_tax + $registration->transport->price, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="invoice-footer">
                <div class="notes">
                    <h4>Catatan:</h4>
                    <ul>
                        <li>Simpan invoice ini sebagai bukti pendaftaran resmi.</li>
                        <li>Silakan lakukan pembayaran sesuai metode yang dipilih.</li>
                        <li>Konfirmasi pembayaran akan diproses dalam 1x24 jam.</li>
                    </ul>
                </div>
                <div class="signature">
                    <p>Kediri, {{ date('d M Y') }}</p>
                    <div class="stamp">
                        <img src="{{ asset('assets/logo_BEC.png') }}" alt="Stamp" style="opacity: 0.1; width: 100px;">
                        <span class="{{ $registration->payment_status === 'paid' ? 'stamp-paid' : 'stamp-pending' }}">
                            {{ $registration->payment_status === 'paid' ? 'PAID / LUNAS' : 'PENDING' }}
                        </span>
                    </div>
                    <strong>Administrasi BEC</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .invoice-container { max-width: 900px; margin: 40px auto; padding: 0 20px; }
    .invoice-actions { display: flex; justify-content: space-between; margin-bottom: 25px; }
    
    .btn-back { text-decoration: none; color: #64748b; font-weight: 600; display: flex; align-items: center; gap: 8px; }
    .btn-print { background: #4f46e5; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; }
    .btn-download-pdf { background: #dc2626; color: white; text-decoration: none; padding: 10px 20px; border-radius: 10px; font-weight: 700; display: flex; align-items: center; gap: 8px; }

    .invoice-card { background: white; border-radius: 0; box-shadow: 0 10px 25px rgba(0,0,0,0.05); padding: 60px; border: 1px solid #f1f5f9; position: relative; overflow: hidden; }
    
    .invoice-header { display: flex; justify-content: space-between; border-bottom: 2px solid #f1f5f9; padding-bottom: 40px; margin-bottom: 40px; }
    .header-left { display: flex; align-items: center; gap: 25px; }
    .header-left img { height: 80px; }
    .bec-info h1 { font-size: 22px; font-weight: 800; color: #1e293b; margin: 0 0 5px 0; }
    .bec-info p { margin: 0; font-size: 13px; color: #64748b; }

    .header-right { text-align: right; }
    .invoice-label { font-size: 42px; font-weight: 900; color: #e2e8f0; line-height: 1; margin-bottom: 10px; }
    .invoice-id { font-size: 18px; font-weight: 800; color: #4f46e5; }
    .invoice-date { font-size: 13px; color: #64748b; margin-top: 5px; }

    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 50px; }
    .info-box h3 { font-size: 12px; font-weight: 800; text-transform: uppercase; color: #94a3b8; letter-spacing: 1px; margin-bottom: 15px; }
    .info-box strong { font-size: 18px; color: #1e293b; display: block; margin-bottom: 5px; }
    .info-box p { margin: 0; font-size: 14px; color: #64748b; line-height: 1.5; }

    .payment-badge { background: #f1f5f9; padding: 6px 12px; border-radius: 6px; display: inline-block; font-weight: 700; font-size: 13px; color: #475569; }
    .status-badge { font-weight: 800; font-size: 12px; padding: 4px 8px; border-radius: 4px; text-transform: uppercase; }
    .status-badge.pending { color: #d97706; background: #fffbeb; border: 1.5px solid #fde68a; }
    .status-badge.paid { color: #059669; background: #ecfdf5; border: 1.5px solid #a7f3d0; }

    .invoice-table { width: 100%; border-collapse: collapse; margin-bottom: 50px; }
    .invoice-table th { text-align: left; background: #f8fafc; padding: 15px 20px; font-size: 12px; font-weight: 800; text-transform: uppercase; color: #64748b; }
    .invoice-table td { padding: 20px; border-bottom: 1px solid #f1f5f9; font-size: 15px; }
    .text-right { text-align: right; }
    
    .total-amount { font-size: 24px; font-weight: 900; color: #4f46e5; }

    .invoice-footer { display: flex; justify-content: space-between; align-items: flex-end; }
    .notes h4 { font-size: 14px; font-weight: 800; margin: 0 0 10px 0; }
    .notes ul { padding-left: 18px; margin: 0; }
    .notes li { font-size: 12px; color: #64748b; margin-bottom: 5px; }

    .signature { text-align: center; width: 200px; }
    .signature p { font-size: 13px; color: #64748b; margin-bottom: 40px; }
    .signature strong { display: block; border-top: 1px solid #1e293b; padding-top: 10px; font-size: 14px; }
    
    .stamp { position: relative; height: 0; }
    .stamp span { position: absolute; top: -30px; left: 50%; transform: translateX(-50%) rotate(-15deg); padding: 5px 10px; font-weight: 900; font-size: 14px; opacity: 0.45; }
    .stamp-paid { border: 3px solid #10b981; color: #10b981; }
    .stamp-pending { border: 3px solid #d97706; color: #d97706; }

    @media print {
        body { background: white; }
        .no-print { display: none; }
        .invoice-container { margin: 0; max-width: 100%; }
        .invoice-card { box-shadow: none; border: none; padding: 0; }
    }
</style>
@endsection
