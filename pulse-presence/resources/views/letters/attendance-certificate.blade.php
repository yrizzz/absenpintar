<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Kehadiran - {{ $user->name ?? 'Karyawan' }}</title>
    <style>
        @page { size: A4; margin: 30mm 25mm 25mm 25mm; }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            font-size: 12pt;
            line-height: 1.8;
        }
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #1a1a1a;
            padding-bottom: 12px;
            margin-bottom: 8px;
        }
        .kop-surat h1 {
            margin: 0;
            font-size: 18pt;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .kop-surat p {
            margin: 2px 0;
            font-size: 9pt;
            color: #444;
        }
        .kop-line-2 {
            border-bottom: 1px solid #1a1a1a;
            margin-bottom: 24px;
        }
        .nomor-surat {
            text-align: center;
            margin-bottom: 24px;
        }
        .nomor-surat h3 {
            margin: 0;
            font-size: 13pt;
            text-decoration: underline;
            font-weight: bold;
            text-transform: uppercase;
        }
        .nomor-surat p {
            margin: 4px 0 0;
            font-size: 10pt;
            color: #555;
        }
        .isi p {
            text-align: justify;
            margin: 6px 0;
        }
        .data-table {
            width: 100%;
            margin: 12px 0 16px 20px;
        }
        .data-table td {
            padding: 3px 8px;
            vertical-align: top;
            font-size: 12pt;
        }
        .data-table td:first-child {
            width: 180px;
        }
        .data-table td:nth-child(2) {
            width: 15px;
            text-align: center;
        }
        .log-table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
            font-size: 10pt;
        }
        .log-table th {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            text-align: left;
            font-weight: bold;
            font-size: 9pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #475569;
        }
        .log-table td {
            border: 1px solid #e2e8f0;
            padding: 7px 10px;
            color: #334155;
        }
        .log-table tr:nth-child(even) td {
            background: #f8fafc;
        }
        .ttd-container {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            page-break-inside: avoid;
        }
        .ttd-box {
            width: 45%;
            text-align: center;
        }
        .ttd-box .label {
            font-size: 10pt;
            color: #555;
            margin-bottom: 4px;
        }
        .ttd-box .garis {
            border-bottom: 1px solid #333;
            width: 200px;
            margin: 70px auto 6px;
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
            padding: 14px 18px;
            margin: 16px 0;
            font-size: 10pt;
        }
        .summary-box strong { color: #1e40af; }
        .footer-doc {
            margin-top: 40px;
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
            font-size: 8pt;
            color: #94a3b8;
            text-align: center;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>

<body>
    @php
        $company = cache()->get('settings.company_name', 'PT AbsenPintar Indonesia');
        $address = cache()->get('settings.company_address', 'Jl. Teknologi No. 1, Jakarta Selatan');
        $phone = cache()->get('settings.company_phone', '(021) 1234-5678');
        $email = cache()->get('settings.company_email', 'hrd@absenpintar.com');
        $tz = cache()->get('settings.timezone', 'Asia/Jakarta');

        $nomorSurat = 'SK/' . str_pad($user->id, 4, '0', STR_PAD_LEFT) . '/HRD/' . \Carbon\Carbon::now()->format('m/Y');

        $totalHadir = $logs->count();
        $totalMasuk = $logs->where('type', 'checkin')->count();
        $totalKeluar = $logs->where('type', 'checkout')->count();
        $totalTerlambat = $logs->where('is_late', true)->count();
    @endphp

    <!-- Kop Surat -->
    <div class="kop-surat">
        <h1>{{ $company }}</h1>
        <p>{{ $address }}</p>
        <p>Telp: {{ $phone }} | Email: {{ $email }}</p>
    </div>
    <div class="kop-line-2"></div>

    <!-- Nomor Surat -->
    <div class="nomor-surat">
        <h3>Surat Keterangan Kehadiran</h3>
        <p>Nomor: {{ $nomorSurat }}</p>
    </div>

    <!-- Isi Surat -->
    <div class="isi">
        <p>Yang bertanda tangan di bawah ini, Pimpinan / HRD {{ $company }}, dengan ini menerangkan bahwa:</p>

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
                <td>Periode</td>
                <td>:</td>
                <td><strong>{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</strong></td>
            </tr>
        </table>

        <p>Adalah benar karyawan pada perusahaan kami dan telah melaksanakan kewajiban kehadiran kerja selama periode tersebut dengan rincian sebagai berikut:</p>

        <!-- Ringkasan -->
        <div class="summary-box">
            <strong>Total Log Kehadiran:</strong> {{ $totalHadir }} entri &nbsp;|&nbsp;
            <strong>Absen Masuk:</strong> {{ $totalMasuk }} &nbsp;|&nbsp;
            <strong>Absen Keluar:</strong> {{ $totalKeluar }} &nbsp;|&nbsp;
            <strong>Terlambat:</strong> {{ $totalTerlambat }} kali
        </div>

        <!-- Tabel Detail Kehadiran -->
        @if($logs->isNotEmpty())
        <table class="log-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal & Waktu</th>
                    <th>Aksi</th>
                    <th>Cabang</th>
                    <th>Akurasi GPS</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $i => $log)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($log->timestamp)->timezone($tz)->translatedFormat('d M Y, H:i:s') }}</td>
                    <td>{{ $log->type === 'checkin' ? 'Masuk' : 'Keluar' }}</td>
                    <td>{{ $log->branch->name ?? 'N/A' }}</td>
                    <td>± {{ round($log->accuracy) }}m</td>
                    <td>{{ $log->is_late ? 'Terlambat' : 'Tepat Waktu' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <!-- Tanda Tangan -->
    <div class="ttd-container">
        <div class="ttd-box">
            <p class="label">Mengetahui,</p>
            <div class="garis"></div>
            <p class="nama">___________________</p>
            <p class="jabatan">HRD / Pejabat Berwenang</p>
        </div>
        <div class="ttd-box">
            <p class="label">Karyawan Bersangkutan,</p>
            <div class="garis"></div>
            <p class="nama">{{ $user->name }}</p>
            <p class="jabatan">ID: {{ $user->employee_id ?? 'N/A' }}</p>
        </div>
    </div>

    <p style="text-align: right; font-size: 10pt; color: #555; margin-top: 24px;">
        {{ cache()->get('settings.company_address', 'Jakarta') }}, {{ \Carbon\Carbon::now()->timezone($tz)->translatedFormat('d F Y') }}
    </p>

    <div class="footer-doc">
        <p>Dokumen ini dicetak secara elektronik oleh sistem AbsenPintar pada {{ \Carbon\Carbon::now()->timezone($tz)->translatedFormat('d F Y, H:i:s') }} WIB.</p>
        <p>Surat keterangan ini sah dan diverifikasi melalui sistem biometrik & geofencing AbsenPintar.</p>
    </div>

    <script>window.onload = function() { window.print(); }</script>
</body>

</html>
