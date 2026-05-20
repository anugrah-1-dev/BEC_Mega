@extends('layouts.admission')
@section('page-title', 'Dashboard Pendaftar')

@section('extra-styles')
<style>
    .dash-hero {
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
    .dash-hero::after {
        content: '';
        position: absolute;
        right: -50px; top: -70px;
        width: 320px; height: 320px;
        border-radius: 50%;
        background: rgba(255,255,255,0.10);
    }
    .dash-hero::before {
        content: '';
        position: absolute;
        right: 100px; top: 80px;
        width: 180px; height: 180px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
    }
    .dash-hero__title {
        font-size: 1.65rem;
        font-weight: 900;
        margin-bottom: 6px;
        position: relative;
        z-index: 1;
        letter-spacing: -0.01em;
    }
    .dash-hero__subtitle {
        font-size: 0.9rem;
        opacity: 0.88;
        position: relative;
        z-index: 1;
        line-height: 1.55;
        max-width: 720px;
    }
    .status-chip {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        padding: 8px 16px; border-radius: 99px;
        font-size: 0.8rem; font-weight: 700;
        position: relative; z-index: 1;
        white-space: nowrap;
    }

    .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }
    .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 28px; }

    .action-card {
        background: white; border: 1.5px solid #e2e8f0;
        border-radius: 18px; padding: 28px 24px;
        cursor: pointer; transition: all 0.3s;
        text-decoration: none; color: inherit;
        display: flex; flex-direction: column; gap: 16px;
        position: relative; overflow: hidden;
    }
    .action-card::before {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(135deg, transparent 70%, rgba(79,70,229,0.03));
        transition: opacity 0.3s;
    }
    .action-card:hover {
        border-color: #4f46e5;
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(79,70,229,0.15);
    }

    .action-icon {
        width: 52px; height: 52px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
    }
    .action-icon svg { width: 26px; height: 26px; }

    .action-card h3 { font-size: 1rem; font-weight: 800; color: #0f172a; }
    .action-card p  { font-size: 0.85rem; color: #64748b; line-height: 1.55; flex: 1; }
    .action-arrow {
        font-size: 0.8rem; font-weight: 800;
        color: #4f46e5; display: flex; align-items: center; gap: 6px;
        transition: gap 0.2s;
    }
    .action-card:hover .action-arrow { gap: 10px; }

    .progress-track {
        background: #f1f5f9; border-radius: 99px; height: 6px;
        overflow: hidden; margin-top: 8px;
    }
    .progress-fill {
        height: 100%; border-radius: 99px;
        background: linear-gradient(90deg, #4f46e5, #7c3aed);
        transition: width 1s ease;
    }

    .step-label { font-size: 0.72rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 6px; }
    .section-title {
        font-size: 0.8rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #64748b;
        margin-bottom: 16px;
    }

    @media (max-width: 900px) { .grid-4 { grid-template-columns: 1fr 1fr; } .grid-2 { grid-template-columns: 1fr; } }
    @media (max-width: 600px) { .grid-4 { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
    {{-- Hero / Header --}}
    <div class="dash-hero">
        <div>
            <div class="dash-hero__title">Halo, {{ Auth::user()->name }}!</div>
            <div class="dash-hero__subtitle">Lengkapi langkah-langkah di bawah untuk menyelesaikan pendaftaran Anda.</div>
        </div>
        <div class="status-chip" style="{{ $registration && $registration->payment_status == 'paid' ? 'background:rgba(16,185,129,0.2); border-color:#10b981; color:#10b981;' : '' }}">
            <svg style="width:14px;height:14px" viewBox="0 0 24 24"><path fill="currentColor" d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,17L7,12L8.41,10.59L11,13.17V7H13V13.17L15.59,10.59L17,12L12,17Z"/></svg>
            Status: {{ $registration ? ($registration->payment_status == 'paid' ? 'Lunas' : ucwords(str_replace('_', ' ', $registration->status))) : 'Belum Mendaftar' }}
        </div>
    </div>

    {{-- Progress Steps --}}
    <div class="card" style="margin-bottom: 28px;">
        <div class="section-title" style="margin-bottom: 20px;">Progres Pendaftaran</div>

        @php
            $step1 = isset(Auth::user()->studentDetail->phone);
            $step2 = isset($registration->course_id);
            $step3 = isset($registration->payment_proof);
            $step4 = isset($registration) && $registration->status === 'verified';
            $step5 = isset($registration) && $registration->status === 'completed';
            $stepsCompleted = ($step1 ? 1 : 0) + ($step2 ? 1 : 0) + ($step3 ? 1 : 0) + ($step4 ? 1 : 0) + ($step5 ? 1 : 0);
            $pct = ($stepsCompleted / 5) * 100;
        @endphp

        <div class="steps-bar" style="display:flex; align-items:flex-start; justify-content:space-between; gap: 8px; margin-bottom: 16px;">
            @foreach([
                ['num'=>1, 'done'=>$step1, 'label'=>'Data Diri'],
                ['num'=>2, 'done'=>$step2, 'label'=>'Kursus'],
                ['num'=>3, 'done'=>$step3, 'label'=>'Pembayaran'],
                ['num'=>4, 'done'=>$step4, 'label'=>'Verifikasi'],
                ['num'=>5, 'done'=>$step5, 'label'=>'Selesai'],
            ] as $s)
                <div style="flex:1; text-align:center;">
                    <div style="width:38px;height:38px;border-radius:50%;margin:0 auto 8px;display:flex;align-items:center;justify-content:center;font-size:0.8rem;font-weight:800;
                        background:{{ $s['done'] ? '#10b981' : ($stepsCompleted+1 == $s['num'] ? '#4f46e5' : '#f1f5f9') }};
                        color:{{ $s['done'] || $stepsCompleted+1 == $s['num'] ? 'white' : '#94a3b8' }};
                        box-shadow:{{ $stepsCompleted+1 == $s['num'] ? '0 0 0 5px rgba(79,70,229,0.15)' : 'none' }};">
                        @if($s['done'])
                            <svg style="width:16px;height:16px" viewBox="0 0 24 24"><path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z"/></svg>
                        @else
                            {{ $s['num'] }}
                        @endif
                    </div>
                    <div style="font-size:0.68rem;font-weight:700;color:{{ $s['done'] ? '#10b981' : '#94a3b8' }};text-transform:uppercase;letter-spacing:0.04em;">{{ $s['label'] }}</div>
                </div>
            @endforeach
        </div>

        <div class="step-label">{{ $stepsCompleted }} dari 5 langkah selesai</div>
        <div class="progress-track">
            <div class="progress-fill" style="width: {{ $pct }}%"></div>
        </div>
    </div>

    {{-- Action Cards --}}
    <div class="grid-2">
        <a href="{{ route('isi_data') }}" class="action-card">
            <div class="action-icon" style="background: rgba(79,70,229,0.1); color:#4f46e5;">
                <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/></svg>
            </div>
            <div>
                <h3>Lengkapi Data Diri</h3>
                <p>Isi nomor telepon, alamat, jenis kelamin, dan tanggal lahir. Data ini diperlukan untuk administrasi pendaftaran.</p>
            </div>
            <span class="action-arrow">
                {{ $step1 ? '✓ Sudah Diisi' : 'Isi Sekarang' }}
                <svg style="width:14px;height:14px" viewBox="0 0 24 24"><path fill="currentColor" d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z"/></svg>
            </span>
        </a>

        <a href="{{ route('pilih_course') }}" class="action-card">
            <div class="action-icon" style="background: rgba(236,72,153,0.1); color:#ec4899;">
                <svg viewBox="0 0 24 24"><path fill="currentColor" d="M19,2L14,6.5V17.5L19,13V2M6.5,5C4.55,5 2.45,5.4 1,6.5V21.16C1,21.41 1.25,21.66 1.5,21.66C1.6,21.66 1.65,21.59 1.75,21.59C3.1,20.94 5.05,20.5 6.5,20.5C8.45,20.5 10.55,20.9 12,22C13.35,21.15 15.8,20.5 17.5,20.5C19.15,20.5 20.85,20.81 22.25,21.56C22.35,21.61 22.4,21.59 22.5,21.59C22.75,21.59 23,21.34 23,21.09V6.5C22.4,6.05 21.75,5.75 21,5.5V19C19.9,18.65 18.7,18.5 17.5,18.5C15.8,18.5 13.35,19.15 12,20V6.5C10.55,5.4 8.45,5 6.5,5Z"/></svg>
            </div>
            <div>
                <h3>Pilih Kursus &amp; Periode</h3>
                <p>Tentukan program belajar yang Anda inginkan beserta jadwal dan periode dimulainya kelas.</p>
            </div>
            <span class="action-arrow" style="color:#ec4899;">
                {{ $step2 ? '✓ Sudah Dipilih' : 'Pilih Kursus' }}
                <svg style="width:14px;height:14px" viewBox="0 0 24 24"><path fill="currentColor" d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z"/></svg>
            </span>
        </a>

        <a href="{{ route('upload_bayar') }}" class="action-card">
            <div class="action-icon" style="background: rgba(16,185,129,0.1); color:#10b981;">
                <svg viewBox="0 0 24 24"><path fill="currentColor" d="M20,8H4V6H20M20,18H4V12H20M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z"/></svg>
            </div>
            <div>
                <h3>Unggah Bukti Pembayaran</h3>
                <p>Transfer ke @if($banks->count() > 0) {{ $banks[0]->name }} {{ $banks[0]->number }} a/n {{ $banks[0]->owner }} @else Rekening BEC @endif kemudian unggah foto/scan struk transfer Anda.</p>
            </div>
            <span class="action-arrow" style="color:#10b981;">
                {{ $step3 ? '✓ Sudah Diunggah' : 'Upload Sekarang' }}
                <svg style="width:14px;height:14px" viewBox="0 0 24 24"><path fill="currentColor" d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z"/></svg>
            </span>
        </a>

        <a href="{{ route('lihat_status') }}" class="action-card">
            <div class="action-icon" style="background: rgba(245,158,11,0.1); color:#f59e0b;">
                <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4M11,17H13V11H11V17M11,9H13V7H11V9Z"/></svg>
            </div>
            <div>
                <h3>Lihat Status &amp; Feedback</h3>
                <p>Pantau hasil verifikasi data dan pembayaran. Berikan pertanyaan melalui fitur komentar.</p>
            </div>
            <span class="action-arrow" style="color:#f59e0b;">
                Cek Status Sekarang
                <svg style="width:14px;height:14px" viewBox="0 0 24 24"><path fill="currentColor" d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z"/></svg>
            </span>
        </a>
    </div>

    {{-- Summary if registered --}}
    @if($registration)
    <div class="card">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
            <div style="font-size:0.875rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.06em;">Ringkasan Pendaftaran Aktif</div>
            @if($registration->payment_status != 'paid' && $registration->status != 'verified' && $registration->status != 'completed')
            <form action="{{ route('pilih_course.destroy') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan/menghapus pilihan kursus ini? Seluruh form yang sudah diisi di pendaftaran ini akan tereset.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline btn-sm" style="color:#ef4444; border-color:#fca5a5; background:#fef2f2;">Hapus Pilihan</button>
            </form>
            @endif
        </div>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px,1fr)); gap:20px;">
            <div>
                <div style="font-size:0.72rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px;">Kursus</div>
                <div style="font-weight:800;color:#0f172a;">{{ $registration->course->name ?? '-' }}</div>
            </div>
            <div>
                <div style="font-size:0.72rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px;">Periode</div>
                <div style="font-weight:800;color:#0f172a;">{{ $registration->period->name ?? '-' }}</div>
            </div>
            <div>
                <div style="font-size:0.72rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px;">Transport</div>
                <div style="font-weight:800;color:#0f172a;">{{ $registration->transport->name ?? '-' }}</div>
            </div>
            <div>
                <div style="font-size:0.72rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px;">Total Biaya</div>
                <div style="font-weight:800;color:#4f46e5;">Rp {{ number_format(($registration->course->price ?? 0) + ($registration->course->admin_tax ?? 0) + ($registration->transport->price ?? 0), 0, ',', '.') }}</div>
            </div>
            <div>
                <div style="font-size:0.72rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px;">Status Bayar</div>
                @php $ps = $registration->payment_status; @endphp
                <span class="badge {{ $ps == 'paid' ? 'badge-success' : ($ps == 'pending_validation' ? 'badge-pending' : 'badge-danger') }}">
                    {{ $ps == 'paid' ? 'Lunas' : ($ps == 'pending_validation' ? 'Menunggu Validasi' : 'Belum Bayar') }}
                </span>
            </div>
        </div>
        
        @if($registration->payment_status == 'paid')
        <div style="margin-top: 24px; border-top: 1px dashed #e2e8f0; padding-top: 16px; display: flex; justify-content: flex-end;">
            <a href="{{ route('pos.invoice', $registration->id) }}" target="_blank" class="btn btn-primary" style="background:#10b981; color:white; border:none; padding:10px 20px; font-weight:800; font-size:0.85rem; border-radius:10px; display:inline-flex; align-items:center; gap:8px; text-decoration:none; transition:all 0.2s; box-shadow: 0 4px 12px rgba(16,185,129,0.15);">
                <i class="fas fa-print"></i> Cetak Invoice / Bukti Daftar
            </a>
        </div>
        @endif
    </div>
    @endif
@endsection
