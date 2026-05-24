@extends('layouts.admission')

@section('title', 'Point of Sale (POS) - Pendaftaran Kursus')

@section('content')
<div class="pos-container">
    <div class="pos-main">
        <div class="pos-header">
            <div class="pos-brand">
                <img src="{{ asset('assets/logo_BEC.png') }}" alt="Logo BEC">
                <div>
                    <h1>Point of Sale (POS)</h1>
                    <p>Pilih program terbaik untuk masa depanmu</p>
                </div>
            </div>
            <div class="pos-user-info">
                <div class="user-avatar">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="user-text">
                    <strong>{{ Auth::user()->name }}</strong>
                    <span>{{ Auth::user()->email }}</span>
                </div>
            </div>
        </div>

        <div class="pos-grid">
            <!-- Program Selection -->
            <div class="pos-section">
                <div class="section-title">
                    <i class="fas fa-graduation-cap"></i>
                    <h2>Pilih Program Kursus</h2>
                </div>
                <div class="items-grid">
                    @foreach($courses as $course)
                    <div class="item-card" data-type="course" data-id="{{ $course->id }}" data-name="{{ $course->name }}" data-price="{{ $course->price }}">
                        <div style="display:flex; gap:6px; justify-content:center; margin-bottom:15px; flex-wrap: wrap;">
                            <span class="type-badge {{ $course->type }}" style="font-size:9px; font-weight:800; text-transform:uppercase; padding:3px 8px; border-radius:99px; display:inline-flex; align-items:center; gap:3px; letter-spacing:0.5px; {{ $course->type === 'online' ? 'background:rgba(16,185,129,0.15); color:#10b981;' : 'background:rgba(0,51,153,0.15); color:#003399;' }}">
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
                        <h3>{{ $course->name }}</h3>
                        <p>{{ Str::limit($course->description ?? 'Program kursus intensif bahasa Inggris berkualitas tinggi di BEC Kampung Inggris Pare.', 60) }}</p>
                        <div class="item-price" style="margin-bottom: 12px">Rp {{ number_format($course->price, 0, ',', '.') }}</div>
                        <div style="margin-bottom: 20px; border-top:1px dashed #cbd5e1; padding-top:10px; display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#64748b">
                            <span>Biaya Admin:</span>
                            <strong style="color:#0f172a">{{ $course->admin_tax > 0 ? 'Rp ' . number_format($course->admin_tax, 0, ',', '.') : 'Gratis' }}</strong>
                        </div>
                        <button class="btn-select" onclick="selectItem('course', {{ $course->id }}, '{{ $course->name }}', {{ $course->price }}, {{ $course->admin_tax ?? 0 }})">
                            Pilih Program
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Period Selection -->
            <div class="pos-section">
                <div class="section-title">
                    <i class="fas fa-calendar-alt"></i>
                    <h2>Pilih Periode Belajar</h2>
                </div>
                <div class="items-grid small">
                    @foreach($periods as $period)
                    @php
                        $periodName = $period->name ?: 'Periode ' . \Carbon\Carbon::parse($period->date)->format('d M Y');
                    @endphp
                    <div class="item-card mini" data-type="period" data-id="{{ $period->id }}" data-name="{{ $periodName }}">
                        <div class="item-icon mini">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3>{{ $periodName }}</h3>
                        <p>{{ \Carbon\Carbon::parse($period->date)->format('d M Y') }}</p>
                        <button class="btn-select mini" onclick="selectItem('period', {{ $period->id }}, '{{ $periodName }}', 0)">
                            Pilih
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Transport Selection -->
            <div class="pos-section">
                <div class="section-title">
                    <i class="fas fa-bus"></i>
                    <h2>Pilih Penjemputan</h2>
                </div>
                <div class="items-grid small">
                    @foreach($transports as $transport)
                    <div class="item-card mini" data-type="transport" data-id="{{ $transport->id }}" data-name="{{ $transport->name }}" data-price="{{ $transport->price }}">
                        <div class="item-icon mini">
                            <i class="fas fa-shuttle-van"></i>
                        </div>
                        <h3>{{ $transport->name }}</h3>
                        <div class="item-price mini">Rp {{ number_format($transport->price, 0, ',', '.') }}</div>
                        <button class="btn-select mini" onclick="selectItem('transport', {{ $transport->id }}, '{{ $transport->name }}', {{ $transport->price }})">
                            Pilih
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Cart -->
    <div class="pos-sidebar">
        <div class="cart-card">
            <div class="cart-header">
                <i class="fas fa-shopping-cart"></i>
                <h2>Ringkasan Pesanan</h2>
            </div>
            
            <div class="cart-content">
                <div class="cart-item-group" id="cart-course">
                    <span class="group-label">Program:</span>
                    <div class="cart-item empty">Belum ada program terpilih</div>
                </div>

                <div class="cart-item-group" id="cart-tax">
                    <span class="group-label">Biaya Admin:</span>
                    <div class="cart-item empty">Belum ada program terpilih</div>
                </div>

                <div class="cart-item-group" id="cart-period">
                    <span class="group-label">Periode:</span>
                    <div class="cart-item empty">Belum ada periode terpilih</div>
                </div>

                <div class="cart-item-group" id="cart-transport">
                    <span class="group-label">Transport:</span>
                    <div class="cart-item empty">Tanpa penjemputan</div>
                </div>
            </div>

            <div class="cart-footer">
                <div class="total-row">
                    <span>Total Pembayaran</span>
                    <h2 id="total-price">Rp 0</h2>
                </div>
                
                <form action="{{ route('pos.process') }}" method="POST" id="pos-form">
                    @csrf
                    <input type="hidden" name="course_id" id="input-course">
                    <input type="hidden" name="period_id" id="input-period">
                    <input type="hidden" name="transport_id" id="input-transport">
                    
                    <button type="submit" class="btn-checkout" id="btn-checkout" disabled>
                        <span>Konfirmasi & Daftar</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                <p class="cart-note">
                    <i class="fas fa-info-circle"></i>
                    Setelah konfirmasi, silakan unggah bukti pembayaran di halaman selanjutnya.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --pos-primary: #003399;
        --pos-secondary: #00c6ff;
        --pos-bg: #f0f2f5;
        --pos-white: #ffffff;
        --pos-text: #1e293b;
        --pos-muted: #64748b;
        --pos-border: #e2e8f0;
    }

    body {
        background-color: var(--pos-bg);
    }

    .pos-container {
        display: flex;
        gap: 30px;
        max-width: 1600px;
        margin: 0 auto;
        padding: 30px;
        min-height: calc(100vh - 100px);
    }

    .pos-main {
        flex: 1;
    }

    .pos-sidebar {
        width: 400px;
        position: sticky;
        top: 30px;
        height: fit-content;
    }

    /* Header */
    .pos-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--pos-white);
        padding: 25px 40px;
        border-radius: 20px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .pos-brand {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .pos-brand img {
        height: 60px;
    }

    .pos-brand h1 {
        font-size: 24px;
        font-weight: 800;
        color: var(--pos-primary);
        margin: 0;
    }

    .pos-brand p {
        color: var(--pos-muted);
        margin: 0;
        font-size: 14px;
    }

    .pos-user-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, var(--pos-primary), var(--pos-secondary));
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 20px;
    }

    .user-text {
        display: flex;
        flex-direction: column;
    }

    .user-text strong {
        font-size: 15px;
        color: var(--pos-text);
    }

    .user-text span {
        font-size: 13px;
        color: var(--pos-muted);
    }

    /* Sections */
    .pos-section {
        margin-bottom: 40px;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-left: 10px;
    }

    .section-title i {
        font-size: 20px;
        color: var(--pos-primary);
    }

    .section-title h2 {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
    }

    /* Grid & Cards */
    .items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }

    .items-grid.small {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }

    .item-card {
        background: var(--pos-white);
        border: 2px solid transparent;
        padding: 30px 25px;
        border-radius: 20px;
        text-align: center;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
    }

    .item-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08);
        border-color: var(--pos-secondary);
    }

    .item-card.active {
        border-color: var(--pos-primary);
        background: #f0f7ff;
    }

    .item-icon {
        width: 60px;
        height: 60px;
        background: #f1f5f9;
        color: var(--pos-primary);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin: 0 auto 20px;
    }

    .item-icon.mini {
        width: 45px;
        height: 45px;
        font-size: 18px;
        margin-bottom: 15px;
    }

    .item-card h3 {
        font-size: 18px;
        font-weight: 700;
        margin: 0 0 10px;
    }

    .item-card p {
        font-size: 13px;
        color: var(--pos-muted);
        line-height: 1.5;
        margin-bottom: 20px;
    }

    .item-price {
        font-size: 20px;
        font-weight: 800;
        color: var(--pos-primary);
        margin-bottom: 20px;
    }

    .item-price.mini {
        font-size: 16px;
        margin-bottom: 15px;
    }

    .btn-select {
        background: #f1f5f9;
        color: var(--pos-text);
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        width: 100%;
        transition: all 0.2s;
        cursor: pointer;
    }

    .item-card:hover .btn-select {
        background: var(--pos-primary);
        color: white;
    }

    .item-card.active .btn-select {
        background: var(--pos-primary);
        color: white;
    }

    /* Sidebar Cart */
    .cart-card {
        background: var(--pos-white);
        border-radius: 24px;
        padding: 35px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .cart-header {
        display: flex;
        align-items: center;
        gap: 15px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--pos-border);
    }

    .cart-header i {
        font-size: 24px;
        color: var(--pos-primary);
    }

    .cart-header h2 {
        font-size: 22px;
        font-weight: 800;
        margin: 0;
    }

    .cart-content {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .cart-item-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .group-label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--pos-muted);
        letter-spacing: 0.5px;
    }

    .cart-item {
        background: #f8fafc;
        border-radius: 12px;
        padding: 15px 20px;
        font-weight: 700;
        color: var(--pos-text);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cart-item.empty {
        color: #94a3b8;
        font-weight: 500;
        font-style: italic;
        border: 1px dashed var(--pos-border);
    }

    .cart-footer {
        border-top: 1px solid var(--pos-border);
        padding-top: 30px;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .total-row span {
        font-weight: 600;
        color: var(--pos-muted);
    }

    .total-row h2 {
        font-size: 28px;
        font-weight: 900;
        color: var(--pos-primary);
        margin: 0;
    }

    .btn-checkout {
        width: 100%;
        padding: 18px;
        background: var(--pos-primary);
        color: white;
        border: none;
        border-radius: 15px;
        font-size: 16px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 10px 20px rgba(0, 51, 153, 0.2);
    }

    .btn-checkout:hover:not(:disabled) {
        transform: translateY(-3px);
        background: #002d8a;
        box-shadow: 0 15px 30px rgba(0, 51, 153, 0.3);
    }

    .btn-checkout:disabled {
        background: #cbd5e1;
        cursor: not-allowed;
        box-shadow: none;
    }

    .cart-note {
        margin-top: 20px;
        font-size: 12px;
        color: var(--pos-muted);
        text-align: center;
        line-height: 1.5;
    }

    @media (max-width: 1100px) {
        .pos-container { flex-direction: column; }
        .pos-sidebar { width: 100%; position: static; }
    }
</style>

<script>
    let cart = {
        course: null,
        period: null,
        transport: null
    };

    function selectItem(type, id, name, price, adminTax = 0) {
        // Update model
        cart[type] = { id, name, price, adminTax };

        // Update UI Cards
        document.querySelectorAll(`.item-card[data-type="${type}"]`).forEach(card => {
            card.classList.remove('active');
            if (parseInt(card.dataset.id) === id) {
                card.classList.add('active');
            }
        });

        // Update Cart Sidebar
        const groupEl = document.getElementById(`cart-${type}`);
        const itemEl = groupEl.querySelector('.cart-item');
        itemEl.classList.remove('empty');
        
        let priceText = price > 0 ? ` <span style="color: var(--pos-primary)">Rp ${price.toLocaleString('id-ID')}</span>` : '';
        itemEl.innerHTML = `<span>${name}</span>${priceText}`;

        // Update Admin Tax Row
        if (type === 'course') {
            const taxGroupEl = document.getElementById('cart-tax');
            const taxItemEl = taxGroupEl.querySelector('.cart-item');
            taxItemEl.classList.remove('empty');
            if (adminTax > 0) {
                taxItemEl.innerHTML = `<span>Biaya Administrasi</span> <span style="color: var(--pos-primary)">Rp ${adminTax.toLocaleString('id-ID')}</span>`;
            } else {
                taxItemEl.innerHTML = `<span>Biaya Administrasi</span> <span style="color: #10b981; font-weight:700">Gratis</span>`;
            }
        }

        // Update Hidden Inputs
        document.getElementById(`input-${type}`).value = id;

        calculateTotal();
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
        const btn = document.getElementById('btn-checkout');
        if (cart.course && cart.period && cart.transport) {
            btn.disabled = false;
        } else {
            btn.disabled = true;
        }
    }

    // Auto-select initial if exists
    document.addEventListener('DOMContentLoaded', () => {
        @if($registration)
            selectItem('course', {{ $registration->course_id }}, '{{ $registration->course->name }}', {{ $registration->course->price }}, {{ $registration->course->admin_tax ?? 0 }});
            selectItem('period', {{ $registration->period_id }}, '{{ $registration->period->name ?: "Periode " . \Carbon\Carbon::parse($registration->period->date)->format("d M Y") }}', 0);
            selectItem('transport', {{ $registration->transport_id }}, '{{ $registration->transport->name }}', {{ $registration->transport->price }});
        @endif
    });
</script>
@endsection
