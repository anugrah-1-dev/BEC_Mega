@extends('layouts.admission')
@section('page-title', 'Pilih Kursus — Langkah 2 dari 4')

@section('extra-styles')
<style>
    .course-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-top: 12px; }
    .course-card {
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid #e2e8f0;
        background: white;
        display: flex;
        flex-direction: column;
        cursor: pointer;
        transition: all 0.25s;
        position: relative;
    }
    .course-card:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(0,0,0,0.15); border-color: #363d72; }
    .course-card.selected {
        border-color: #363d72;
        box-shadow: 0 0 0 4px rgba(54, 61, 114, 0.2);
    }
    
    .pc-top { background: #363d72; color: white; padding: 25px; transition: background 0.3s; position: relative; }
    .pc-kode { font-size: 0.85rem; font-weight: 600; margin-bottom: 5px; opacity: 0.9; }
    .pc-price { font-size: 2rem; font-weight: 800; margin: 0 0 10px 0; color: white; }
    .pc-name { font-size: 0.9rem; font-weight: 700; text-transform: uppercase; margin-bottom: 5px; }
    .pc-desc { font-size: 0.9rem; opacity: 0.9; margin-bottom: 8px;}
    .pc-admin { font-size: 0.85rem; font-weight: 600; }
    
    .pc-bottom { padding: 25px; background: white; flex: 1; border-top: 1px solid #e2e8f0; }
    .pc-feature { display: flex; align-items: flex-start; gap: 12px; font-size: 0.85rem; color: #475569; margin-bottom: 15px; line-height: 1.4; font-weight: 600; }
    .pc-check { background: #10b981; color: white; border-radius: 50%; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; font-size: 10px; flex-shrink: 0; margin-top: 2px; }

    .course-card input[type=radio] { position: absolute; top: 20px; right: 20px; accent-color: #363d72; width:18px; height:18px; z-index: 10; }

    .period-select-wrap {
        background: #f8fafc; border-radius: 14px; padding: 24px; margin-top: 28px;
        border: 1.5px solid #e2e8f0;
    }
</style>
@endsection

@section('content')
<div style="max-width: 860px; margin: 0 auto;">

    {{-- Step indicator --}}
    <div class="card" style="margin-bottom: 24px; padding: 24px 32px;">
        <div style="display:flex; align-items:center; justify-content:space-between; gap: 8px;">
            @foreach([
                ['n'=>1,'l'=>'Data Diri','active'=>false,'done'=>true],
                ['n'=>2,'l'=>'Pilih Kursus','active'=>true,'done'=>false],
                ['n'=>3,'l'=>'Pembayaran','active'=>false,'done'=>false],
                ['n'=>4,'l'=>'Status','active'=>false,'done'=>false],
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
        <div style="margin-bottom: 28px;">
            <h1 style="font-size:1.4rem; font-weight:900; color:#0f172a; margin-bottom:6px;">📚 Pilih Program Kursus &amp; Periode</h1>
            <p style="font-size:0.875rem; color:#64748b; line-height:1.6;">Pilih program kursus yang sesuai dengan kebutuhan belajar Anda. Perhatikan harga dan deskripsi masing-masing program.</p>
        </div>

        <form action="{{ route('pilih_course.store') }}" method="POST" id="course-form">
            @csrf

            {{-- Language filter tabs --}}
            @php
                $languages = $courses->pluck('language')->unique()->sort()->values();
                $langColors = ['Inggris'=>'#2563eb','Jerman'=>'#16a34a','Mandarin'=>'#dc2626','Arab'=>'#d97706'];
            @endphp
            @if($languages->count() > 1)
            <div style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:20px;">
                <button type="button" onclick="filterLanguage('all')" id="tab-all"
                    style="padding:7px 18px; border-radius:20px; border:1.5px solid #363d72; background:#363d72; color:white; font-size:0.8rem; font-weight:700; cursor:pointer;">
                    Semua
                </button>
                @foreach($languages as $lang)
                <button type="button" onclick="filterLanguage('{{ $lang }}')" id="tab-{{ Str::slug($lang) }}"
                    style="padding:7px 18px; border-radius:20px; border:1.5px solid {{ $langColors[$lang] ?? '#363d72' }}; background:white; color:{{ $langColors[$lang] ?? '#363d72' }}; font-size:0.8rem; font-weight:700; cursor:pointer;">
                    {{ $lang }}
                </button>
                @endforeach
            </div>
            @endif

            {{-- Courses --}}
            <div style="font-size:0.78rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.07em; margin-bottom:12px;">Program Kursus Tersedia</div>

            @if($courses->isEmpty())
                <div style="padding:40px; text-align:center; background:#f8fafc; border-radius:14px; color:#94a3b8;">
                    <svg style="width:40px;height:40px;margin-bottom:12px;opacity:0.4" viewBox="0 0 24 24"><path fill="currentColor" d="M19,2L14,6.5V17.5L19,13V2M6.5,5C4.55,5 2.45,5.4 1,6.5V21.16C1,21.41 1.25,21.66 1.5,21.66C1.6,21.66 1.65,21.59 1.75,21.59C3.1,20.94 5.05,20.5 6.5,20.5C8.45,20.5 10.55,20.9 12,22C13.35,21.15 15.8,20.5 17.5,20.5C19.15,20.5 20.85,20.81 22.25,21.56C22.35,21.61 22.4,21.59 22.5,21.59C22.75,21.59 23,21.34 23,21.09V6.5C22.4,6.05 21.75,5.75 21,5.5V19C19.9,18.65 18.7,18.5 17.5,18.5C15.8,18.5 13.35,19.15 12,20V6.5C10.55,5.4 8.45,5 6.5,5Z"/></svg>
                    <div style="font-weight:700; font-size:0.9rem;">Belum ada kursus tersedia</div>
                </div>
            @else
                <div class="course-grid">
                    @foreach($courses as $course)
                    <div class="course-card" id="card-{{ $course->id }}"
                         data-language="{{ $course->language }}"
                         onclick="selectCourse({{ $course->id }})">
                        <input type="radio" name="course_id" value="{{ $course->id }}" id="course-{{ $course->id }}" required>
                        <div class="pc-top">
                            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:6px;">
                                <div class="pc-kode">Kode : {{ substr(md5($course->id), 0, 9) }} ⓘ</div>
                                <span style="background:rgba(255,255,255,0.25); color:white; font-size:0.7rem; font-weight:800; padding:3px 10px; border-radius:12px; letter-spacing:0.04em;">
                                    {{ strtoupper($course->language ?? 'Inggris') }}
                                </span>
                            </div>
                            <h3 class="pc-price">Rp {{ number_format($course->price, 0, ',', '.') }}</h3>
                            <div class="pc-name">{{ strtoupper($course->name) }}</div>
                            <div class="pc-desc" style="display: flex; gap: 8px; font-size: 0.8rem; margin-bottom: 8px; opacity: 0.95;">
                                <span style="background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 4px;">{{ strtoupper($course->type) }}</span>
                                <span style="background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 4px;">{{ $course->duration ?? '30 Hari' }}</span>
                            </div>
                            <div class="pc-admin">Admin : {{ $course->admin_tax > 0 ? 'Rp ' . number_format($course->admin_tax, 0, ',', '.') : 'Gratis' }}</div>
                        </div>
                        <div class="pc-bottom">
                            @if(isset($features[$course->id]) && count($features[$course->id]) > 0)
                                @foreach($features[$course->id] as $f)
                                <div class="pc-feature"><span class="pc-check">✔</span> {{ $f->feature }}</div>
                                @endforeach
                            @else
                                <div class="pc-feature"><span class="pc-check">✔</span> Free Voucher Brilliant Health Care</div>
                                <div class="pc-feature"><span class="pc-check">✔</span> Tempat Tinggal / Camp</div>
                                <div class="pc-feature"><span class="pc-check">✔</span> Modul, Competence & Gelang</div>
                                <div class="pc-feature"><span class="pc-check">✔</span> Sertifikat</div>
                                <div class="pc-feature"><span class="pc-check">✔</span> Bonus Materi Psychotraining & Enterpreneurship</div>
                                <div class="pc-feature"><span class="pc-check">✔</span> Pendidikan Budi Pekerti Luhur Etika Sopan Santun Budaya Jawa</div>
                                <div class="pc-feature"><span class="pc-check">✔</span> Program 6 Kelas/Hari X 75 Menit</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

            {{-- Period --}}
            <div class="period-select-wrap">
                <div style="font-size:0.78rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.07em; margin-bottom:14px;">Periode Belajar</div>

                @if($periods->isEmpty())
                    <div style="color:#94a3b8; font-size:0.875rem;">Belum ada periode tersedia.</div>
                @else
                    <select name="period_id" class="form-control" required style="max-width:400px;">
                        <option value="">— Pilih Periode —</option>
                        @foreach($periods as $period)
                        <option value="{{ $period->id }}">
                            {{ $period->name }} · Mulai {{ \Carbon\Carbon::parse($period->start_date)->format('d M Y') }}
                        </option>
                        @endforeach
                    </select>
                @endif
            </div>

            {{-- Tambahan Layanan --}}
            <div class="period-select-wrap" style="margin-top: 15px;">
                <div style="font-size:0.78rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.07em; margin-bottom:14px;">
                    Tambahan Layanan <span style="font-weight:400; text-transform:none; font-size:0.75rem; color:#94a3b8;">(Opsional)</span>
                </div>
                <div style="display:flex; flex-wrap:wrap; gap:12px;">
                    @foreach([
                        ['has_catering', 'Catering', '🍽️'],
                        ['has_laundry',  'Laundry',  '👕'],
                        ['has_holiday',  'Holiday',  '🏖️'],
                    ] as [$field, $label, $icon])
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;
                        padding:14px 22px; border:1.5px solid #e2e8f0; border-radius:10px;
                        background:#fafafa; transition:all 0.2s;"
                        onclick="this.style.borderColor=this.querySelector('input').checked?'#e2e8f0':'#363d72';
                                 this.style.background=this.querySelector('input').checked?'#fafafa':'rgba(54,61,114,0.06)';">
                        <input type="checkbox" name="{{ $field }}" value="1"
                               style="accent-color:#363d72; width:16px; height:16px;"
                               onchange="calculateTotal()">
                        <span style="font-size:0.875rem; font-weight:600; color:#0f172a;">{{ $icon }} {{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Transport --}}
            <div class="period-select-wrap" style="margin-top: 15px;">
                <div style="font-size:0.78rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.07em; margin-bottom:14px;">Layanan Penjemputan / Transportasi</div>

                @if($transports->isEmpty())
                    <div style="color:#94a3b8; font-size:0.875rem;">Layanan transportasi tidak tersedia.</div>
                @else
                    <select name="transport_id" class="form-control" required style="max-width:400px;">
                        <option value="">— Pilih Layanan Transport —</option>
                        @foreach($transports as $transport)
                        <option value="{{ $transport->id }}">
                            {{ $transport->name }} (Rp {{ number_format($transport->price, 0, ',', '.') }})
                        </option>
                        @endforeach
                    </select>
                @endif
            </div>

            <div id="total-display" style="margin-top: 20px; padding: 20px; background: #e0e7ff; border: 1.5px solid #c7d2fe; border-radius: 14px; display: flex; justify-content: space-between; align-items: center; display: none;">
                <div style="font-weight: 800; color: #3730a3; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.85rem;">Total Biaya</div>
                <div id="total-amount" style="font-size: 1.5rem; font-weight: 900; color: #4f46e5;">Rp 0</div>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:32px; padding-top:24px; border-top:1px solid #f1f5f9;">
                <a href="{{ route('dashboard') }}" class="btn btn-outline">
                    <svg style="width:16px;height:16px" viewBox="0 0 24 24"><path fill="currentColor" d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z"/></svg>
                    Kembali
                </a>
                <button type="submit" class="btn btn-primary btn-lg"
                        {{ ($courses->isEmpty() || $periods->isEmpty()) ? 'disabled' : '' }}>
                    Simpan &amp; Lanjutkan
                    <svg style="width:18px;height:18px" viewBox="0 0 24 24"><path fill="currentColor" d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z"/></svg>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const coursesData = @json($courses->keyBy('id'));
const transportPrices = @json($transports->pluck('price', 'id'));

function calculateTotal() {
    let total = 0;
    const courseSelected = document.querySelector('input[name="course_id"]:checked');
    if (courseSelected && coursesData[courseSelected.value]) {
        const selectedCourse = coursesData[courseSelected.value];
        total += parseFloat(selectedCourse.price);
        total += parseFloat(selectedCourse.admin_tax || 0);
    }

    const transportSelect = document.querySelector('select[name="transport_id"]');
    if (transportSelect) {
        const transportSelected = transportSelect.value;
        if (transportSelected && transportPrices[transportSelected]) {
            total += parseFloat(transportPrices[transportSelected]);
        }
    }

    if (courseSelected) {
        document.getElementById('total-display').style.display = 'flex';
        document.getElementById('total-amount').innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(total);
    }
}

function selectCourse(id) {
    document.querySelectorAll('.course-card').forEach(c => c.classList.remove('selected'));
    document.getElementById('card-' + id).classList.add('selected');
    document.getElementById('course-' + id).checked = true;
    calculateTotal();
}

document.addEventListener('DOMContentLoaded', function() {
    const transportSelect = document.querySelector('select[name="transport_id"]');
    if (transportSelect) {
        transportSelect.addEventListener('change', calculateTotal);
    }
    // Initialize if returning back
    if (document.querySelector('input[name="course_id"]:checked')) {
        calculateTotal();
    }
});

function filterLanguage(lang) {
    document.querySelectorAll('.course-card').forEach(card => {
        card.style.display = (lang === 'all' || card.dataset.language === lang) ? '' : 'none';
    });
    // Update active tab style
    document.querySelectorAll('[id^="tab-"]').forEach(btn => {
        const isActive = btn.id === 'tab-' + (lang === 'all' ? 'all' : lang.toLowerCase().replace(/ /g, '-'));
        btn.style.background = isActive ? '#363d72' : 'white';
        btn.style.color      = isActive ? 'white'   : btn.style.borderColor;
    });
}
</script>
@endsection
