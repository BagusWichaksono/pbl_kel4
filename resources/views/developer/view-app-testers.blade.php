<x-filament-panels::page>
    @php
        $activeTesterCount = $record->testers->where('status', 'active')->count();
        $testerCount = $record->testers->count();
    @endphp

    {{-- INFO CARDS --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3 mb-6">

        {{-- Status Pengujian --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
            <div class="p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3 px-3 py-2">
                    Status Pengujian
                </p>

                @php
                    $statusColor = match ($record->testing_status) {
                        'pending_approval' => 'text-yellow-600 bg-yellow-100 dark:bg-yellow-900/40 dark:text-yellow-300',
                        'open' => 'text-yellow-600 bg-yellow-100 dark:bg-yellow-900/40 dark:text-yellow-300',
                        'in_progress' => 'text-blue-600 bg-blue-100 dark:bg-blue-900/40 dark:text-blue-300',
                        'completed' => 'text-green-600 bg-green-100 dark:bg-green-900/40 dark:text-green-300',
                        'rejected' => 'text-red-600 bg-red-100 dark:bg-red-900/40 dark:text-red-300',
                        default => 'text-gray-600 bg-gray-100 dark:bg-gray-700 dark:text-gray-300',
                    };

                    $statusLabel = match ($record->testing_status) {
                        'pending_approval' => 'Menunggu Admin',
                        'open' => 'Mencari Tester',
                        'in_progress' => 'Sedang Dites',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                        default => $record->testing_status,
                    };
                @endphp

                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-semibold w-fit {{ $statusColor }} ">
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

                @php
                    $pct = $record->max_testers > 0
                        ? min(100, ($testerCount / $record->max_testers) * 100)
                        : 0;
                @endphp

                <div class="flex items-baseline mb-3">
                    <span class="text-xl font-bold text-gray-800 dark:text-white px-3 py-1">
                        {{ $testerCount }}
                    </span>
                    <span class="text-sm text-gray-400 dark:text-gray-500 ml-2 px-1 py-2">
                        / {{ $record->max_testers }} tester
                    </span>
                </div>

                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                    <div class="bg-primary-500 h-1.5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                </div>

                <p class="text-xs text-gray-400 dark:text-gray-500 mt-2 px-1 py-1">
                    Tester aktif: {{ $activeTesterCount }} / 12 minimum
                </p>
            </div>
        </div>

        {{-- Tanggal Testing --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
            <div class="p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3 px-3 py-2">
                    Tanggal Testing
                </p>

                @if($record->start_date)
                    <p class="text-base font-bold text-gray-800 dark:text-white px-3 py-2">
                        {{ \Carbon\Carbon::parse($record->start_date)->translatedFormat('d F Y') }}
                    </p>

                    @if($record->end_date)
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 px-3 py-2">
                            Berakhir: {{ \Carbon\Carbon::parse($record->end_date)->translatedFormat('d F Y') }}
                        </p>
                    @endif
                @else
                    <p class="text-sm font-medium text-gray-400 dark:text-gray-500 italic px-3 py-2">
                        Belum dimulai
                    </p>

                    @if($activeTesterCount < 12)
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 px-3 py-2">
                            Sesi bisa dimulai setelah minimal 12 tester aktif terkumpul.
                        </p>
                    @elseif(!$record->app_link)
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 px-3 py-2">
                            Input link closed testing terlebih dahulu.
                        </p>
                    @else
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 px-3 py-2">
                            Klik "Mulai Sesi Testing" di atas untuk memulai.
                        </p>
                    @endif
                @endif
            </div>
        </div>
    </div>

    {{-- AKSI CLOSED TESTING --}}
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm mb-6">
        <div class="p-5">
            <h2 class="text-sm font-semibold text-gray-800 dark:text-white flex items-center gap-2 mb-3 px-3 py-2">
                <x-heroicon-o-link class="w-4 h-4 text-primary-500" />
                Link Closed Testing
            </h2>

            @if($activeTesterCount < 12)
                <div class="rounded-lg bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 p-4">
                    <p class="text-sm font-semibold text-yellow-700 dark:text-yellow-300">
                        Link testing belum bisa diinput.
                    </p>
                    <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                        Minimal harus ada 12 tester aktif terlebih dahulu.
                        Saat ini: {{ $activeTesterCount }} / 12 tester.
                    </p>
                </div>
            @elseif(!$record->app_link)
                <div class="rounded-lg bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 p-4">
                    <p class="text-sm font-semibold text-blue-700 dark:text-blue-300">
                        Tester sudah cukup.
                    </p>
                    <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                        Silakan copy daftar email tester, masukkan ke Google Play Console,
                        lalu input link closed testing melalui tombol "Input Link Closed Testing".
                    </p>
                </div>
            @else
                <div class="rounded-lg bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 p-4">
                    <p class="text-sm font-semibold text-green-700 dark:text-green-300 mb-2">
                        Link testing sudah tersedia:
                    </p>
                    <a href="{{ $record->app_link }}" target="_blank"
                       class="text-sm text-primary-600 dark:text-primary-400 underline break-all">
                        {{ $record->app_link }}
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- TABEL DAFTAR TESTER --}}
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm overflow-hidden px-3 py-2">

        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700 ">
            <h2 class="text-sm font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                <x-heroicon-o-users class="w-4 h-4 text-primary-500" />
                Daftar Tester yang Mendaftar
            </h2>

            <span class="text-xs text-gray-400 dark:text-gray-500">
                Total: {{ $testerCount }} tester
            </span>
        </div>

        @if($record->testers->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 px-6 text-center px-3 py-2">
                <div class="rounded-full bg-gray-100 dark:bg-gray-700 p-5 mb-4">
                    <x-heroicon-o-user-group class="w-10 h-10 text-gray-400" />
                </div>

                <p class="text-base font-medium text-gray-600 dark:text-gray-300">
                    Belum ada tester yang mendaftar
                </p>

                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1 max-w-sm">
                    Tester akan muncul di sini setelah mereka mendaftarkan diri pada aplikasi Anda di marketplace.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            <th class="px-5 py-3 font-semibold w-12 text-center">#</th>
                            <th class="px-5 py-3 font-semibold">Nama Tester</th>
                            <th class="px-5 py-3 font-semibold">Email</th>
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

                                $rowStatusColor = match ($applicationTester->status) {
                                    'active' => 'text-green-700 bg-green-100 dark:bg-green-900/40 dark:text-green-300',
                                    'dropped' => 'text-red-700 bg-red-100 dark:bg-red-900/40 dark:text-red-300',
                                    'completed' => 'text-blue-700 bg-blue-100 dark:bg-blue-900/40 dark:text-blue-300',
                                    default => 'text-gray-600 bg-gray-100 dark:bg-gray-700 dark:text-gray-300',
                                };

                                $rowStatusLabel = match ($applicationTester->status) {
                                    'active' => 'Aktif',
                                    'dropped' => 'Mundur',
                                    'completed' => 'Selesai',
                                    default => $applicationTester->status,
                                };
                            @endphp

                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-5 py-3.5 text-center text-xs text-gray-400 dark:text-gray-500 font-mono">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">

                                        <span class="font-medium text-gray-800 dark:text-white text-sm">
                                            {{ $tester?->name ?? 'N/A' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 text-sm">
                                    {{ $tester?->email ?? 'N/A' }}
                                </td>

                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 text-sm">
                                    {{ number_format($profile?->points ?? 0) }} poin
                                </td>

                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 text-sm whitespace-nowrap">
                                    {{ $applicationTester->created_at->format('d M Y, H:i') }}
                                </td>

                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-semibold {{ $rowStatusColor }}">
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