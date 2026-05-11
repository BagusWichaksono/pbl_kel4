<x-filament-panels::page>
    {{-- INFO CARDS --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3 mb-6">

        {{-- Status Pengujian --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
            <div class="">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 px-3 py-2">
                    Status Pengujian
                </p>
                @php
                    $statusColor = match($record->testing_status) {
                        'open'        => 'text-yellow-600 bg-yellow-100 dark:bg-yellow-900/40 dark:text-yellow-300',
                        'in_progress' => 'text-blue-600 bg-blue-100 dark:bg-blue-900/40 dark:text-blue-300',
                        'completed'   => 'text-green-600 bg-green-100 dark:bg-green-900/40 dark:text-green-300',
                        default       => 'text-gray-600 bg-gray-100',
                    };
                    $statusLabel = match($record->testing_status) {
                        'open'        => 'Terbuka',
                        'in_progress' => 'Sedang Dites',
                        'completed'   => 'Selesai',
                        default       => $record->testing_status,
                    };
                @endphp
                <span class="inline-flex items-center gap-1.5 rounded-full px-3 text-sm font-semibold w-fit {{ $statusColor }}">
                    {{ $statusLabel }}
                </span>
            </div>
        </div>

        {{-- Jumlah Tester --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
            <div class="p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3 px-3 py-2">
                    Tester Terdaftar
                </p>
                @php $pct = $record->max_testers > 0 ? min(100, ($record->testers->count() / $record->max_testers) * 100) : 0; @endphp
                <div class="flex items-baseline mb-3">
                    <span class="text-xl font-bold text-gray-800 dark:text-white px-3">{{ $record->testers->count() }}</span>
                    <span class="text-sm text-gray-400 dark:text-gray-500 py-2">/ {{ $record->max_testers }} tester</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                    <div class="bg-primary-500 h-1.5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                </div>
            </div>
        </div>

        {{-- Tanggal Testing --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
            <div class="p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 px-3 py-2">
                    Tanggal Testing
                </p>
                @if($record->start_date)
                    <p class="text-base font-bold text-gray-800 dark:text-white px-3">
                        {{ \Carbon\Carbon::parse($record->start_date)->translatedFormat('d F Y') }}
                    </p>
                    @if($record->end_date)
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Berakhir: {{ \Carbon\Carbon::parse($record->end_date)->translatedFormat('d F Y') }}
                        </p>
                    @endif
                @else
                    <p class="text-sm font-medium text-gray-400 dark:text-gray-500 italic px-3">Belum dimulai</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 px-3">Klik "Mulai Sesi Testing" di atas untuk memulai</p>
                @endif
            </div>
        </div>
    </div>

    {{-- TABEL DAFTAR TESTER --}}
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm overflow-hidden">

        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-sm font-semibold text-gray-800 dark:text-white flex items-center gap-2 px-3 py-2">
                <x-heroicon-o-users class="w-4 h-4 text-primary-500" />
                Daftar Tester yang Mendaftar
            </h2>
            <span class="text-xs text-gray-400 dark:text-gray-500 px-3 py-2">
                Total: {{ $record->testers->count() }} tester
            </span>
        </div>

        @if($record->testers->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                <div class="rounded-full bg-gray-100 dark:bg-gray-700 p-5 mb-4">
                    <x-heroicon-o-user-group class="w-10 h-10 text-gray-400" />
                </div>
                <p class="text-base font-medium text-gray-600 dark:text-gray-300">Belum ada tester yang mendaftar</p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1 max-w-sm">
                    Tester akan muncul di sini setelah mereka mendaftarkan diri pada aplikasi Anda di marketplace.
                </p>
            </div>

        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            <th class="px-5 py-3 font-semibold w-12 text-center px-1 py-2">#</th>
                            <th class="px-5 py-3 font-semibold">Nama Tester</th>
                            <th class="px-5 py-3 font-semibold">Email</th>
                            <th class="px-5 py-3 font-semibold">E-Wallet</th>
                            <th class="px-5 py-3 font-semibold">Poin</th>
                            <th class="px-5 py-3 font-semibold">Tanggal Daftar</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($record->testers as $index => $applicationTester)
                            @php
                                $tester = $applicationTester->tester;
                                $profile = $tester?->testerProfile;

                                $rowStatusColor = match($applicationTester->status) {
                                    'active'    => 'text-green-700 bg-green-100 dark:bg-green-900/40 dark:text-green-300',
                                    'dropped'   => 'text-red-700 bg-red-100 dark:bg-red-900/40 dark:text-red-300',
                                    'completed' => 'text-blue-700 bg-blue-100 dark:bg-blue-900/40 dark:text-blue-300',
                                    default     => 'text-gray-600 bg-gray-100 dark:bg-gray-700',
                                };
                                $rowStatusLabel = match($applicationTester->status) {
                                    'active'    => 'Aktif',
                                    'dropped'   => 'Mundur',
                                    'completed' => 'Selesai',
                                    default     => $applicationTester->status,
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-5 py-3.5 text-center text-xs text-gray-400 dark:text-gray-500 font-mono">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center flex-shrink-0">
                                            <span class="text-primary-600 dark:text-primary-400 font-bold text-xs">
                                                {{ strtoupper(substr($tester?->name ?? '?', 0, 1)) }}
                                            </span>
                                        </div>
                                        <span class="font-medium text-gray-800 dark:text-white text-sm">
                                            {{ $tester?->name ?? 'N/A' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 text-sm">
                                    {{ $tester?->email ?? 'N/A' }}
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 text-sm">
                                    {{ $profile?->e_wallet_provider ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 text-sm">
                                    {{ number_format($profile?->points ?? 0) }} poin
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 text-sm whitespace-nowrap">
                                    {{ $applicationTester->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center rounded-full text-xs font-semibold {{ $rowStatusColor }}">
                                        {{ $rowStatusLabel }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-filament-panels::page>