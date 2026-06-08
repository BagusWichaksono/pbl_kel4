<x-filament-panels::page>
    <form wire:submit.prevent="submit" class="space-y-6">
        {{ $this->form }}

        <div class="flex flex-wrap items-center gap-3">
            <x-filament::button type="submit" icon="heroicon-o-paper-airplane">
                Kirim Pengajuan
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
</x-filament-panels::page>
