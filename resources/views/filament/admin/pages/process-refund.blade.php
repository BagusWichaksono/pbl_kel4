<x-filament-panels::page>
    @php
        $record = $this->getRecord();
    @endphp

    <style>
        .refund-process-summary {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: #ffffff;
            padding: 24px;
            box-shadow: 0 18px 40px -32px rgba(15, 23, 42, .34);
        }

        .refund-process-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .refund-process-item {
            min-height: 92px;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            background: #f8fafc;
            padding: 16px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: hidden;
        }

        .refund-process-label {
            margin: 0 0 8px;
            color: #64748b;
            font-size: 12px;
            font-weight: 800;
            line-height: 1.2;
            text-transform: uppercase;
        }

        .refund-process-value {
            margin: 0;
            color: #0f172a;
            font-size: 15px;
            font-weight: 800;
            line-height: 1.45;
            overflow-wrap: anywhere;
        }

        .refund-process-box {
            margin-top: 18px;
            border-radius: 14px;
            padding: 16px;
        }

        @media (max-width: 768px) {
            .refund-process-summary {
                padding: 18px;
            }

            .refund-process-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="space-y-6">
        <div class="refund-process-summary">
            <div class="refund-process-grid">
                <div class="refund-process-item">
                    <p class="refund-process-label">Developer</p>
                    <p class="refund-process-value">{{ $record->developer?->name ?? '-' }}</p>
                </div>

                <div class="refund-process-item">
                    <p class="refund-process-label">Aplikasi</p>
                    <p class="refund-process-value">{{ $record->application?->title ?? '-' }}</p>
                </div>

                <div class="refund-process-item">
                    <p class="refund-process-label">Nominal Refund</p>
                    <p class="refund-process-value">Rp {{ number_format((float) $record->amount, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="refund-process-box" style="background:#f8fafc;border:1px solid #e2e8f0;">
                <p class="refund-process-label">Tujuan Transfer</p>
                <p class="refund-process-value" style="font-size:14px;">
                    {{ $record->bank_name ?? '-' }} - {{ $record->account_number ?? '-' }} a.n. {{ $record->account_name ?? '-' }}
                </p>
            </div>

            <div class="refund-process-box" style="background:#fffbeb;border:1px solid #fde68a;">
                <p class="refund-process-label" style="color:#92400e;">Alasan Developer</p>
                <p class="refund-process-value" style="color:#78350f;font-size:14px;font-weight:700;white-space:pre-line;">{{ $record->reason }}</p>
            </div>
        </div>

        <form wire:submit.prevent="submit" class="space-y-6">
            {{ $this->form }}

            <div class="flex flex-wrap items-center gap-3">
                <x-filament::button type="submit" icon="heroicon-o-check-circle">
                    Simpan Keputusan
                </x-filament::button>

                <x-filament::button
                    tag="a"
                    color="gray"
                    icon="heroicon-o-x-mark"
                    href="{{ $this->getCancelUrl() }}"
                >
                    Batal
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
