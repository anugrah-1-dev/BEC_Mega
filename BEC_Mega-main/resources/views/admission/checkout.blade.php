@extends('layouts.admission')

@section('title', 'Checkout POS - Brilliant English Course')

@section('extra-styles')
<style>
    :root {
        --checkout-primary: #4f46e5;
        --checkout-success: #10b981;
        --checkout-bg: #f8fafc;
        --checkout-border: #e2e8f0;
        --radius-lg: 20px;
        --radius-md: 14px;
        --shadow-glow: 0 10px 30px rgba(79, 70, 229, 0.12);
    }

    .checkout-flow-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 30px;
        align-items: start;
    }

    .checkout-card {
        background: #ffffff;
        border-radius: var(--radius-lg);
        border: 1px solid var(--checkout-border);
        box-shadow: 0 4px 24px rgba(0,0,0,0.04);
        padding: 35px;
        margin-bottom: 25px;
    }

    .checkout-card-title {
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #1e293b;
        border-bottom: 1px solid var(--checkout-border);
        padding-bottom: 15px;
    }

    .checkout-card-title i {
        color: var(--checkout-primary);
    }

    /* Program detail badge list */
    .detail-item-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        background: #f8fafc;
        border-radius: var(--radius-md);
        border: 1px solid #f1f5f9;
        transition: transform 0.2s;
    }
    .detail-item:hover {
        transform: translateX(4px);
    }

    .detail-item-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .detail-item-icon {
        width: 44px;
        height: 44px;
        background: var(--accent-light);
        color: var(--checkout-primary);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .detail-item-text h4 {
        font-size: 14.5px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 2px;
    }

    .detail-item-text p {
        font-size: 12.5px;
        color: #64748b;
    }

    .detail-item-price {
        font-size: 15.5px;
        font-weight: 800;
        color: #0f172a;
    }

    /* Payment Methods selector */
    .payment-methods-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 30px;
    }

    .payment-method-btn {
        background: white;
        border: 2px solid var(--checkout-border);
        border-radius: var(--radius-md);
        padding: 20px 15px;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        transition: all 0.25s;
    }

    .payment-method-btn:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }

    .payment-method-btn.active {
        border-color: var(--checkout-primary);
        background: rgba(79, 70, 229, 0.04);
        box-shadow: var(--shadow-glow);
    }

    .payment-method-btn i {
        font-size: 24px;
        color: #64748b;
        transition: color 0.25s;
    }

    .payment-method-btn.active i {
        color: var(--checkout-primary);
    }

    .payment-method-btn span {
        font-size: 13.5px;
        font-weight: 700;
        color: #475569;
    }

    .payment-method-btn.active span {
        color: var(--checkout-primary);
    }

    /* Payment details container */
    .payment-details-box {
        background: #f8fafc;
        border: 1px solid var(--checkout-border);
        border-radius: var(--radius-md);
        padding: 25px;
        margin-top: 25px;
        display: none;
    }

    .payment-details-box.active {
        display: block;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .payment-instructions h4 {
        font-size: 15px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .bank-acc-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 15px;
    }

    .bank-acc-item {
        background: white;
        padding: 12px 18px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .bank-acc-item span {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
    }

    .bank-acc-item strong {
        font-size: 14px;
        color: #0f172a;
        font-weight: 800;
    }

    /* QR Code styling */
    .qris-wrapper {
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .qris-code {
        width: 160px;
        height: 160px;
        background: white;
        padding: 10px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .qris-code img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    /* Checkout summary details */
    .checkout-summary-box {
        background: #ffffff;
        border: 1px solid var(--checkout-border);
        border-radius: var(--radius-lg);
        padding: 30px;
        position: sticky;
        top: 100px;
    }

    .summary-title {
        font-size: 18px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 20px;
        border-bottom: 1.5px solid #f1f5f9;
        padding-bottom: 15px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 14px;
    }

    .summary-row.total {
        margin-top: 20px;
        padding-top: 15px;
        border-top: 2px dashed #e2e8f0;
        font-size: 16px;
    }

    .summary-row.total h3 {
        font-weight: 900;
        color: var(--checkout-success);
        font-size: 24px;
    }

    .btn-checkout-pay {
        width: 100%;
        padding: 18px;
        background: linear-gradient(135deg, var(--checkout-primary) 0%, #3b82f6 100%);
        color: white;
        border: none;
        border-radius: var(--radius-md);
        font-size: 16px;
        font-weight: 800;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-top: 25px;
        transition: all 0.3s;
        box-shadow: 0 8px 24px rgba(79, 70, 229, 0.2);
    }

    .btn-checkout-pay:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(79, 70, 229, 0.3);
        filter: brightness(1.05);
    }
    
    .btn-checkout-pay:disabled {
        background: #cbd5e1;
        cursor: not-allowed;
        box-shadow: none;
    }
</style>
@endsection

@section('page-title', 'Point of Sale (POS) Checkout')

@section('content')
<div class="checkout-flow-container">
    <div>
        <!-- Card 1: Detail Program Terpilih -->
        <div class="checkout-card">
            <div class="checkout-card-title">
                <i class="fa-solid fa-graduation-cap"></i>
                <h2>Detail Program Pendaftaran</h2>
            </div>

            <div class="detail-item-list">
                <!-- Course -->
                <div class="detail-item">
                    <div class="detail-item-left">
                        <div class="detail-item-icon">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <div class="detail-item-text">
                            <h4>{{ $registration->course->name }}</h4>
                            <p>Program Kursus Utama</p>
                        </div>
                    </div>
                    <div class="detail-item-price">
                        Rp {{ number_format($registration->course->price, 0, ',', '.') }}
                    </div>
                </div>

                <!-- Period -->
                <div class="detail-item">
                    <div class="detail-item-left">
                        <div class="detail-item-icon">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div class="detail-item-text">
                            <h4>{{ $registration->period->name }}</h4>
                            <p>Periode Mulai: {{ \Carbon\Carbon::parse($registration->period->start_date)->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="detail-item-price" style="color: #64748b; font-size: 13.5px; font-weight:600">
                        Termasuk paket
                    </div>
                </div>

                <!-- Transport -->
                <div class="detail-item">
                    <div class="detail-item-left">
                        <div class="detail-item-icon">
                            <i class="fa-solid fa-shuttle-van"></i>
                        </div>
                        <div class="detail-item-text">
                            <h4>{{ $registration->transport->name }}</h4>
                            <p>Fasilitas Penjemputan Penumpang</p>
                        </div>
                    </div>
                    <div class="detail-item-price">
                        Rp {{ number_format($registration->transport->price, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Metode Pembayaran -->
        <div class="checkout-card">
            <div class="checkout-card-title">
                <i class="fa-solid fa-wallet"></i>
                <h2>Pilih Metode Pembayaran</h2>
            </div>

            <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                @csrf
                <input type="hidden" name="payment_method" id="input-payment-method">

                <div class="payment-methods-grid">
                    <!-- Method 1: Transfer Bank -->
                    <div class="payment-method-btn" onclick="selectPaymentMethod('Transfer Bank')">
                        <i class="fa-solid fa-building-columns"></i>
                        <span>Transfer Bank</span>
                    </div>

                    <!-- Method 2: E-Wallet -->
                    <div class="payment-method-btn" onclick="selectPaymentMethod('E-Wallet')">
                        <i class="fa-solid fa-mobile-screen-button"></i>
                        <span>E-Wallet</span>
                    </div>

                    <!-- Method 3: QRIS -->
                    <div class="payment-method-btn" onclick="selectPaymentMethod('QRIS')">
                        <i class="fa-solid fa-qrcode"></i>
                        <span>QRIS</span>
                    </div>
                </div>

                <!-- Payment Details: Transfer Bank -->
                <div class="payment-details-box" id="details-Transfer-Bank">
                    <div class="payment-instructions">
                        <h4><i class="fa-solid fa-circle-info"></i> Petunjuk Transfer Bank</h4>
                        <p style="font-size: 13px; color:#64748b; line-height: 1.5">
                            Silakan lakukan transfer ke salah satu rekening resmi Brilliant English Course di bawah ini. Harap simpan bukti transaksi Anda.
                        </p>
                        <div class="bank-acc-list">
                            <div class="bank-acc-item">
                                <span>Bank BCA (BEC Official)</span>
                                <strong>125-098-8831</strong>
                            </div>
                            <div class="bank-acc-item">
                                <span>Bank Mandiri (BEC Official)</span>
                                <strong>171-00-0987654-1</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Details: E-Wallet -->
                <div class="payment-details-box" id="details-E-Wallet">
                    <div class="payment-instructions">
                        <h4><i class="fa-solid fa-circle-info"></i> Pembayaran E-Wallet</h4>
                        <p style="font-size: 13px; color:#64748b; line-height: 1.5; margin-bottom:15px">
                            Kami mendukung pembayaran instant via e-wallet terpopuler. Masukkan nomor HP aktif e-wallet Anda.
                        </p>
                        <div style="display: flex; gap:12px; align-items:center">
                            <input type="text" class="form-control" placeholder="Masukkan No HP (Dana/Ovo/Gopay)" style="background:white; border:1px solid #cbd5e1; max-width:300px">
                            <span style="font-size:12px; color:var(--checkout-success); font-weight:700"><i class="fa-solid fa-shield-halved"></i> Aman &amp; Terenkripsi</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Details: QRIS -->
                <div class="payment-details-box" id="details-QRIS">
                    <div class="payment-instructions">
                        <div class="qris-wrapper">
                            <h4><i class="fa-solid fa-qrcode"></i> Scan QRIS BEC</h4>
                            <p style="font-size: 13px; color:#64748b; line-height: 1.5; max-width: 400px; margin-bottom:10px">
                                Pindai kode QRIS di bawah ini dengan aplikasi mobile banking atau e-wallet (GoPay, Dana, OVO, ShopeePay, dll).
                            </p>
                            <div class="qris-code">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://brilliantenglishcourse.com/pay" alt="BEC QRIS">
                            </div>
                            <span style="font-size:11px; font-weight:800; color:#64748b; letter-spacing:0.5px">MERCHANT ID: BEC-OFFICIAL-01</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar Cart Summary -->
    <div>
        <div class="checkout-summary-box">
            <h2 class="summary-title">Ringkasan Pembayaran</h2>

            <div class="summary-row">
                <span style="color:#64748b">Subtotal Program:</span>
                <strong style="color:#0f172a">Rp {{ number_format($registration->course->price, 0, ',', '.') }}</strong>
            </div>

            <div class="summary-row">
                <span style="color:#64748b">Biaya Administrasi:</span>
                <strong style="color:#0f172a">
                    @if($registration->course->admin_tax > 0)
                        Rp {{ number_format($registration->course->admin_tax, 0, ',', '.') }}
                    @else
                        <span style="color:#10b981">Gratis</span>
                    @endif
                </strong>
            </div>

            <div class="summary-row">
                <span style="color:#64748b">Subtotal Penjemputan:</span>
                <strong style="color:#0f172a">Rp {{ number_format($registration->transport->price, 0, ',', '.') }}</strong>
            </div>

            <div class="summary-row">
                <span style="color:#64748b">Pajak &amp; Biaya Layanan:</span>
                <strong style="color:#10b981; font-weight:700">Rp 0 (GRATIS)</strong>
            </div>

            <div class="summary-row total">
                <div style="display:flex; flex-direction:column">
                    <strong style="color:#0f172a; font-weight:800">Total Pembayaran</strong>
                    <small style="color:#94a3b8; font-size:11px; font-weight:600">Terhitung Semua Fasilitas</small>
                </div>
                <h3>Rp {{ number_format($registration->course->price + $registration->course->admin_tax + $registration->transport->price, 0, ',', '.') }}</h3>
            </div>

            <button type="submit" form="checkout-form" class="btn-checkout-pay" id="btn-pay" disabled>
                <i class="fa-solid fa-circle-check" style="font-size: 18px"></i>
                <span>Bayar Sekarang</span>
            </button>
        </div>
    </div>
</div>
@endsection

@section('extra-scripts')
<script>
    function selectPaymentMethod(method) {
        // Set hidden input value
        document.getElementById('input-payment-method').value = method;

        // Toggle Active Button Styles
        document.querySelectorAll('.payment-method-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.querySelector('span').textContent.trim() === method) {
                btn.classList.add('active');
            }
        });

        // Hide all details boxes, then show the active one
        document.querySelectorAll('.payment-details-box').forEach(box => {
            box.classList.remove('active');
        });
        
        // Escape spaces for element selection
        const targetId = 'details-' + method.replace(' ', '-');
        const activeBox = document.getElementById(targetId);
        if (activeBox) {
            activeBox.classList.add('active');
        }

        // Enable Submit Button
        document.getElementById('btn-pay').disabled = false;
    }
</script>
@endsection
