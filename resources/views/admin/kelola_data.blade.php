@extends('layouts.admission')
@section('page-title', 'Kelola Data Master')

@section('extra-styles')
<style>
    .data-master-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
    .section-header { font-size:0.78rem; font-weight:800; text-transform:uppercase; letter-spacing:0.07em; color:#64748b; margin-bottom:20px; }

    .item-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 18px; background: #f8fafc; border-radius: 12px;
        margin-bottom: 10px; border: 1px solid #f1f5f9;
        transition: all 0.2s;
    }
    .item-row:hover { border-color: #e2e8f0; background: white; }
    
    .course-item-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 20px; background: var(--primary); border-radius: 12px;
        margin-bottom: 12px; border: 1px solid var(--primary-dark);
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(54, 61, 114, 0.15);
    }
    .course-item-row:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(54, 61, 114, 0.25); }
    .course-item-name { font-weight: 800; font-size: 0.95rem; color: white; text-transform: uppercase; }
    .course-item-sub { font-size: 0.8rem; color: rgba(255,255,255,0.7); margin-top: 4px; }
    .course-item-price { font-size: 1.05rem; font-weight: 900; color: white; }

    .item-name { font-weight: 800; font-size: 0.9rem; color: #0f172a; }
    .item-sub  { font-size: 0.78rem; color: #64748b; margin-top: 2px; }
    .item-price { font-size: 0.95rem; font-weight: 900; color: var(--primary); }

    .add-form-collapsible { margin-bottom: 24px; }
    .add-form-toggle {
        width: 100%; padding: 13px 18px;
        background: rgba(54, 61, 114, 0.05); border: 1.5px dashed rgba(54, 61, 114, 0.3);
        border-radius: 12px; cursor: pointer;
        font-size: 0.875rem; font-weight: 700; color: var(--primary);
        display: flex; align-items: center; justify-content: center; gap: 8px;
        transition: all 0.2s;
    }
    .add-form-toggle:hover { background: rgba(54, 61, 114, 0.1); }

    .add-form-body {
        background: #f8fafc; border-radius: 14px; padding: 24px;
        border: 1.5px solid #e2e8f0; margin-top: 12px;
        display: none;
    }
    .add-form-body.open { display: block; }

    @media(max-width:1100px) { .data-master-grid { grid-template-columns: 1fr 1fr; } }
    @media(max-width:768px) { .data-master-grid { grid-template-columns:1fr; } }
</style>
@endsection

@section('content')
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
        <h1 style="font-size:1.5rem; font-weight:900; color:#0f172a;">⚙️ Kelola Data Master</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">← Dashboard</a>
    </div>

    <div class="data-master-grid">

        {{-- COURSES --}}
        <div class="card">
            <div class="section-header">📚 Program Kursus</div>

            {{-- Add Course Form --}}
            <div class="add-form-collapsible">
                <button class="add-form-toggle" onclick="toggleForm('course-form')">
                    <svg style="width:16px;height:16px" viewBox="0 0 24 24"><path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z"/></svg>
                    Tambah Kursus Baru
                </button>
                <div class="add-form-body" id="course-form">
                    <form action="{{ route('admin.course.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Nama Kursus</label>
                            <input type="text" name="name" class="form-control" required
                                   placeholder="Contoh: Basic 1, Intermediate 2...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Harga (Rupiah)</label>
                            <input type="number" name="price" class="form-control" required
                                   placeholder="Contoh: 500000">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="2"
                                      placeholder="Deskripsi singkat program kursus..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <svg style="width:16px;height:16px" viewBox="0 0 24 24"><path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z"/></svg>
                            Simpan Kursus
                        </button>
                    </form>
                </div>
            </div>

            {{-- Course List --}}
            <div style="font-size:0.78rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:12px;">
                {{ $courses->count() }} Kursus Terdaftar
            </div>
            <div style="max-height:350px; overflow-y:auto;">
                @forelse($courses as $c)
                <div class="course-item-row">
                    <div>
                        <div class="course-item-name">{{ $c->name }}</div>
                        @if($c->description)
                        <div class="course-item-sub">{{ Str::limit($c->description, 50) }}</div>
                        @endif
                    </div>
                    <div style="display:flex; align-items:center; gap:16px;">
                        <div class="course-item-price">Rp {{ number_format($c->price, 0, ',', '.') }}</div>
                        <form action="{{ route('admin.course.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kursus ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:rgba(239,68,68,0.15); border:none; width:32px; height:32px; border-radius:8px; color:#fca5a5; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:0.2s;" onmouseover="this.style.background='#ef4444';this.style.color='white'" onmouseout="this.style.background='rgba(239,68,68,0.15)';this.style.color='#fca5a5'" title="Hapus Kursus">
                                <svg style="width:16px;height:16px" viewBox="0 0 24 24"><path fill="currentColor" d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div style="text-align:center; color:#94a3b8; padding:24px; font-size:0.875rem; font-weight:600;">
                    Belum ada kursus. Tambah yang pertama!
                </div>
                @endforelse
            </div>
        </div>

        {{-- PERIODS --}}
        <div class="card">
            <div class="section-header">📅 Periode Belajar</div>

            {{-- Add Period Form --}}
            <div class="add-form-collapsible">
                <button class="add-form-toggle" onclick="toggleForm('period-form')">
                    <svg style="width:16px;height:16px" viewBox="0 0 24 24"><path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z"/></svg>
                    Tambah Periode Baru
                </button>
                <div class="add-form-body" id="period-form">
                    <form action="{{ route('admin.period.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Nama Periode</label>
                            <input type="text" name="name" class="form-control" required
                                   placeholder="Contoh: April 2026, Semester Ganjil 2026...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <svg style="width:16px;height:16px" viewBox="0 0 24 24"><path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z"/></svg>
                            Simpan Periode
                        </button>
                    </form>
                </div>
            </div>

            {{-- Period List --}}
            <div style="font-size:0.78rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:12px;">
                {{ $periods->count() }} Periode Terdaftar
            </div>
            <div style="max-height:350px; overflow-y:auto;">
                @forelse($periods as $p)
                <div class="course-item-row">
                    <div>
                        <div class="course-item-name">{{ $p->name }}</div>
                        <div class="course-item-sub">Mulai: {{ \Carbon\Carbon::parse($p->start_date)->format('d M Y') }}</div>
                    </div>
                    <div style="display:flex; align-items:center; gap:16px;">
                        <span class="badge" style="background: white; color: var(--primary); font-size:0.7rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">Aktif</span>
                        <form action="{{ route('admin.period.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus periode ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:rgba(239,68,68,0.15); border:none; width:32px; height:32px; border-radius:8px; color:#fca5a5; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:0.2s;" onmouseover="this.style.background='#ef4444';this.style.color='white'" onmouseout="this.style.background='rgba(239,68,68,0.15)';this.style.color='#fca5a5'" title="Hapus Periode">
                                <svg style="width:16px;height:16px" viewBox="0 0 24 24"><path fill="currentColor" d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div style="text-align:center; color:#94a3b8; padding:24px; font-size:0.875rem; font-weight:600;">
                    Belum ada periode. Tambah yang pertama!
                </div>
                @endforelse
            </div>
        </div>

        {{-- TRANSPORTS --}}
        <div class="card">
            <div class="section-header">🚚 Layanan Transport</div>

            {{-- Add Transport Form --}}
            <div class="add-form-collapsible">
                <button class="add-form-toggle" onclick="toggleForm('transport-form')">
                    <svg style="width:16px;height:16px" viewBox="0 0 24 24"><path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z"/></svg>
                    Tambah Transport Baru
                </button>
                <div class="add-form-body" id="transport-form">
                    <form action="{{ route('admin.transport.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Nama Layanan</label>
                            <input type="text" name="name" class="form-control" required
                                   placeholder="Contoh: Jemput Bandara, Antar Stasiun...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Harga (Rupiah)</label>
                            <input type="number" name="price" class="form-control" required
                                   placeholder="Contoh: 150000">
                        </div>
                        <input type="hidden" name="status" value="active">
                        <button type="submit" class="btn btn-primary btn-block">
                            <svg style="width:16px;height:16px" viewBox="0 0 24 24"><path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z"/></svg>
                            Simpan Transport
                        </button>
                    </form>
                </div>
            </div>

            {{-- Transport List --}}
            <div style="font-size:0.78rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:12px;">
                {{ $transports->count() }} Transport Terdaftar
            </div>
            <div style="max-height:350px; overflow-y:auto;">
                @forelse($transports as $t)
                <div class="course-item-row">
                    <div>
                        <div class="course-item-name">{{ $t->name }}</div>
                        <div class="course-item-sub">Status: {{ ucfirst($t->status) }}</div>
                    </div>
                    <div style="display:flex; align-items:center; gap:16px;">
                        <div class="course-item-price">Rp {{ number_format($t->price, 0, ',', '.') }}</div>
                        <form action="{{ route('admin.transport.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan transport ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:rgba(239,68,68,0.15); border:none; width:32px; height:32px; border-radius:8px; color:#fca5a5; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:0.2s;" onmouseover="this.style.background='#ef4444';this.style.color='white'" onmouseout="this.style.background='rgba(239,68,68,0.15)';this.style.color='#fca5a5'" title="Hapus Transport">
                                <svg style="width:16px;height:16px" viewBox="0 0 24 24"><path fill="currentColor" d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div style="text-align:center; color:#94a3b8; padding:24px; font-size:0.875rem; font-weight:600;">
                    Belum ada layanan transport.
                </div>
                @endforelse
            </div>
        </div>

        {{-- BANKS --}}
        <div class="card">
            <div class="section-header">💳 Rekening Bank Pembayaran</div>

            {{-- Add Bank Form --}}
            <div class="add-form-collapsible">
                <button class="add-form-toggle" onclick="toggleForm('bank-form')">
                    <svg style="width:16px;height:16px" viewBox="0 0 24 24"><path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z"/></svg>
                    Tambah Rekening Bank Baru
                </button>
                <div class="add-form-body" id="bank-form">
                    <form action="{{ route('admin.bank.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Nama Bank</label>
                            <input type="text" name="name" class="form-control" required
                                   placeholder="Contoh: BCA, Mandiri, BNI...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nomor Rekening</label>
                            <input type="text" name="number" class="form-control" required
                                   placeholder="Contoh: 1234567890">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Atas Nama</label>
                            <input type="text" name="owner" class="form-control" required
                                   placeholder="Contoh: Brilliant English Course">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <svg style="width:16px;height:16px" viewBox="0 0 24 24"><path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z"/></svg>
                            Simpan Rekening Bank
                        </button>
                    </form>
                </div>
            </div>

            {{-- Bank List --}}
            <div style="font-size:0.78rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:12px;">
                {{ count($banks) ?? 0 }} Rekening Terdaftar
            </div>
            <div style="max-height:350px; overflow-y:auto;">
                @forelse($banks as $b)
                <div class="course-item-row" style="background:#0f172a; border-color:#1e293b;">
                    <div>
                        <div class="course-item-name">{{ $b->name }}</div>
                        <div class="course-item-sub">a/n {{ $b->owner }}</div>
                    </div>
                    <div style="display:flex; align-items:center; gap:16px;">
                        <div class="course-item-price" style="font-size:0.95rem; letter-spacing:1px;">{{ $b->number }}</div>
                        <form action="{{ route('admin.bank.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rekening bank ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:rgba(239,68,68,0.15); border:none; width:32px; height:32px; border-radius:8px; color:#fca5a5; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:0.2s;" onmouseover="this.style.background='#ef4444';this.style.color='white'" onmouseout="this.style.background='rgba(239,68,68,0.15)';this.style.color='#fca5a5'" title="Hapus Bank">
                                <svg style="width:16px;height:16px" viewBox="0 0 24 24"><path fill="currentColor" d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div style="text-align:center; color:#94a3b8; padding:24px; font-size:0.875rem; font-weight:600;">
                    Belum ada rekening bank.
                </div>
                @endforelse
            </div>
        </div>
    </div>

<script>
function toggleForm(id) {
    const el = document.getElementById(id);
    el.classList.toggle('open');
}
</script>
@endsection
