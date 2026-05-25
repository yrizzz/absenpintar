<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class AttendanceLogsExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithDrawings, WithEvents
{
    protected $logs;
    protected $reportType;
    protected $title;
    protected $arrayData = [];

    public function __construct($logs, string $reportType, string $title)
    {
        $this->logs = $logs;
        $this->reportType = $reportType;
        $this->title = $title;

        // Build array data for cells
        if ($this->reportType === 'presence_summary') {
            foreach ($this->logs as $log) {
                $this->arrayData[] = [
                    $log->user->employee_id ?? 'N/A',
                    $log->user->name ?? 'N/A',
                    strtoupper($log->type),
                    $log->timestamp,
                    $log->accuracy,
                    $log->ip_address,
                    strtoupper($log->risk_level),
                    strtoupper($log->status),
                    strtoupper($log->work_mode),
                    $log->is_late ? 'YA' : 'TIDAK',
                    '' // Anchor for image drawing
                ];
            }
        } elseif ($this->reportType === 'coordinates_log') {
            foreach ($this->logs as $log) {
                $this->arrayData[] = [
                    $log->timestamp,
                    $log->user->name ?? 'N/A',
                    $log->branch->name ?? 'N/A',
                    $log->latitude,
                    $log->longitude,
                    $log->accuracy,
                    $log->ip_address,
                    strtoupper($log->risk_level),
                    $log->risk_score
                ];
            }
        } elseif ($this->reportType === 'leaves_audit') {
            foreach ($this->logs as $leave) {
                $start = \Carbon\Carbon::parse($leave->start_date);
                $end = \Carbon\Carbon::parse($leave->end_date);
                $days = $start->diffInDays($end) + 1;

                $this->arrayData[] = [
                    $leave->user->name ?? 'N/A',
                    strtoupper($leave->type),
                    $leave->start_date,
                    $leave->end_date,
                    $days,
                    strtoupper($leave->status),
                    $leave->reason
                ];
            }
        } else { // system_logs
            foreach ($this->logs as $d) {
                $this->arrayData[] = [
                    $d->user->name ?? 'N/A',
                    $d->browser,
                    $d->os,
                    $d->platform,
                    $d->device_hash,
                    $d->trusted ? 'TERPERCAYA' : 'TIDAK TERPERCAYA',
                    $d->last_used_at
                ];
            }
        }
    }

    public function array(): array
    {
        return $this->arrayData;
    }

    public function headings(): array
    {
        if ($this->reportType === 'presence_summary') {
            return ['ID Karyawan', 'Nama Karyawan', 'Tipe Absen', 'Waktu Kehadiran', 'Akurasi (m)', 'IP Address', 'Tingkat Risiko', 'Status', 'Mode Kerja', 'Keterlambatan', 'Foto Selfie'];
        } elseif ($this->reportType === 'coordinates_log') {
            return ['Waktu', 'Nama Karyawan', 'Cabang', 'Latitude', 'Longitude', 'Presisi GPS', 'IP Address', 'Tingkat Risiko', 'Skor Risiko'];
        } elseif ($this->reportType === 'leaves_audit') {
            return ['Nama Karyawan', 'Tipe Cuti', 'Tanggal Mulai', 'Tanggal Selesai', 'Total Hari', 'Status Persetujuan', 'Alasan'];
        } else {
            return ['Nama Karyawan', 'Browser', 'Sistem Operasi', 'Platform', 'Device Hash', 'Status Terpercaya', 'Terakhir Digunakan'];
        }
    }

    public function title(): string
    {
        return $this->title;
    }

    public function drawings()
    {
        $drawings = [];

        // Only draw selfies in presence_summary
        if ($this->reportType === 'presence_summary') {
            $rowNumber = 2; // Row 1 is headings
            foreach ($this->logs as $log) {
                if ($log->selfie_path) {
                    $absolutePath = storage_path('app/public/' . $log->selfie_path);
                    if (file_exists($absolutePath)) {
                        $drawing = new Drawing();
                        $drawing->setName('Selfie');
                        $drawing->setDescription('Selfie Karyawan');
                        $drawing->setPath($absolutePath);
                        $drawing->setHeight(40);
                        // Column K is index 11 (K)
                        $drawing->setCoordinates('K' . $rowNumber);
                        $drawing->setOffsetX(10);
                        $drawing->setOffsetY(5);
                        $drawings[] = $drawing;
                    }
                }
                $rowNumber++;
            }
        }

        return $drawings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Style header row
                $event->sheet->getDelegate()->getStyle('A1:K1')->getFont()->setBold(true);
                
                // Adjust row heights for drawings
                if ($this->reportType === 'presence_summary') {
                    $totalRows = count($this->arrayData) + 1;
                    for ($i = 2; $i <= $totalRows; $i++) {
                        $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(50);
                    }
                    
                    // Force set width of Column K for selfies
                    $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(18);
                }
            },
        ];
    }
}
