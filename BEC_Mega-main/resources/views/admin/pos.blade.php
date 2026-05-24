@extends('layouts.admission')

@section('page-title', 'Admin POS - Pendaftaran Manual')

@section('content')
<div class="pos-container">
    <div class="pos-main">
        <div class="pos-header">
            <div class="pos-brand">
                <img src="{{ asset('assets/logo_BEC.png') }}" alt="Logo BEC">
                <div>
                    <h1>Admin POS Console</h1>
                    <p>Proses pendaftaran manual untuk siswa</p>
                </div>
            </div>
            <div class="pos-admin-badge">
                <i class="fas fa-user-shield"></i>
                <span>Admin Mode</span>
            </div>
        </div>

        <!-- Student Selection -->
        <div class="pos-section">
            <div class="section-title">
                <i class="fas fa-user-graduate"></i>
                <h2>1. Pilih Siswa</h2>
            </div>
            <div class="select-wrapper">
                <select id="user-selector" class="form-control select2" style="width: 100%">
                    <option value="">-- Cari Nama Siswa --</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}">
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="pos-grid">
            <!-- Program Selection -->
            <div class="pos-section">
                <div class="section-title">
                    <i class="fas fa-graduation-cap"></i>
                    <h2>2. Pilih Program</h2>
                </div>
                <div class="items-grid small">
                    @foreach($courses as $course)
                    <div class="item-card mini" data-type="course" data-id="{{ $course->id }}" data-name="{{ $course->name }}" data-price="{{ $course->price }}">
                        <div class="item-icon mini">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h3>{{ $course->name }}</h3>
                        <div class="item-price mini">Rp {{ number_format($course->price, 0, ',', '.') }}</div>
                        <button class="btn-select mini" onclick="selectItem('course', {{ $course->id }}, '{{ $course->name }}', {{ $course->price }})">
                            Pilih
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Period Selection -->
            <div class="pos-section">
                <div class="section-title">
                    <i class="fas fa-calendar-alt"></i>
                    <h2>3. Pilih Periode</h2>
                </div>
                <div class="items-grid small">
                    @foreach($periods as $period)
                    <div class="item-card mini" data-type="period" data-id="{{ $period->id }}" data-name="{{ $period->name }}">
                        <div class="item-icon mini">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3>{{ $period->name }}</h3>
                        <button class="btn-select mini" onclick="selectItem('period', {{ $period->id }}, '{{ $period->name }}', 0)">
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
                    <h2>4. Pilih Penjemputan</h2>
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
                <i class="fas fa-file-invoice-dollar"></i>
                <h2>Admin Checkout</h2>
            </div>
            
            <div class="cart-content">
                <div class="cart-item-group" id="cart-user">
                    <span class="group-label">Siswa:</span>
                    <div class="cart-item empty">Belum ada siswa terpilih</div>
                </div>

                <div class="cart-item-group" id="cart-course">
                    <span class="group-label">Program:</span>
                    <div class="cart-item empty">Belum ada program</div>
                </div>

                <div class="cart-item-group" id="cart-period">
                    <span class="group-label">Periode:</span>
                    <div class="cart-item empty">Belum ada periode</div>
                </div>

                <div class="cart-item-group" id="cart-transport">
                    <span class="group-label">Transport:</span>
                    <div class="cart-item empty">Tanpa penjemputan</div>
                </div>
            </div>

            <div class="cart-footer">
                <div class="total-row">
                    <span>Grand Total</span>
                    <h2 id="total-price">Rp 0</h2>
                </div>
                
                <form action="{{ route('admin.pos.process') }}" method="POST" id="pos-form">
                    @csrf
                    <input type="hidden" name="user_id" id="input-user">
                    <input type="hidden" name="course_id" id="input-course">
                    <input type="hidden" name="period_id" id="input-period">
                    <input type="hidden" name="transport_id" id="input-transport">
                    
                    <button type="submit" class="btn-checkout" id="btn-checkout" disabled>
                        <span>Selesaikan Pendaftaran</span>
                        <i class="fas fa-check-circle"></i>
                    </button>
                </form>

                <p class="cart-note">
                    <i class="fas fa-exclamation-triangle"></i>
                    Pendaftaran via POS Admin akan otomatis berstatus <strong>Verified</strong> dan <strong>Paid</strong>.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-styles')
<style>
    :root {
        --pos-primary: #003399;
        --pos-secondary: #00c6ff;
        --pos-bg: #f4f7f6;
        --pos-white: #ffffff;
        --pos-text: #1e293b;
        --pos-muted: #64748b;
        --pos-border: #e2e8f0;
    }

    .pos-container {
        display: flex;
        gap: 30px;
        padding: 20px;
    }

    .pos-main { flex: 1; }
    .pos-sidebar { width: 400px; position: sticky; top: 20px; height: fit-content; }

    .pos-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        padding: 20px 30px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }

    .pos-brand { display: flex; align-items: center; gap: 15px; }
    .pos-brand img { height: 50px; }
    .pos-brand h1 { font-size: 20px; font-weight: 800; margin: 0; color: var(--pos-primary); }
    .pos-brand p { margin: 0; font-size: 13px; color: var(--pos-muted); }

    .pos-admin-badge {
        background: #fee2e2;
        color: #b91c1c;
        padding: 8px 15px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .select-wrapper {
        background: white;
        padding: 15px;
        border-radius: 15px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .pos-section { margin-bottom: 30px; }
    .section-title { display: flex; align-items: center; gap: 10px; margin-bottom: 15px; }
    .section-title h2 { font-size: 18px; font-weight: 700; margin: 0; }
    .section-title i { color: var(--pos-primary); }

    .items-grid.small {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 15px;
    }

    .item-card.mini {
        background: white;
        border: 2px solid transparent;
        padding: 20px 15px;
        border-radius: 15px;
        text-align: center;
        transition: all 0.2s;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .item-card.mini:hover { border-color: var(--pos-secondary); transform: translateY(-3px); }
    .item-card.mini.active { border-color: var(--pos-primary); background: #f0f7ff; }

    .item-icon.mini {
        width: 40px;
        height: 40px;
        background: #f1f5f9;
        color: var(--pos-primary);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
    }

    .item-card h3 { font-size: 14px; font-weight: 700; margin: 0 0 8px; }
    .item-price.mini { font-size: 15px; font-weight: 800; color: var(--pos-primary); margin-bottom: 12px; }

    .btn-select.mini {
        background: #f1f5f9;
        border: none;
        border-radius: 8px;
        padding: 7px;
        width: 100%;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .item-card.active .btn-select.mini { background: var(--pos-primary); color: white; }

    /* Cart */
    .cart-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .cart-header { display: flex; align-items: center; gap: 12px; margin-bottom: 25px; }
    .cart-header h2 { font-size: 20px; font-weight: 800; margin: 0; }
    .cart-header i { color: var(--pos-primary); font-size: 22px; }

    .cart-item-group { margin-bottom: 15px; }
    .group-label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--pos-muted); }
    .cart-item {
        background: #f8fafc;
        padding: 10px 15px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        margin-top: 5px;
    }
    .cart-item.empty { color: #94a3b8; font-weight: 500; border: 1px dashed #e2e8f0; }

    .total-row { display: flex; justify-content: space-between; align-items: center; margin: 25px 0; }
    .total-row h2 { font-size: 24px; font-weight: 900; color: var(--pos-primary); margin: 0; }

    .btn-checkout {
        width: 100%;
        padding: 15px;
        background: var(--pos-primary);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.3s;
    }
    .btn-checkout:hover:not(:disabled) { background: #002d8a; }
    .btn-checkout:disabled { background: #cbd5e1; cursor: not-allowed; }

    .cart-note { font-size: 11px; color: var(--pos-muted); margin-top: 15px; text-align: center; }
</style>
@endsection

@section('extra-scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    let cart = { user: null, course: null, period: null, transport: null };

    // Select2 student search
    $(document).ready(function() {
        if (typeof $.fn.select2 !== 'undefined') {
            $('#user-selector').select2({
                placeholder: "-- Cari Nama Siswa --",
                allowClear: true
            });
        }

        $('#user-selector').on('change', function() {
            const val = $(this).val();
            if (val) {
                const opt = $(this).find(':selected');
                cart.user = { id: val, name: opt.data('name'), email: opt.data('email') };
                
                const itemEl = document.querySelector('#cart-user .cart-item');
                itemEl.classList.remove('empty');
                itemEl.innerHTML = `<span>${cart.user.name}</span><br><small style="color:var(--pos-muted)">${cart.user.email}</small>`;
                document.getElementById('input-user').value = val;
            } else {
                cart.user = null;
                document.querySelector('#cart-user .cart-item').classList.add('empty');
                document.querySelector('#cart-user .cart-item').textContent = 'Belum ada siswa terpilih';
                document.getElementById('input-user').value = '';
            }
            checkValidation();
        });
    });

    function selectItem(type, id, name, price) {
        cart[type] = { id, name, price };
        document.querySelectorAll(`.item-card[data-type="${type}"]`).forEach(card => {
            card.classList.remove('active');
            if (parseInt(card.dataset.id) === id) card.classList.add('active');
        });
        const groupEl = document.getElementById(`cart-${type}`);
        const itemEl = groupEl.querySelector('.cart-item');
        itemEl.classList.remove('empty');
        let priceText = price > 0 ? ` <span style="float:right">Rp ${price.toLocaleString('id-ID')}</span>` : '';
        itemEl.innerHTML = `<span>${name}</span>${priceText}`;
        document.getElementById(`input-${type}`).value = id;
        calculateTotal();
        checkValidation();
    }

    function calculateTotal() {
        let total = 0;
        if (cart.course) total += cart.course.price;
        if (cart.transport) total += cart.transport.price;
        document.getElementById('total-price').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    }

    function checkValidation() {
        const btn = document.getElementById('btn-checkout');
        if (cart.user && cart.course && cart.period && cart.transport) {
            btn.disabled = false;
        } else {
            btn.disabled = true;
        }
    }
</script>
@endsection
