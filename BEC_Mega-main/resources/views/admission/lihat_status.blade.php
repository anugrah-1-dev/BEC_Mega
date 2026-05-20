@extends('layouts.admission')
@section('page-title','Status Pendaftaran')

@section('content')

<div style="max-width:1000px;margin:auto">

<h1 style="font-size:28px;font-weight:900;margin-bottom:25px">
🎓 Student Admission Status
</h1>

@if($registration)

@php
$status = $registration->status;
$steps = [
    'pending' => 1,
    'verified' => 2,
    'completed' => 3,
];
$currentStep = $steps[$status] ?? 1;
@endphp

{{-- ================= STATUS HERO ================= --}}
<div class="card" style="margin-bottom:25px">

<h2>Status Saat Ini</h2>

<h1 style="font-size:32px;font-weight:900;color:{{ $registration->payment_status == 'paid' ? '#10b981' : '#4f46e5' }}">
{{ $registration->payment_status == 'paid' ? 'LUNAS' : strtoupper($status) }}
</h1>

<p>
@if($registration->payment_status == 'paid')
Selamat! Pembayaran Anda sudah diverifikasi dan Anda resmi terdaftar.
@elseif($status=='pending')
Data Anda sedang diperiksa admin.
@elseif($status=='verified')
Data sudah diverifikasi, lanjut pembayaran.
@elseif($status=='completed')
Selamat! Anda resmi terdaftar.
@endif
</p>

</div>


{{-- ================= TIMELINE ================= --}}
<div class="card" style="margin-bottom:25px">

<h3>Progress Pendaftaran</h3>

<div style="display:flex;justify-content:space-between">

@foreach(['Isi Data','Verifikasi','Selesai'] as $i=>$label)

<div style="text-align:center;flex:1">

<div style="
width:50px;height:50px;
margin:auto;
border-radius:50%;
line-height:50px;
font-weight:bold;
color:white;
background:
{{ $currentStep >= $i+1 ? ($registration->payment_status == 'paid' ? '#10b981' : '#4f46e5') : '#cbd5e1' }}">
{{ $i+1 }}
</div>

<p style="margin-top:8px">{{ $label }}</p>

</div>

@endforeach

</div>
</div>


{{-- ================= DETAIL ================= --}}
<div class="card" style="margin-bottom:25px">

<h3>Detail Pendaftaran</h3>

<p><b>Kursus :</b> {{ $registration->course->name }} (Rp {{ number_format($registration->course->price ?? 0, 0, ',', '.') }})</p>
<p><b>Biaya Pendaftaran / Admin :</b> 
    @if(($registration->course->admin_tax ?? 0) > 0)
        Rp {{ number_format($registration->course->admin_tax, 0, ',', '.') }}
    @else
        <span style="color:#10b981; font-weight:bold">Gratis</span>
    @endif
</p>
<p><b>Transport :</b> {{ $registration->transport->name ?? '-' }} (Rp {{ number_format($registration->transport->price ?? 0, 0, ',', '.') }})</p>
<p><b>Total Biaya :</b> Rp {{ number_format(($registration->course->price ?? 0) + ($registration->course->admin_tax ?? 0) + ($registration->transport->price ?? 0), 0, ',', '.') }}</p>

<p>
<b>Status Bayar :</b>

@if($registration->payment_status=='paid')
<span style="color:#10b981; font-weight:bold;">✔ Lunas ({{ strtoupper($registration->payment_type ?? 'Konfirmasi Admin') }})</span>
@else
<span style="color:orange; font-weight:bold;">Menunggu Pembayaran</span>
@endif
</p>

@if($registration->payment_status == 'paid')
<a href="{{ route('pos.invoice', $registration->id) }}" target="_blank" class="btn btn-primary" style="background:#10b981; border:none; margin-top:10px; width:100%; font-weight:bold; font-size:1.1rem; padding:15px; text-align:center; display:flex; align-items:center; justify-content:center; gap:10px; text-decoration:none; color:white; border-radius:10px; box-shadow: 0 4px 12px rgba(16,185,129,0.2);">
    <i class="fas fa-print"></i> CETAK INVOICE / BUKTI DAFTAR
</a>
@endif

@if($registration->payment_status != 'paid' && $registration->snap_token)
<button id="pay-button" class="btn btn-primary" style="background:#10b981; border:none; margin-top:10px; width:100%; font-weight:bold; font-size:1.1rem; padding:15px;">
    💳 BAYAR SEKARANG
</button>
@endif

@if($registration->payment_proof)
<img src="{{ asset('storage/'.$registration->payment_proof) }}"
style="width:300px;border-radius:10px;margin-top:10px">
@endif

</div>


{{-- ================= CHAT ADMIN ================= --}}
<div class="card">

<h3>💬 Komunikasi Admin</h3>

<div style="max-height:300px;overflow:auto;margin-bottom:15px">

@forelse($comments as $comment)

<div style="
padding:12px;
margin-bottom:10px;
border-radius:10px;
background:
{{ $comment->user->role=='admin' ? '#eef2ff':'#f1f5f9' }}">

<b>{{ $comment->user->name }}</b>

<p>{{ $comment->comment }}</p>

<small>{{ $comment->created_at->diffForHumans() }}</small>

</div>

@empty

<p>Belum ada komentar</p>

@endforelse

</div>


<form method="POST" action="{{ route('komentar.store') }}">
@csrf

<div style="display:flex;gap:10px">
<input type="text"
name="comment"
placeholder="Tulis pesan ke admin..."
required
class="form-control">

<button class="btn btn-primary">
Kirim
</button>
</div>

</form>

</div>

@else

<div class="card" style="text-align:center">

<h2>Belum Ada Pendaftaran</h2>

<a href="{{ route('isi_data') }}" class="btn btn-primary">
Mulai Daftar
</a>

</div>

@endif

</div>

@endsection

@section('extra-scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    const payButton = document.getElementById('pay-button');
    if (payButton) {
        payButton.onclick = function () {
            window.snap.pay('{{ $registration->snap_token }}', {
                onSuccess: function (result) {
                    window.location.href = "{{ route('lihat_status') }}";
                },
                onPending: function (result) {
                    window.location.href = "{{ route('lihat_status') }}";
                },
                onError: function (result) {
                    alert("Pembayaran gagal!");
                },
                onClose: function () {
                    alert('Anda menutup popup pembayaran sebelum selesai.');
                }
            });
        };
    }
</script>
@endsection