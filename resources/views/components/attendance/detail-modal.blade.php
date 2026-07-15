@props(['isAdmin' => false])

{{-- Attendance Detail Modal — modern two-column, pill-driven, theme-aware via global .dark tokens --}}
<style>
    .atd-modal-backdrop {
        position: fixed; inset: 0; z-index: 100;
        display: flex; align-items: center; justify-content: center; padding: 16px;
        background: color-mix(in srgb, #0f172a 50%, transparent);
        backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
    }
    .atd-modal-card {
        position: relative; width: 100%; max-width: 780px;
        background: var(--surface); color: var(--fg);
        border: 1px solid var(--border); border-radius: 20px;
        box-shadow: var(--shadow-md); overflow: hidden;
        display: flex; flex-direction: column; max-height: 92vh;
    }

    /* Header */
    .atd-modal-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 18px 22px; border-bottom: 1px solid var(--border);
        background: linear-gradient(180deg, var(--surface-muted), var(--surface));
    }
    .atd-header-icon {
        width: 40px; height: 40px; border-radius: 12px;
        background: var(--primary-soft); color: var(--primary);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .atd-header-close-btn {
        display: flex; align-items: center; justify-content: center; padding: 9px;
        border-radius: 10px; background: var(--surface); border: 1px solid var(--border);
        color: var(--fg-muted); cursor: pointer; transition: background-color .15s, color .15s; flex-shrink: 0;
    }
    .atd-header-close-btn:hover { background: var(--surface-muted); color: var(--fg); }

    /* Body grid */
    .atd-body { padding: 22px; overflow-y: auto; flex-grow: 1; }
    .atd-grid { display: grid; grid-template-columns: 1fr; gap: 22px; }
    @media (min-width: 740px) {
        .atd-grid { grid-template-columns: 286px 1fr; }
    }

    .atd-section-title {
        font-size: 10px; font-weight: 700; color: var(--fg-subtle);
        text-transform: uppercase; letter-spacing: .07em; margin-bottom: 9px;
        display: flex; align-items: center; gap: 6px;
    }
    .atd-section-title svg { width: 13px; height: 13px; }

    /* Left column media */
    .atd-foto-box {
        border-radius: 14px; overflow: hidden; border: 1px solid var(--border);
        background: var(--surface-muted); position: relative; aspect-ratio: 3 / 4;
    }
    .atd-foto-box img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .atd-media-badge {
        padding: 4px 8px; border-radius: 7px; font-size: 9px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em;
        background: rgba(0,0,0,.58); backdrop-filter: blur(4px);
    }
    .atd-map-box { height: 168px; border-radius: 14px; overflow: hidden; border: 1px solid var(--border); }

    /* Right column */
    .atd-name { font-size: 19px; font-weight: 800; letter-spacing: -.02em; color: var(--fg); margin: 0; line-height: 1.2; }
    .atd-subtime { font-size: 12px; color: var(--fg-muted); font-weight: 500; margin: 4px 0 0 0; display: flex; align-items: center; gap: 6px; }

    .atd-badges { display: flex; flex-wrap: wrap; gap: 7px; margin: 14px 0 4px; }
    .atd-pill {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 5px 11px; border-radius: 999px; font-size: 10px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .03em; border: 1px solid;
    }
    .atd-pill svg { width: 11px; height: 11px; }

    .atd-rows { margin-top: 16px; }
    .atd-row {
        display: flex; align-items: center; justify-content: space-between; gap: 12px;
        padding: 11px 0; border-bottom: 1px dashed var(--border);
    }
    .atd-row:last-child { border-bottom: none; }
    .atd-row-label {
        font-size: 11px; font-weight: 600; color: var(--fg-muted);
        display: flex; align-items: center; gap: 7px; white-space: nowrap;
    }
    .atd-row-label svg { width: 14px; height: 14px; color: var(--fg-subtle); }
    .atd-row-value { font-size: 12.5px; font-weight: 700; color: var(--fg); text-align: right; }
    .atd-mono { font-family: var(--font-mono); }

    .atd-copy-btn {
        font-size: 9px; background: var(--surface-muted); color: var(--fg-muted);
        border: 1px solid var(--border); padding: 3px 7px; border-radius: 6px;
        font-weight: 700; text-transform: uppercase; cursor: pointer; margin-left: 8px;
        transition: background-color .15s, color .15s;
    }
    .atd-copy-btn:hover { background: var(--primary-soft); color: var(--primary); }

    .atd-block {
        margin-top: 14px; padding: 13px 14px; border-radius: 12px;
        border: 1px solid var(--border); background: var(--surface-muted);
    }
    .atd-block-title { font-size: 9px; text-transform: uppercase; letter-spacing: .06em; font-weight: 700; color: var(--fg-subtle); display: block; }
    .atd-muted-text { font-size: 12px; font-weight: 500; line-height: 1.55; color: var(--fg-muted); }

    /* Footer */
    .atd-modal-footer {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
        padding: 15px 22px; border-top: 1px solid var(--border); background: var(--surface-muted);
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
            <div style="display:flex; align-items:center; gap:13px;">
                <div class="atd-header-icon">
                    <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                </div>
                <div>
                    <h3 style="font-size:16px; font-weight:800; letter-spacing:-.025em; margin:0; color:inherit;" x-text="'Detail ' + (selectedLog ? selectedLog.type : '')"></h3>
                    <p style="font-size:11px; color:var(--fg-muted); font-weight:500; margin:2px 0 0 0;">Telemetri kehadiran resmi</p>
                </div>
            </div>
            <button @click="showModal = false" class="atd-header-close-btn">
                <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="atd-body" x-show="selectedLog">
            <div class="atd-grid">

                {{-- Col 1: photo + map --}}
                <div style="display:flex; flex-direction:column; gap:16px;">
                    <div>
                        <div class="atd-section-title">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Foto Verifikasi
                        </div>
                        <div class="atd-foto-box cursor-zoom-in group relative overflow-hidden" @click="zoomPhotoUrl = selectedLog.selfie_url" title="Klik untuk memperbesar foto">
                            <template x-if="selectedLog && selectedLog.selfie_url">
                                <img :src="selectedLog.selfie_url" alt="Selfie" class="transition-transform duration-300 group-hover:scale-105">
                            </template>
                            <template x-if="selectedLog && !selectedLog.selfie_url">
                                <div style="height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; color:var(--fg-subtle);">
                                    <svg style="width:34px;height:34px;margin-bottom:6px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    <span style="font-size:10px; font-weight:600; text-transform:uppercase; letter-spacing:.05em;">Tidak ada selfie</span>
                                </div>
                            </template>
                            <div style="position:absolute; bottom:8px; left:8px; right:8px; display:flex; justify-content:space-between; pointer-events:none; z-index: 10;">
                                <span class="atd-media-badge" style="color:#34d399;">Liveness ✓</span>
                                <span class="atd-media-badge" style="color:#60a5fa;" x-text="selectedLog ? selectedLog.work_mode : ''"></span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="atd-section-title">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Lokasi Peta
                        </div>
                        <div class="atd-map-box">
                            <iframe :src="selectedLog ? 'https://www.google.com/maps?q=' + selectedLog.latitude + ',' + selectedLog.longitude + '&z=17&output=embed&hl=id' : ''"
                                style="width:100%; height:100%; border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>

                {{-- Col 2: identity + pills + parameter rows --}}
                <div>
                    {{-- Identity --}}
                    <h4 class="atd-name" x-text="selectedLog ? selectedLog.employee_name : ''"></h4>
                    <p class="atd-subtime">
                        <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span x-text="selectedLog ? selectedLog.timestamp : ''"></span>
                    </p>

                    {{-- Status pills --}}
                    <div class="atd-badges">
                        {{-- Validation status --}}
                        <span class="atd-pill"
                            :style="selectedLog && selectedLog.status_class === 'approved' ? 'background:var(--success-soft); border-color:var(--success); color:var(--success);' : (selectedLog && (selectedLog.status_class === 'flagged' || selectedLog.status_class === 'rejected') ? 'background:var(--danger-soft); border-color:var(--danger); color:var(--danger);' : 'background:var(--warning-soft); border-color:var(--warning); color:var(--warning);')"
                            x-text="selectedLog ? selectedLog.status : ''"></span>

                        {{-- Punctuality --}}
                        <template x-if="selectedLog && selectedLog.is_late">
                            <span class="atd-pill" style="background:var(--danger-soft); border-color:var(--danger); color:var(--danger);">Terlambat</span>
                        </template>
                        <template x-if="selectedLog && !selectedLog.is_late">
                            <span class="atd-pill" style="background:var(--success-soft); border-color:var(--success); color:var(--success);">
                                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                Tepat Waktu
                            </span>
                        </template>

                        {{-- Risk --}}
                        <span class="atd-pill"
                            :style="selectedLog && selectedLog.risk_class === 'high' ? 'background:var(--danger-soft); border-color:var(--danger); color:var(--danger);' : (selectedLog && selectedLog.risk_class === 'medium' ? 'background:var(--warning-soft); border-color:var(--warning); color:var(--warning);' : 'background:var(--success-soft); border-color:var(--success); color:var(--success);')"
                            x-text="selectedLog ? 'Risiko ' + selectedLog.risk_level + ' · ' + selectedLog.risk_score + '%' : ''"></span>
                    </div>

                    {{-- Parameter rows --}}
                    <div class="atd-rows">
                        <div class="atd-row">
                            <span class="atd-row-label">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                Cabang
                            </span>
                            <span class="atd-row-value" x-text="selectedLog ? selectedLog.branch_name : ''"></span>
                        </div>

                        <div class="atd-row">
                            <span class="atd-row-label">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                IP Address
                            </span>
                            <span class="atd-row-value atd-mono" x-text="selectedLog ? selectedLog.ip_address : ''"></span>
                        </div>

                        <div class="atd-row">
                            <span class="atd-row-label">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" /></svg>
                                Presisi GPS
                            </span>
                            <span class="atd-row-value" style="color:var(--success);" x-text="selectedLog ? '± ' + parseFloat(selectedLog.accuracy).toFixed(2) + ' m' : ''"></span>
                        </div>

                        <div class="atd-row">
                            <span class="atd-row-label">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                Koordinat
                            </span>
                            <span style="text-align:right; display:flex; align-items:center; justify-content:flex-end;">
                                <span class="atd-mono" style="font-size:11px; color:var(--fg-muted); font-weight:600;" x-text="selectedLog ? selectedLog.latitude + ', ' + selectedLog.longitude : ''"></span>
                                <button @click="navigator.clipboard.writeText(selectedLog.latitude + ', ' + selectedLog.longitude); showToast('Koordinat disalin!', 'success')" class="atd-copy-btn">Salin</button>
                            </span>
                        </div>

                        <div class="atd-row" x-show="selectedLog && selectedLog.resolved_address">
                            <span class="atd-row-label">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                Alamat GPS
                            </span>
                            <span class="atd-row-value" style="font-size:11px; max-width:220px; white-space:normal; line-height:1.45; text-align:right;" x-text="selectedLog ? selectedLog.resolved_address : ''"></span>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="atd-block" x-show="selectedLog && selectedLog.notes">
                        <span class="atd-block-title">Catatan Kehadiran</span>
                        <p class="atd-muted-text" style="font-style:italic; margin:4px 0 0 0;" x-text="selectedLog ? selectedLog.notes : ''"></p>
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
