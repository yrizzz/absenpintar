@props(['isAdmin' => false])

<!-- Attendance Detail Modal CSS Style System -->
<style>
    /* Modal Backdrop Overlay */
    .atd-modal-backdrop {
        position: fixed;
        inset: 0;
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        background: rgba(0, 0, 0, 0.45) !important;
        backdrop-filter: blur(8px) !important;
        -webkit-backdrop-filter: blur(8px) !important;
    }

    html.light .atd-modal-backdrop {
        background: rgba(15, 23, 42, 0.3) !important;
        backdrop-filter: blur(8px) !important;
        -webkit-backdrop-filter: blur(8px) !important;
    }

    /* Modal Card */
    .atd-modal-card {
        position: relative;
        width: 100%;
        max-width: 768px;
        background: #121d33 !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: #f1f5f9 !important;
        border-radius: 24px !important;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5) !important;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        max-height: 90vh;
    }

    html.light .atd-modal-card {
        background: #ffffff !important;
        border: 1px solid rgba(15, 23, 42, 0.08) !important;
        color: #0f172a !important;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15) !important;
    }

    /* Header */
    .atd-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 24px;
        background: #162238 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
    }

    html.light .atd-modal-header {
        background: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }

    /* Header icon */
    .atd-header-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(59, 130, 246, 0.1) !important;
        border: 1px solid rgba(59, 130, 246, 0.2) !important;
        color: #60a5fa !important;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    html.light .atd-header-icon {
        background: #eff6ff !important;
        border: 1px solid #bfdbfe !important;
        color: #3b82f6 !important;
    }

    /* Header close button */
    .atd-header-close-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        color: #94a3b8 !important;
        cursor: pointer;
        transition: all 0.2s;
    }
    .atd-header-close-btn:hover {
        background: rgba(255, 255, 255, 0.1) !important;
        color: #ffffff !important;
    }
    html.light .atd-header-close-btn {
        background: #f1f5f9 !important;
        border: 1px solid #e2e8f0 !important;
        color: #64748b !important;
    }
    html.light .atd-header-close-btn:hover {
        background: #e2e8f0 !important;
        color: #0f172a !important;
    }

    /* Footer */
    .atd-modal-footer {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        padding: 18px 24px;
        background: #162238 !important;
        border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
        gap: 12px;
    }

    html.light .atd-modal-footer {
        background: #f8fafc !important;
        border-top: 1px solid #e2e8f0 !important;
    }

    /* Media queries & Layout */
    @media (min-width: 768px) {
        .atd-grid {
            grid-template-columns: 280px 1fr !important;
        }
        .atd-left {
            border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
            padding-right: 20px;
        }
        html.light .atd-left {
            border-right: 1px solid #e2e8f0 !important;
        }
    }

    .atd-grid table td {
        padding: 10px 0;
        font-size: 12px;
        vertical-align: middle;
    }
    .atd-grid table tr {
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
    }
    html.light .atd-grid table tr {
        border-bottom: 1px solid #f1f5f9 !important;
    }
    .atd-grid table tr:last-child {
        border-bottom: none !important;
    }

    .atd-label {
        font-weight: 700;
        color: #94a3b8;
        white-space: nowrap;
    }
    html.light .atd-label {
        color: #64748b;
    }

    .atd-value {
        font-weight: 600;
        color: #f1f5f9;
        text-align: right;
    }
    html.light .atd-value {
        color: #1e293b;
    }

    .atd-value-bold {
        font-weight: 850;
        color: #ffffff;
        text-align: right;
    }
    html.light .atd-value-bold {
        color: #0f172a;
    }

    .atd-mono {
        font-family: 'JetBrains Mono', ui-monospace, monospace;
        font-weight: 700;
    }
    .atd-section-title {
        font-size: 10px;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    html.light .atd-section-title {
        color: #64748b;
    }
    .atd-section-title svg {
        width: 14px;
        height: 14px;
    }

    /* Foto Container border */
    .atd-foto-box {
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        background: #0f172a;
        position: relative;
    }
    html.light .atd-foto-box {
        border: 1px solid #e2e8f0 !important;
        background: #f8fafc;
    }

    /* Map Container border */
    .atd-map-box {
        height: 160px;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    html.light .atd-map-box {
        border: 1px solid #e2e8f0 !important;
    }

    /* Catatan Box */
    .atd-notes-box {
        padding: 12px;
        border-radius: 12px;
        margin-top: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        background: rgba(255, 255, 255, 0.02) !important;
    }
    html.light .atd-notes-box {
        border: 1px solid #e2e8f0 !important;
        background: #f8fafc !important;
    }

    /* Premium Button Stylings */
    .atd-btn-approve {
        padding: 10px 20px !important;
        font-size: 12px !important;
        font-weight: 900 !important;
        border-radius: 12px !important;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        color: #ffffff !important;
        border: none !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        cursor: pointer !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25) !important;
        transition: all 0.2s !important;
    }
    .atd-btn-approve:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35) !important;
    }

    .atd-btn-reject {
        padding: 10px 20px !important;
        font-size: 12px !important;
        font-weight: 900 !important;
        border-radius: 12px !important;
        background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%) !important;
        color: #ffffff !important;
        border: none !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        cursor: pointer !important;
        box-shadow: 0 4px 12px rgba(244, 63, 94, 0.25) !important;
        transition: all 0.2s !important;
    }
    .atd-btn-reject:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 16px rgba(244, 63, 94, 0.35) !important;
    }

    .atd-btn-close {
        padding: 10px 24px !important;
        font-size: 12px !important;
        font-weight: 900 !important;
        border-radius: 12px !important;
        background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%) !important;
        color: #ffffff !important;
        border: none !important;
        cursor: pointer !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25) !important;
        transition: all 0.2s !important;
    }
    .atd-btn-close:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35) !important;
    }
</style>

<!-- Attendance Detail Modal -->
<div x-show="showModal"
    class="atd-modal-backdrop"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    style="display: none;">

    <!-- Modal Card Wrapper -->
    <div class="atd-modal-card"
        @click.away="showModal = false"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="scale-95 translate-y-4 opacity-0"
        x-transition:enter-end="scale-100 translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="scale-100 translate-y-0 opacity-100"
        x-transition:leave-end="scale-95 translate-y-4 opacity-0">

        <!-- Header -->
        <div class="atd-modal-header">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="atd-header-icon">
                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h3 style="font-size: 15px; font-weight: 900; letter-spacing: -0.025em; margin: 0; color: inherit;" x-text="'Detail ' + (selectedLog ? selectedLog.type : '')"></h3>
                    <p style="font-size: 10px; color: #94a3b8; font-weight: 600; margin: 2px 0 0 0;">Telemetri Kehadiran Resmi</p>
                </div>
            </div>
            <button @click="showModal = false" class="atd-header-close-btn">
                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div x-show="selectedLog" style="padding: 24px; overflow-y: auto; flex-grow: 1;">

            <div class="atd-grid" style="display: grid; grid-template-columns: 1fr; gap: 20px;">

                <!-- Col 1: Photo + Map -->
                <div class="atd-left" style="display: flex; flex-direction: column; gap: 16px;">

                    <!-- Foto Card -->
                    <div>
                        <div class="atd-section-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Foto
                        </div>
                        <div class="atd-foto-box">
                            <template x-if="selectedLog && selectedLog.selfie_url">
                                <img :src="selectedLog.selfie_url" alt="Selfie" style="width: 100%; height: auto; max-height: 220px; object-fit: cover; transform: scaleX(-1); display: block;">
                            </template>
                            <template x-if="selectedLog && !selectedLog.selfie_url">
                                <div style="padding: 40px 0; text-align: center; color: #94a3b8;">
                                    <svg style="width: 32px; height: 32px; margin: 0 auto 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">No Selfie</span>
                                </div>
                            </template>
                            <!-- Badges -->
                            <div style="position: absolute; bottom: 6px; left: 6px; right: 6px; display: flex; justify-content: space-between; pointer-events: none;">
                                <span style="padding: 2px 6px; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); border-radius: 4px; font-size: 7px; font-weight: 900; color: #34d399; text-transform: uppercase; letter-spacing: 0.05em;">Liveness ✓</span>
                                <span style="padding: 2px 6px; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); border-radius: 4px; font-size: 7px; font-weight: 900; color: #60a5fa; text-transform: uppercase; letter-spacing: 0.05em;" x-text="selectedLog ? selectedLog.work_mode : ''"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Maps Card -->
                    <div>
                        <div class="atd-section-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Maps
                        </div>
                        <div class="atd-map-box">
                            <iframe
                                :src="selectedLog ? 'https://www.google.com/maps?q=' + selectedLog.latitude + ',' + selectedLog.longitude + '&z=17&output=embed&hl=id' : ''"
                                style="width: 100%; height: 100%; border: 0;"
                                allowfullscreen
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>

                <!-- Col 2: Data Parameters -->
                <div>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tbody>
                            <tr>
                                <td class="atd-label">Nama Karyawan</td>
                                <td class="atd-value-bold" x-text="selectedLog ? selectedLog.employee_name : ''"></td>
                            </tr>
                            <tr>
                                <td class="atd-label">Waktu Absen</td>
                                <td class="atd-value" x-text="selectedLog ? selectedLog.timestamp : ''"></td>
                            </tr>
                            <tr>
                                <td class="atd-label">Status Validasi</td>
                                <td style="text-align: right;">
                                    <span style="display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 4px; font-size: 9px; font-weight: 900; border: 1px solid; text-transform: uppercase;"
                                        :style="selectedLog && selectedLog.status_class === 'approved' ? 'background: rgba(16,185,129,0.08); border-color: rgba(16,185,129,0.2); color: #059669;' : (selectedLog && selectedLog.status_class === 'flagged' ? 'background: rgba(244,63,94,0.08); border-color: rgba(244,63,94,0.2); color: #e11d48;' : 'background: rgba(245,158,11,0.08); border-color: rgba(245,158,11,0.2); color: #d97706;')"
                                        x-text="selectedLog ? selectedLog.status : ''"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="atd-label">Ketepatan Waktu</td>
                                <td style="text-align: right;">
                                    <template x-if="selectedLog && selectedLog.is_late">
                                        <span style="display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 4px; font-size: 9px; font-weight: 900; text-transform: uppercase; background: rgba(244,63,94,0.08); border: 1px solid rgba(244,63,94,0.2); color: #e11d48;">Terlambat</span>
                                    </template>
                                    <template x-if="selectedLog && !selectedLog.is_late">
                                        <span style="display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 4px; font-size: 9px; font-weight: 900; text-transform: uppercase; background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.2); color: #059669;">Tepat Waktu</span>
                                    </template>
                                </td>
                            </tr>
                            <tr>
                                <td class="atd-label">Cabang</td>
                                <td class="atd-value" x-text="selectedLog ? selectedLog.branch_name : ''"></td>
                            </tr>
                            <tr>
                                <td class="atd-label">IP Address</td>
                                <td class="atd-value atd-mono" x-text="selectedLog ? selectedLog.ip_address : ''"></td>
                            </tr>
                            <tr>
                                <td class="atd-label">Presisi GPS</td>
                                <td style="text-align: right; font-size: 12px; font-weight: 700; color: #059669;" x-text="selectedLog ? '± ' + parseFloat(selectedLog.accuracy).toFixed(2) + ' m' : ''"></td>
                            </tr>
                            <tr>
                                <td class="atd-label">Koordinat</td>
                                <td style="text-align: right;">
                                    <span class="atd-mono" style="font-size: 11px;" :style="document.documentElement.classList.contains('light') ? 'color: #334155;' : 'color: #cbd5e1;'" x-text="selectedLog ? selectedLog.latitude + ', ' + selectedLog.longitude : ''"></span>
                                    <button @click="navigator.clipboard.writeText(selectedLog.latitude + ', ' + selectedLog.longitude); showToast('Koordinat disalin!', 'success')"
                                        style="font-size: 9px; background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; padding: 2px 6px; border-radius: 4px; font-weight: 700; text-transform: uppercase; cursor: pointer; margin-left: 6px; transition: all 0.2s;"
                                        onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                                        Salin
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="atd-label">Keamanan Biometrik</td>
                                <td style="text-align: right;">
                                    <span style="font-size: 12px; font-weight: 700;" :style="document.documentElement.classList.contains('light') ? 'color: #334155;' : 'color: #e2e8f0;'" x-text="selectedLog ? selectedLog.risk_score + '% Risk' : ''"></span>
                                    <span style="font-size: 9px; text-transform: uppercase; font-weight: 900; padding: 2px 6px; border-radius: 4px; border: 1px solid; margin-left: 4px;"
                                        :style="selectedLog && selectedLog.risk_class === 'high' ? 'background: rgba(244,63,94,0.08); border-color: rgba(244,63,94,0.2); color: #e11d48;' : (selectedLog && selectedLog.risk_class === 'medium' ? 'background: rgba(245,158,11,0.08); border-color: rgba(245,158,11,0.2); color: #d97706;' : 'background: rgba(16,185,129,0.08); border-color: rgba(16,185,129,0.2); color: #059669;')"
                                        x-text="selectedLog ? selectedLog.risk_level : ''"></span>
                                </td>
                            </tr>
                            <tr x-show="selectedLog && selectedLog.resolved_address">
                                <td colspan="2" style="vertical-align: top;">
                                    <span class="atd-label" style="display: block; margin-bottom: 2px;">Alamat Terdeteksi</span>
                                    <span style="font-weight: 500; color: #94a3b8; line-height: 1.5; font-size: 12px;" :style="document.documentElement.classList.contains('light') ? 'color: #475569;' : 'color: #94a3b8;'" x-text="selectedLog ? selectedLog.resolved_address : ''"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Catatan -->
                    <div class="atd-notes-box" x-show="selectedLog && selectedLog.notes">
                        <span style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 800; color: #94a3b8; display: block;">Catatan Kehadiran</span>
                        <p style="font-size: 12px; line-height: 1.5; font-weight: 600; font-style: italic; margin: 2px 0 0 0;" :style="document.documentElement.classList.contains('light') ? 'color: #475569;' : 'color: #cbd5e1;'" x-text="selectedLog ? selectedLog.notes : ''"></p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Footer -->
        <div class="atd-modal-footer">
            <div>
                @if ($isAdmin)
                    <template x-if="selectedLog && selectedLog.status_class !== 'approved' && selectedLog.status_class !== 'rejected'">
                        <div style="display: flex; gap: 10px;">
                            <button type="button" @click="$wire.call('approveAttendance', selectedLog.id); showModal = false;" class="atd-btn-approve">
                                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Setujui
                            </button>
                            <button type="button" @click="$wire.call('rejectAttendance', selectedLog.id); showModal = false;" class="atd-btn-reject">
                                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Tolak
                            </button>
                        </div>
                    </template>
                @endif
            </div>
            <button type="button" @click="showModal = false" class="atd-btn-close">
                Tutup Detail
            </button>
        </div>

    </div>
</div>
