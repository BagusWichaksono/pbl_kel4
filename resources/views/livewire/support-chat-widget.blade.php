<div
    class="tesyuk-chat-widget"
    x-data="{ open: false }"
    x-on:keydown.escape.window="open = false"
>
    @php
        $helpLogo = asset('assets/logo-bantuan.png') . '?v=20260607';
    @endphp

    <button
        type="button"
        class="tesyuk-chat-button"
        aria-label="Hubungi Admin"
        title="Hubungi Admin"
        x-on:click="open = ! open; if (open) { $wire.openChat() }"
    >
        <img src="{{ $helpLogo }}" alt="Hubungi Admin" class="tesyuk-chat-button-logo">

        @if ($unreadAdminMessages > 0)
            <span class="tesyuk-chat-badge">{{ $unreadAdminMessages > 9 ? '9+' : $unreadAdminMessages }}</span>
        @endif

        <span class="tesyuk-chat-tooltip">Hubungi Admin</span>
    </button>

    <section
        class="tesyuk-chat-panel"
        x-cloak
        x-show.important="open"
        x-transition:enter="tesyuk-chat-enter"
        x-transition:enter-start="tesyuk-chat-enter-start"
        x-transition:enter-end="tesyuk-chat-enter-end"
        x-transition:leave="tesyuk-chat-leave"
        x-transition:leave-start="tesyuk-chat-leave-start"
        x-transition:leave-end="tesyuk-chat-leave-end"
        aria-label="Chat Hubungi Admin"
    >
        <header class="tesyuk-chat-header">
            <div class="tesyuk-chat-header-logo">
                <img src="{{ $helpLogo }}" alt="">
            </div>
            <div>
                <h2>Hubungi Admin</h2>
                <p>{{ ucfirst($role) }} Support</p>
            </div>
            <button type="button" class="tesyuk-chat-close" aria-label="Tutup chat" x-on:click="open = false">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </header>

        <p class="tesyuk-chat-note">
            Chat ini khusus untuk bantuan platform TesYuk seperti akun, verifikasi, dashboard, misi, poin, atau kendala sistem. Untuk aplikasi yang sedang diuji, gunakan instruksi misi dan form report.
        </p>

        <div class="tesyuk-chat-messages" wire:poll.8s="refreshMessages">
            @forelse ($messages as $chatMessage)
                @php
                    $isMine = $chatMessage->sender_id == auth()->id();
                    $senderName = $isMine ? 'Saya' : ($chatMessage->sender?->name ?? 'Admin');
                @endphp

                <article class="tesyuk-chat-message {{ $isMine ? 'is-mine' : 'is-admin' }}">
                    <div class="tesyuk-chat-bubble">
                        <div class="tesyuk-chat-sender">{{ $senderName }}</div>

                        @if (filled($chatMessage->message))
                            <p>{{ $chatMessage->message }}</p>
                        @endif

                        @if ($chatMessage->attachment_path)
                            <a href="{{ asset('storage/' . $chatMessage->attachment_path) }}" target="_blank" rel="noopener" class="tesyuk-chat-attachment">
                                <img src="{{ asset('storage/' . $chatMessage->attachment_path) }}" alt="{{ $chatMessage->attachment_original_name ?? 'Lampiran chat' }}">
                            </a>
                        @endif

                        <time>
                            {{ $chatMessage->created_at?->format('H:i') }}
                            @if ($isMine)
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="{{ $chatMessage->is_read ? '#3b82f6' : '#9ca3af' }}" viewBox="0 0 16 16" style="width: 14px; height: 14px; display: inline-block; margin-bottom: -2px; margin-left: 3px;">
                                    <path d="M8.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L2.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093L8.95 4.992a.252.252 0 0 1 .02-.022zm-.92 5.14.92.92a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 1 0-1.091-1.028L9.477 9.417l-.485-.486-.943 1.179z"/>
                                </svg>
                            @endif
                        </time>
                    </div>
                </article>
            @empty
                <div class="tesyuk-chat-empty">
                    <img src="{{ $helpLogo }}" alt="">
                    <h3>Butuh bantuan?</h3>
                    <p>Kirim pesan ke admin, nanti balasannya akan muncul di sini.</p>
                </div>
            @endforelse
        </div>

        <form class="tesyuk-chat-form" wire:submit.prevent="sendMessage">
            @if ($attachment_upload)
                <div class="tesyuk-chat-file-preview">
                    Foto siap dikirim.
                    <button type="button" wire:click="$set('attachment_upload', null)">Hapus</button>
                </div>
            @endif

            <div class="tesyuk-chat-input-row">
                <label class="tesyuk-chat-file-button" title="Upload foto">
                    <input type="file" accept="image/*" wire:model="attachment_upload">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94a3 3 0 1 1 4.243 4.243L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                    </svg>
                </label>

                <textarea
                    wire:model="message"
                    rows="1"
                    placeholder="Tulis pesan..."
                    maxlength="1000"
                ></textarea>

                <button type="submit" class="tesyuk-chat-send" wire:loading.attr="disabled" wire:target="sendMessage">
                    <span wire:loading.remove wire:target="sendMessage">Kirim</span>
                    <span wire:loading wire:target="sendMessage">Mengirim</span>
                </button>
            </div>

            @error('message')
                <div class="tesyuk-chat-error">{{ $message }}</div>
            @enderror

            @error('attachment_upload')
                <div class="tesyuk-chat-error">{{ $message }}</div>
            @enderror
        </form>
    </section>
</div>
