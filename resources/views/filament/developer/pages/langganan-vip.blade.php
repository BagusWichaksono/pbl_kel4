<x-filament-panels::page>
    <!-- Import Font Poppins & Icon Phosphor -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- DESAIN MENGGUNAKAN PURE CSS AGAR TIDAK DIRUBAH FILAMENT -->
    <style>
        .vip-wrapper {
            font-family: 'Poppins', sans-serif !important;
            padding: 2rem 0;
        }
        
        .vip-cards-container {
            display: flex;
            flex-direction: column;
            gap: 2rem;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
        }

        /* Mode Desktop: Kartu Bersebelahan */
        @media (min-width: 768px) {
            .vip-cards-container {
                flex-direction: row;
                align-items: stretch;
            }
        }

        /* Desain Kartu Reguler */
        .vip-card { 
            width: 100%; 
            max-width: 320px; 
            border-radius: 24px; 
            padding: 32px; 
            background: white; 
            border: 1px solid #e2e8f0; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); 
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }
        .dark .vip-card { background: #111827; border-color: #374151; }
        
        /* Desain Kartu VIP */
        .vip-card.premium { 
            border: 2px solid #0f172a; 
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            position: relative;
        }
        @media (min-width: 768px) {
            .vip-card.premium { transform: translateY(-16px); }
        }
        .dark .vip-card.premium { border-color: #64748b; }

        /* Badge Most Popular */
        .vip-badge {
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            background: #0f172a;
            color: white;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 6px 16px;
            border-radius: 9999px;
            white-space: nowrap;
        }
        .dark .vip-badge { background: #64748b; }

        /* Tipografi Bawaan Custom */
        .vip-title-main { font-size: 36px; font-weight: 800; line-height: 1.2; margin-bottom: 12px; }
        .vip-subtitle { font-size: 16px; color: #64748b; font-weight: 500; }
        .vip-card-title { font-size: 20px; font-weight: 700; margin-bottom: 4px; }
        .vip-card-price { font-size: 32px; font-weight: 800; margin-bottom: 24px; display: flex; align-items: baseline; gap: 4px; }
        
        /* List Fitur */
        .vip-list { list-style: none; padding: 0; margin: 0 0 32px 0; display: flex; flex-direction: column; gap: 16px; flex-grow: 1; }
        .vip-list li { display: flex; align-items: center; gap: 12px; font-size: 14px; font-weight: 500; color: #475569; }
        .dark .vip-list li { color: #d1d5db; }
        .vip-list li.disabled { color: #94a3b8; }
        .dark .vip-list li.disabled { color: #6b7280; }
        
        /* Desain Tombol */
        .vip-btn { 
            width: 100%; 
            text-align: center; 
            padding: 14px; 
            border-radius: 12px; 
            font-weight: 700; 
            transition: all 0.2s; 
            margin-top: auto;
        }
        .vip-btn-regular { background: #f1f5f9; color: #94a3b8; cursor: not-allowed; }
        .dark .vip-btn-regular { background: #1f2937; color: #6b7280; }
        .vip-btn-premium { background: #0f172a; color: white; box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.2); }
        .vip-btn-premium:hover { background: #1e293b; }
        .dark .vip-btn-premium { background: #64748b; box-shadow: none; }
        .dark .vip-btn-premium:hover { background: #475569; }

        /* Pembungkus Ikon */
        .vip-icon-box { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 24px; }
        .vip-icon-box.regular { background: #f1f5f9; }
        .dark .vip-icon-box.regular { background: #1f2937; }
        .vip-icon-box.premium { background: #0f172a; }
        .dark .vip-icon-box.premium { background: #64748b; }
    </style>

    <!-- KONTEN HALAMAN -->
    <div class="vip-wrapper">
        
        <div style="text-align: center; margin-bottom: 48px;">
            <h1 class="vip-title-main text-slate-900 dark:text-white">Tingkatkan ke VIP</h1>
            <p class="vip-subtitle dark:text-slate-400">Buka semua fitur eksklusif dan dapatkan tester tanpa batas.</p>
        </div>

        <div class="vip-cards-container">
            
            <!-- KARTU 1: REGULER -->
            <div class="vip-card">
                <div class="vip-icon-box regular">
                    <i class="ph-fill ph-user" style="font-size: 24px; color: #475569;"></i>
                </div>
                <h3 class="vip-card-title text-slate-900 dark:text-white">Reguler</h3>
                <div class="vip-card-price text-slate-900 dark:text-white" style="font-size: 28px;">
                    Paket Saat Ini
                </div>
                
                <ul class="vip-list">
                    <li>
                        <i class="ph-bold ph-check" style="color: #10b981; font-size: 18px;"></i> Maksimal 12 Tester
                    </li>
                    <li>
                        <i class="ph-bold ph-check" style="color: #10b981; font-size: 18px;"></i> Laporan Standar
                    </li>
                    <li class="disabled">
                        <i class="ph-bold ph-x" style="color: #cbd5e1; font-size: 18px;"></i> Prioritas Listing
                    </li>
                </ul>

                <button disabled class="vip-btn vip-btn-regular">
                    Aktif
                </button>
            </div>

            <!-- KARTU 2: VIP DEVELOPER -->
            <div class="vip-card premium">
                <div class="vip-badge">Most Popular</div>
                <div class="vip-icon-box premium">
                    <i class="ph-fill ph-crown" style="font-size: 24px; color: white;"></i>
                </div>
                <h3 class="vip-card-title text-slate-900 dark:text-white">VIP Developer</h3>
                <div class="vip-card-price text-slate-900 dark:text-white">
                    Rp 150.000 <span style="font-size: 14px; font-weight: 500; color: #94a3b8;">/aplikasi</span>
                </div>
                
                <ul class="vip-list">
                    <li>
                        <i class="ph-bold ph-check" style="color: #10b981; font-size: 18px;"></i> Tester Tak Terbatas
                    </li>
                    <li>
                        <i class="ph-bold ph-check" style="color: #10b981; font-size: 18px;"></i> Analytics UI/UX Detail
                    </li>
                    <li>
                        <i class="ph-bold ph-check" style="color: #10b981; font-size: 18px;"></i> Prioritas Halaman Utama
                    </li>
                </ul>

                <button type="button" class="vip-btn vip-btn-premium">
                    Upgrade Sekarang
                </button>
            </div>

        </div>
    </div>
</x-filament-panels::page>