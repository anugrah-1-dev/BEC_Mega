@extends('layouts.admission')

@section('title', 'Pendaftaran Cepat - Brilliant English Course')

@section('content')
<div class="pos-container">
    <form action="{{ route('pos.direct.process') }}" method="POST" id="pos-form" class="pos-main">
        @csrf
        <div class="pos-header">
            <div class="pos-brand">
                <img src="{{ asset('assets/logo_BEC.png') }}" alt="Logo BEC">
                <div>
                    <h1>Direct Point of Sale (POS)</h1>
                    <p>Pendaftaran Cepat & Instan</p>
                </div>
            </div>
        </div>

        <!-- Step 1: Data Diri -->
        <div class="pos-section">
            <div class="section-title">
                <div class="step-num">1</div>
                <h2>Data Diri Pendaftar</h2>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso" required>
                </div>
                <div class="form-group">
                    <label>Email Aktif</label>
                    <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                </div>
                <div class="form-group">
                    <label>Nomor WhatsApp</label>
                    <input type="text" name="phone" class="form-control" placeholder="0812xxxxxxxx" required>
                </div>
            </div>
        </div>

        <!-- Step 2: Pilih Program -->
        <div class="pos-section">
            <div class="section-title">
                <div class="step-num">2</div>
                <h2>Pilih Program Kursus</h2>
            </div>
            <div class="items-grid">
                @foreach($courses as $course)
                <div class="item-card" data-type="course" data-id="{{ $course->id }}" data-name="{{ $course->name }}" data-price="{{ $course->price }}" onclick="selectItem('course', {{ $course->id }}, '{{ $course->name }}', {{ $course->price }}, {{ $course->admin_tax ?? 0 }})">
                    <div class="select-badge">PILIH</div>
                    <div style="display:flex; gap:6px; justify-content:center; margin-bottom:12px; flex-wrap: wrap;">
                        <span class="type-badge {{ $course->type }}" style="font-size:9px; font-weight:800; text-transform:uppercase; padding:3px 8px; border-radius:99px; display:inline-flex; align-items:center; gap:3px; letter-spacing:0.5px; {{ $course->type === 'online' ? 'background:rgba(16,185,129,0.15); color:#10b981;' : 'background:rgba(79,70,229,0.15); color:#4f46e5;' }}">
                            <i class="fa-solid {{ $course->type === 'online' ? 'fa-globe' : 'fa-house-laptop' }}"></i>
                            {{ strtoupper($course->type) }}
                        </span>
                        @if($course->duration)
                            <span class="duration-badge" style="font-size:9px; font-weight:800; background:rgba(100,116,139,0.1); color:#475569; padding:3px 8px; border-radius:99px; display:inline-flex; align-items:center; gap:3px; letter-spacing:0.5px;">
                                <i class="fa-solid fa-clock"></i>
                                {{ $course->duration }}
                            </span>
                        @endif
                    </div>
                    <h3 style="margin-bottom:10px">{{ $course->name }}</h3>
                    <div class="item-price">Rp {{ number_format($course->price, 0, ',', '.') }}</div>
                    <div style="margin-top:12px; border-top:1px dashed #cbd5e1; padding-top:10px; display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#64748b">
                        <span>Biaya Admin:</span>
                        <strong style="color:#0f172a">{{ $course->admin_tax > 0 ? 'Rp ' . number_format($course->admin_tax, 0, ',', '.') : 'Gratis' }}</strong>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Step 3: Periode & Transport -->
        <div class="pos-section split">
            <div class="section-col">
                <div class="section-title">
                    <div class="step-num">3</div>
                    <h2>Pilih Periode</h2>
                </div>
                <div class="items-grid small">
                    @foreach($periods as $period)
                    @php
                        $periodName = $period->name ?: 'Periode ' . \Carbon\Carbon::parse($period->date)->format('d M Y');
                    @endphp
                    <div class="item-card mini" data-type="period" data-id="{{ $period->id }}" data-name="{{ $periodName }}" onclick="selectItem('period', {{ $period->id }}, '{{ $periodName }}', 0)">
                        <h3>{{ $periodName }}</h3>
                        <small>{{ \Carbon\Carbon::parse($period->date)->format('d M Y') }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="section-col">
                <div class="section-title">
                    <div class="step-num">4</div>
                    <h2>Layanan Tambahan</h2>
                </div>
                <div class="items-grid small">
                    @foreach($transports as $transport)
                    <div class="item-card mini" data-type="transport" data-id="{{ $transport->id }}" data-name="{{ $transport->name }}" data-price="{{ $transport->price }}" onclick="selectItem('transport', {{ $transport->id }}, '{{ $transport->name }}', {{ $transport->price }})">
                        <h3>{{ $transport->name }}</h3>
                        <div class="item-price mini">Rp {{ number_format($transport->price, 0, ',', '.') }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Step 4: Metode Pembayaran -->
        <div class="pos-section">
            <div class="section-title">
                <div class="step-num">5</div>
                <h2>Metode Pembayaran</h2>
            </div>
            <div class="payment-grid">
                <label class="payment-card">
                    <input type="radio" name="payment_method" value="Transfer Bank" required onclick="updateCart('payment', 'Transfer Bank')">
                    <div class="payment-inner">
                        <i class="fas fa-university"></i>
                        <span>Transfer Bank</span>
                    </div>
                </label>
                <label class="payment-card">
                    <input type="radio" name="payment_method" value="E-Wallet" onclick="updateCart('payment', 'E-Wallet')">
                    <div class="payment-inner">
                        <i class="fas fa-wallet"></i>
                        <span>E-Wallet (OVO/Dana/Gopay)</span>
                    </div>
                </label>
                <label class="payment-card">
                    <input type="radio" name="payment_method" value="QRIS" onclick="updateCart('payment', 'QRIS')">
                    <div class="payment-inner">
                        <i class="fas fa-qrcode"></i>
                        <span>QRIS / Scan Pay</span>
                    </div>
                </label>
            </div>
        </div>

        <!-- Hidden Inputs -->
        <input type="hidden" name="course_id" id="input-course">
        <input type="hidden" name="period_id" id="input-period">
        <input type="hidden" name="transport_id" id="input-transport">
    </form>

    <!-- Right Summary Sidebar -->
    <div class="pos-sidebar">
        <div class="summary-card">
            <div class="summary-header">
                <i class="fas fa-receipt"></i>
                <h2>Ringkasan Pendaftaran</h2>
            </div>
            <div class="summary-content">
                <div class="summary-row" id="row-course">
                    <span class="lbl">Program:</span>
                    <span class="val empty">Belum dipilih</span>
                </div>
                <div class="summary-row" id="row-tax">
                    <span class="lbl">Biaya Admin:</span>
                    <span class="val empty">Belum dipilih</span>
                </div>
                <div class="summary-row" id="row-period">
                    <span class="lbl">Periode:</span>
                    <span class="val empty">Belum dipilih</span>
                </div>
                <div class="summary-row" id="row-transport">
                    <span class="lbl">Transport:</span>
                    <span class="val empty">Belum dipilih</span>
                </div>
                <div class="summary-row" id="row-payment">
                    <span class="lbl">Metode Bayar:</span>
                    <span class="val empty">Belum dipilih</span>
                </div>
            </div>
            <div class="summary-total">
                <span class="total-lbl">Total Pembayaran</span>
                <h2 class="total-val" id="total-price">Rp 0</h2>
            </div>
            <button type="button" class="btn-pay" id="btn-pay" onclick="document.getElementById('pos-form').submit()" disabled>
                BAYAR SEKARANG
                <i class="fas fa-shield-alt"></i>
            </button>
            <p class="secure-text">
                <i class="fas fa-lock"></i>
                Pembayaran Aman & Terverifikasi Otomatis
            </p>
        </div>
    </div>
</div>

<style>
    :root {
        --pos-primary: #4f46e5;
        --pos-accent: #818cf8;
        --pos-bg: #f8fafc;
        --pos-card: #ffffff;
        --pos-text: #1e293b;
    }

    .pos-container {
        display: flex; gap: 30px; max-width: 1400px; margin: 30px auto; padding: 0 20px;
    }
    .pos-main { flex: 1; display: flex; flex-direction: column; gap: 25px; }
    .pos-sidebar { width: 380px; position: sticky; top: 30px; height: fit-content; }

    .pos-header {
        background: var(--pos-card); padding: 30px; border-radius: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    }
    .pos-brand { display: flex; align-items: center; gap: 15px; }
    .pos-brand img { height: 50px; }
    .pos-brand h1 { font-size: 22px; font-weight: 800; margin: 0; color: var(--pos-primary); }
    .pos-brand p { font-size: 14px; color: #64748b; margin: 0; }

    .pos-section {
        background: var(--pos-card); padding: 35px; border-radius: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    }
    .section-title { display: flex; align-items: center; gap: 15px; margin-bottom: 25px; }
    .step-num {
        width: 32px; height: 32px; background: var(--pos-primary); color: white;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 14px;
    }
    .section-title h2 { font-size: 18px; font-weight: 800; margin: 0; color: var(--pos-text); }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-control {
        width: 100%; padding: 12px 18px; border: 2px solid #e2e8f0; border-radius: 12px;
        font-size: 14px; transition: 0.2s;
    }
    .form-control:focus { border-color: var(--pos-primary); outline: none; box-shadow: 0 0 0 4px rgba(79,70,229,0.1); }

    .items-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; }
    .items-grid.small { grid-template-columns: 1fr; }

    .item-card {
        background: #f8fafc; border: 2px solid #f1f5f9; padding: 25px 20px; border-radius: 18px;
        text-align: center; cursor: pointer; transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative;
    }
    .item-card:hover { transform: translateY(-5px); border-color: var(--pos-accent); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
    .item-card.active { border-color: var(--pos-primary); background: #f5f3ff; }
    
    .item-icon { font-size: 24px; color: var(--pos-primary); margin-bottom: 15px; }
    .item-card h3 { font-size: 15px; font-weight: 700; margin-bottom: 8px; }
    .item-price { font-size: 18px; font-weight: 800; color: var(--pos-primary); }
    .select-badge {
        display: none; position: absolute; top: 10px; right: 10px; background: var(--pos-primary);
        color: white; font-size: 9px; padding: 3px 8px; border-radius: 5px; font-weight: 800;
    }
    .item-card.active .select-badge { display: block; }

    .pos-section.split { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; background: transparent; padding: 0; box-shadow: none; }
    .section-col { background: white; padding: 30px; border-radius: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }

    .payment-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
    .payment-card { cursor: pointer; }
    .payment-card input { display: none; }
    .payment-inner {
        background: #f8fafc; border: 2px solid #f1f5f9; padding: 20px; border-radius: 15px;
        display: flex; flex-direction: column; align-items: center; gap: 10px; transition: 0.2s;
    }
    .payment-card input:checked + .payment-inner { border-color: var(--pos-primary); background: #f5f3ff; }
    .payment-inner i { font-size: 20px; color: var(--pos-primary); }
    .payment-inner span { font-size: 13px; font-weight: 700; text-align: center; }

    /* Summary Sidebar */
    .summary-card { background: white; padding: 35px; border-radius: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
    .summary-header { display: flex; align-items: center; gap: 12px; margin-bottom: 30px; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; }
    .summary-header h2 { font-size: 20px; font-weight: 800; margin: 0; }
    .summary-header i { color: var(--pos-primary); font-size: 22px; }

    .summary-content { display: flex; flex-direction: column; gap: 18px; }
    .summary-row { display: flex; justify-content: space-between; font-size: 14px; }
    .summary-row .lbl { color: #64748b; font-weight: 600; }
    .summary-row .val { font-weight: 800; color: var(--pos-text); }
    .summary-row .val.empty { color: #cbd5e1; font-weight: 500; font-style: italic; }

    .summary-total { margin-top: 30px; padding-top: 25px; border-top: 2px dashed #f1f5f9; text-align: center; }
    .total-lbl { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; }
    .total-val { font-size: 32px; font-weight: 900; color: var(--pos-primary); margin: 8px 0 25px; }

    .btn-pay {
        width: 100%; padding: 18px; background: var(--pos-primary); color: white; border: none;
        border-radius: 16px; font-weight: 800; font-size: 16px; cursor: pointer; transition: 0.3s;
        display: flex; align-items: center; justify-content: center; gap: 12px;
    }
    .btn-pay:hover:not(:disabled) { background: #4338ca; transform: scale(1.02); box-shadow: 0 10px 15px -3px rgba(79,70,229,0.4); }
    .btn-pay:disabled { background: #cbd5e1; cursor: not-allowed; }

    .secure-text { margin-top: 20px; font-size: 11px; color: #94a3b8; text-align: center; display: flex; align-items: center; justify-content: center; gap: 6px; }

    @media (max-width: 1100px) {
        .pos-container { flex-direction: column; }
        .pos-sidebar { width: 100%; position: static; }
        .pos-section.split { grid-template-columns: 1fr; }
    }
</style>

<script>
    let cart = { course: null, period: null, transport: null, payment: null };

    function selectItem(type, id, name, price, adminTax = 0) {
        cart[type] = { id, name, price, adminTax };
        document.querySelectorAll(`.item-card[data-type="${type}"]`).forEach(c => {
            c.classList.remove('active');
            if (parseInt(c.dataset.id) === id) c.classList.add('active');
        });
        
        const row = document.getElementById(`row-${type}`);
        const val = row.querySelector('.val');
        val.classList.remove('empty');
        val.textContent = name;

        // Update Admin Tax Row
        if (type === 'course') {
            const taxRow = document.getElementById('row-tax');
            const taxVal = taxRow.querySelector('.val');
            taxVal.classList.remove('empty');
            if (adminTax > 0) {
                taxVal.textContent = `Rp ${adminTax.toLocaleString('id-ID')}`;
            } else {
                taxVal.innerHTML = `<span style="color:#10b981; font-weight:700">Gratis</span>`;
            }
        }
        
        document.getElementById(`input-${type}`).value = id;
        calculateTotal();
        checkValidation();
    }

    function updateCart(type, val) {
        cart[type] = val;
        const row = document.getElementById(`row-${type}`);
        const valEl = row.querySelector('.val');
        valEl.classList.remove('empty');
        valEl.textContent = val;
        checkValidation();
    }

    function calculateTotal() {
        let total = 0;
        if (cart.course) {
            total += cart.course.price;
            total += cart.course.adminTax;
        }
        if (cart.transport) total += cart.transport.price;
        document.getElementById('total-price').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    }

    function checkValidation() {
        const btn = document.getElementById('btn-pay');
        if (cart.course && cart.period && cart.transport && cart.payment) {
            btn.disabled = false;
        } else {
            btn.disabled = true;
        }
    }
</script>
@endsection
