<x-filament-panels::page>
    <style>
        .misi-container { font-family: 'Inter', system-ui, sans-serif; }

        /* Header */
        .misi-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            border-radius: 1rem;
            padding: 2rem 2rem;
            color: white;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }
        .misi-hero::before {
            content: '';
            position: absolute;
            top: -3rem;
            right: -3rem;
            width: 10rem;
            height: 10rem;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
        }
        .misi-hero::after {
            content: '';
            position: absolute;
            bottom: -2rem;
            left: 30%;
            width: 6rem;
            height: 6rem;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
        }
        .misi-hero-title { font-size: 1.5rem; font-weight: 800; margin-bottom: 0.375rem; position: relative; z-index: 1; }
        .misi-hero-sub { font-size: 0.875rem; color: #94a3b8; position: relative; z-index: 1; }

        /* Search Bar */
        .misi-search-wrapper {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .misi-search-input {
            flex: 1;
            min-width: 200px;
            padding: 0.625rem 1rem 0.625rem 2.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            background: #f8fafc;
            transition: all 0.2s;
            outline: none;
        }
        .misi-search-input:focus { border-color: #0f172a; box-shadow: 0 0 0 3px rgba(15,23,42,0.08); background: white; }
        .misi-search-box { position: relative; flex: 1; min-width: 200px; }
        .misi-search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }
        .misi-count {
            font-size: 0.8125rem;
            font-weight: 700;
            color: #64748b;
            white-space: nowrap;
        }
        .misi-count span { color: #0f172a; }

        /* Card Grid */
        .misi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.25rem;
        }

        /* Card */
        .misi-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .misi-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }
        .misi-card-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; }
        .misi-card-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.25rem;
            color: white;
            flex-shrink: 0;
        }
        .misi-card-badges { display: flex; gap: 0.375rem; flex-wrap: wrap; }
        .misi-badge {
            padding: 0.1875rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.6875rem;
            font-weight: 700;
        }
        .misi-badge-slot { background: #dbeafe; color: #2563eb; }
        .misi-badge-slot-full { background: #fee2e2; color: #dc2626; }
        .misi-badge-days { background: #f0fdf4; color: #16a34a; }
        .misi-badge-ended { background: #fef2f2; color: #dc2626; }

        .misi-card-title { font-size: 1.0625rem; font-weight: 700; color: #0f172a; margin-bottom: 0.375rem; transition: color 0.2s; }
        .misi-card:hover .misi-card-title { color: #2563eb; }
        .misi-card-desc { font-size: 0.8125rem; color: #64748b; line-height: 1.5; margin-bottom: 0.75rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .misi-card-developer { font-size: 0.75rem; color: #94a3b8; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.375rem; }

        .misi-card-footer { border-top: 1px solid #f1f5f9; padding-top: 1rem; display: flex; justify-content: space-between; align-items: center; }

        /* Buttons */
        .btn-daftar {
            padding: 0.5rem 1.25rem;
            border-radius: 0.625rem;
            font-size: 0.8125rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            background: #0f172a;
            color: white;
        }
        .btn-daftar:hover { background: #1e293b; box-shadow: 0 4px 12px rgba(15,23,42,0.2); }
        .btn-registered {
            padding: 0.5rem 1.25rem;
            border-radius: 0.625rem;
            font-size: 0.8125rem;
            font-weight: 700;
            border: 1px solid #d1fae5;
            background: #ecfdf5;
            color: #059669;
            cursor: default;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }
        .btn-full {
            padding: 0.5rem 1.25rem;
            border-radius: 0.625rem;
            font-size: 0.8125rem;
            font-weight: 700;
            border: 1px solid #fee2e2;
            background: #fef2f2;
            color: #dc2626;
            cursor: default;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }
        .btn-ended {
            padding: 0.5rem 1.25rem;
            border-radius: 0.625rem;
            font-size: 0.8125rem;
            font-weight: 700;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #94a3b8;
            cursor: default;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .misi-card-max { font-size: 0.75rem; color: #94a3b8; font-weight: 600; }

        /* Empty state */
        .misi-empty {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
        }
        .misi-empty svg { width: 4rem; height: 4rem; margin: 0 auto 1rem; color: #cbd5e1; }
        .misi-empty h4 { font-size: 1.0625rem; font-weight: 700; color: #475569; margin-bottom: 0.375rem; }
        .misi-empty p { font-size: 0.8125rem; color: #94a3b8; }
    </style>

    <div class="misi-container">
        {{-- Hero Header --}}
        <div class="misi-hero">
            <div class="misi-hero-title">Cari Misi Testing</div>
            <div class="misi-hero-sub">Pilih aplikasi yang ingin kamu uji. Selesaikan misi, kirim feedback, dan kumpulkan poin!</div>
        </div>

        {{-- Search Bar --}}
        <div class="misi-search-wrapper">
            <div class="misi-search-box">
                <svg class="misi-search-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama aplikasi atau developer..." class="misi-search-input">
            </div>
            <div class="misi-count">
                <span>{{ $this->applications->count() }}</span> aplikasi tersedia
            </div>
        </div>

        @if($this->applications->count() > 0)
            <div class="misi-grid">
                @foreach($this->applications as $app)
                    @php
                        $colors = ['#0f172a', '#7c3aed', '#2563eb', '#059669', '#d97706', '#dc2626', '#0891b2', '#7c3aed'];
                        $colorIndex = $app->id % count($colors);
                        $bgColor = $colors[$colorIndex];
                        $initial = strtoupper(substr($app->title, 0, 1));
                        $isRegistered = $this->isRegistered($app->id);
                        $isFull = $app->testers_count >= $app->max_testers;
                        $isEnded = $app->end_date && $app->end_date->isPast();
                        $remainingDays = $app->end_date ? (int) max(0, now()->diffInDays($app->end_date, false)) : null;
                        $slotLeft = $app->max_testers - $app->testers_count;
                    @endphp
                    <div class="misi-card">
                        <div>
                            <div class="misi-card-header">
                                <div class="misi-card-icon" style="background-color: {{ $bgColor }};">
                                    {{ $initial }}
                                </div>
                                <div class="misi-card-badges">
                                    @if($isFull)
                                        <span class="misi-badge misi-badge-slot-full">Slot Penuh</span>
                                    @else
                                        <span class="misi-badge misi-badge-slot">{{ $slotLeft }} slot tersisa</span>
                                    @endif

                                    @if($isEnded)
                                        <span class="misi-badge misi-badge-ended">Berakhir</span>
                                    @elseif($remainingDays !== null)
                                        <span class="misi-badge misi-badge-days">{{ $remainingDays }} hari lagi</span>
                                    @endif
                                </div>
                            </div>
                            <div class="misi-card-title">{{ $app->title }}</div>
                            <div class="misi-card-desc">{{ $app->description ?? 'Tidak ada deskripsi.' }}</div>
                            <div class="misi-card-developer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                {{ $app->developer ? $app->developer->name : 'Unknown Developer' }}
                            </div>
                        </div>
                        <div class="misi-card-footer">
                            <div class="misi-card-max">{{ $app->testers_count }}/{{ $app->max_testers }} Tester</div>

                            @if($isRegistered)
                                <span class="btn-registered">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    Sudah Terdaftar
                                </span>
                            @elseif($isEnded)
                                <span class="btn-ended">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                    Sesi Berakhir
                                </span>
                            @elseif($isFull)
                                <span class="btn-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                    Slot Penuh
                                </span>
                            @else
                                <button wire:click="daftarMisi({{ $app->id }})" wire:confirm="Yakin ingin mendaftar sebagai tester di '{{ $app->title }}'?" class="btn-daftar">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                    Daftar Misi
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="misi-empty">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <h4>Belum Ada Misi Tersedia</h4>
                <p>Belum ada aplikasi yang siap untuk diuji. Cek kembali nanti ya!</p>
            </div>
        @endif
    </div>
</x-filament-panels::page>