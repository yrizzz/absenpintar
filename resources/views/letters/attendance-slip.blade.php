<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Slip Kehadiran - {{ $log->user->name ?? 'Karyawan' }}</title>
    <style>
        @page { size: A5; margin: 12mm 15mm 12mm 15mm; }
        body {
            font-family: Arial, sans-serif;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            font-size: 9.5pt;
            line-height: 1.5;
        }
        .kop-surat-table {
            width: 100%;
            border-bottom: 2px solid #111;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .kop-logo-cell {
            width: 50px;
            vertical-align: middle;
        }
        .kop-text-cell {
            text-align: center;
            vertical-align: middle;
        }
        .kop-surat h1 {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .kop-surat p {
            margin: 2px 0;
            font-size: 7.5pt;
            color: #444;
        }
        .slip-title {
            text-align: center;
            margin-bottom: 12px;
        }
        .slip-title h3 {
            margin: 0;
            font-size: 10.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-decoration: underline;
        }
        .slip-title p {
            margin: 3px 0 0;
            font-size: 8pt;
            color: #555;
            font-family: 'Courier New', Courier, monospace;
        }
        .type-badge-container {
            text-align: center;
            margin-bottom: 10px;
        }
        .type-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 4px;
            font-size: 8.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .type-checkin  { background: #dcfce7; border: 1px solid #86efac; color: #15803d; }
        .type-checkout { background: #dbeafe; border: 1px solid #93c5fd; color: #1d4ed8; }

        .data-table {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }
        .data-table td {
            padding: 4px 6px;
            vertical-align: top;
            font-size: 9.5pt;
            border-bottom: 1px solid #f1f5f9;
        }
        .data-table td:first-child {
            width: 130px;
            color: #475569;
            font-weight: bold;
        }
        .data-table td:nth-child(2) {
            width: 12px;
            text-align: center;
            color: #94a3b8;
        }
        .data-table td:last-child {
            font-weight: 600;
            color: #0f172a;
        }
        .separator {
            border: none;
            border-top: 1px dashed #cbd5e1;
            margin: 10px 0;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }
        .meta-table td {
            width: 33.33%;
            padding: 0 4px;
        }
        .meta-chip {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 5px 8px;
            text-align: center;
            background: #f8fafc;
        }
        .meta-chip .label {
            font-size: 7pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            font-weight: bold;
        }
        .meta-chip .value {
            font-size: 9pt;
            font-weight: bold;
            color: #0f172a;
            margin-top: 2px;
        }
        .meta-chip .value.green  { color: #16a34a; }
        .meta-chip .value.amber  { color: #d97706; }
        .meta-chip .value.red    { color: #dc2626; }

        .ttd-table {
            width: 100%;
            margin-top: 15px;
            border: none;
        }
        .ttd-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            border: none;
            padding: 0;
        }
        .ttd-box .label {
            font-size: 8.5pt;
            color: #444;
            margin-bottom: 2px;
        }
        .ttd-box .garis {
            border-bottom: 1px solid #111;
            width: 150px;
            margin: 45px auto 4px;
        }
        .ttd-box .nama {
            font-weight: bold;
            font-size: 9.5pt;
        }
        .ttd-box .jabatan {
            font-size: 8pt;
            color: #666;
        }
        .footer-doc-table {
            width: 100%;
            margin-top: 15px;
            border-top: 1px solid #cbd5e1;
            padding-top: 8px;
        }
        .footer-doc-table td {
            vertical-align: middle;
            border: none;
        }
        .footer-doc-text {
            font-size: 7pt;
            color: #64748b;
            line-height: 1.3;
        }
        .footer-doc-qr {
            width: 55px;
            text-align: right;
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
            default       => ucfirst($log->type),
        };
        $typeBadgeClass = $log->type === 'checkin' ? 'type-checkin' : 'type-checkout';

        $riskLabel = match($log->risk_level) { 'high' => 'Tinggi', 'medium' => 'Sedang', default => 'Rendah' };
        $riskClass = match($log->risk_level) { 'high' => 'red', 'medium' => 'amber', default => 'green' };

        $statusLabel = match($log->status) { 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'flagged' => 'Dicurigai', default => 'Diproses' };
        $statusClass = match($log->status) { 'approved' => 'green', 'rejected' => 'red', 'flagged' => 'red', default => 'amber' };
    @endphp

    <!-- Kop Surat -->
    <table class="kop-surat-table">
        <tr>
            <td class="kop-logo-cell">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </td>
            <td class="kop-text-cell">
                <div class="kop-surat">
                    <h1>{{ $company }}</h1>
                    <p>{{ $address }}</p>
                    <p>Telp: {{ $phone }} | Email: {{ $email }}</p>
                </div>
            </td>
        </tr>
    </table>

    <!-- Judul -->
    <div class="slip-title">
        <h3>Slip Kehadiran Karyawan</h3>
        <p>No. {{ $nomorSlip }}</p>
    </div>

    <!-- Tipe Badge -->
    <div class="type-badge-container">
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

    <!-- Chip Ringkasan (Using standard Table for print compatibility) -->
    <table class="meta-table">
        <tr>
            <td>
                <div class="meta-chip">
                    <div class="label">Ketepatan</div>
                    <div class="value {{ $log->is_late ? 'amber' : 'green' }}">{{ $log->is_late ? 'Terlambat' : 'Tepat' }}</div>
                </div>
            </td>
            <td>
                <div class="meta-chip">
                    <div class="label">Status</div>
                    <div class="value {{ $statusClass }}">{{ $statusLabel }}</div>
                </div>
            </td>
            <td>
                <div class="meta-chip">
                    <div class="label">Risiko</div>
                    <div class="value {{ $riskClass }}">{{ $riskLabel }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- TTD Table -->
    <table class="ttd-table">
        <tr>
            <td>
                <div class="ttd-box">
                    <p class="label">Karyawan Bersangkutan,</p>
                    <div class="garis"></div>
                    <p class="nama">{{ $log->user->name ?? 'Karyawan' }}</p>
                    <p class="jabatan">ID: {{ $log->user->employee_id ?? '-' }}</p>
                </div>
            </td>
            <td>
                <div class="ttd-box">
                    <p class="label">Mengetahui,<br>HRD / Kepala Divisi</p>
                    <div class="garis"></div>
                    <p class="nama">( _____________________ )</p>
                    <p class="jabatan">Pejabat Berwenang</p>
                </div>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <table class="footer-doc-table">
        <tr>
            <td class="footer-doc-text">
                Slip Kehadiran ini dicetak secara otomatis oleh sistem PresensiKu pada {{ now()->timezone($tz)->translatedFormat('d F Y, H:i') }} {{ $tzLabel }}.<br>
                Log ID: {{ $log->id }} | Valid secara hukum menggunakan validasi biometrik & geofence sistem.
            </td>
            <td class="footer-doc-qr">
                <svg width="45" height="45" viewBox="0 0 100 100" fill="none" stroke="#222" stroke-width="2.5">
                    <rect x="5" y="5" width="90" height="90" rx="8" stroke-width="3" />
                    <rect x="15" y="15" width="20" height="20" stroke-width="4.5" />
                    <rect x="20" y="20" width="10" height="10" fill="#222" />
                    <rect x="65" y="15" width="20" height="20" stroke-width="4.5" />
                    <rect x="70" y="20" width="10" height="10" fill="#222" />
                    <rect x="15" y="65" width="20" height="20" stroke-width="4.5" />
                    <rect x="20" y="70" width="10" height="10" fill="#222" />
                    <rect x="45" y="20" width="8" height="8" fill="#222" />
                    <rect x="45" y="40" width="8" height="8" fill="#222" />
                    <rect x="65" y="45" width="8" height="8" fill="#222" />
                    <rect x="75" y="55" width="8" height="8" fill="#222" />
                    <rect x="35" y="55" width="8" height="8" fill="#222" />
                    <rect x="55" y="65" width="8" height="8" fill="#222" />
                    <rect x="45" y="75" width="8" height="8" fill="#222" />
                    <rect x="65" y="75" width="8" height="8" fill="#222" />
                </svg>
            </td>
        </tr>
    </table>
</body>

</html>
