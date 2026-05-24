@extends('layouts.admission')
@section('page-title', 'Detail Pendaftar')

@section('extra-styles')
<style>
    .detail-grid { display: grid; grid-template-columns: 1.6fr 1fr; gap: 24px; }
    .info-block { margin-bottom: 20px; }
    .info-key { font-size:0.72rem; font-weight:800; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:4px; }
    .info-val { font-size:0.95rem; font-weight:700; color:#0f172a; }

    .action-section { display: flex; flex-direction: column; gap: 16px; }
    .action-box { background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:16px; padding:24px; }
    .action-box h3 { font-size:0.9rem; font-weight:800; color:#0f172a; margin-bottom:16px; }

    .comment-list { max-height:240px; overflow-y:auto; display:flex; flex-direction:column; gap:10px; margin-bottom:14px; }
    .comment-item { padding:12px 14px; border-radius:10px; }
    .comment-item.admin { background:rgba(79,70,229,0.07); border:1px solid rgba(79,70,229,0.15); }
    .comment-item.student { background:#f8fafc; border:1px solid #f1f5f9; }
</style>
@endsection

@section('content')
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
        <h1 style="font-size:1.4rem; font-weight:900; color:#0f172a;">Detail: {{ $applicant->user->name }}</h1>
        <div style="display:flex; gap:12px;">
            <a href="{{ route('admin.pendaftar') }}" class="btn btn-outline">← Kembali</a>
        </div>
    </div>

    <div class="detail-grid">
        {{-- LEFT: Profile + Payment Proof --}}
        <div style="display:flex; flex-direction:column; gap:24px;">

            {{-- Profile Info --}}
            <div class="card">
                <div style="font-size:0.78rem; font-weight:800; text-transform:uppercase; letter-spacing:0.07em; color:#64748b; margin-bottom:20px;">👤 Informasi Profil</div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div class="info-block">
                        <div class="info-key">Nama Lengkap</div>
                        <div class="info-val">{{ $applicant->user->name }}</div>
                    </div>
                    <div class="info-block">
                        <div class="info-key">Email</div>
                        <div class="info-val" style="font-size:0.85rem;">{{ $applicant->user->email }}</div>
                    </div>
                    <div class="info-block">
                        <div class="info-key">Telepon</div>
                        <div class="info-val">{{ $applicant->user->studentDetail->phone ?? '-' }}</div>
                    </div>
                    <div class="info-block">
                        <div class="info-key">Jenis Kelamin</div>
                        <div class="info-val">
                            {{ ($applicant->user->studentDetail->gender ?? '-') == 'L' ? '♂ Laki-laki' : (($applicant->user->studentDetail->gender ?? '-') == 'P' ? '♀ Perempuan' : '-') }}
                        </div>
                    </div>
                    <div class="info-block">
                        <div class="info-key">Tanggal Lahir</div>
                        <div class="info-val">
                            {{ $applicant->user->studentDetail->birth_date ? \Carbon\Carbon::parse($applicant->user->studentDetail->birth_date)->format('d M Y') : '-' }}
                        </div>
                    </div>
                    <div class="info-block">
                        <div class="info-key">Tanggal Daftar</div>
                        <div class="info-val">{{ $applicant->created_at->format('d M Y') }}</div>
                    </div>
                    <div class="info-block" style="grid-column:span 2;">
                        <div class="info-key">Alamat</div>
                        <div class="info-val" style="font-weight:500;">{{ $applicant->user->studentDetail->address ?? '-' }}</div>
                    </div>
                </div>
            </div>

            {{-- Registration Details --}}
            <div class="card">
                <div style="font-size:0.78rem; font-weight:800; text-transform:uppercase; letter-spacing:0.07em; color:#64748b; margin-bottom:20px;">📚 Detail Pendaftaran</div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div class="info-block">
                        <div class="info-key">Program Kursus</div>
                        <div class="info-val">{{ $applicant->course->name }}</div>
                    </div>
                    <div class="info-block">
                        <div class="info-key">Periode</div>
                        <div class="info-val">{{ $applicant->period->name }}</div>
                    </div>
                    <div class="info-block">
                        <div class="info-key">Biaya Kursus</div>
                        <div class="info-val">Rp {{ number_format($applicant->course->price, 0, ',', '.') }}</div>
                    </div>
                    <div class="info-block">
                        <div class="info-key">Transport</div>
                        <div class="info-val">{{ $applicant->transport->name ?? '-' }} (Rp {{ number_format($applicant->transport->price ?? 0, 0, ',', '.') }})</div>
                    </div>
                    <div class="info-block">
                        <div class="info-key">Total Biaya</div>
                        <div class="info-val" style="color:#4f46e5;">Rp {{ number_format(($applicant->course->price ?? 0) + ($applicant->transport->price ?? 0), 0, ',', '.') }}</div>
                    </div>
                    <div class="info-block">
                        <div class="info-key">Status Verifikasi</div>
                        <span class="badge
                            {{ $applicant->status == 'completed' ? 'badge-success' : '' }}
                            {{ $applicant->status == 'verified'  ? 'badge-primary' : '' }}
                            {{ $applicant->status == 'pending'   ? 'badge-pending' : '' }}
                            {{ $applicant->status == 'rejected'  ? 'badge-danger'  : '' }}">
                            {{ ucwords($applicant->status) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Payment Proof --}}
            <div class="card">
                <div style="font-size:0.78rem; font-weight:800; text-transform:uppercase; letter-spacing:0.07em; color:#64748b; margin-bottom:16px;">📸 Bukti Pembayaran</div>
                @if($applicant->payment_proof)
                    <div style="border-radius:14px; overflow:hidden; border:1px solid #e2e8f0; max-height:280px;">
                        <img src="{{ asset('storage/' . $applicant->payment_proof) }}"
                             style="width:100%; object-fit:contain; background:#000; display:block; max-height:280px;"
                             alt="Bukti Pembayaran">
                    </div>
                    <div style="margin-top:10px; font-size:0.8rem; color:#64748b;">
                        Status: <span class="badge
                            {{ $applicant->payment_status == 'paid' ? 'badge-success' : 'badge-pending' }}">
                            {{ ucwords(str_replace('_', ' ', $applicant->payment_status)) }}
                        </span>
                    </div>
                @else
                    <div style="padding:40px; text-align:center; background:#f8fafc; border-radius:14px; color:#94a3b8;">
                        <div style="font-size:2rem; margin-bottom:8px;">📁</div>
                        <div style="font-weight:700; font-size:0.875rem;">Belum ada bukti diunggah</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- RIGHT: Actions + Comments --}}
        <div class="action-section">

            {{-- Verify Action --}}
            <div class="action-box">
                <h3>⚡ Aksi Verifikasi</h3>
                @if($applicant->status == 'pending')
                    <form action="{{ route('admin.pendaftar.verify', $applicant->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-block">
                            <svg style="width:16px;height:16px" viewBox="0 0 24 24"><path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z"/></svg>
                            Verifikasi Data Sekarang
                        </button>
                    </form>
                @elseif(in_array($applicant->status, ['verified','completed']))
                    <div class="alert alert-success" style="margin:0;">
                        <svg style="width:18px;height:18px;flex-shrink:0" viewBox="0 0 24 24"><path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z"/></svg>
                        Data sudah diverifikasi ✓
                    </div>
                @endif
            </div>

            {{-- Payment Validation --}}
            <div class="action-box">
                <h3>💰 Status Keuangan</h3>
                @if($applicant->payment_status != 'paid')
                    <form action="{{ route('admin.pendaftar.validate_payment', $applicant->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block">
                            <svg style="width:16px;height:16px" viewBox="0 0 24 24"><path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z"/></svg>
                            Tandai Sebagai LUNAS
                        </button>
                    </form>
                    @if($applicant->payment_status == 'pending_validation')
                        <p style="font-size: 0.75rem; color: #f59e0b; margin-top: 8px; font-weight: 600; text-align: center;">
                            ⚠️ Siswa sudah mengunggah bukti bayar.
                        </p>
                    @endif
                @else
                    <div class="alert alert-success" style="margin:0;">
                        <svg style="width:18px;height:18px;flex-shrink:0" viewBox="0 0 24 24"><path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z"/></svg>
                        Transaksi Lunas ✓
                    </div>
                    <div style="font-size: 0.72rem; color: #94a3b8; margin-top: 8px; text-align: center; font-weight: 600;">
                        Tercatat dalam laporan keuangan.
                    </div>
                @endif
            </div>

            {{-- Comments --}}
            <div class="action-box" style="flex:1;">
                <h3>💬 Feedback &amp; Komentar</h3>

                <div class="comment-list">
                    @forelse($comments as $c)
                    <div class="comment-item {{ $c->user->role === 'admin' ? 'admin' : 'student' }}">
                        <div style="font-size:0.72rem; font-weight:800; text-transform:uppercase; letter-spacing:0.05em; color:{{ $c->user->role === 'admin' ? '#4f46e5' : '#64748b' }}; margin-bottom:4px;">
                            {{ $c->user->name }} @if($c->user->role==='admin')(Admin)@endif
                        </div>
                        <div style="font-size:0.85rem; color:#0f172a; line-height:1.5;">{{ $c->comment }}</div>
                        <div style="font-size:0.7rem; color:#94a3b8; margin-top:4px;">{{ $c->created_at->diffForHumans() }}</div>
                    </div>
                    @empty
                    <div style="text-align:center; padding:20px; color:#94a3b8; font-size:0.85rem;">Belum ada komentar.</div>
                    @endforelse
                </div>

                <form action="{{ route('komentar.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="registration_id" value="{{ $applicant->id }}">
                    <textarea name="comment" class="form-control" rows="2"
                              placeholder="Tulis catatan / instruksi untuk pendaftar..." required
                              style="margin-bottom:10px;"></textarea>
                    <button type="submit" class="btn btn-primary btn-block">
                        <svg style="width:16px;height:16px" viewBox="0 0 24 24"><path fill="currentColor" d="M2,21L23,12L2,3V10L17,12L2,14V21Z"/></svg>
                        Kirim Feedback
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
