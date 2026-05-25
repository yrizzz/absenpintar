<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>AbsenPintar Ledger - Laporan Kehadiran Resmi</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 40px;
            font-size: 11px;
            line-height: 1.5;
        }

        .header {
            border-bottom: 2px solid #0284c7;
            padding-bottom: 20px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #0f172a;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .header p {
            margin: 5px 0 0 0;
            color: #64748b;
            font-size: 11px;
        }

        .meta-info {
            text-align: right;
            font-size: 10px;
            color: #64748b;
        }

        .meta-info strong {
            color: #0f172a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
            padding: 10px;
            text-align: left;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            vertical-align: middle;
        }

        tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .badge-warning {
            background-color: #fef9c3;
            color: #854d0e;
        }

        .badge-info {
            background-color: #e0f2fe;
            color: #075985;
        }

        .selfie-img {
            width: 45px;
            height: 45px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .footer {
            margin-top: 50px;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #94a3b8;
        }

        .signature-space {
            margin-top: 40px;
            text-align: right;
        }

        .signature-line {
            display: inline-block;
            width: 200px;
            border-top: 1px solid #333;
            margin-top: 60px;
        }

        @media print {
            body {
                margin: 20px;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <div>
            <h1>AbsenPintar</h1>
            <p>Sistem Manajemen Kehadiran & Telemetri Perusahaan</p>
        </div>
        <div class="meta-info">
            <p>Jenis Laporan: <strong>{{ strtoupper(str_replace('_', ' ', $report_type)) }}</strong></p>
            <p>Periode: <strong>{{ strtoupper($report_period) }}</strong></p>
            <p>Total Data: <strong>{{ $data->count() }} Baris</strong></p>
            <p>Tanggal Cetak: <strong>{{ date('d F Y, H:i') }}</strong></p>
        </div>
    </div>

    @if ($report_type === 'presence_summary')
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">Selfie</th>
                    <th>ID</th>
                    <th>Nama Karyawan</th>
                    <th>Aksi</th>
                    <th>Waktu</th>
                    <th>Akurasi GPS</th>
                    <th>IP Address</th>
                    <th>Risiko</th>
                    <th>Mode</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $log)
                    <tr>
                        <td>
                            @if ($log->selfie_path)
                                <img src="{{ asset('storage/' . $log->selfie_path) }}" class="selfie-img">
                            @else
                                <span style="font-size: 8px; font-weight: bold; color: #94a3b8;">NO PHOTO</span>
                            @endif
                        </td>
                        <td><strong>{{ $log->user->employee_id ?? 'N/A' }}</strong></td>
                        <td>{{ $log->user->name ?? 'N/A' }}</td>
                        <td><span
                                class="badge {{ $log->type === 'checkin' ? 'badge-success' : 'badge-info' }}">{{ strtoupper($log->type) }}</span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($log->timestamp)->translatedFormat('d M Y, H:i:s') }}</td>
                        <td>± {{ $log->accuracy ?? '0' }}m</td>
                        <td><code>{{ $log->ip_address }}</code></td>
                        <td>
                            <span
                                class="badge {{ $log->risk_level === 'high' ? 'badge-danger' : ($log->risk_level === 'medium' ? 'badge-warning' : 'badge-success') }}">
                                {{ $log->risk_level }}
                            </span>
                        </td>
                        <td>{{ strtoupper($log->work_mode) }}</td>
                        <td>
                            @if ($log->is_late)
                                <span class="badge badge-danger">TERLAMBAT</span>
                            @else
                                <span class="badge badge-success">ON TIME</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif($report_type === 'coordinates_log')
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Karyawan</th>
                    <th>Penempatan Cabang</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Presisi GPS</th>
                    <th>IP Address</th>
                    <th>Tingkat Risiko</th>
                    <th>Skor Kerawanan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $log)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($log->timestamp)->translatedFormat('d M Y, H:i') }}</td>
                        <td><strong>{{ $log->user->name ?? 'N/A' }}</strong></td>
                        <td>{{ $log->branch->name ?? 'N/A' }}</td>
                        <td><code>{{ $log->latitude }}</code></td>
                        <td><code>{{ $log->longitude }}</code></td>
                        <td>± {{ $log->accuracy }}m</td>
                        <td><code>{{ $log->ip_address }}</code></td>
                        <td>
                            <span
                                class="badge {{ $log->risk_level === 'high' ? 'badge-danger' : ($log->risk_level === 'medium' ? 'badge-warning' : 'badge-success') }}">
                                {{ $log->risk_level }}
                            </span>
                        </td>
                        <td><strong
                                style="color: {{ $log->risk_score > 50 ? '#b91c1c' : '#334155' }}">{{ $log->risk_score }}%</strong>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif($report_type === 'leaves_audit')
        <table>
            <thead>
                <tr>
                    <th>Karyawan</th>
                    <th>Tipe Permohonan</th>
                    <th>Mulai Tanggal</th>
                    <th>Hingga Tanggal</th>
                    <th>Durasi Cuti</th>
                    <th>Status Verifikasi</th>
                    <th>Catatan/Alasan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $leave)
                    @php
                        $start = \Carbon\Carbon::parse($leave->start_date);
                        $end = \Carbon\Carbon::parse($leave->end_date);
                        $days = $start->diffInDays($end) + 1;
                    @endphp
                    <tr>
                        <td><strong>{{ $leave->user->name ?? 'N/A' }}</strong></td>
                        <td><span class="badge badge-info">{{ strtoupper($leave->type) }}</span></td>
                        <td>{{ $start->translatedFormat('d M Y') }}</td>
                        <td>{{ $end->translatedFormat('d M Y') }}</td>
                        <td><strong>{{ $days }} Hari Kerja</strong></td>
                        <td>
                            <span
                                class="badge {{ $leave->status === 'approved' ? 'badge-success' : ($leave->status === 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                                {{ $leave->status }}
                            </span>
                        </td>
                        <td><em>"{{ $leave->reason }}"</em></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <table>
            <thead>
                <tr>
                    <th>Karyawan</th>
                    <th>Browser Klien</th>
                    <th>Sistem Operasi</th>
                    <th>Platform Perangkat</th>
                    <th>Sidik Jari Hash</th>
                    <th>Status Perangkat</th>
                    <th>Waktu Terakhir Absen</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $d)
                    <tr>
                        <td><strong>{{ $d->user->name ?? 'N/A' }}</strong></td>
                        <td>{{ $d->browser ?? 'N/A' }}</td>
                        <td>{{ $d->os ?? 'N/A' }}</td>
                        <td>{{ $d->platform ?? 'N/A' }}</td>
                        <td><code style="font-size: 10px;">{{ $d->device_hash }}</code></td>
                        <td>
                            <span class="badge {{ $d->trusted ? 'badge-success' : 'badge-danger' }}">
                                {{ $d->trusted ? 'TRUSTED' : 'UNTRUSTED' }}
                            </span>
                        </td>
                        <td>{{ $d->last_used_at ? \Carbon\Carbon::parse($d->last_used_at)->translatedFormat('d M Y, H:i') : 'Belum Pernah' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="signature-space">
        <p>Disetujui dan disahkan oleh,</p>
        <span class="signature-line"></span>
        <p style="margin-top: 5px; font-weight: 700;">HR & Super Admin AbsenPintar</p>
    </div>

    <div class="footer">
        <span>AbsenPintar Ledger - Dokumen ini sah dan dicetak secara elektronik dari server sistem absensi
            biometrik.</span>
        <span>Halaman 1 dari 1</span>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
