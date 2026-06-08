<x-filament-panels::page>
    @php
        /** @var \App\Models\RefundRequest $record */
        $record = $this->record;
        $refundProofUrl = $this->getRefundProofUrl();
    @endphp

    <style>
        .refund-detail-shell {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: #ffffff;
            overflow: hidden;
            box-shadow: 0 18px 40px -32px rgba(15, 23, 42, .34);
        }

        .refund-detail-header {
            padding: 24px;
            border-bottom: 1px solid #e2e8f0;
        }

        .refund-detail-title {
            margin: 0;
            color: #0f172a;
            font-size: 20px;
            font-weight: 800;
            line-height: 1.3;
        }

        .refund-detail-subtitle {
            margin: 8px 0 0;
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
        }

        .refund-detail-body {
            padding: 24px;
            display: grid;
            gap: 22px;
        }

        .refund-detail-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .refund-detail-item,
        .refund-detail-box {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            background: #f8fafc;
            padding: 16px;
        }

        .refund-detail-item {
            min-height: 92px;
        }

        .refund-detail-label {
            margin: 0 0 8px;
            color: #64748b;
            font-size: 12px;
            font-weight: 800;
            line-height: 1.2;
            text-transform: uppercase;
        }

        .refund-detail-value {
            margin: 0;
            color: #0f172a;
            font-size: 15px;
            font-weight: 800;
            line-height: 1.45;
            overflow-wrap: anywhere;
            white-space: pre-line;
        }

        .refund-detail-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 6px 10px;
            font-size: 13px;
            font-weight: 800;
            line-height: 1;
        }

        .refund-proof-image {
            display: block;
            width: 100%;
            max-height: 72vh;
            object-fit: contain;
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
        }

        .refund-proof-empty {
            border: 1px dashed #cbd5e1;
            border-radius: 14px;
            background: #ffffff;
            color: #64748b;
            padding: 28px;
            text-align: center;
            font-size: 14px;
            font-weight: 700;
        }

        @media (max-width: 900px) {
            .refund-detail-grid {
                grid-template-columns: 1fr;
            }

            .refund-detail-header,
            .refund-detail-body {
                padding: 18px;
            }
        }
    </style>

    <div class="refund-detail-shell">
        <div class="refund-detail-header">
            <h2 class="refund-detail-title">Detail Refund</h2>
            <p class="refund-detail-subtitle">Informasi lengkap pengajuan refund developer.</p>
        </div>

        <div class="refund-detail-body">
            <div class="refund-detail-grid">
                <div class="refund-detail-item">
                    <p class="refund-detail-label">Developer</p>
                    <p class="refund-detail-value">{{ $record->developer?->name ?? '-' }}</p>
                </div>

                <div class="refund-detail-item">
                    <p class="refund-detail-label">Aplikasi</p>
                    <p class="refund-detail-value">{{ $record->application?->title ?? 'Aplikasi dihapus' }}</p>
                </div>

                <div class="refund-detail-item">
                    <p class="refund-detail-label">Nominal</p>
                    <p class="refund-detail-value">Rp {{ number_format((float) $record->amount, 0, ',', '.') }}</p>
                </div>

                <div class="refund-detail-item">
                    <p class="refund-detail-label">Status</p>
                    <span class="refund-detail-badge" style="{{ $this->statusStyle($record->status) }}">
                        {{ $this->statusLabel($record->status) }}
                    </span>
                </div>

                <div class="refund-detail-item">
                    <p class="refund-detail-label">Diajukan Pada</p>
                    <p class="refund-detail-value">{{ $record->created_at?->format('d M Y, H:i') ?? '-' }}</p>
                </div>

                <div class="refund-detail-item">
                    <p class="refund-detail-label">Diproses Pada</p>
                    <p class="refund-detail-value">{{ $record->processed_at?->format('d M Y, H:i') ?? '-' }}</p>
                </div>
            </div>

            <div class="refund-detail-box">
                <p class="refund-detail-label">Alasan Refund</p>
                <p class="refund-detail-value">{{ $record->reason }}</p>
            </div>

            <div class="refund-detail-grid">
                <div class="refund-detail-item">
                    <p class="refund-detail-label">Bank / E-Wallet</p>
                    <p class="refund-detail-value">{{ $record->bank_name ?? '-' }}</p>
                </div>

                <div class="refund-detail-item">
                    <p class="refund-detail-label">Atas Nama</p>
                    <p class="refund-detail-value">{{ $record->account_name ?? '-' }}</p>
                </div>

                <div class="refund-detail-item">
                    <p class="refund-detail-label">Nomor Rekening / E-Wallet</p>
                    <p class="refund-detail-value">{{ $record->account_number ?? '-' }}</p>
                </div>
            </div>

            <div class="refund-detail-box">
                <p class="refund-detail-label">Catatan Admin</p>
                <p class="refund-detail-value">{{ $record->admin_note ?: '-' }}</p>
            </div>

            <div class="refund-detail-box">
                <p class="refund-detail-label">Bukti Refund</p>

                @if($refundProofUrl)
                    <img
                        src="{{ $refundProofUrl }}"
                        alt="Bukti refund {{ $record->application?->title ?? 'aplikasi' }}"
                        class="refund-proof-image"
                    >

                    <div style="margin-top:12px;">
                        <x-filament::button
                            tag="a"
                            href="{{ $refundProofUrl }}"
                            target="_blank"
                            color="gray"
                            icon="heroicon-o-arrow-top-right-on-square"
                        >
                            Buka Gambar
                        </x-filament::button>
                    </div>
                @elseif($record->status === \App\Models\RefundRequest::STATUS_REJECTED)
                    <div class="refund-proof-empty">
                        Refund ditolak, bukti transfer refund tidak diperlukan.
                    </div>
                @else
                    <div class="refund-proof-empty">
                        Bukti refund belum tersedia.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
