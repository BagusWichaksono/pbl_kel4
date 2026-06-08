<x-filament-panels::page>
    @php
        /** @var \App\Models\App $record */
        $record = $this->record;
        $paymentProofUrl = $this->getPaymentProofUrl();
        $paymentProofPath = $this->getPaymentProofPath();
        $latestRefund = $this->getLatestRefundRequest();
        $refundStatus = $latestRefund?->status;
        $refundProofUrl = $this->getRefundProofUrl();
    @endphp

    <style>
        .transaction-detail-shell {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: #ffffff;
            overflow: hidden;
            box-shadow: 0 18px 40px -32px rgba(15, 23, 42, .34);
        }

        .transaction-detail-header {
            padding: 24px;
            border-bottom: 1px solid #e2e8f0;
        }

        .transaction-detail-title {
            margin: 0;
            color: #0f172a;
            font-size: 20px;
            font-weight: 800;
            line-height: 1.3;
        }

        .transaction-detail-subtitle {
            margin: 8px 0 0;
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
        }

        .transaction-detail-body {
            padding: 24px;
            display: grid;
            gap: 22px;
        }

        .transaction-detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .transaction-detail-item {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            background: #f8fafc;
            padding: 16px;
            min-height: 92px;
        }

        .transaction-detail-label {
            margin: 0 0 8px;
            color: #64748b;
            font-size: 12px;
            font-weight: 800;
            line-height: 1.2;
            text-transform: uppercase;
        }

        .transaction-detail-value {
            margin: 0;
            color: #0f172a;
            font-size: 15px;
            font-weight: 800;
            line-height: 1.45;
            overflow-wrap: anywhere;
        }

        .transaction-detail-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 6px 10px;
            font-size: 13px;
            font-weight: 800;
            line-height: 1;
        }

        .transaction-proof-box {
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            background: #f8fafc;
            padding: 16px;
        }

        .transaction-proof-image {
            display: block;
            width: 100%;
            max-height: 70vh;
            object-fit: contain;
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
        }

        .transaction-proof-empty {
            border: 1px dashed #cbd5e1;
            border-radius: 14px;
            background: #ffffff;
            color: #64748b;
            padding: 28px;
            text-align: center;
            font-size: 14px;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .transaction-detail-grid {
                grid-template-columns: 1fr;
            }

            .transaction-detail-header,
            .transaction-detail-body {
                padding: 18px;
            }
        }
    </style>

    <div class="transaction-detail-shell">
        <div class="transaction-detail-header">
            <h2 class="transaction-detail-title">Detail Transaksi</h2>
            <p class="transaction-detail-subtitle">Informasi lengkap mengenai pembayaran aplikasi.</p>
        </div>

        <div class="transaction-detail-body">
            <div class="transaction-detail-grid">
                <div class="transaction-detail-item">
                    <p class="transaction-detail-label">Untuk Aplikasi</p>
                    <p class="transaction-detail-value">{{ $record->title }}</p>
                </div>

                <div class="transaction-detail-item">
                    <p class="transaction-detail-label">Status Validasi Admin</p>
                    <span class="transaction-detail-badge" style="{{ $this->paymentStatusStyle($record->payment_status) }}">
                        {{ $this->paymentStatusLabel($record->payment_status) }}
                    </span>
                </div>

                <div class="transaction-detail-item">
                    <p class="transaction-detail-label">Tanggal Pembayaran</p>
                    <p class="transaction-detail-value">{{ $record->created_at?->format('d M Y, H:i') ?? '-' }}</p>
                </div>

                <div class="transaction-detail-item">
                    <p class="transaction-detail-label">Status Refund</p>
                    <span class="transaction-detail-badge" style="{{ $this->refundStatusStyle($refundStatus) }}">
                        {{ $this->refundStatusLabel($refundStatus) }}
                    </span>
                </div>
            </div>

            <div class="transaction-proof-box">
                <p class="transaction-detail-label">Bukti Transfer</p>

                @if($paymentProofUrl)
                    <img
                        src="{{ $paymentProofUrl }}"
                        alt="Bukti transfer {{ $record->title }}"
                        class="transaction-proof-image"
                    >

                    <div style="margin-top:12px;">
                        <x-filament::button
                            tag="a"
                            href="{{ $paymentProofUrl }}"
                            target="_blank"
                            color="gray"
                            icon="heroicon-o-arrow-top-right-on-square"
                        >
                            Buka Gambar
                        </x-filament::button>
                    </div>
                @else
                    <div class="transaction-proof-empty">
                        Bukti transfer belum tersedia untuk transaksi ini.
                    </div>
                @endif
            </div>

            @if($latestRefund)
                <div class="transaction-proof-box">
                    <p class="transaction-detail-label">Bukti Refund dari Admin</p>

                    @if($refundProofUrl)
                        <img
                            src="{{ $refundProofUrl }}"
                            alt="Bukti refund {{ $record->title }}"
                            class="transaction-proof-image"
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
                    @elseif($latestRefund->status === \App\Models\RefundRequest::STATUS_REJECTED)
                        <div class="transaction-proof-empty">
                            Permintaan refund ditolak admin, sehingga bukti refund tidak diperlukan.
                        </div>
                    @else
                        <div class="transaction-proof-empty">
                            Bukti refund belum tersedia untuk transaksi ini.
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
