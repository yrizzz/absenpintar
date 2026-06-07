<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Slip Absen - {{ $log->user->name ?? 'Karyawan' }}</title>
    <style>
        @page { size: A5; margin: 15mm 18mm 15mm 18mm; }
        body {
            font-family: 'Arial', sans-serif;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            font-size: 10pt;
            line-height: 1.6;
        }

        .kop-surat {
            text-align: center;
            border-bottom: 2px solid #1a1a1a;
            padding-bottom: 8px;
            margin-bottom: 6px;
        }
        .kop-surat h1 {
            margin: 0 0 2px;
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .kop-surat p {
            margin: 1px 0;
            font-size: 8pt;
            color: #444;
        }
        .kop-line-2 {
            border-bottom: 1px solid #1a1a1a;
            margin-bottom: 14px;
        }

        .slip-title {
            text-align: center;
            margin-bottom: 14px;
        }
        .slip-title h3 {
            margin: 0;
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-decoration: underline;
        }
        .slip-title p {
            margin: 3px 0 0;
            font-size: 8pt;
            color: #555;
        }

        /* Type badge */
        .type-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 4px;
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 14px;
        }
        .type-checkin  { background: #dcfce7; border: 1px solid #86efac; color: #15803d; }
        .type-checkout { background: #dbeafe; border: 1px solid #93c5fd; color: #1d4ed8; }

        .data-table {
            width: 100%;
            margin: 0 0 14px 0;
            border-collapse: collapse;
        }
        .data-table td {
            padding: 5px 6px;
            vertical-align: top;
            font-size: 10pt;
            border-bottom: 1px solid #f1f5f9;
        }
        .data-table td:first-child {
            width: 140px;
            color: #64748b;
            font-weight: 600;
        }
        .data-table td:nth-child(2) {
            width: 12px;
            text-align: center;
            color: #94a3b8;
        }
        .data-table td:last-child {
            font-weight: 600;
            color: #1e293b;
        }

        .separator { border: none; border-top: 1px dashed #cbd5e1; margin: 12px 0; }

        .meta-row {
            display: flex;
            gap: 8px;
            margin-bottom: 14px;
        }
        .meta-chip {
            flex: 1;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 6px 10px;
            text-align: center;
            background: #f8fafc;
        }
        .meta-chip .label { font-size: 7.5pt; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.4px; font-weight: 700; }
        .meta-chip .value { font-size: 10pt; font-weight: 800; color: #1e293b; margin-top: 2px; }
        .meta-chip .value.green  { color: #059669; }
        .meta-chip .value.amber  { color: #d97706; }
        .meta-chip .value.red    { color: #dc2626; }
        .meta-chip .value.blue   { color: #2563eb; }

        .ttd-box {
            text-align: center;
            margin-top: 20px;
        }
        .ttd-box .label { font-size: 9pt; color: #555; margin-bottom: 2px; }
        .ttd-box .garis { border-bottom: 1px solid #333; width: 160px; margin: 55px auto 5px; }
        .ttd-box .nama  { font-weight: bold; font-size: 10pt; }
        .ttd-box .jabatan { font-size: 8pt; color: #666; }

        .footer-doc {
            margin-top: 16px;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            font-size: 7pt;
            color: #94a3b8;
            text-align: center;
        }

        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
    <script>window.onload = function() { window.print(); };</script>
</head>

<body>
    @php
        $company = cache()->get('settings.company_name', 'PT PresensiKu Indonesia');
        $address = cache()->get('settings.company_address', 'Jl. Teknologi No. 1, Jakarta Selatan');
        $phone   = cache()->get('settings.company_phone', '(021) 1234-5678');
        $email   = cache()->get('settings.company_email', 'hrd@presensiku.com');
        $tz      = cache()->get('settings.timezone', 'Asia/Jakarta');
        $tzLabel = match($tz) { 'Asia/Makassar' => 'WITA', 'Asia/Jayapura' => 'WIT', default => 'WIB' };

        $ts = $log->timestamp->timezone($tz);
        $nomorSlip = 'SLP/' . str_pad($log->id, 6, '0', STR_PAD_LEFT) . '/' . $ts->format('m/Y');

        $typeLabel = match($log->type) {
            'checkin'     => 'Absen Masuk',
            'checkout'    => 'Absen Keluar',
            'break_start' => 'Mulai Istirahat',
            'break_end'   => 'Selesai Istirahat',
            default       => ucfirst($log->type),
        };
        $typeBadgeClass = str_starts_with($log->type, 'check') && $log->type === 'checkin' ? 'type-checkin' : 'type-checkout';

        $riskLabel = match($log->risk_level) { 'high' => 'Tinggi', 'medium' => 'Sedang', default => 'Rendah' };
        $riskClass = match($log->risk_level) { 'high' => 'red', 'medium' => 'amber', default => 'green' };

        $statusLabel = match($log->status) { 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'flagged' => 'Dicurigai', default => 'Diproses' };
        $statusClass = match($log->status) { 'approved' => 'green', 'rejected' => 'red', 'flagged' => 'red', default => 'amber' };
    @endphp

    <!-- Kop Surat -->
    <div class="kop-surat">
        <h1>{{ $company }}</h1>
        <p>{{ $address }}</p>
        <p>Telp: {{ $phone }} | Email: {{ $email }}</p>
    </div>
    <div class="kop-line-2"></div>

    <!-- Judul -->
    <div class="slip-title">
        <h3>Slip Kehadiran Karyawan</h3>
        <p>No. {{ $nomorSlip }}</p>
    </div>

    <!-- Tipe Badge -->
    <div style="text-align: center;">
        <span class="type-badge {{ $typeBadgeClass }}">{{ $typeLabel }}</span>
    </div>

    <!-- Data Karyawan -->
    <table class="data-table">
        <tr>
            <td>Nama Karyawan</td><td>:</td>
            <td>{{ $log->user->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>ID Karyawan</td><td>:</td>
            <td>{{ $log->user->employee_id ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Jabatan / Peran</td><td>:</td>
            <td>{{ $log->user->roles->first()?->display_name ?? ucfirst($log->user->roles->first()?->name ?? 'Karyawan') }}</td>
        </tr>
        <tr>
            <td>Cabang</td><td>:</td>
            <td>{{ $log->branch->name ?? 'Kantor Pusat' }}</td>
        </tr>
    </table>

    <hr class="separator">

    <!-- Data Absen -->
    <table class="data-table">
        <tr>
            <td>Waktu Absen</td><td>:</td>
            <td><strong>{{ $ts->translatedFormat('H:i:s, l d F Y') }} {{ $tzLabel }}</strong></td>
        </tr>
        <tr>
            <td>Mode Kerja</td><td>:</td>
            <td>{{ strtoupper($log->work_mode ?? 'WFO') }}</td>
        </tr>
        <tr>
            <td>Lokasi GPS</td><td>:</td>
            <td>{{ number_format((float)$log->latitude, 6) }}, {{ number_format((float)$log->longitude, 6) }}</td>
        </tr>
        <tr>
            <td>Akurasi GPS</td><td>:</td>
            <td>± {{ round((float)$log->accuracy) }} meter</td>
        </tr>
        <tr>
            <td>IP Address</td><td>:</td>
            <td style="font-family: monospace;">{{ $log->ip_address ?? '-' }}</td>
        </tr>
        @if($log->notes)
        <tr>
            <td>Catatan</td><td>:</td>
            <td style="font-style: italic; color: #475569;">{{ $log->notes }}</td>
        </tr>
        @endif
    </table>

    <!-- Chip Ringkasan -->
    <div class="meta-row">
        <div class="meta-chip">
            <div class="label">Ketepatan</div>
            <div class="value {{ $log->is_late ? 'amber' : 'green' }}">{{ $log->is_late ? 'Terlambat' : 'Tepat Waktu' }}</div>
        </div>
        <div class="meta-chip">
            <div class="label">Status</div>
            <div class="value {{ $statusClass }}">{{ $statusLabel }}</div>
        </div>
        <div class="meta-chip">
            <div class="label">Risiko Wajah</div>
            <div class="value {{ $riskClass }}">{{ $riskLabel }} ({{ $log->risk_score ?? 0 }})</div>
        </div>
    </div>

    <!-- TTD -->
    <div style="display: flex; justify-content: space-between; margin-top: 16px;">
        <div class="ttd-box">
            <div class="label">Karyawan Bersangkutan,</div>
            <div class="garis"></div>
            <div class="nama">{{ $log->user->name ?? 'Karyawan' }}</div>
            <div class="jabatan">{{ $log->user->employee_id ?? '' }}</div>
        </div>
        <div class="ttd-box">
            <div class="label">Mengetahui,<br>HRD / Kepala Divisi</div>
            <div class="garis"></div>
            <div class="nama">( _____________________ )</div>
            <div class="jabatan">Pejabat Berwenang</div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer-doc">
        Slip ini dicetak secara otomatis oleh sistem PresensiKu &mdash; {{ now()->timezone($tz)->translatedFormat('d F Y, H:i') }} {{ $tzLabel }}
        &nbsp;|&nbsp; Log ID: {{ $log->id }}
        &nbsp;|&nbsp; Dokumen resmi, tidak memerlukan tanda tangan basah apabila telah divalidasi digital.
    </div>
</body>
</html>
