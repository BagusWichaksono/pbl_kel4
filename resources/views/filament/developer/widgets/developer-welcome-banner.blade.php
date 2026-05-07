<x-filament-widgets::widget>
    <x-filament::section class="relative overflow-hidden">
        
        <div class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-primary-500/20 blur-3xl dark:bg-primary-400/10 pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 h-48 w-48 rounded-full bg-primary-500/20 blur-3xl dark:bg-primary-400/10 pointer-events-none"></div>

        <div class="relative z-10 flex flex-col items-center justify-center gap-4 py-4 text-center sm:py-6">
            
            <h2 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl font-sans">
                Selamat Datang Developer TesYuk!
            </h2>
            
            <p class="max-w-xl text-sm text-gray-500 dark:text-gray-400 font-sans">
                Pantau progress validasi aplikasimu secara real-time dan lihat laporan bug komprehensif dari para tester.
            </p>

            <div class="mt-4">
                <x-filament::button
                    tag="a"
                    href="#"
                    color="primary"
                    size="lg"
                    icon="heroicon-m-plus"
                >
                    Upload Aplikasi Baru
                </x-filament::button>
            </div>
            
        </div>
    </x-filament::section>
</x-filament-widgets::widget>