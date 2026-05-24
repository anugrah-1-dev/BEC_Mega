@extends('layouts.admission')
@section('page-title', 'Admin Dashboard')

@section('extra-styles')
<style>
    .admin-hero {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 60%, #4338ca 100%);
        border-radius: 20px;
        padding: 34px 36px;
        color: white;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }
    .admin-hero::after {
        content: '';
        position: absolute;
        right: -50px; top: -70px;
        width: 320px; height: 320px;
        border-radius: 50%;
        background: rgba(255,255,255,0.10);
    }
    .admin-hero::before {
        content: '';
        position: absolute;
        right: 100px; top: 80px;
        width: 180px; height: 180px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
    }
    .admin-hero__title {
        font-size: 1.65rem;
        font-weight: 900;
        margin-bottom: 6px;
        position: relative;
        z-index: 1;
        letter-spacing: -0.01em;
    }
    .admin-hero__subtitle {
        font-size: 0.9rem;
        opacity: 0.88;
        position: relative;
        z-index: 1;
        line-height: 1.55;
        max-width: 680px;
    }
    .admin-hero__cta {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.28);
        background: rgba(255,255,255,0.12);
        color: white;
        text-decoration: none;
        font-weight: 800;
        box-shadow: 0 12px 28px rgba(0,0,0,0.12);
        transition: transform 0.2s, box-shadow 0.2s, background 0.2s;
        white-space: nowrap;
    }
    .admin-hero__cta:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 36px rgba(0,0,0,0.18);
        background: rgba(255,255,255,0.16);
    }
    .admin-hero__cta svg { width: 18px; height: 18px; }

    .stat-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 18px; margin-bottom: 26px; }

    .quick-action {
        background: white; border: 1.5px solid #e2e8f0;
        border-radius: 18px; padding: 28px;
        display: flex; align-items: center; gap: 20px;
        text-decoration: none; color: inherit;
        transition: all 0.25s;
    }
    .quick-action:hover {
        border-color: #4f46e5; transform: translateY(-3px);
        box-shadow: 0 10px 28px rgba(79,70,229,0.12);
    }
    .qa-icon {
        width: 56px; height: 56px; border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .qa-icon svg { width: 28px; height: 28px; }
    .qa-title { font-size: 1rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; }
    .qa-desc  { font-size: 0.83rem; color: #64748b; line-height: 1.45; }
    .section-title {
        font-size: 0.8rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #64748b;
        margin-bottom: 16px;
    }

    @media (max-width: 1200px) { .stat-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 900px) { .stat-grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 600px)  { .stat-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')

    {{-- Hero / Header --}}
    <div class="admin-hero">
        <div>
            <div class="admin-hero__title">Selamat Datang, Admin</div>
            <div class="admin-hero__subtitle">Ringkasan data sistem pendaftaran BEC per hari ini.</div>
        </div>
        <a href="{{ route('admin.pendaftar') }}" class="admin-hero__cta">
            <svg viewBox="0 0 24 24"><path fill="currentColor" d="M16,11C17.66,11 18.99,9.66 18.99,8C18.99,6.34 17.66,5 16,5C14.34,5 13,6.34 13,8C13,9.66 14.34,11 16,11M8,11C9.66,11 10.99,9.66 10.99,8C10.99,6.34 9.66,5 8,5C6.34,5 5,6.34 5,8C5,9.66 6.34,11 8,11M8,13C5.67,13 1,14.17 1,16.5V18H15V16.5C15,14.17 10.33,13 8,13M16,13C15.71,13 15.38,13.02 15.03,13.05C16.19,13.89 17,15.02 17,16.5V18H23V16.5C23,14.17 18.33,13 16,13Z"/></svg>
            Lihat Semua Pendaftar
        </a>
    </div>

    {{-- Stat Cards --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(79,70,229,0.1);">
                <svg style="width:26px;height:26px;color:#4f46e5" viewBox="0 0 24 24"><path fill="currentColor" d="M16,11C17.66,11 18.99,9.66 18.99,8C18.99,6.34 17.66,5 16,5C14.34,5 13,6.34 13,8C13,9.66 14.34,11 16,11M8,11C9.66,11 10.99,9.66 10.99,8C10.99,6.34 9.66,5 8,5C6.34,5 5,6.34 5,8C5,9.66 6.34,11 8,11M8,13C5.67,13 1,14.17 1,16.5V18H15V16.5C15,14.17 10.33,13 8,13M16,13C15.71,13 15.38,13.02 15.03,13.05C16.19,13.89 17,15.02 17,16.5V18H23V16.5C23,14.17 18.33,13 16,13Z"/></svg>
            </div>
            <div>
                <div class="stat-value" style="color:#4f46e5;">{{ $stats['total_applicants'] }}</div>
                <div class="stat-label">Total Pendaftar</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(245,158,11,0.1);">
                <svg style="width:26px;height:26px;color:#f59e0b" viewBox="0 0 24 24"><path fill="currentColor" d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4M11,17H13V11H11V17M11,9H13V7H11V9Z"/></svg>
            </div>
            <div>
                <div class="stat-value" style="color:#f59e0b;">{{ $stats['pending_verifications'] }}</div>
                <div class="stat-label">Menunggu Verifikasi</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(239,68,68,0.1);">
                <svg style="width:26px;height:26px;color:#ef4444" viewBox="0 0 24 24"><path fill="currentColor" d="M20,8H4V6H20M20,18H4V12H20M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z"/></svg>
            </div>
            <div>
                <div class="stat-value" style="color:#ef4444;">{{ $stats['pending_payments'] }}</div>
                <div class="stat-label">Menunggu Validasi</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(16,185,129,0.1);">
                <svg style="width:26px;height:26px;color:#10b981" viewBox="0 0 24 24"><path fill="currentColor" d="M12,2C6.48,2 2,6.48 2,12C2,17.52 6.48,22 12,22C17.52,22 22,17.52 22,12C22,6.48 17.52,2 12,2M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,7C10.34,7 9,8.34 9,10C9,11.66 10.34,13 12,13C13.66,13 15,11.66 15,10C15,8.34 13.66,7 12,7M12,14C10,14 6,15 6,17V18H18V17C18,15 14,14 12,14Z"/></svg>
            </div>
            <div>
                <div class="stat-value" style="color:#10b981;">{{ $stats['total_students'] }}</div>
                <div class="stat-label">Total Siswa</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(59,130,246,0.1);">
                <svg style="width:26px;height:26px;color:#3b82f6" viewBox="0 0 24 24"><path fill="currentColor" d="M12,2C6.48,2 2,6.48 2,12C2,17.52 6.48,22 12,22C17.52,22 22,17.52 22,12C22,6.48 17.52,2 12,2M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M15,13V15H13V13H15M11,13V15H9V13H11M15,11V12H9V11H15M15,9V10H9V9H15Z"/></svg>
            </div>
            <div>
                <div class="stat-value" style="color:#3b82f6;">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</div>
                <div class="stat-label">Total Pemasukan</div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card">
        <div class="section-title">Aksi Cepat Admin POS</div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit,minmax(280px,1fr)); gap: 20px;">
            <a href="{{ route('admin.pos') }}" class="quick-action">
                <div class="qa-icon" style="background:rgba(79,70,229,0.1);">
                    <svg style="color:#4f46e5" viewBox="0 0 24 24"><path fill="currentColor" d="M11,9H13V6H16V4H13V1H11V4H8V6H11V9M7,18A2,2 0 0,0 5,20A2,2 0 0,0 7,22A2,2 0 0,0 9,20A2,2 0 0,0 7,18M17,18A2,2 0 0,0 15,20A2,2 0 0,0 17,22A2,2 0 0,0 19,20A2,2 0 0,0 17,18M7.17,14.75L7.2,14.63L8.1,13H15.55C16.3,13 16.96,12.59 17.3,11.97L21.16,4.96L19.42,4H19.41L18.31,6L15.55,11H8.53L8.4,10.73L6.16,6L5.21,4L4.27,2H1V4H3L6.6,11.59L5.25,14.04C5.09,14.32 5,14.65 5,15A2,2 0 0,0 7,17H19V15H7.42C7.29,15 7.17,14.89 7.17,14.75Z"/></svg>
                </div>
                <div>
                    <div class="qa-title">POS Pendaftaran Cepat</div>
                    <div class="qa-desc">Daftarkan siswa baru langsung dari meja admin secara offline.</div>
                </div>
            </a>
            <a href="{{ route('admin.pendaftar') }}" class="quick-action">
                <div class="qa-icon" style="background:rgba(245,158,11,0.1);">
                    <svg style="color:#f59e0b" viewBox="0 0 24 24"><path fill="currentColor" d="M12,15V17H2V15H12M12,11V13H2V11H12M12,7V9H2V7H12M16,5V21H14V5H16M22,5V21H20V5H22M18,5V21H18V5H18Z"/></svg>
                </div>
                <div>
                    <div class="qa-title">Verifikasi Transaksi</div>
                    <div class="qa-desc">Cek pembayaran masuk dan verifikasi status pendaftaran.</div>
                </div>
            </a>
            <a href="{{ route('admin.laporan') }}" class="quick-action">
                <div class="qa-icon" style="background:rgba(16,185,129,0.1);">
                    <svg style="color:#10b981" viewBox="0 0 24 24"><path fill="currentColor" d="M13,9V3.5L18.5,9M6,2C4.89,2 4,2.89 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2H6Z"/></svg>
                </div>
                <div>
                    <div class="qa-title">Laporan Keuangan</div>
                    <div class="qa-desc">Lihat rekapitulasi pemasukan harian dan bulanan secara otomatis.</div>
                </div>
            </a>
            <a href="{{ route('admin.siswa') }}" class="quick-action">
                <div class="qa-icon" style="background:rgba(59,130,246,0.1);">
                    <svg style="color:#3b82f6" viewBox="0 0 24 24"><path fill="currentColor" d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/></svg>
                </div>
                <div>
                    <div class="qa-title">Manajemen Siswa</div>
                    <div class="qa-desc">Kelola data seluruh siswa yang telah terdaftar di sistem.</div>
                </div>
            </a>
        </div>
    </div>

@endsection
