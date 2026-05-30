@extends('layouts.admission')
@section('page-title', 'Laporan Keuangan')

@section('extra-styles')
<style>
    .report-header {
        background: white;
        padding: 24px;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    .period-selector {
        display: flex;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 12px;
    }
    .period-btn {
        padding: 8px 16px;
        border-radius: 10px;
        text-decoration: none;
        color: #64748b;
        font-size: 0.875rem;
        font-weight: 700;
        transition: all 0.2s;
    }
    .period-btn.active {
        background: white;
        color: var(--primary);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .summary-box {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 24px;
        border-radius: 20px;
        margin-bottom: 24px;
    }
    .summary-label { font-size: 0.875rem; opacity: 0.9; font-weight: 600; margin-bottom: 4px; }
    .summary-value { font-size: 2rem; font-weight: 900; }
</style>
@endsection

@section('content')
    <div class="report-header">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 900; color: #0f172a;">📊 Laporan Keuangan</h1>
            <p style="color: #64748b; font-size: 0.875rem;">Rekapitulasi pemasukan pendaftaran otomatis.</p>
        </div>
        <div class="period-selector">
            <a href="{{ route('admin.laporan', ['period' => 'daily']) }}" class="period-btn {{ $period == 'daily' ? 'active' : '' }}">Harian</a>
            <a href="{{ route('admin.laporan', ['period' => 'monthly']) }}" class="period-btn {{ $period == 'monthly' ? 'active' : '' }}">Bulanan</a>
        </div>
    </div>

    <div class="summary-box">
        <div class="summary-label">Total Pemasukan ({{ $period == 'daily' ? 'Hari Ini' : 'Bulan Ini' }})</div>
        <div class="summary-value">Rp {{ number_format($total_income, 0, ',', '.') }}</div>
    </div>

    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1rem; font-weight: 800;">Rincian Transaksi Selesai</h3>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('admin.laporan.export_excel', ['period' => $period]) }}"
                   style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:#059669;color:white;border-radius:10px;font-size:0.875rem;font-weight:700;text-decoration:none;">
                    <svg style="width:16px;height:16px" viewBox="0 0 24 24"><path fill="currentColor" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20M12,19L8,15H10.5V12H13.5V15H16L12,19Z"/></svg>
                    Download Excel
                </a>
                <button class="btn btn-outline btn-sm" onclick="window.print()">
                    <svg style="width:16px;height:16px" viewBox="0 0 24 24"><path fill="currentColor" d="M18,3H6V7H18M19,12A1,1 0 0,1 18,11A1,1 0 0,1 19,10A1,1 0 0,1 20,11A1,1 0 0,1 19,12M16,19H8V14H16M19,8H5A3,3 0 0,0 2,11V17H6V21H18V17H22V11A3,3 0 0,0 19,8Z"/></svg>
                    Cetak Laporan
                </button>
            </div>
        </div>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal Lunas</th>
                        <th>Siswa</th>
                        <th>Program & Transport</th>
                        <th>Metode</th>
                        <th style="text-align: right;">Total Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reportData as $data)
                    @php
                        $total = ($data->course->price ?? 0) + ($data->transport->price ?? 0);
                    @endphp
                    <tr>
                        <td style="font-weight: 600; color: #64748b;">{{ $data->updated_at->format('d M Y, H:i') }}</td>
                        <td>
                            <div style="font-weight: 700;">{{ $data->user->name }}</div>
                            <div style="font-size: 0.75rem; color: #94a3b8;">{{ $data->user->email }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $data->course->name ?? 'N/A' }}</div>
                            <div style="font-size: 0.75rem; color: #94a3b8;">Transport: {{ $data->transport->name ?? 'Tidak ada' }}</div>
                        </td>
                        <td>
                            <span class="badge badge-primary">{{ strtoupper($data->payment_method ?? 'Transfer') }}</span>
                        </td>
                        <td style="text-align: right; font-weight: 800; color: #059669;">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 60px; color: #94a3b8;">Tidak ada data transaksi lunas untuk periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
