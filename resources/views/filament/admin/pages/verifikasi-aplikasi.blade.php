<x-filament-panels::page>
    <style>
        .verifikasi-container { font-family: 'Inter', system-ui, sans-serif; }
        .verifikasi-card {
            background: white;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            overflow: hidden;
        }
        .verifikasi-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .verifikasi-title { font-size: 1.125rem; font-weight: 700; color: #0f172a; }
        .verifikasi-subtitle { font-size: 0.8125rem; color: #64748b; margin-top: 0.125rem; }
        .badge-pending { background: #fef3c7; color: #b45309; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; }
        .badge-valid { background: #d1fae5; color: #059669; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; }
        .badge-invalid { background: #fee2e2; color: #dc2626; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; }
        .badge-count { background: #fef3c7; color: #b45309; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; }

        /* Filter Tabs */
        .filter-tabs { display: flex; gap: 0.5rem; padding: 1rem 1.5rem; border-bottom: 1px solid #e2e8f0; background: #fafbfc; flex-wrap: wrap; }
        .filter-tab {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
            background: white;
            color: #64748b;
        }
        .filter-tab:hover { background: #f1f5f9; color: #0f172a; }
        .filter-tab.active { background: #0f172a; color: white; border-color: #0f172a; }

        /* Table */
        .verifikasi-table { width: 100%; text-align: left; font-size: 0.875rem; border-collapse: collapse; }
        .verifikasi-table thead { background: #f8fafc; border-bottom: 2px solid #e2e8f0; }
        .verifikasi-table th { padding: 0.875rem 1.5rem; font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; }
        .verifikasi-table td { padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; color: #334155; }
        .verifikasi-table tbody tr { transition: background 0.15s; }
        .verifikasi-table tbody tr:hover { background: #f8fafc; }
        .app-name { font-weight: 700; color: #0f172a; }
        .app-date { font-size: 0.75rem; color: #94a3b8; margin-top: 0.125rem; }

        /* Buttons */
        .btn { padding: 0.4375rem 0.875rem; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.2s; border: none; display: inline-flex; align-items: center; gap: 0.375rem; }
        .btn-approve { background: #059669; color: white; }
        .btn-approve:hover { background: #047857; box-shadow: 0 2px 8px rgba(5,150,105,0.3); }
        .btn-reject { background: #dc2626; color: white; }
        .btn-reject:hover { background: #b91c1c; box-shadow: 0 2px 8px rgba(220,38,38,0.3); }
        .btn-detail { background: #3b82f6; color: white; }
        .btn-detail:hover { background: #2563eb; box-shadow: 0 2px 8px rgba(59,130,246,0.3); }
        .btn-back { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
        .btn-back:hover { background: #e2e8f0; color: #0f172a; }
        .btn-group { display: flex; gap: 0.5rem; justify-content: center; flex-wrap: wrap; }

        /* Detail View */
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; padding: 1.5rem; }
        @media (max-width: 768px) { .detail-grid { grid-template-columns: 1fr; } }
        .detail-section {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1.25rem;
        }
        .detail-section-title { font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 0.75rem; }
        .detail-field { margin-bottom: 0.75rem; }
        .detail-label { font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 0.25rem; }
        .detail-value { font-size: 0.875rem; font-weight: 600; color: #0f172a; }
        .detail-description { font-size: 0.875rem; color: #334155; line-height: 1.6; white-space: pre-wrap; }
        .payment-proof-img {
            max-width: 100%;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            margin-top: 0.5rem;
        }
        .detail-actions {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid #e2e8f0;
            background: #f8fafc;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #94a3b8;
        }
        .empty-state svg { width: 4rem; height: 4rem; margin: 0 auto 1rem; opacity: 0.4; }
        .empty-state h4 { font-size: 1rem; font-weight: 700; color: #64748b; margin-bottom: 0.25rem; }
        .empty-state p { font-size: 0.8125rem; }

        .detail-full { grid-column: 1 / -1; }
    </style>

    <div class="verifikasi-container">
        @if(!$showDetail)
            {{-- ===== TAMPILAN DAFTAR ===== --}}
            <div class="verifikasi-card">
                <div class="verifikasi-header">
                    <div>
                        <div class="verifikasi-title">Verifikasi Aplikasi</div>
                        <div class="verifikasi-subtitle">Kelola verifikasi pembayaran aplikasi dari Developer.</div>
                    </div>
                    @if($this->pendingCount > 0)
                        <span class="badge-count">{{ $this->pendingCount }} Antrean</span>
                    @endif
                </div>

                {{-- Filter Tabs --}}
                <div class="filter-tabs">
                    <button wire:click="setFilter('semua')" class="filter-tab {{ $filter === 'semua' ? 'active' : '' }}">
                        Semua
                    </button>
                    <button wire:click="setFilter('pending')" class="filter-tab {{ $filter === 'pending' ? 'active' : '' }}">
                        Pending
                    </button>
                    <button wire:click="setFilter('valid')" class="filter-tab {{ $filter === 'valid' ? 'active' : '' }}">
                         Valid
                    </button>
                    <button wire:click="setFilter('invalid')" class="filter-tab {{ $filter === 'invalid' ? 'active' : '' }}">
                         Invalid
                    </button>
                </div>

                @if($this->applications->count() > 0)
                    <table class="verifikasi-table">
                        <thead>
                            <tr>
                                <th>Nama Aplikasi</th>
                                <th>Developer</th>
                                <th>Tanggal Upload</th>
                                <th>Status</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($this->applications as $app)
                                <tr>
                                    <td>
                                        <div class="app-name">{{ $app->title }}</div>
                                        <div class="app-date">{{ $app->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td>{{ $app->developer ? $app->developer->name : '-' }}</td>
                                    <td>{{ $app->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        @if($app->payment_status === 'pending')
                                            <span class="badge-pending"> Pending</span>
                                        @elseif($app->payment_status === 'valid')
                                            <span class="badge-valid"> Valid</span>
                                        @elseif($app->payment_status === 'invalid')
                                            <span class="badge-invalid"> Invalid</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button wire:click="lihatDetail({{ $app->id }})" class="btn btn-detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                                Detail
                                            </button>
                                            @if($app->payment_status === 'pending')
                                                <button wire:click="setujui({{ $app->id }})" wire:confirm="Yakin ingin menyetujui pembayaran aplikasi '{{ $app->title }}'?" class="btn btn-approve">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                                    Setujui
                                                </button>
                                                <button wire:click="tolak({{ $app->id }})" wire:confirm="Yakin ingin menolak pembayaran aplikasi '{{ $app->title }}'?" class="btn btn-reject">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                                    Tolak
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <h4>Tidak ada data</h4>
                        <p>Belum ada aplikasi dengan status "{{ $filter }}".</p>
                    </div>
                @endif
            </div>

        @else
            {{-- ===== TAMPILAN DETAIL ===== --}}
            @if($this->selectedApp)
                <div class="verifikasi-card">
                    <div class="verifikasi-header">
                        <div>
                            <div class="verifikasi-title">Detail Aplikasi</div>
                            <div class="verifikasi-subtitle">Review informasi dan bukti pembayaran sebelum memverifikasi.</div>
                        </div>
                        @if($this->selectedApp->payment_status === 'pending')
                            <span class="badge-pending"> Menunggu Verifikasi</span>
                        @elseif($this->selectedApp->payment_status === 'valid')
                            <span class="badge-valid"> Sudah Diverifikasi</span>
                        @elseif($this->selectedApp->payment_status === 'invalid')
                            <span class="badge-invalid"> Ditolak</span>
                        @endif
                    </div>

                    <div class="detail-grid">
                        {{-- Info Aplikasi --}}
                        <div class="detail-section">
                            <div class="detail-section-title"> Informasi Aplikasi</div>
                            <div class="detail-field">
                                <div class="detail-label">Nama Aplikasi</div>
                                <div class="detail-value">{{ $this->selectedApp->title }}</div>
                            </div>
                            <div class="detail-field">
                                <div class="detail-label">Developer</div>
                                <div class="detail-value">{{ $this->selectedApp->developer ? $this->selectedApp->developer->name : '-' }}</div>
                            </div>
                            <div class="detail-field">
                                <div class="detail-label">Tanggal Upload</div>
                                <div class="detail-value">{{ $this->selectedApp->created_at->format('d M Y, H:i') }}</div>
                            </div>
                            <div class="detail-field">
                                <div class="detail-label">Status Pembayaran</div>
                                <div>
                                    @if($this->selectedApp->payment_status === 'pending')
                                        <span class="badge-pending">Pending</span>
                                    @elseif($this->selectedApp->payment_status === 'valid')
                                        <span class="badge-valid">Valid</span>
                                    @elseif($this->selectedApp->payment_status === 'invalid')
                                        <span class="badge-invalid">Invalid</span>
                                    @endif
                                </div>
                            </div>
                            @if($this->selectedApp->start_date)
                                <div class="detail-field">
                                    <div class="detail-label">Periode Testing</div>
                                    <div class="detail-value">{{ $this->selectedApp->start_date->format('d M Y') }} — {{ $this->selectedApp->end_date->format('d M Y') }}</div>
                                </div>
                            @endif
                        </div>

                        {{-- Bukti Pembayaran --}}
                        <div class="detail-section">
                            <div class="detail-section-title">Bukti Pembayaran</div>
                            @if($this->selectedApp->payment_proof)
                                <img src="{{ asset('storage/' . $this->selectedApp->payment_proof) }}" alt="Bukti Pembayaran" class="payment-proof-img">
                            @else
                                <div style="text-align:center; padding: 2rem; color: #94a3b8;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin: 0 auto 0.5rem; opacity: 0.4;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p style="font-size: 0.8125rem;">Belum ada bukti pembayaran yang diupload.</p>
                                </div>
                            @endif
                        </div>

                        {{-- Skenario Pengujian --}}
                        <div class="detail-section detail-full">
                            <div class="detail-section-title">Skenario Pengujian / Deskripsi</div>
                            <div class="detail-description">{{ $this->selectedApp->description ?? 'Tidak ada deskripsi.' }}</div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="detail-actions">
                        <button wire:click="kembali" class="btn btn-back">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                            Kembali ke Daftar
                        </button>

                        @if($this->selectedApp->payment_status === 'pending')
                            <div class="btn-group">
                                <button wire:click="setujui({{ $this->selectedApp->id }})" wire:confirm="Yakin ingin menyetujui pembayaran aplikasi '{{ $this->selectedApp->title }}'? Sesi testing 14 hari akan dimulai." class="btn btn-approve">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    Setujui Pembayaran
                                </button>
                                <button wire:click="tolak({{ $this->selectedApp->id }})" wire:confirm="Yakin ingin menolak pembayaran aplikasi '{{ $this->selectedApp->title }}'?" class="btn btn-reject">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    Tolak Pembayaran
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>
</x-filament-panels::page>