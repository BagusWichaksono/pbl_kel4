<x-filament-panels::page>
    <style>
        .admin-chat-wrapper {
            width: 100%;
            max-width: 1320px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 360px 1fr;
            gap: 24px;
        }

        .admin-inbox-card,
        .admin-chat-card {
            border-radius: 28px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 50px -20px rgba(15, 23, 42, 0.22);
            overflow: hidden;
        }

        .dark .admin-inbox-card,
        .dark .admin-chat-card {
            background: #0f172a;
            border-color: #334155;
        }

        .admin-inbox-header {
            padding: 22px;
            color: #ffffff;
            background: linear-gradient(135deg, #141c33 0%, #2f456f 60%, #5374ac 100%);
        }

        .admin-inbox-title {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .admin-inbox-subtitle {
            margin: 6px 0 0;
            font-size: 13px;
            color: #dbeafe;
            line-height: 1.5;
        }

        .admin-ticket-list {
            max-height: calc(100vh - 280px);
            min-height: 560px;
            overflow-y: auto;
            background: #ffffff;
        }

        .dark .admin-ticket-list {
            background: #0f172a;
        }

        .admin-ticket-item {
            width: 100%;
            border: none;
            border-bottom: 1px solid #f1f5f9;
            background: transparent;
            padding: 16px;
            text-align: left;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .admin-ticket-item:hover {
            background: #f8fafc;
        }

        .admin-ticket-item.active {
            background: #eff5fa;
        }

        .dark .admin-ticket-item {
            border-bottom-color: #1e293b;
        }

        .dark .admin-ticket-item:hover,
        .dark .admin-ticket-item.active {
            background: #1e293b;
        }

        .admin-ticket-row {
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .admin-ticket-avatar {
            width: 44px;
            height: 44px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eff5fa;
            color: #2f456f;
            font-size: 14px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .dark .admin-ticket-avatar {
            background: #334155;
            color: #f8fafc;
        }

        .admin-ticket-content {
            min-width: 0;
            flex: 1;
        }

        .admin-ticket-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .admin-ticket-name {
            color: #0f172a;
            font-size: 14px;
            font-weight: 800;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dark .admin-ticket-name {
            color: #ffffff;
        }

        .admin-ticket-message {
            margin-top: 4px;
            color: #64748b;
            font-size: 13px;
            line-height: 1.4;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dark .admin-ticket-message {
            color: #94a3b8;
        }

        .admin-ticket-time {
            margin-top: 8px;
            color: #94a3b8;
            font-size: 12px;
        }

        .admin-unread-badge {
            min-width: 22px;
            height: 22px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e11d48;
            color: #ffffff;
            font-size: 11px;
            font-weight: 800;
            padding: 0 7px;
        }

        .admin-chat-card {
            height: calc(100vh - 220px);
            min-height: 660px;
            display: flex;
            flex-direction: column;
        }

        .admin-chat-header {
            position: relative;
            overflow: hidden;
            padding: 24px 28px;
            background: linear-gradient(135deg, #141c33 0%, #2f456f 55%, #5374ac 100%);
            color: #ffffff;
        }

        .admin-chat-header::before {
            content: "";
            position: absolute;
            right: -50px;
            top: -50px;
            width: 180px;
            height: 180px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            filter: blur(24px);
        }

        .admin-chat-header-content {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .admin-chat-user {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .admin-chat-avatar {
            width: 56px;
            height: 56px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.22);
            color: #ffffff;
            font-size: 20px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .admin-chat-name {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .admin-chat-email {
            margin: 5px 0 0;
            color: #dbeafe;
            font-size: 13px;
        }

        .admin-chat-body {
            flex: 1;
            overflow-y: auto;
            padding: 28px;
            background:
                radial-gradient(circle at top left, rgba(83, 116, 172, 0.08), transparent 300px),
                linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
        }

        .dark .admin-chat-body {
            background:
                radial-gradient(circle at top left, rgba(83, 116, 172, 0.12), transparent 300px),
                linear-gradient(180deg, #020617 0%, #0f172a 100%);
        }

        .admin-message-row {
            display: flex;
            margin-bottom: 18px;
        }

        .admin-message-row.mine {
            justify-content: flex-end;
        }

        .admin-message-row.tester {
            justify-content: flex-start;
        }

        .admin-message-content {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            max-width: 72%;
        }

        .admin-message-row.mine .admin-message-content {
            flex-direction: row-reverse;
        }

        .admin-message-avatar-small {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .admin-message-row.mine .admin-message-avatar-small {
            background: #eff5fa;
            color: #2f456f;
        }

        .admin-message-row.tester .admin-message-avatar-small {
            background: #e2e8f0;
            color: #334155;
        }

        .dark .admin-message-row.tester .admin-message-avatar-small {
            background: #334155;
            color: #f8fafc;
        }

        .admin-message-meta {
            margin-bottom: 5px;
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
        }

        .admin-message-row.mine .admin-message-meta {
            text-align: right;
        }

        .dark .admin-message-meta {
            color: #94a3b8;
        }

        .admin-message-bubble {
            display: inline-block;
            width: fit-content;
            max-width: 100%;
            min-width: 0;
            word-break: break-word;
            border-radius: 22px;
            padding: 13px 15px;
            font-size: 14px;
            line-height: 1.65;
            box-shadow: 0 10px 25px -18px rgba(15, 23, 42, 0.35);
        }

        .admin-message-row.mine .admin-message-bubble {
            background: #5374ac;
            color: #ffffff;
            border-bottom-right-radius: 6px;
        }

        .admin-message-row.tester .admin-message-bubble {
            background: #ffffff;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            border-bottom-left-radius: 6px;
        }

        .dark .admin-message-row.tester .admin-message-bubble {
            background: #1e293b;
            color: #f8fafc;
            border-color: #334155;
        }

        .admin-message-attachment {
            display: block;
            max-width: 280px;
            width: 100%;
            border-radius: 16px;
            margin-bottom: 10px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            object-fit: cover;
        }

        .admin-message-time {
            margin-top: 7px;
            text-align: right;
            font-size: 11px;
            opacity: 0.7;
        }

        .admin-chat-footer {
            padding: 18px 20px;
            border-top: 1px solid #e2e8f0;
            background: #ffffff;
        }

        .dark .admin-chat-footer {
            background: #0f172a;
            border-color: #334155;
        }

        .admin-chat-form {
            display: flex;
            align-items: flex-end;
            gap: 12px;
        }

        .admin-chat-input-box {
            flex: 1;
            border-radius: 22px;
            border: 1px solid #dbe3ef;
            background: #f8fafc;
            padding: 12px 14px;
            box-shadow: inset 0 1px 3px rgba(15, 23, 42, 0.04);
            transition: all 0.2s ease;
        }

        .admin-chat-input-box:hover {
            border-color: #b3cce2;
            background: #ffffff;
        }

        .admin-chat-input-box:focus-within {
            border-color: #5374ac;
            background: #ffffff;
            box-shadow:
                0 0 0 4px rgba(83, 116, 172, 0.12),
                inset 0 1px 3px rgba(15, 23, 42, 0.04);
        }

        .dark .admin-chat-input-box {
            background: #020617;
            border-color: #334155;
        }

        .dark .admin-chat-input-box:hover,
        .dark .admin-chat-input-box:focus-within {
            background: #0f172a;
            border-color: #5374ac;
        }

        .admin-chat-textarea {
            width: 100%;
            min-height: 52px;
            resize: none;
            border: 0 !important;
            outline: none !important;
            box-shadow: none !important;
            background: transparent !important;
            color: #0f172a;
            font-size: 14px;
            appearance: none;
        }

        .admin-chat-textarea:focus,
        .admin-chat-textarea:focus-visible,
        .admin-chat-textarea:active {
            border: 0 !important;
            outline: none !important;
            box-shadow: none !important;
        }

        .dark .admin-chat-textarea {
            color: #ffffff;
        }

        .admin-chat-textarea::placeholder {
            color: #94a3b8;
        }

        .admin-attach-button {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            margin-top: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: #eff5fa;
            color: #2f456f;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .admin-attach-button:hover {
            background: #d1e1f1;
        }

        .admin-attach-button input {
            display: none;
        }

        .admin-upload-preview {
            width: fit-content;
            margin-bottom: 10px;
            border-radius: 18px;
            border: 1px solid #dbe3ef;
            background: #ffffff;
            padding: 8px;
            box-shadow: 0 12px 28px -18px rgba(15, 23, 42, 0.35);
        }

        .admin-upload-preview img {
            display: block;
            width: 160px;
            max-height: 120px;
            object-fit: cover;
            border-radius: 14px;
        }

        .admin-upload-preview-text {
            margin-top: 6px;
            color: #64748b;
            font-size: 12px;
            font-weight: 700;
        }

        .admin-send-button {
            height: 58px;
            border: none;
            border-radius: 20px;
            padding: 0 26px;
            background: #5374ac;
            color: #ffffff;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 14px 28px -14px rgba(83, 116, 172, 0.75);
            transition: all 0.2s ease;
        }

        .admin-send-button:hover {
            background: #425d8a;
            transform: translateY(-1px);
        }

        .admin-empty-chat {
            height: 100%;
            min-height: 660px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 32px;
            color: #64748b;
        }

        @media (max-width: 1024px) {
            .admin-chat-wrapper {
                grid-template-columns: 1fr;
            }

            .admin-ticket-list {
                min-height: 260px;
                max-height: 360px;
            }
        }

        @media (max-width: 768px) {
            .admin-chat-card {
                height: calc(100vh - 180px);
                min-height: 560px;
                border-radius: 22px;
            }

            .admin-message-content {
                max-width: 88%;
            }

            .admin-chat-form {
                flex-direction: column;
                align-items: stretch;
            }

            .admin-send-button {
                width: 100%;
            }
        }
    </style>

    <div
        class="admin-chat-wrapper"
        wire:poll.2s="refreshInbox"
        x-data
        x-init="
            const boxId = 'chat-box-admin';
            let shouldAutoScroll = true;
            let lastScrollHeight = 0;

            const getBox = () => document.getElementById(boxId);

            const isNearBottom = () => {
                const box = getBox();

                if (! box) {
                    return true;
                }

                return (box.scrollHeight - box.scrollTop - box.clientHeight) < 120;
            };

            const scrollBottom = () => {
                const box = getBox();

                if (box) {
                    box.scrollTop = box.scrollHeight;
                }
            };

            setTimeout(() => {
                const box = getBox();

                scrollBottom();

                if (box) {
                    lastScrollHeight = box.scrollHeight;

                    box.addEventListener('scroll', () => {
                        shouldAutoScroll = isNearBottom();
                    });
                }
            }, 200);

            document.addEventListener('livewire:initialized', () => {
                Livewire.hook('morph.updated', () => {
                    setTimeout(() => {
                        const box = getBox();

                        if (! box) {
                            return;
                        }

                        const hasNewContent = box.scrollHeight !== lastScrollHeight;

                        if (shouldAutoScroll && hasNewContent) {
                            scrollBottom();
                        }

                        lastScrollHeight = box.scrollHeight;
                    }, 150);
                });
            });
        "
    >
        <div class="admin-inbox-card">
            <div class="admin-inbox-header">
                <h2 class="admin-inbox-title">Inbox Tester</h2>
                <p class="admin-inbox-subtitle">
                    Pilih tester untuk melihat dan membalas pesan.
                </p>
            </div>

            <div class="admin-ticket-list">
                @forelse ($this->tickets as $ticket)
                    <button
                        type="button"
                        wire:click="selectTicket({{ $ticket->id }})"
                        class="admin-ticket-item {{ $selectedTicketId === $ticket->id ? 'active' : '' }}"
                    >
                        <div class="admin-ticket-row">
                            <div class="admin-ticket-avatar">
                                {{ strtoupper(substr($ticket->tester?->name ?? 'T', 0, 1)) }}
                            </div>

                            <div class="admin-ticket-content">
                                <div class="admin-ticket-top">
                                    <div class="admin-ticket-name">
                                        {{ $ticket->tester?->name ?? 'Tester' }}
                                    </div>

                                    @if ($ticket->unread_count > 0)
                                        <div class="admin-unread-badge">
                                            {{ $ticket->unread_count }}
                                        </div>
                                    @endif
                                </div>

                                <div class="admin-ticket-message">
                                    @if ($ticket->latestMessage?->message)
                                        {{ $ticket->latestMessage->message }}
                                    @elseif ($ticket->latestMessage?->attachment_path)
                                        Mengirim foto
                                    @else
                                        Belum ada pesan
                                    @endif
                                </div>

                                <div class="admin-ticket-time">
                                    {{ $ticket->last_message_at?->format('d M Y, H:i') ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </button>
                @empty
                    <div style="padding: 28px; text-align: center; color: #64748b; font-size: 14px;">
                        Belum ada pesan dari tester.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="admin-chat-card">
            @if ($this->selectedTicket)
                <div class="admin-chat-header">
                    <div class="admin-chat-header-content">
                        <div class="admin-chat-user">
                            <div class="admin-chat-avatar">
                                {{ strtoupper(substr($this->selectedTicket->tester?->name ?? 'T', 0, 1)) }}
                            </div>

                            <div>
                                <h2 class="admin-chat-name">
                                    {{ $this->selectedTicket->tester?->name ?? 'Tester' }}
                                </h2>
                                <p class="admin-chat-email">
                                    {{ $this->selectedTicket->tester?->email ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="chat-box-admin" class="admin-chat-body">
                    @foreach ($this->selectedTicket->messages as $chat)
                        @php
                            $isAdmin = $chat->sender_role === 'admin';
                        @endphp

                        <div class="admin-message-row {{ $isAdmin ? 'mine' : 'tester' }}">
                            <div class="admin-message-content">
                                <div class="admin-message-avatar-small">
                                    {{ $isAdmin ? 'A' : strtoupper(substr($chat->sender?->name ?? 'T', 0, 1)) }}
                                </div>

                                <div>
                                    <div class="admin-message-meta">
                                        {{ $isAdmin ? 'Admin' : ($chat->sender?->name ?? 'Tester') }}
                                    </div>

                                    <div class="admin-message-bubble">
                                        @if ($chat->attachment_path)
                                            <a href="{{ asset('storage/' . $chat->attachment_path) }}" target="_blank">
                                                <img
                                                    src="{{ asset('storage/' . $chat->attachment_path) }}"
                                                    alt="{{ $chat->attachment_original_name ?? 'Lampiran' }}"
                                                    class="admin-message-attachment"
                                                >
                                            </a>
                                        @endif

                                        @if (filled($chat->message))
                                            <div style="white-space: pre-line;">
                                                {{ $chat->message }}
                                            </div>
                                        @endif

                                        <div class="admin-message-time">
                                            {{ $chat->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="admin-chat-footer">
                    <form wire:submit.prevent="sendReply" class="admin-chat-form">
                        <div class="admin-chat-input-box">
                            @if ($attachment_upload)
                                <div class="admin-upload-preview">
                                    <img src="{{ $attachment_upload->temporaryUrl() }}" alt="Preview foto">
                                    <div class="admin-upload-preview-text">
                                        Foto siap dikirim
                                    </div>
                                </div>
                            @endif

                            <textarea
                                wire:model.defer="reply"
                                rows="2"
                                placeholder="Tulis balasan admin..."
                                class="admin-chat-textarea"
                            ></textarea>

                            <label class="admin-attach-button">
                                Kirim Foto
                                <input type="file" wire:model="attachment_upload" accept="image/*">
                            </label>

                            @error('reply')
                                <p style="margin-top: 8px; color: #e11d48; font-size: 13px;">{{ $message }}</p>
                            @enderror

                            @error('attachment_upload')
                                <p style="margin-top: 8px; color: #e11d48; font-size: 13px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="admin-send-button">
                            Balas
                        </button>
                    </form>
                </div>
            @else
                <div class="admin-empty-chat">
                    <div>
                        <h3 style="margin: 0; font-size: 20px; font-weight: 800; color: #1e293b;">
                            Belum ada percakapan
                        </h3>
                        <p style="margin: 8px 0 0; font-size: 14px;">
                            Pesan dari tester akan muncul di sini.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>