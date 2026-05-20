<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Kursus - Brilliant English Course</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #1e1b4b; /* Navy */
            --primary-glow: rgba(79, 70, 229, 0.1);
            --accent: #4f46e5; /* Indigo */
            --accent-light: #f5f3ff;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --bg-page: #f8fafc; /* Premium off-white page background */
            --radius-xl: 24px;
            --radius-lg: 16px;
            --radius-md: 12px;
            --shadow-premium: 0 20px 50px rgba(15, 23, 42, 0.06);
        }

        *, *::before, *::after {
            margin: 0; padding: 0; box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-page);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            overflow-x: hidden;
        }

        /* Floating background elements removed for clean light aesthetic */
        .decor-blob {
            display: none;
        }

        .container {
            width: 100%;
            max-width: 1250px;
            z-index: 1;
            position: relative;
        }

        .pos-card {
            background: #ffffff; /* Inside box changed to pure white */
            border: 1px solid #e2e8f0;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-premium);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1.6fr 1fr;
        }

        .main-form-section {
            padding: 45px;
            background: #ffffff; /* White background inside main form box */
            border-right: 1px solid #e2e8f0;
        }

        .sidebar-summary-section {
            padding: 45px;
            background: #f8fafc; /* Receipt-style light background side panel */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Header styling */
        .header-brand {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 35px;
        }
        .header-brand img {
            height: 55px;
            width: auto;
        }
        .header-brand h1 {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--text-dark); /* Dark brand header */
        }
        .header-brand p {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .step-indicator {
            display: flex;
            gap: 12px;
            margin-bottom: 30px;
        }
        .step-pill {
            padding: 6px 14px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .step-pill.active {
            background: var(--accent);
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }
        .step-pill.next {
            background: #f1f5f9;
            color: var(--text-muted);
            border: 1px solid #e2e8f0;
        }

        /* Form Controls */
        .form-section-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-dark); /* Dark section titles */
        }
        .form-section-title i {
            color: var(--accent);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 35px;
        }
        .form-group.full {
            grid-column: span 2;
        }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #475569; /* Slate gray labels */
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .form-control {
            width: 100%;
            padding: 14px 18px;
            background: #ffffff;
            border: 2px solid #e2e8f0;
            border-radius: var(--radius-md);
            color: var(--text-dark); /* Dark text input */
            font-size: 14.5px;
            font-family: inherit;
            transition: all 0.25s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }
        .form-control::placeholder {
            color: #94a3b8;
        }

        /* Course Selector Grid — KECUALI KOTAK PILIH KURSUS TETAP BIRU! */
        .course-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 18px;
            margin-bottom: 35px;
        }
        .course-card {
            background: #1e1b4b; /* Navy Blue background */
            border: 2px solid rgba(255, 255, 255, 0.08);
            border-radius: var(--radius-lg);
            padding: 24px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .course-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 255, 255, 0.25);
            background: #15123d; /* Darken slightly on hover */
        }
        .course-card.active {
            border-color: #3b82f6; /* Vivid blue active border */
            background: #0f0d2b;
            box-shadow: 0 8px 30px rgba(59, 130, 246, 0.3);
        }
        .course-card-top h3 {
            font-size: 16px;
            font-weight: 700;
            color: #ffffff; /* White title inside course card */
            margin-bottom: 6px;
        }
        .course-card-top p {
            font-size: 12.5px;
            color: #cbd5e1; /* Muted light text */
            line-height: 1.4;
            margin-bottom: 15px;
        }
        .course-price {
            font-size: 18px;
            font-weight: 800;
            color: #10b981; /* Green price badge */
        }
        .selected-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--accent);
            color: white;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            opacity: 0;
            transform: scale(0.6);
            transition: 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .course-card.active .selected-badge {
            opacity: 1;
            transform: scale(1);
        }

        /* Mini Grids for Period & Transport (Light Theme) */
        .mini-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: 35px;
        }
        .mini-card {
            background: #ffffff;
            border: 2px solid #e2e8f0;
            border-radius: var(--radius-md);
            padding: 16px 20px;
            cursor: pointer;
            text-align: center;
            transition: all 0.25s;
        }
        .mini-card:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }
        .mini-card.active {
            border-color: var(--accent);
            background: rgba(79, 70, 229, 0.05);
        }
        .mini-card h4 {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }
        .mini-card p {
            font-size: 12px;
            color: var(--text-muted);
        }

        /* Sidebar cart summary styles (Light Theme) */
        .summary-title {
            font-size: 20px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 20px;
            color: var(--text-dark);
        }
        .summary-title i {
            color: var(--accent);
        }
        
        .summary-items {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .summary-item-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            font-size: 14px;
        }
        .summary-item-row .lbl {
            color: var(--text-muted);
            font-weight: 500;
        }
        .summary-item-row .val {
            color: var(--text-dark);
            font-weight: 700;
            text-align: right;
            max-width: 60%;
        }
        .summary-item-row .val.placeholder {
            color: #94a3b8;
            font-weight: 400;
            font-style: italic;
        }

        .grand-total-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: var(--radius-lg);
            padding: 24px;
            text-align: center;
            margin-top: 35px;
            margin-bottom: 35px;
        }
        .grand-total-box p {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .grand-total-box h2 {
            font-size: 32px;
            font-weight: 900;
            color: #10b981;
        }

        .btn-submit-main {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, var(--accent) 0%, #3b82f6 100%);
            color: white;
            border: none;
            border-radius: var(--radius-lg);
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.2);
        }
        .btn-submit-main:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(79, 70, 229, 0.35);
            filter: brightness(1.1);
        }
        .btn-submit-main:disabled {
            background: #cbd5e1;
            color: #94a3b8;
            cursor: not-allowed;
            box-shadow: none;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: var(--text-muted);
            font-size: 13.5px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s;
        }
        .back-link a:hover {
            color: var(--text-dark);
        }

        @media (max-width: 1024px) {
            .pos-card {
                grid-template-columns: 1fr;
            }
            .main-form-section {
                border-right: none;
                border-bottom: 1px solid #e2e8f0;
            }
        }
        @media (max-width: 600px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .form-group.full {
                grid-column: span 1;
            }
            body {
                padding: 15px;
            }
        .type-badge {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 99px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            letter-spacing: 0.5px;
        }
        .type-badge.online {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
        }
        .type-badge.offline {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
        }
        .duration-badge {
            font-size: 10px;
            font-weight: 800;
            background: rgba(255, 255, 255, 0.1);
            color: #cbd5e1;
            padding: 4px 10px;
            border-radius: 99px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>
    <div class="decor-blob blob-1"></div>
    <div class="decor-blob blob-2"></div>

    <div class="container">
        <form action="{{ route('register.pos.process') }}" method="POST" id="register-pos-form">
            @csrf
            
            <div class="pos-card">
                <!-- Main Form Area -->
                <div class="main-form-section">
                    <div class="header-brand">
                        <img src="{{ asset('assets/logo_BEC.png') }}" alt="Logo BEC">
                        <div>
                            <h1>Brilliant English Course</h1>
                            <p>Sistem Point of Sale (POS) Pendaftaran</p>
                        </div>
                    </div>

                    <div class="step-indicator">
                        <span class="step-pill active">① Pengisian Data &amp; Program</span>
                        <span class="step-pill next">② Ringkasan &amp; Checkout</span>
                        <span class="step-pill next">③ Selesai (Invoice)</span>
                    </div>

                    @if ($errors->any())
                        <div style="background: rgba(239, 68, 68, 0.1); border: 1.5px solid rgba(239, 68, 68, 0.3); color: #fca5a5; padding: 16px 20px; border-radius: var(--radius-md); margin-bottom: 25px; font-size: 14px;">
                            @foreach ($errors->all() as $error)
                                <div>• {{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Step 1: Personal Data -->
                    <div class="form-section-title">
                        <i class="fa-solid fa-user-astronaut"></i>
                        <h2>1. Lengkapi Data Diri Anda</h2>
                    </div>

                    <div class="form-grid">
                        <div class="form-group full">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso" value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Alamat Email</label>
                            <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nomor Telepon / WhatsApp</label>
                            <input type="text" name="phone" class="form-control" placeholder="0812xxxxxxxx" value="{{ old('phone') }}" required>
                        </div>
                    </div>

                    <!-- Step 2: Choose Course Program -->
                    <div class="form-section-title">
                        <i class="fa-solid fa-graduation-cap"></i>
                        <h2>2. Pilih Program Kursus</h2>
                    </div>

                    <div class="course-grid">
                        @foreach($courses as $course)
                            <div class="course-card" data-id="{{ $course->id }}" data-name="{{ $course->name }}" data-price="{{ $course->price }}" data-admin-tax="{{ $course->admin_tax ?? 0 }}" onclick="selectCourse(this, {{ $course->id }}, '{{ $course->name }}', {{ $course->price }}, {{ $course->admin_tax ?? 0 }})">
                                <div class="selected-badge">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <div class="course-card-top">
                                    <div style="display:flex; gap:6px; margin-bottom:12px; flex-wrap: wrap;">
                                        <span class="type-badge {{ $course->type }}">
                                            <i class="fa-solid {{ $course->type === 'online' ? 'fa-globe' : 'fa-house-laptop' }}"></i>
                                            {{ strtoupper($course->type) }}
                                        </span>
                                        @if($course->duration)
                                            <span class="duration-badge">
                                                <i class="fa-solid fa-clock"></i>
                                                {{ $course->duration }}
                                            </span>
                                        @endif
                                    </div>
                                    <h3>{{ $course->name }}</h3>
                                    <p>{{ Str::limit($course->description ?? 'Program kursus intensif bahasa Inggris berkualitas tinggi di BEC Kampung Inggris Pare.', 90) }}</p>
                                </div>
                                <div style="margin-top: auto;">
                                    <div class="course-price">
                                        Rp {{ number_format($course->price, 0, ',', '.') }}
                                    </div>
                                    <div style="margin-top: 15px; border-top: 1px dashed rgba(255,255,255,0.15); padding-top: 12px; display: flex; justify-content: space-between; align-items: center;">
                                        <span style="font-size: 11px; color: #cbd5e1; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Biaya Admin:</span>
                                        <span style="font-size: 12.5px; color: #ffffff; font-weight: 700;">
                                            {{ $course->admin_tax > 0 ? 'Rp ' . number_format($course->admin_tax, 0, ',', '.') : 'Gratis' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Step 3: Choose Period -->
                    <div class="form-section-title">
                        <i class="fa-solid fa-calendar-days"></i>
                        <h2>3. Pilih Periode Belajar</h2>
                    </div>

                    <div class="mini-grid">
                        @foreach($periods as $period)
                            @php
                                $periodName = $period->name ?: 'Periode ' . \Carbon\Carbon::parse($period->date)->format('d M Y');
                            @endphp
                            <div class="mini-card" data-type="period" data-id="{{ $period->id }}" data-name="{{ $periodName }}" onclick="selectMini(this, 'period', {{ $period->id }}, '{{ $periodName }}', 0)">
                                <h4>{{ $periodName }}</h4>
                                <p>{{ \Carbon\Carbon::parse($period->date)->format('d M Y') }}</p>
                            </div>
                        @endforeach
                    </div>

                    <!-- Step 4: Choose Transport Option -->
                    <div class="form-section-title">
                        <i class="fa-solid fa-plane-arrival"></i>
                        <h2>4. Tambahan Penjemputan</h2>
                    </div>

                    <div class="mini-grid">
                        @foreach($transports as $transport)
                            <div class="mini-card" data-type="transport" data-id="{{ $transport->id }}" data-name="{{ $transport->name }}" data-price="{{ $transport->price }}" onclick="selectMini(this, 'transport', {{ $transport->id }}, '{{ $transport->name }}', {{ $transport->price }})">
                                <h4>{{ $transport->name }}</h4>
                                <p>Rp {{ number_format($transport->price, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Hidden Input fields -->
                <input type="hidden" name="course_id" id="input-course">
                <input type="hidden" name="period_id" id="input-period">
                <input type="hidden" name="transport_id" id="input-transport">

                <!-- Sidebar Cart Summary Area -->
                <div class="sidebar-summary-section">
                    <div>
                        <div class="summary-title">
                            <i class="fa-solid fa-receipt"></i>
                            <h2>Ringkasan Pendaftaran</h2>
                        </div>

                        <div class="summary-items">
                            <div class="summary-item-row" id="row-course">
                                <span class="lbl">Program Kursus:</span>
                                <span class="val placeholder">Belum dipilih</span>
                            </div>
                            <div class="summary-item-row" id="row-tax">
                                <span class="lbl">Biaya Pendaftaran (Admin):</span>
                                <span class="val placeholder">Belum dipilih</span>
                            </div>
                            <div class="summary-item-row" id="row-period">
                                <span class="lbl">Periode Belajar:</span>
                                <span class="val placeholder">Belum dipilih</span>
                            </div>
                            <div class="summary-item-row" id="row-transport">
                                <span class="lbl">Fasilitas Penjemputan:</span>
                                <span class="val placeholder">Belum dipilih</span>
                            </div>
                        </div>

                        <div class="grand-total-box">
                            <p>Total Pendaftaran</p>
                            <h2 id="grand-total">Rp 0</h2>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="btn-submit-main" id="btn-submit" disabled>
                            <span>Lanjut Ke Pembayaran</span>
                            <i class="fa-solid fa-arrow-right-long"></i>
                        </button>
                        
                        <div class="back-link">
                            <a href="{{ route('home') }}"><i class="fa-solid fa-circle-chevron-left" style="margin-right:6px"></i> Kembali ke Virtual Tour</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        let selection = {
            course: null,
            period: null,
            transport: null
        };

        function selectCourse(element, id, name, price, adminTax) {
            selection.course = { id, name, price, adminTax };
            
            // Update UI
            document.querySelectorAll('.course-card').forEach(card => {
                card.classList.remove('active');
            });
            element.classList.add('active');

            // Set hidden input
            document.getElementById('input-course').value = id;

            // Update Summary
            const el = document.getElementById('row-course').querySelector('.val');
            el.classList.remove('placeholder');
            el.innerHTML = name + `<br><small style="color:#10b981; font-weight:600">Rp ${price.toLocaleString('id-ID')}</small>`;

            // Update Admin Tax Row
            const taxEl = document.getElementById('row-tax').querySelector('.val');
            taxEl.classList.remove('placeholder');
            if (adminTax > 0) {
                taxEl.innerHTML = `Rp ${adminTax.toLocaleString('id-ID')}`;
            } else {
                taxEl.innerHTML = `<span style="color:#10b981; font-weight:600">Gratis</span>`;
            }

            updateTotal();
            validateForm();
        }

        function selectMini(element, type, id, name, price) {
            selection[type] = { id, name, price };

            // Update UI
            document.querySelectorAll(`.mini-card[data-type="${type}"]`).forEach(card => {
                card.classList.remove('active');
            });
            element.classList.add('active');

            // Set hidden input
            document.getElementById(`input-${type}`).value = id;

            // Update Summary
            const el = document.getElementById(`row-${type}`).querySelector('.val');
            el.classList.remove('placeholder');
            if (price > 0) {
                el.innerHTML = name + `<br><small style="color:#10b981; font-weight:600">Rp ${price.toLocaleString('id-ID')}</small>`;
            } else {
                el.innerHTML = name;
            }

            updateTotal();
            validateForm();
        }

        function updateTotal() {
            let total = 0;
            if (selection.course) {
                total += selection.course.price;
                total += selection.course.adminTax;
            }
            if (selection.transport) total += selection.transport.price;

            document.getElementById('grand-total').textContent = `Rp ${total.toLocaleString('id-ID')}`;
        }

        function validateForm() {
            const submitBtn = document.getElementById('btn-submit');
            if (selection.course && selection.period && selection.transport) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }
    </script>
</body>
</html>
