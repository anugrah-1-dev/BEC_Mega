@extends('layouts.admission')
@section('page-title', 'Upload Pembayaran — Langkah 3 dari 4')

@section('extra-styles')
<style>
    .upload-zone {
        border: 2.5px dashed #e2e8f0; border-radius: 18px;
        padding: 48px 32px; text-align: center; cursor: pointer;
        background: #fafbff; transition: all 0.3s; position: relative;
    }
    .upload-zone:hover, .upload-zone.drag-over {
        border-color: #4f46e5; background: rgba(79,70,229,0.04);
    }
    .upload-zone input { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    .upload-icon {
        width: 64px; height: 64px; border-radius: 18px;
        background: rgba(79,70,229,0.1); margin: 0 auto 20px;
        display: flex; align-items: center; justify-content: center;
    }
    .upload-icon svg { width: 32px; height: 32px; color: #4f46e5; }
    .upload-title { font-size: 1rem; font-weight: 800; color: #0f172a; margin-bottom: 8px; }
    .upload-hint  { font-size: 0.85rem; color: #64748b; }
    .upload-filename {
        margin-top: 16px; font-size: 0.875rem; font-weight: 700;
        color: #4f46e5; display: none;
    }

    .bank-info {
        background: linear-gradient(135deg, rgba(79,70,229,0.06), rgba(124,58,237,0.04));
        border: 1.5px solid rgba(79,70,229,0.15);
        border-radius: 16px; padding: 24px;
        margin-bottom: 28px;
    }
    .bank-info .bill-label { font-size:0.72rem; font-weight:800; text-transform:uppercase; letter-spacing:0.07em; color:rgba(79,70,229,0.7); margin-bottom:8px; }
    .bank-info .bill-amount { font-size:2rem; font-weight:900; color:#4f46e5; margin-bottom:12px; }
    .bank-info .detail-row { display:flex; justify-content:space-between; font-size:0.875rem; margin-bottom:6px; }
    .bank-info .detail-row .key { color:#64748b; }
    .bank-info .detail-row .val { font-weight:700; color:#0f172a; }
</style>
@endsection

@section('content')
<div style="max-width: 640px; margin: 0 auto;">

    {{-- Step indicator --}}
    <div class="card" style="margin-bottom: 24px; padding: 24px 32px;">
        <div style="display:flex; align-items:center; justify-content:space-between; gap: 8px;">
            @foreach([
                ['n'=>1,'l'=>'Data Diri','done'=>true,'active'=>false],
                ['n'=>2,'l'=>'Pilih Kursus','done'=>true,'active'=>false],
                ['n'=>3,'l'=>'Pembayaran','done'=>false,'active'=>true],
                ['n'=>4,'l'=>'Status','done'=>false,'active'=>false],
            ] as $s)
            <div style="flex:1; text-align:center;">
                <div style="width:36px;height:36px;border-radius:50%;margin:0 auto 6px;display:flex;align-items:center;justify-content:center;font-size:0.8rem;font-weight:800;
                    background:{{ $s['done'] ? '#10b981' : ($s['active'] ? '#4f46e5' : '#f1f5f9') }};
                    color:{{ $s['done'] || $s['active'] ? 'white' : '#94a3b8' }};
                    box-shadow:{{ $s['active'] ? '0 0 0 4px rgba(79,70,229,0.15)' : 'none' }};">
                    @if($s['done'])✓@else{{ $s['n'] }}@endif
                </div>
                <div style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.04em;color:{{ $s['done'] ? '#10b981' : ($s['active'] ? '#4f46e5' : '#94a3b8') }};">{{ $s['l'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="card">
        <h1 style="font-size:1.4rem; font-weight:900; color:#0f172a; margin-bottom:6px;">💳 Unggah Bukti Pembayaran</h1>
        <p style="font-size:0.875rem; color:#64748b; margin-bottom:28px; line-height:1.6;">Transfer ke rekening di bawah, kemudian unggah foto/screenshot struk transfer Anda.</p>

        @if($registration)
        {{-- Bill Info --}}
        <div class="bank-info">
            <div class="bill-label">Total Tagihan</div>
            @php
                $totalPrice = ($registration->course->price ?? 0) + ($registration->course->admin_tax ?? 0) + ($registration->transport->price ?? 0);
            @endphp
            <div class="bill-amount">Rp {{ number_format($totalPrice, 0, ',', '.') }}</div>
            <div class="detail-row"><span class="key">Kursus</span><span class="val">{{ $registration->course->name }} (Rp {{ number_format($registration->course->price ?? 0, 0, ',', '.') }})</span></div>
            <div class="detail-row">
                <span class="key">Biaya Pendaftaran / Admin</span>
                <span class="val">
                    @if(($registration->course->admin_tax ?? 0) > 0)
                        Rp {{ number_format($registration->course->admin_tax, 0, ',', '.') }}
                    @else
                        <span style="color:#10b981">Gratis</span>
                    @endif
                </span>
            </div>
            @if($registration->transport)
                <div class="detail-row"><span class="key">Transportasi</span><span class="val">{{ $registration->transport->name }} (Rp {{ number_format($registration->transport->price, 0, ',', '.') }})</span></div>
            @endif
            @foreach($banks as $bank)
                <div class="detail-row" style="margin-top: 10px; border-top: 1px dashed rgba(0,0,0,0.05); padding-top: 10px;">
                    <span class="key">Rekening {{ $bank->name }}</span>
                    <span class="val">{{ $bank->number }}</span>
                </div>
                <div class="detail-row"><span class="key">Atas Nama</span><span class="val">{{ $bank->owner }}</span></div>
            @endforeach
        </div>

        {{-- If payment already uploaded --}}
        @if($registration->payment_proof)
        <div style="background:#d1fae5; border:1px solid #a7f3d0; border-radius:14px; padding:20px; margin-bottom:24px; display:flex; align-items:center; gap:14px;">
            <svg style="width:24px;height:24px;color:#059669;flex-shrink:0" viewBox="0 0 24 24"><path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z"/></svg>
            <div>
                <div style="font-weight:800;color:#065f46;font-size:0.9rem;">Bukti Pembayaran Sudah Diunggah</div>
                <div style="font-size:0.8rem;color:#047857;margin-top:2px;">Status: {{ ucwords(str_replace('_', ' ', $registration->payment_status)) }}</div>
            </div>
        </div>
        @endif

        <form action="{{ route('upload_bayar.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="upload-zone" id="upload-zone" onclick="document.getElementById('payment_proof').click()">
                <input type="file" name="payment_proof" id="payment_proof" accept="image/*"
                       onchange="handleFileChange(this)">
                <div class="upload-icon">
                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M9,16V10H5L12,3L19,10H15V16H9M5,20V18H19V20H5Z"/></svg>
                </div>
                <div class="upload-title">Klik atau Seret File ke Sini</div>
                <div class="upload-hint">Format: JPG, PNG, PDF · Maks 2MB</div>
                <div class="upload-filename" id="upload-filename"></div>
            </div>
            @error('payment_proof') <div class="form-error" style="margin-top:8px;">{{ $message }}</div> @enderror

            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:32px; padding-top:24px; border-top:1px solid #f1f5f9;">
                <a href="{{ route('pilih_course') }}" class="btn btn-outline">
                    <svg style="width:16px;height:16px" viewBox="0 0 24 24"><path fill="currentColor" d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z"/></svg>
                    Kembali
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    Unggah Sekarang
                    <svg style="width:18px;height:18px" viewBox="0 0 24 24"><path fill="currentColor" d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z"/></svg>
                </button>
            </div>
        </form>

        @else
        <div style="padding:40px; text-align:center; background:#f8fafc; border-radius:14px;">
            <div style="font-size:2rem; margin-bottom:12px;">📚</div>
            <div style="font-weight:700; color:#0f172a; margin-bottom:8px;">Belum Memilih Kursus</div>
            <p style="font-size:0.875rem; color:#64748b; margin-bottom:20px; line-height:1.6;">Silakan pilih kursus dan periode terlebih dahulu sebelum melakukan pembayaran.</p>
            <a href="{{ route('pilih_course') }}" class="btn btn-primary">Pilih Kursus Sekarang</a>
        </div>
        @endif
    </div>
</div>

<script>
function handleFileChange(input) {
    const zone = document.getElementById('upload-zone');
    const label = document.getElementById('upload-filename');
    if (input.files && input.files[0]) {
        const f = input.files[0];
        label.style.display = 'block';
        label.textContent = '✓ ' + f.name + ' (' + (f.size / 1024).toFixed(1) + ' KB)';
        zone.style.borderColor = '#10b981';
        zone.style.background = 'rgba(16,185,129,0.04)';
    }
}

const zone = document.getElementById('upload-zone');
if (zone) {
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('drag-over');
        const dt = e.dataTransfer;
        if (dt.files.length) {
            document.getElementById('payment_proof').files = dt.files;
            handleFileChange(document.getElementById('payment_proof'));
        }
    });
}
</script>
@endsection
