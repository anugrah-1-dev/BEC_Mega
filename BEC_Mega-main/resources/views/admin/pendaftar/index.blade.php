@extends('layouts.admission')
@section('page-title', 'Data Pendaftar')

@section('extra-styles')
<style>
    .filter-bar {
        display: flex; align-items: center; gap: 12px;
        margin-bottom: 20px; flex-wrap: wrap;
    }
    .search-input {
        padding: 10px 16px;
        border: 1.5px solid #e2e8f0; border-radius: 10px;
        width: 260px; font-family:'Inter',sans-serif;
        font-size: 0.875rem; background: white;
        transition: all 0.2s;
    }
    .search-input:focus { outline:none; border-color:#4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,0.12); }
    .filter-chip {
        padding: 8px 16px; border-radius: 99px; border: 1.5px solid #e2e8f0;
        font-size: 0.78rem; font-weight: 700; cursor: pointer; background: white;
        color: #64748b; transition: all 0.2s;
    }
    .filter-chip:hover, .filter-chip.active { border-color: #4f46e5; color: #4f46e5; background: rgba(79,70,229,0.06); }

    .applicant-avatar {
        width: 38px; height: 38px; border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white; font-weight: 800; font-size: 0.8rem;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
</style>
@endsection

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
        <h1 style="font-size:1.5rem; font-weight:900; color:#0f172a;">👥 Data Pendaftar</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">
            <svg style="width:16px;height:16px" viewBox="0 0 24 24"><path fill="currentColor" d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z"/></svg>
            Kembali ke Dashboard
        </a>
    </div>

    <div class="card" style="padding: 0; overflow: hidden;">
        {{-- Filter Bar --}}
        <div style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9;">
            <div class="filter-bar">
                <input type="text" class="search-input" id="search-input" placeholder="🔍  Cari nama atau email...">
                <button class="filter-chip active" onclick="filterRows('all', this)">Semua</button>
                <button class="filter-chip" onclick="filterRows('pending', this)">Pending</button>
                <button class="filter-chip" onclick="filterRows('verified', this)">Terverifikasi</button>
                <button class="filter-chip" onclick="filterRows('completed', this)">Selesai</button>
            </div>
        </div>

        <div class="table-wrap">
            <table class="data-table" id="applicant-table">
                <thead>
                    <tr>
                        <th>Pendaftar</th>
                        <th>Program Kursus</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applicants as $app)
                    <tr data-status="{{ $app->status }}" data-name="{{ strtolower($app->user->name) }}" data-email="{{ strtolower($app->user->email) }}">
                        <td>
                            <div style="display:flex; align-items:center; gap:12px;">
                                <div class="applicant-avatar">{{ strtoupper(substr($app->user->name, 0, 2)) }}</div>
                                <div>
                                    <div style="font-weight:800; color:#0f172a; font-size:0.9rem;">{{ $app->user->name }}</div>
                                    <div style="font-size:0.78rem; color:#64748b;">{{ $app->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight:700; font-size:0.875rem;">{{ $app->course->name }}</div>
                            <div style="font-size:0.78rem; color:#64748b;">Rp {{ number_format($app->course->price, 0, ',', '.') }}</div>
                        </td>
                        <td style="font-size:0.875rem; font-weight:600; color:#475569;">{{ $app->period->name }}</td>
                        <td>
                            <span class="badge
                                {{ $app->status == 'completed' ? 'badge-success' : '' }}
                                {{ $app->status == 'verified'  ? 'badge-primary' : '' }}
                                {{ $app->status == 'pending'   ? 'badge-pending' : '' }}
                                {{ $app->status == 'rejected'  ? 'badge-danger'  : '' }}">
                                {{ ucwords($app->status) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge
                                {{ $app->payment_status == 'paid'               ? 'badge-success' : '' }}
                                {{ $app->payment_status == 'pending_validation' ? 'badge-pending' : '' }}
                                {{ $app->payment_status == 'unpaid'             ? 'badge-danger'  : '' }}">
                                {{ $app->payment_status == 'paid' ? 'Lunas' : ($app->payment_status == 'pending_validation' ? 'Menunggu Validasi' : 'Belum Bayar') }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.pendaftar.show', $app->id) }}"
                               class="btn btn-primary btn-sm">Detail →</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:60px; color:#94a3b8; font-weight:600;">
                            Belum ada pendaftar masuk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding:16px 24px; border-top:1px solid #f1f5f9; font-size:0.8rem; color:#94a3b8; font-weight:600;">
            Total: {{ $applicants->count() }} pendaftar
        </div>
    </div>

<script>
document.getElementById('search-input').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#applicant-table tbody tr').forEach(row => {
        const nm = (row.dataset.name || '') + (row.dataset.email || '');
        row.style.display = nm.includes(q) ? '' : 'none';
    });
});

function filterRows(status, btn) {
    document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('#applicant-table tbody tr').forEach(row => {
        row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none';
    });
}
</script>
@endsection
