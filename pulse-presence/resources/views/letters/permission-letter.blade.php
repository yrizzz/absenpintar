<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Izin Kerja - {{ $permission->user->name ?? 'Karyawan' }}</title>
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
        .status-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 4px;
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-approved { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .badge-pending { background: #fef9c3; color: #854d0e; border: 1px solid #fde68a; }
        .badge-rejected { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .catatan {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 16px;
            margin: 16px 0;
            font-size: 10pt;
            color: #475569;
        }
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

        $permTypes = [
            'late_arrival' => 'Izin Terlambat Masuk',
            'early_leave' => 'Izin Pulang Lebih Awal',
            'official_duty' => 'Tugas Dinas / Perjalanan Resmi',
            'personal' => 'Keperluan Pribadi',
            'medical' => 'Keperluan Medis / Berobat',
            'other' => 'Lainnya',
        ];
        $typeLabel = $permTypes[$permission->type] ?? ucfirst(str_replace('_', ' ', $permission->type));

        $statusLabels = [
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
        ];
        $statusLabel = $statusLabels[$permission->status] ?? ucfirst($permission->status);
        $statusClass = $permission->status === 'approved' ? 'badge-approved' : ($permission->status === 'rejected' ? 'badge-rejected' : 'badge-pending');

        $nomorSurat = 'IK/' . str_pad($permission->id, 4, '0', STR_PAD_LEFT) . '/HRD/' . \Carbon\Carbon::now()->format('m/Y');
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
        <h3>Surat Izin Kerja / Dispensasi</h3>
        <p>Nomor: {{ $nomorSurat }}</p>
    </div>

    <!-- Isi Surat -->
    <div class="isi">
        <p>Yang bertanda tangan di bawah ini, Pimpinan / HRD {{ $company }}, menerangkan bahwa:</p>

        <table class="data-table">
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td><strong>{{ $permission->user->name ?? 'N/A' }}</strong></td>
            </tr>
            <tr>
                <td>ID Karyawan</td>
                <td>:</td>
                <td>{{ $permission->user->employee_id ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Penempatan Cabang</td>
                <td>:</td>
                <td>{{ $permission->user->branch->name ?? 'Kantor Pusat' }}</td>
            </tr>
            <tr>
                <td>Jabatan / Peran</td>
                <td>:</td>
                <td>{{ ucfirst($permission->user->roles->first()?->name ?? 'Karyawan') }}</td>
            </tr>
        </table>

        <p>Telah diberikan izin kerja dengan keterangan sebagai berikut:</p>

        <table class="data-table">
            <tr>
                <td>Jenis Izin</td>
                <td>:</td>
                <td><strong>{{ $typeLabel }}</strong></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($permission->date)->translatedFormat('l, d F Y') }}</td>
            </tr>
            <tr>
                <td>Waktu Mulai</td>
                <td>:</td>
                <td>{{ $permission->start_time ?? '-' }}</td>
            </tr>
            <tr>
                <td>Waktu Selesai</td>
                <td>:</td>
                <td>{{ $permission->end_time ?? '-' }}</td>
            </tr>
            <tr>
                <td>Alasan / Keperluan</td>
                <td>:</td>
                <td>{{ $permission->reason }}</td>
            </tr>
            <tr>
                <td>Status Persetujuan</td>
                <td>:</td>
                <td><span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span></td>
            </tr>
        </table>

        <p>Demikian surat izin kerja ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>

        @if($permission->approval_notes)
        <div class="catatan">
            <strong>Catatan Pejabat Berwenang:</strong><br>
            <em>"{{ $permission->approval_notes }}"</em>
        </div>
        @endif
    </div>

    <!-- Tanda Tangan -->
    <div class="ttd-container">
        <div class="ttd-box">
            <p class="label">Pemohon,</p>
            <div class="garis"></div>
            <p class="nama">{{ $permission->user->name ?? 'N/A' }}</p>
            <p class="jabatan">ID: {{ $permission->user->employee_id ?? 'N/A' }}</p>
        </div>
        <div class="ttd-box">
            <p class="label">Mengetahui & Menyetujui,</p>
            <div class="garis"></div>
            <p class="nama">{{ $permission->hr->name ?? $permission->deptHead->name ?? '___________________' }}</p>
            <p class="jabatan">HRD / Kepala Departemen</p>
        </div>
    </div>

    <p style="text-align: right; font-size: 10pt; color: #555; margin-top: 24px;">
        {{ cache()->get('settings.company_address', 'Jakarta') }}, {{ \Carbon\Carbon::now()->timezone($tz)->translatedFormat('d F Y') }}
    </p>

    <div class="footer-doc">
        <p>Dokumen ini dicetak secara elektronik oleh sistem AbsenPintar pada {{ \Carbon\Carbon::now()->timezone($tz)->translatedFormat('d F Y, H:i:s') }} WIB.</p>
        <p>Surat ini sah tanpa tanda tangan basah apabila status persetujuan berstatus "Disetujui" pada sistem.</p>
    </div>

    <script>window.onload = function() { window.print(); }</script>
</body>

</html>
