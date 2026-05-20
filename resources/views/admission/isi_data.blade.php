@extends('layouts.admission')
@section('page-title', 'Data Diri — Langkah 1 dari 4')

@section('content')
<div style="max-width: 640px; margin: 0 auto;">

    {{-- Step indicator --}}
    <div class="card" style="margin-bottom: 24px; padding: 24px 32px;">
        <div style="display:flex; align-items:center; justify-content:space-between; gap: 8px;">
            @foreach([
                ['n'=>1,'l'=>'Data Diri','active'=>true,'done'=>false],
                ['n'=>2,'l'=>'Pilih Kursus','active'=>false,'done'=>false],
                ['n'=>3,'l'=>'Pembayaran','active'=>false,'done'=>false],
                ['n'=>4,'l'=>'Status','active'=>false,'done'=>false],
            ] as $s)
            <div style="flex:1; text-align:center;">
                <div style="width:36px;height:36px;border-radius:50%;margin:0 auto 6px;display:flex;align-items:center;justify-content:center;font-size:0.8rem;font-weight:800;
                    background:{{ $s['active'] ? '#4f46e5' : '#f1f5f9' }};
                    color:{{ $s['active'] ? 'white' : '#94a3b8' }};
                    box-shadow:{{ $s['active'] ? '0 0 0 4px rgba(79,70,229,0.15)' : 'none' }};">
                    {{ $s['n'] }}
                </div>
                <div style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.04em;color:{{ $s['active'] ? '#4f46e5' : '#94a3b8' }};">{{ $s['l'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="card">
        <div style="margin-bottom: 28px;">
            <h1 style="font-size:1.4rem; font-weight:900; color:#0f172a; margin-bottom:6px;">📝 Kelengkapan Data Diri</h1>
            <p style="font-size:0.875rem; color:#64748b; line-height:1.6;">Isi data diri Anda dengan benar. Informasi ini digunakan untuk keperluan administrasi pendaftaran BEC.</p>
        </div>

        <form action="{{ route('isi_data.store') }}" method="POST">
            @csrf

            <div style="display:grid; grid-template-columns:1fr 1fr; gap: 0 24px;">
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Nomor Telepon (WhatsApp Aktif)</label>
                    <input type="text" name="phone" class="form-control"
                           value="{{ $detail->phone ?? '' }}" required
                           placeholder="Contoh: 08123456789">
                    @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">No. HP Wali</label>
                    <input type="text" name="guardian_phone" class="form-control"
                           value="{{ $detail->guardian_phone ?? '' }}"
                           placeholder="Contoh: 08123456789">
                    @error('guardian_phone') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="address" rows="3" class="form-control"
                              required placeholder="Jalan, Desa/Kelurahan, Kecamatan, Kota...">{{ $detail->address ?? '' }}</textarea>
                    @error('address') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Jenis Kelamin</label>
                    <div style="display:flex; gap:16px; margin-top:4px;">
                        @foreach([['L','Laki-laki'],['P','Perempuan']] as $g)
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;
                            padding:12px 20px; border:1.5px solid {{($detail->gender ?? '')==$g[0] ? '#4f46e5' : '#e2e8f0'}};
                            border-radius:10px; background:{{($detail->gender ?? '')==$g[0] ? 'rgba(79,70,229,0.06)' : '#fafafa'}};
                            flex:1; transition:all 0.2s;">
                            <input type="radio" name="gender" value="{{ $g[0] }}"
                                   {{ ($detail->gender ?? '') == $g[0] ? 'checked' : '' }} required
                                   style="accent-color:#4f46e5;">
                            <span style="font-size:0.875rem; font-weight:600; color:#0f172a;">{{ $g[1] }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('gender') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="birth_place" class="form-control"
                           value="{{ $detail->birth_place ?? '' }}"
                           placeholder="Contoh: Yogyakarta">
                    @error('birth_place') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="birth_date" class="form-control"
                           value="{{ $detail->birth_date ?? '' }}" required>
                    @error('birth_date') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Ukuran Seragam</label>
                    <select name="uniform_size" class="form-control" required>
                        <option value="">— Pilih Ukuran —</option>
                        @foreach(['XS','S','M','L','XL','XXL','XXXL'] as $size)
                        <option value="{{ $size }}" {{ ($detail->uniform_size ?? '') == $size ? 'selected' : '' }}>
                            {{ $size }}
                        </option>
                        @endforeach
                    </select>
                    @error('uniform_size') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:32px; padding-top:24px; border-top:1px solid #f1f5f9;">
                <a href="{{ route('dashboard') }}" class="btn btn-outline">
                    <svg style="width:16px;height:16px" viewBox="0 0 24 24"><path fill="currentColor" d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z"/></svg>
                    Kembali
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    Simpan &amp; Lanjutkan
                    <svg style="width:18px;height:18px" viewBox="0 0 24 24"><path fill="currentColor" d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z"/></svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
