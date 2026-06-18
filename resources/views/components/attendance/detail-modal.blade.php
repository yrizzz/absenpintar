@props(['isAdmin' => false])

{{-- Attendance Detail Modal — token-driven, theme-aware via global .dark tokens --}}
<style>
    .atd-modal-backdrop {
        position: fixed; inset: 0; z-index: 100;
        display: flex; align-items: center; justify-content: center; padding: 16px;
        background: color-mix(in srgb, #0f172a 45%, transparent);
        backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
    }
    .atd-modal-card {
        position: relative; width: 100%; max-width: 768px;
        background: var(--surface); color: var(--fg);
        border: 1px solid var(--border); border-radius: 16px;
        box-shadow: var(--shadow-md); overflow: hidden;
        display: flex; flex-direction: column; max-height: 90vh;
    }
    .atd-modal-header, .atd-modal-footer {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 24px; background: var(--surface-muted);
    }
    .atd-modal-header { border-bottom: 1px solid var(--border); }
    .atd-modal-footer { border-top: 1px solid var(--border); flex-wrap: wrap; gap: 12px; }

    .atd-header-icon {
        width: 36px; height: 36px; border-radius: 10px;
        background: var(--primary-soft); color: var(--primary);
        display: flex; align-items: center; justify-content: center;
    }
    .atd-header-close-btn {
        display: flex; align-items: center; justify-content: center; padding: 8px;
        border-radius: 8px; background: var(--surface); border: 1px solid var(--border);
        color: var(--fg-muted); cursor: pointer; transition: background-color .15s, color .15s;
    }
    .atd-header-close-btn:hover { background: var(--surface-muted); color: var(--fg); }

    @media (min-width: 768px) {
        .atd-grid { grid-template-columns: 280px 1fr; }
        .atd-left { border-right: 1px solid var(--border); padding-right: 20px; }
    }

    .atd-grid { display: grid; grid-template-columns: 1fr; gap: 20px; }
    .atd-grid table td { padding: 10px 0; font-size: 12px; vertical-align: middle; }
    .atd-grid table tr { border-bottom: 1px solid var(--border); }
    .atd-grid table tr:last-child { border-bottom: none; }

    .atd-label      { font-weight: 600; color: var(--fg-muted); white-space: nowrap; }
    .atd-value      { font-weight: 600; color: var(--fg); text-align: right; }
    .atd-value-bold { font-weight: 700; color: var(--fg); text-align: right; }
    .atd-mono       { font-family: var(--font-mono); font-weight: 600; }
    .atd-coord-text { font-size: 11px; color: var(--fg-muted); }
    .atd-risk-score { font-size: 12px; font-weight: 600; color: var(--fg); }
    .atd-muted-text { font-size: 12px; font-weight: 500; line-height: 1.5; color: var(--fg-muted); }
    .atd-section-title {
        font-size: 11px; font-weight: 700; color: var(--fg-muted);
        text-transform: uppercase; letter-spacing: .06em; margin-bottom: 8px;
        display: flex; align-items: center; gap: 6px;
    }
    .atd-section-title svg { width: 14px; height: 14px; }

    .atd-foto-box {
        border-radius: 12px; overflow: hidden; border: 1px solid var(--border);
        background: var(--surface-muted); position: relative;
    }
    .atd-map-box { height: 160px; border-radius: 12px; overflow: hidden; border: 1px solid var(--border); }
    .atd-notes-box {
        padding: 12px; border-radius: 10px; margin-top: 12px;
        border: 1px solid var(--border); background: var(--surface-muted);
    }
</style>

<div x-show="showModal" x-cloak class="atd-modal-backdrop"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

    <div class="atd-modal-card" @click.away="showModal = false"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="scale-95 translate-y-4 opacity-0" x-transition:enter-end="scale-100 translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="scale-100 translate-y-0 opacity-100" x-transition:leave-end="scale-95 translate-y-4 opacity-0">

        {{-- Header --}}
        <div class="atd-modal-header">
            <div style="display:flex; align-items:center; gap:12px;">
                <div class="atd-header-icon">
                    <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                </div>
                <div>
                    <h3 style="font-size:15px; font-weight:700; letter-spacing:-.025em; margin:0; color:inherit;" x-text="'Detail ' + (selectedLog ? selectedLog.type : '')"></h3>
                    <p style="font-size:11px; color:var(--fg-muted); font-weight:500; margin:2px 0 0 0;">Telemetri kehadiran resmi</p>
                </div>
            </div>
            <button @click="showModal = false" class="atd-header-close-btn">
                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        {{-- Body --}}
        <div x-show="selectedLog" style="padding:24px; overflow-y:auto; flex-grow:1;">
            <div class="atd-grid">

                {{-- Col 1: photo + map --}}
                <div class="atd-left" style="display:flex; flex-direction:column; gap:16px;">
                    <div>
                        <div class="atd-section-title">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Foto
                        </div>
                        <div class="atd-foto-box">
                            <template x-if="selectedLog && selectedLog.selfie_url">
                                <img :src="selectedLog.selfie_url" alt="Selfie" style="width:100%; height:auto; max-height:220px; object-fit:cover; transform:scaleX(-1); display:block;">
                            </template>
                            <template x-if="selectedLog && !selectedLog.selfie_url">
                                <div style="padding:40px 0; text-align:center; color:var(--fg-subtle);">
                                    <svg style="width:32px;height:32px;margin:0 auto 6px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    <span style="font-size:10px; font-weight:600; text-transform:uppercase; letter-spacing:.05em;">Tidak ada selfie</span>
                                </div>
                            </template>
                            <div style="position:absolute; bottom:6px; left:6px; right:6px; display:flex; justify-content:space-between; pointer-events:none;">
                                <span style="padding:3px 7px; background:rgba(0,0,0,.55); backdrop-filter:blur(4px); border-radius:5px; font-size:9px; font-weight:700; color:#34d399; text-transform:uppercase; letter-spacing:.05em;">Liveness ✓</span>
                                <span style="padding:3px 7px; background:rgba(0,0,0,.55); backdrop-filter:blur(4px); border-radius:5px; font-size:9px; font-weight:700; color:#60a5fa; text-transform:uppercase; letter-spacing:.05em;" x-text="selectedLog ? selectedLog.work_mode : ''"></span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="atd-section-title">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Maps
                        </div>
                        <div class="atd-map-box">
                            <iframe :src="selectedLog ? 'https://www.google.com/maps?q=' + selectedLog.latitude + ',' + selectedLog.longitude + '&z=17&output=embed&hl=id' : ''"
                                style="width:100%; height:100%; border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>

                {{-- Col 2: parameters --}}
                <div>
                    <table style="width:100%; border-collapse:collapse;">
                        <tbody>
                            <tr><td class="atd-label">Nama Karyawan</td><td class="atd-value-bold" x-text="selectedLog ? selectedLog.employee_name : ''"></td></tr>
                            <tr><td class="atd-label">Waktu Absen</td><td class="atd-value" x-text="selectedLog ? selectedLog.timestamp : ''"></td></tr>
                            <tr>
                                <td class="atd-label">Status Validasi</td>
                                <td style="text-align:right;">
                                    <span style="display:inline-flex; align-items:center; padding:2px 8px; border-radius:6px; font-size:9px; font-weight:700; border:1px solid; text-transform:uppercase;"
                                        :style="selectedLog && selectedLog.status_class === 'approved' ? 'background:var(--success-soft); border-color:var(--success); color:var(--success);' : (selectedLog && selectedLog.status_class === 'flagged' ? 'background:var(--danger-soft); border-color:var(--danger); color:var(--danger);' : 'background:var(--warning-soft); border-color:var(--warning); color:var(--warning);')"
                                        x-text="selectedLog ? selectedLog.status : ''"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="atd-label">Ketepatan Waktu</td>
                                <td style="text-align:right;">
                                    <template x-if="selectedLog && selectedLog.is_late">
                                        <span style="display:inline-flex; align-items:center; padding:2px 8px; border-radius:6px; font-size:9px; font-weight:700; text-transform:uppercase; background:var(--danger-soft); border:1px solid var(--danger); color:var(--danger);">Terlambat</span>
                                    </template>
                                    <template x-if="selectedLog && !selectedLog.is_late">
                                        <span style="display:inline-flex; align-items:center; padding:2px 8px; border-radius:6px; font-size:9px; font-weight:700; text-transform:uppercase; background:var(--success-soft); border:1px solid var(--success); color:var(--success);">Tepat Waktu</span>
                                    </template>
                                </td>
                            </tr>
                            <tr><td class="atd-label">Cabang</td><td class="atd-value" x-text="selectedLog ? selectedLog.branch_name : ''"></td></tr>
                            <tr><td class="atd-label">IP Address</td><td class="atd-value atd-mono" x-text="selectedLog ? selectedLog.ip_address : ''"></td></tr>
                            <tr><td class="atd-label">Presisi GPS</td><td style="text-align:right; font-size:12px; font-weight:600; color:var(--success);" x-text="selectedLog ? '± ' + parseFloat(selectedLog.accuracy).toFixed(2) + ' m' : ''"></td></tr>
                            <tr>
                                <td class="atd-label">Koordinat</td>
                                <td style="text-align:right;">
                                    <span class="atd-mono atd-coord-text" x-text="selectedLog ? selectedLog.latitude + ', ' + selectedLog.longitude : ''"></span>
                                    <button @click="navigator.clipboard.writeText(selectedLog.latitude + ', ' + selectedLog.longitude); showToast('Koordinat disalin!', 'success')"
                                        style="font-size:9px; background:var(--surface-muted); color:var(--fg-muted); border:1px solid var(--border); padding:2px 6px; border-radius:5px; font-weight:600; text-transform:uppercase; cursor:pointer; margin-left:6px;">Salin</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="atd-label">Keamanan Wajah</td>
                                <td style="text-align:right;">
                                    <span class="atd-risk-score" x-text="selectedLog ? selectedLog.risk_score + '% Risk' : ''"></span>
                                    <span style="font-size:9px; text-transform:uppercase; font-weight:700; padding:2px 6px; border-radius:6px; border:1px solid; margin-left:4px;"
                                        :style="selectedLog && selectedLog.risk_class === 'high' ? 'background:var(--danger-soft); border-color:var(--danger); color:var(--danger);' : (selectedLog && selectedLog.risk_class === 'medium' ? 'background:var(--warning-soft); border-color:var(--warning); color:var(--warning);' : 'background:var(--success-soft); border-color:var(--success); color:var(--success);')"
                                        x-text="selectedLog ? selectedLog.risk_level : ''"></span>
                                </td>
                            </tr>
                            <tr x-show="selectedLog && selectedLog.resolved_address">
                                <td colspan="2" style="vertical-align:top;">
                                    <span class="atd-label" style="display:block; margin-bottom:2px;">Alamat Terdeteksi</span>
                                    <span class="atd-muted-text" x-text="selectedLog ? selectedLog.resolved_address : ''"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="atd-notes-box" x-show="selectedLog && selectedLog.notes">
                        <span style="font-size:9px; text-transform:uppercase; letter-spacing:.05em; font-weight:700; color:var(--fg-muted); display:block;">Catatan Kehadiran</span>
                        <p class="atd-muted-text" style="font-weight:500; font-style:italic; margin:2px 0 0 0;" x-text="selectedLog ? selectedLog.notes : ''"></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="atd-modal-footer">
            <div>
                @if ($isAdmin)
                    <template x-if="selectedLog && selectedLog.status_class !== 'approved' && selectedLog.status_class !== 'rejected'">
                        <div style="display:flex; gap:10px;">
                            <button type="button" @click="$wire.call('approveAttendance', selectedLog.id); showModal = false;" class="btn-success btn-sm">
                                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                Setujui
                            </button>
                            <button type="button" @click="$wire.call('rejectAttendance', selectedLog.id); showModal = false;" class="btn-danger btn-sm">
                                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                Tolak
                            </button>
                        </div>
                    </template>
                @endif
            </div>
            <button type="button" @click="showModal = false" class="btn-primary btn-sm">Tutup Detail</button>
        </div>

    </div>
</div>
