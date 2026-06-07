<x-filament-panels::page>
    @if(!$app)
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:18px;padding:2rem;text-align:center;">
            <p style="margin:0;color:#b91c1c;font-weight:800;">Misi tidak ditemukan.</p>
        </div>
    @else
        @php
            $remaining = max(0, $maxTester - $filled);
            $percent = min(100, (int) round(($filled / max($maxTester, 1)) * 100));
            $description = trim(strip_tags($app->description ?? ''));
            $description = $description !== ''
                ? $description
                : 'Ikuti misi testing selama 14 hari, unggah screenshot harian, dan bantu developer menemukan bug.';

            $initials = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $app->title ?? 'Aplikasi'), 0, 2));
            $initials = $initials !== '' ? $initials : 'AP';

            $statusLabel = 'Misi tersedia';
            $statusDescription = "Masih ada {$remaining} slot tester. Klik Ambil Misi untuk bergabung.";
            $statusStyle = 'background:#ecfdf5;color:#047857;border-color:#a7f3d0;';

            if ($isRegistered) {
                $statusLabel = 'Misi sudah kamu ambil';
                $statusDescription = 'Kamu bisa lanjut mengerjakan misi ini dari menu Misi Saya.';
                $statusStyle = 'background:#f8fafc;color:#475569;border-color:#e2e8f0;';
            } elseif ($isFull) {
                $statusLabel = 'Slot tester penuh';
                $statusDescription = 'Semua slot untuk aplikasi ini sudah terisi.';
                $statusStyle = 'background:#fff7ed;color:#9a3412;border-color:#fed7aa;';
            } elseif ($isExpired) {
                $statusLabel = 'Sesi testing berakhir';
                $statusDescription = 'Aplikasi ini sudah melewati batas sesi testing.';
                $statusStyle = 'background:#fff7ed;color:#9a3412;border-color:#fed7aa;';
            }
        @endphp

        <div style="margin-bottom:1rem;">
            <a href="{{ \App\Filament\Tester\Resources\CariMisiResource::getUrl('index') }}"
                style="display:inline-flex;align-items:center;gap:.4rem;color:var(--tesyuk-primary);font-size:.9rem;font-weight:800;text-decoration:none;">
                <x-heroicon-o-arrow-left style="width:1rem;height:1rem;" />
                Kembali ke Cari Misi
            </a>
        </div>

        <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:24px;box-shadow:0 18px 42px -32px rgba(15,23,42,.35);overflow:hidden;">
            <div style="padding:1.5rem;border-bottom:1px solid #e2e8f0;background:#ffffff;">
                <div style="display:flex;flex-wrap:wrap;align-items:flex-start;justify-content:space-between;gap:1.25rem;">
                    <div style="display:flex;align-items:flex-start;gap:1rem;min-width:0;flex:1;">
                        <div style="width:112px;height:112px;min-width:112px;border-radius:24px;border:1px solid #e2e8f0;background:#f8fafc;display:flex;align-items:center;justify-content:center;overflow:hidden;box-shadow:0 18px 36px -28px rgba(15,23,42,.45);">
                            @if($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $app->title }}"
                                    style="width:100%;height:100%;max-width:112px;max-height:112px;object-fit:contain;padding:10px;background:#ffffff;">
                            @else
                                <span style="color:var(--tesyuk-primary);font-size:2.25rem;font-weight:900;letter-spacing:.04em;">{{ $initials }}</span>
                            @endif
                        </div>

                        <div style="min-width:0;flex:1;">
                            <div style="display:flex;flex-wrap:wrap;gap:.5rem;margin-bottom:.75rem;">
                                <span style="display:inline-flex;border-radius:999px;background:var(--tesyuk-secondary);color:var(--tesyuk-primary);border:1px solid rgba(var(--tesyuk-primary-rgb),.22);padding:.38rem .7rem;font-size:.76rem;font-weight:900;">
                                    {{ $app->platform ?? 'Platform' }}
                                </span>
                                <span style="display:inline-flex;border-radius:999px;background:#f8fafc;color:#475569;border:1px solid #e2e8f0;padding:.38rem .7rem;font-size:.76rem;font-weight:900;">14 Hari</span>
                                <span style="display:inline-flex;border-radius:999px;background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;padding:.38rem .7rem;font-size:.76rem;font-weight:900;">Reward 10 Poin</span>
                            </div>

                            <h2 style="margin:0;color:#0f172a;font-size:1.65rem;line-height:1.25;font-weight:900;word-break:break-word;">
                                {{ $app->title ?? 'Aplikasi' }}
                            </h2>

                            <p style="margin:.5rem 0 0;color:#64748b;font-size:.95rem;">
                                Developer: <strong style="color:#475569;">{{ $app->developer?->name ?? 'Developer' }}</strong>
                            </p>
                        </div>
                    </div>

                    <div style="display:flex;flex-wrap:wrap;justify-content:flex-end;gap:.6rem;">
                        @if($app->app_link)
                            <a href="{{ $app->app_link }}" target="_blank" rel="noopener noreferrer"
                                style="display:inline-flex;align-items:center;justify-content:center;gap:.45rem;border-radius:999px;background:#f8fafc;border:1px solid #e2e8f0;color:#0f172a;font-size:.9rem;font-weight:900;padding:.78rem 1.05rem;text-decoration:none;">
                                <x-heroicon-o-arrow-top-right-on-square style="width:1rem;height:1rem;" />
                                Buka Link Aplikasi
                            </a>
                        @endif

                        @if($canTakeMission)
                            <button type="button" wire:click="takeMission" wire:loading.attr="disabled" wire:target="takeMission"
                                style="display:inline-flex;align-items:center;justify-content:center;gap:.45rem;border-radius:999px;background:var(--tesyuk-accent);border:0;color:#ffffff;font-size:.9rem;font-weight:900;padding:.82rem 1.1rem;cursor:pointer;">
                                <x-heroicon-o-plus-circle style="width:1rem;height:1rem;" />
                                <span wire:loading.remove wire:target="takeMission">Ambil Misi</span>
                                <span wire:loading wire:target="takeMission">Memproses...</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div style="padding:1.5rem;display:grid;grid-template-columns:minmax(0,1.35fr) minmax(260px,.65fr);gap:1rem;">
                <div style="display:flex;flex-direction:column;gap:1rem;min-width:0;">
                    <div style="{{ $statusStyle }}border-width:1px;border-style:solid;border-radius:18px;padding:1rem;">
                        <div style="font-weight:900;">{{ $statusLabel }}</div>
                        <div style="margin-top:.25rem;font-size:.9rem;line-height:1.65;">{{ $statusDescription }}</div>
                    </div>

                    <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:18px;padding:1.1rem;">
                        <div style="color:#64748b;font-size:.78rem;font-weight:900;letter-spacing:.08em;text-transform:uppercase;">Deskripsi Aplikasi</div>
                        <p style="margin:.75rem 0 0;color:#475569;font-size:.95rem;line-height:1.8;white-space:pre-line;">{{ $description }}</p>
                    </div>
                </div>

                <div style="display:grid;gap:.75rem;align-content:start;">
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:18px;padding:1rem;">
                        <div style="color:#64748b;font-size:.8rem;font-weight:800;">Terisi</div>
                        <div style="margin-top:.25rem;color:#0f172a;font-size:1.35rem;font-weight:900;">{{ $filled }} / {{ $maxTester }}</div>
                    </div>

                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:18px;padding:1rem;">
                        <div style="color:#64748b;font-size:.8rem;font-weight:800;">Sisa Slot</div>
                        <div style="margin-top:.25rem;color:#0f172a;font-size:1.35rem;font-weight:900;">{{ $remaining }}</div>
                    </div>

                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:18px;padding:1rem;">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:.75rem;">
                            <div>
                                <div style="color:#64748b;font-size:.8rem;font-weight:800;">Kuota Tester</div>
                                <div style="margin-top:.25rem;color:#0f172a;font-size:1.35rem;font-weight:900;">{{ $percent }}%</div>
                            </div>
                            <div style="color:var(--tesyuk-primary);font-size:.9rem;font-weight:900;">{{ $filled }} / {{ $maxTester }}</div>
                        </div>
                        <div style="margin-top:.85rem;height:10px;background:#e2e8f0;border-radius:999px;overflow:hidden;">
                            <div style="width:{{ $percent }}%;height:100%;background:linear-gradient(90deg,var(--tesyuk-accent),var(--tesyuk-primary));border-radius:999px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>
