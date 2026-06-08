<x-filament-panels::page>
    @php
        $record = $this->getRecord();
    @endphp

    <div class="space-y-6">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <p class="text-xs font-semibold uppercase text-gray-400">Developer</p>
                    <p class="mt-1 font-bold text-gray-900">{{ $record->developer?->name ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase text-gray-400">Aplikasi</p>
                    <p class="mt-1 font-bold text-gray-900">{{ $record->application?->title ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase text-gray-400">Nominal Refund</p>
                    <p class="mt-1 font-bold text-gray-900">Rp {{ number_format((float) $record->amount, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="mt-5 rounded-lg bg-gray-50 p-4">
                <p class="text-xs font-semibold uppercase text-gray-400">Tujuan Transfer</p>
                <p class="mt-1 text-sm font-semibold text-gray-800">
                    {{ $record->bank_name ?? '-' }} - {{ $record->account_number ?? '-' }} a.n. {{ $record->account_name ?? '-' }}
                </p>
            </div>

            <div class="mt-5 rounded-lg bg-yellow-50 p-4">
                <p class="text-xs font-semibold uppercase text-yellow-700">Alasan Developer</p>
                <p class="mt-1 whitespace-pre-line text-sm text-yellow-900">{{ $record->reason }}</p>
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
