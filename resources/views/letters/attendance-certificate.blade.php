<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Kehadiran - {{ $user->name ?? 'Karyawan' }}</title>
    <style>
        @page { size: A4; margin: 25mm 20mm 20mm 20mm; }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #111;
            margin: 0;
            padding: 0;
            font-size: 11pt;
            line-height: 1.6;
        }
        .kop-surat-table {
            width: 100%;
            border-bottom: 3px double #111;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .kop-logo-cell {
            width: 70px;
            vertical-align: middle;
        }
        .kop-text-cell {
            text-align: center;
            vertical-align: middle;
        }
        .kop-surat h1 {
            margin: 0;
            font-size: 18pt;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #111;
        }
        .kop-surat p {
            margin: 3px 0;
            font-size: 9pt;
            color: #444;
            font-family: Arial, sans-serif;
        }
        .nomor-surat {
            text-align: center;
            margin-bottom: 30px;
        }
        .nomor-surat h3 {
            margin: 0;
            font-size: 13pt;
            text-decoration: underline;
            font-weight: bold;
            text-transform: uppercase;
        }
        .nomor-surat p {
            margin: 5px 0 0;
            font-size: 10pt;
            color: #333;
            font-family: 'Courier New', Courier, monospace;
        }
        .isi p {
            text-align: justify;
            margin: 10px 0;
            text-indent: 30px;
        }
        .data-table {
            width: 90%;
            margin: 15px auto 20px auto;
            border-collapse: collapse;
        }
        .data-table td {
            padding: 4px 8px;
            vertical-align: top;
            font-size: 11pt;
        }
        .data-table td:first-child {
            width: 170px;
            color: #333;
        }
        .data-table td:nth-child(2) {
            width: 15px;
            text-align: center;
        }
        .log-table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
            font-size: 9.5pt;
        }
        .log-table th {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            text-align: left;
            font-weight: bold;
            font-size: 8.5pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #475569;
            font-family: Arial, sans-serif;
        }
        .log-table td {
            border: 1px solid #e2e8f0;
            padding: 6px 10px;
            color: #334155;
            font-family: Arial, sans-serif;
        }
        .log-table tr:nth-child(even) td {
            background: #f8fafc;
        }
        .ttd-table {
            width: 100%;
            margin-top: 40px;
            border: none;
            page-break-inside: avoid;
        }
        .ttd-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            border: none;
            padding: 0;
        }
        .ttd-box .label {
            font-size: 10pt;
            color: #333;
            margin-bottom: 5px;
        }
        .ttd-box .garis {
            border-bottom: 1.5px solid #111;
            width: 180px;
            margin: 60px auto 5px;
        }
        .ttd-box .nama {
            font-weight: bold;
            font-size: 11pt;
        }
        .ttd-box .jabatan {
            font-size: 9pt;
            color: #555;
        }
        .summary-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            padding: 10px 15px;
            margin: 15px 0;
            font-size: 10pt;
            color: #1e3a8a;
            font-family: Arial, sans-serif;
        }
        .summary-box strong { color: #1e40af; }
        .footer-doc-table {
            width: 100%;
            margin-top: 50px;
            border-top: 1px solid #cbd5e1;
            padding-top: 15px;
        }
        .footer-doc-table td {
            vertical-align: middle;
            border: none;
        }
        .footer-doc-text {
            font-size: 8pt;
            color: #64748b;
            line-height: 1.4;
            font-family: Arial, sans-serif;
        }
        .footer-doc-qr {
            width: 75px;
            text-align: right;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>

<body>
    @php
        $company = cache()->get('settings.company_name', 'PT PresensiKu Indonesia');
        $address = cache()->get('settings.company_address', 'Jl. Teknologi No. 1, Jakarta Selatan');
        $phone = cache()->get('settings.company_phone', '(021) 1234-5678');
        $email = cache()->get('settings.company_email', 'hrd@presensiku.com');
        $tz = cache()->get('settings.timezone', 'Asia/Jakarta');
        $tzLabel = match($tz) { 'Asia/Makassar' => 'WITA', 'Asia/Jayapura' => 'WIT', default => 'WIB' };

        $nomorSurat = 'SK/' . str_pad($user->id, 4, '0', STR_PAD_LEFT) . '/HRD/' . \Carbon\Carbon::now()->format('m/Y');

        $totalHadir = $logs->count();
        $totalMasuk = $logs->where('type', 'checkin')->count();
        $totalKeluar = $logs->where('type', 'checkout')->count();
        $totalTerlambat = $logs->where('is_late', true)->count();
    @endphp

    <!-- Kop Surat -->
    <table class="kop-surat-table">
        <tr>
            <td class="kop-logo-cell">
                <svg width="55" height="55" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2">
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

    <!-- Nomor Surat -->
    <div class="nomor-surat">
        <h3>Surat Keterangan Kehadiran Kerja</h3>
        <p>Nomor: {{ $nomorSurat }}</p>
    </div>

    <!-- Isi Surat -->
    <div class="isi">
        <p>Yang bertanda tangan di bawah ini, Pimpinan / HRD {{ $company }}, dengan ini menerangkan bahwa karyawan yang tercantum di bawah ini:</p>

        <table class="data-table">
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td><strong>{{ $user->name }}</strong></td>
            </tr>
            <tr>
                <td>ID Karyawan</td>
                <td>:</td>
                <td>{{ $user->employee_id ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Penempatan Cabang</td>
                <td>:</td>
                <td>{{ $user->branch->name ?? 'Kantor Pusat' }}</td>
            </tr>
            <tr>
                <td>Jabatan / Peran</td>
                <td>:</td>
                <td>{{ ucfirst($user->roles->first()?->name ?? 'Karyawan') }}</td>
            </tr>
            <tr>
                <td>Periode Laporan</td>
                <td>:</td>
                <td><strong>{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</strong></td>
            </tr>
        </table>

        <p>Adalah benar karyawan aktif pada perusahaan kami dan telah melaksanakan kewajiban kehadiran kerja biometrik & geofencing dengan rekapitulasi data sebagai berikut:</p>

        <!-- Ringkasan -->
        <div class="summary-box">
            <strong>Total Entri:</strong> {{ $totalHadir }} log &nbsp;|&nbsp;
            <strong>Masuk (Check-In):</strong> {{ $totalMasuk }} &nbsp;|&nbsp;
            <strong>Keluar (Check-Out):</strong> {{ $totalKeluar }} &nbsp;|&nbsp;
            <strong>Terlambat:</strong> {{ $totalTerlambat }} kali
        </div>

        <!-- Tabel Detail Kehadiran -->
        @if($logs->isNotEmpty())
        <table class="log-table">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">No</th>
                    <th>Hari, Tanggal & Waktu</th>
                    <th>Tipe Aktivitas</th>
                    <th>Cabang Penempatan</th>
                    <th>Akurasi GPS</th>
                    <th>Ketepatan Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $i => $log)
                <tr>
                    <td style="text-align: center;">{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($log->timestamp)->timezone($tz)->translatedFormat('l, d M Y, H:i:s') }}</td>
                    <td><strong>{{ $log->type === 'checkin' ? 'Masuk (Check-In)' : 'Keluar (Check-Out)' }}</strong></td>
                    <td>{{ $log->branch->name ?? 'N/A' }}</td>
                    <td>± {{ round($log->accuracy) }}m</td>
                    <td>{{ $log->is_late ? 'Terlambat' : 'Tepat Waktu' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <p>Demikian surat keterangan kehadiran ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <!-- Tanda Tangan Table -->
    <table class="ttd-table">
        <tr>
            <td>
                <div class="ttd-box">
                    <p class="label">Karyawan Bersangkutan,</p>
                    <div class="garis"></div>
                    <p class="nama">{{ $user->name }}</p>
                    <p class="jabatan">ID: {{ $user->employee_id ?? 'N/A' }}</p>
                </div>
            </td>
            <td>
                <div class="ttd-box">
                    <p class="label">Mengetahui,</p>
                    <div class="garis"></div>
                    <p class="nama">___________________</p>
                    <p class="jabatan">HRD / Pejabat Berwenang</p>
                </div>
            </td>
        </tr>
    </table>

    <!-- Footer Document with Verification QR -->
    <table class="footer-doc-table">
        <tr>
            <td class="footer-doc-text">
                Dokumen ini diterbitkan secara elektronik oleh sistem PresensiKu pada {{ \Carbon\Carbon::now()->timezone($tz)->translatedFormat('d F Y, H:i:s') }} {{ $tzLabel }}.<br>
                Validitas log kehadiran diverifikasi secara otomatis melalui enkripsi biometrik sidik jari & deteksi koordinat satelit GPS.
            </td>
            <td class="footer-doc-qr">
                <svg width="60" height="60" viewBox="0 0 100 100" fill="none" stroke="#222" stroke-width="2">
                    <rect x="5" y="5" width="90" height="90" rx="6" stroke-width="3" />
                    <rect x="15" y="15" width="20" height="20" stroke-width="4" />
                    <rect x="20" y="20" width="10" height="10" fill="#222" />
                    <rect x="65" y="15" width="20" height="20" stroke-width="4" />
                    <rect x="70" y="20" width="10" height="10" fill="#222" />
                    <rect x="15" y="65" width="20" height="20" stroke-width="4" />
                    <rect x="20" y="70" width="10" height="10" fill="#222" />
                    <rect x="45" y="20" width="8" height="8" fill="#222" />
                    <rect x="45" y="35" width="8" height="8" fill="#222" />
                    <rect x="65" y="45" width="8" height="8" fill="#222" />
                    <rect x="75" y="55" width="8" height="8" fill="#222" />
                    <rect x="35" y="55" width="8" height="8" fill="#222" />
                    <rect x="55" y="65" width="8" height="8" fill="#222" />
                    <rect x="45" y="75" width="8" height="8" fill="#222" />
                    <rect x="65" y="75" width="8" height="8" fill="#222" />
                    <rect x="75" y="75" width="8" height="8" fill="#222" />
                </svg>
                <div style="font-size: 6pt; color: #555; text-align: center; font-family: sans-serif; margin-top: 3px; font-weight: bold; letter-spacing: 0.5px;">SYSTEM VALIDATED</div>
            </td>
        </tr>
    </table>

    <script>window.onload = function() { window.print(); }</script>
</body>

</html>
