@extends('layouts.admission')
@section('page-title', 'Manajemen Siswa')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h1 style="font-size:1.5rem; font-weight:900; color:#0f172a;">👨‍🎓 Manajemen Siswa</h1>
    </div>

    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9;">
            <input type="text" id="siswa-search" class="form-control" placeholder="🔍 Cari nama, email, atau no. HP..." style="max-width: 400px;">
        </div>
        <div class="table-wrap">
            <table class="data-table" id="siswa-table">
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Kontak</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th>Terdaftar Sejak</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr data-search="{{ strtolower($student->name . ' ' . $student->email . ' ' . ($student->studentDetail->phone ?? '')) }}">
                        <td>
                            <div style="display:flex; align-items:center; gap:12px;">
                                <div style="width:36px; height:36px; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-weight:800; color:var(--primary);">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:700;">{{ $student->name }}</div>
                                    <div style="font-size:0.75rem; color:#64748b;">{{ $student->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-size:0.875rem; font-weight:600;">{{ $student->studentDetail->phone ?? '—' }}</div>
                        </td>
                        <td>
                            <div style="font-size:0.8125rem; color:#475569; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                {{ $student->studentDetail->address ?? '—' }}
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $student->Status == 'Aktif' ? 'badge-success' : 'badge-danger' }}">
                                {{ $student->Status ?? 'Aktif' }}
                            </span>
                        </td>
                        <td style="font-size:0.8125rem; color:#94a3b8;">
                            {{ $student->created_at->format('d M Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:60px; color:#94a3b8;">Belum ada siswa yang terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

<script>
    document.getElementById('siswa-search').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#siswa-table tbody tr').forEach(row => {
            row.style.display = row.dataset.search.includes(q) ? '' : 'none';
        });
    });
</script>
@endsection
