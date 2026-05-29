@props(['isAdmin' => false])

<!-- Attendance Detail Modal -->
<div x-show="showModal"
    class="fixed inset-0 z-[100] flex items-start justify-center p-4 py-8 overflow-y-auto bg-black/40 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    style="display: none;">

    <!-- Modal Card — White -->
    <div style="position: relative; width: 100%; max-width: 768px; background: #ffffff; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15); overflow: hidden;"
        @click.away="showModal = false"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="scale-95 translate-y-4 opacity-0"
        x-transition:enter-end="scale-100 translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="scale-100 translate-y-0 opacity-100"
        x-transition:leave-end="scale-95 translate-y-4 opacity-0">

        <!-- Header -->
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 36px; height: 36px; border-radius: 8px; background: #eff6ff; border: 1px solid #bfdbfe; display: flex; align-items: center; justify-content: center; color: #3b82f6;">
                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h3 style="font-size: 15px; font-weight: 900; color: #0f172a; letter-spacing: -0.025em; margin: 0;" x-text="'Detail ' + (selectedLog ? selectedLog.type : '')"></h3>
                    <p style="font-size: 10px; color: #94a3b8; font-weight: 600; margin: 2px 0 0 0;">Telemetri Kehadiran Resmi</p>
                </div>
            </div>
            <button @click="showModal = false" style="color: #94a3b8; background: #f1f5f9; border: 1px solid #e2e8f0; padding: 8px; border-radius: 8px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#e2e8f0';this.style.color='#334155'" onmouseout="this.style.background='#f1f5f9';this.style.color='#94a3b8'">
                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div x-show="selectedLog" style="padding: 20px;">

            <style>
                @media (min-width: 768px) {
                    .atd-grid { grid-template-columns: 280px 1fr !important; }
                    .atd-left { border-right: 1px solid #e2e8f0; padding-right: 20px; }
                }
                .atd-grid table td { padding: 9px 0; font-size: 12px; vertical-align: middle; }
                .atd-grid table tr { border-bottom: 1px solid #f1f5f9; }
                .atd-grid table tr:last-child { border-bottom: none; }
                .atd-label { font-weight: 700; color: #64748b; white-space: nowrap; }
                .atd-value { font-weight: 600; color: #1e293b; text-align: right; }
                .atd-value-bold { font-weight: 800; color: #0f172a; text-align: right; }
                .atd-mono { font-family: 'JetBrains Mono', ui-monospace, monospace; font-weight: 700; }
                .atd-section-title { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
                .atd-section-title svg { width: 14px; height: 14px; }
            </style>

            <div class="atd-grid" style="display: grid; grid-template-columns: 1fr; gap: 20px;">

                <!-- Col 1: Photo + Map -->
                <div class="atd-left" style="display: flex; flex-direction: column; gap: 16px;">

                    <!-- Foto Card -->
                    <div>
                        <div class="atd-section-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Foto
                        </div>
                        <div style="border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0; background: #f8fafc; position: relative;">
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
                        <div style="height: 160px; border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0;">
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
                                    <span class="atd-mono" style="font-size: 11px; color: #334155;" x-text="selectedLog ? selectedLog.latitude + ', ' + selectedLog.longitude : ''"></span>
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
                                    <span style="font-size: 12px; font-weight: 700; color: #334155;" x-text="selectedLog ? selectedLog.risk_score + '% Risk' : ''"></span>
                                    <span style="font-size: 9px; text-transform: uppercase; font-weight: 900; padding: 2px 6px; border-radius: 4px; border: 1px solid; margin-left: 4px;"
                                        :style="selectedLog && selectedLog.risk_class === 'high' ? 'background: rgba(244,63,94,0.08); border-color: rgba(244,63,94,0.2); color: #e11d48;' : (selectedLog && selectedLog.risk_class === 'medium' ? 'background: rgba(245,158,11,0.08); border-color: rgba(245,158,11,0.2); color: #d97706;' : 'background: rgba(16,185,129,0.08); border-color: rgba(16,185,129,0.2); color: #059669;')"
                                        x-text="selectedLog ? selectedLog.risk_level : ''"></span>
                                </td>
                            </tr>
                            <tr x-show="selectedLog && selectedLog.resolved_address">
                                <td colspan="2" style="vertical-align: top;">
                                    <span class="atd-label" style="display: block; margin-bottom: 2px;">Alamat Terdeteksi</span>
                                    <span style="font-weight: 500; color: #475569; line-height: 1.5; font-size: 12px;" x-text="selectedLog ? selectedLog.resolved_address : ''"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Catatan -->
                    <div x-show="selectedLog && selectedLog.notes" style="padding: 12px; border-radius: 8px; margin-top: 12px; border: 1px solid #e2e8f0; background: #f8fafc;">
                        <span style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 800; color: #94a3b8; display: block;">Catatan Kehadiran</span>
                        <p style="font-size: 12px; line-height: 1.5; font-weight: 600; font-style: italic; margin: 2px 0 0 0; color: #475569;" x-text="selectedLog ? selectedLog.notes : ''"></p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Footer -->
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; padding: 16px 20px; border-top: 1px solid #e2e8f0; background: #f8fafc; gap: 12px; border-radius: 0 0 16px 16px;">
            <div>
                @if ($isAdmin)
                    <template x-if="selectedLog && selectedLog.status_class !== 'approved' && selectedLog.status_class !== 'rejected'">
                        <div style="display: flex; gap: 8px;">
                            <button type="button" @click="$wire.approveAttendance(selectedLog.id).then(() => { showModal = false; })"
                                style="padding: 8px 16px; font-size: 12px; font-weight: 900; border-radius: 8px; background: #10b981; color: #fff; border: none; display: flex; align-items: center; gap: 6px; cursor: pointer; transition: all 0.2s;"
                                onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                Setujui
                            </button>
                            <button type="button" @click="$wire.rejectAttendance(selectedLog.id).then(() => { showModal = false; })"
                                style="padding: 8px 16px; font-size: 12px; font-weight: 900; border-radius: 8px; background: rgba(244,63,94,0.08); color: #e11d48; border: 1px solid rgba(244,63,94,0.2); display: flex; align-items: center; gap: 6px; cursor: pointer; transition: all 0.2s;"
                                onmouseover="this.style.background='rgba(244,63,94,0.15)'" onmouseout="this.style.background='rgba(244,63,94,0.08)'">
                                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                                Tolak
                            </button>
                        </div>
                    </template>
                @endif
            </div>
            <button type="button" @click="showModal = false"
                style="padding: 8px 20px; font-size: 12px; font-weight: 900; border-radius: 8px; background: #3b82f6; color: #fff; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(59,130,246,0.25); transition: all 0.2s; width: auto; text-align: center;"
                onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
                Tutup Detail
            </button>
        </div>

    </div>
</div>
