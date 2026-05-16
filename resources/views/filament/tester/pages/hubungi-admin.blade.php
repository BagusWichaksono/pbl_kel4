<x-filament-panels::page>
    <style>
        .chat-wrapper {
            width: 100%;
            max-width: 1180px;
            margin: 0 auto;
        }

        .chat-card {
            width: 100%;
            height: calc(100vh - 220px);
            min-height: 620px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border-radius: 28px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 50px -20px rgba(15, 23, 42, 0.22);
        }

        .dark .chat-card {
            background: #0f172a;
            border-color: #334155;
        }

        .chat-header {
            position: relative;
            overflow: hidden;
            padding: 24px 28px;
            background: linear-gradient(135deg, #141c33 0%, #2f456f 55%, #5374ac 100%);
            color: #ffffff;
        }

        .chat-header::before {
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

        .chat-header-content {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .chat-title-wrap {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .chat-icon {
            width: 56px;
            height: 56px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.22);
            color: #ffffff;
            flex-shrink: 0;
        }

        .chat-title {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .chat-subtitle {
            margin: 5px 0 0;
            color: #dbeafe;
            font-size: 14px;
        }

        .chat-body {
            flex: 1;
            overflow-y: auto;
            padding: 28px;
            background:
                radial-gradient(circle at top left, rgba(83, 116, 172, 0.08), transparent 300px),
                linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
        }

        .dark .chat-body {
            background:
                radial-gradient(circle at top left, rgba(83, 116, 172, 0.12), transparent 300px),
                linear-gradient(180deg, #020617 0%, #0f172a 100%);
        }

        .message-row {
            display: flex;
            margin-bottom: 18px;
        }

        .message-row.mine {
            justify-content: flex-end;
        }

        .message-row.admin {
            justify-content: flex-start;
        }

        .message-content {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            max-width: 72%;
        }

        .message-row.mine .message-content {
            flex-direction: row-reverse;
        }

        .message-avatar {
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

        .message-row.mine .message-avatar {
            background: #eff5fa;
            color: #2f456f;
        }

        .message-row.admin .message-avatar {
            background: #e2e8f0;
            color: #334155;
        }

        .dark .message-row.admin .message-avatar {
            background: #334155;
            color: #f8fafc;
        }

        .message-meta {
            margin-bottom: 5px;
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
        }

        .message-row.mine .message-meta {
            text-align: right;
        }

        .dark .message-meta {
            color: #94a3b8;
        }

        .message-bubble {
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

        .message-row.mine .message-bubble {
            background: #5374ac;
            color: #ffffff;
            border-bottom-right-radius: 6px;
        }

        .message-row.admin .message-bubble {
            background: #ffffff;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            border-bottom-left-radius: 6px;
        }

        .dark .message-row.admin .message-bubble {
            background: #1e293b;
            color: #f8fafc;
            border-color: #334155;
        }

        .message-attachment {
            display: block;
            max-width: 260px;
            width: 100%;
            border-radius: 16px;
            margin-bottom: 10px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            object-fit: cover;
        }

        .message-time {
            margin-top: 7px;
            text-align: right;
            font-size: 11px;
            opacity: 0.7;
        }

        .empty-state {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-box {
            width: 100%;
            max-width: 420px;
            text-align: center;
            border-radius: 24px;
            border: 1px dashed #cbd5e1;
            background: rgba(255, 255, 255, 0.75);
            padding: 36px;
        }

        .dark .empty-box {
            background: rgba(30, 41, 59, 0.72);
            border-color: #475569;
        }

        .chat-footer {
            padding: 18px 20px;
            border-top: 1px solid #e2e8f0;
            background: #ffffff;
        }

        .dark .chat-footer {
            background: #0f172a;
            border-color: #334155;
        }

        .chat-form {
            display: flex;
            align-items: flex-end;
            gap: 12px;
        }

        .chat-input-box {
            flex: 1;
            border-radius: 22px;
            border: 1px solid #dbe3ef;
            background: #f8fafc;
            padding: 12px 14px;
            box-shadow: inset 0 1px 3px rgba(15, 23, 42, 0.04);
            transition: all 0.2s ease;
        }

        .chat-input-box:hover {
            border-color: #b3cce2;
            background: #ffffff;
        }

        .chat-input-box:focus-within {
            border-color: #5374ac;
            background: #ffffff;
            box-shadow:
                0 0 0 4px rgba(83, 116, 172, 0.12),
                inset 0 1px 3px rgba(15, 23, 42, 0.04);
        }

        .dark .chat-input-box {
            background: #020617;
            border-color: #334155;
        }

        .dark .chat-input-box:hover,
        .dark .chat-input-box:focus-within {
            background: #0f172a;
            border-color: #5374ac;
        }

        .chat-textarea {
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

        .chat-textarea:focus,
        .chat-textarea:focus-visible,
        .chat-textarea:active {
            border: 0 !important;
            outline: none !important;
            box-shadow: none !important;
        }

        .dark .chat-textarea {
            color: #ffffff;
        }

        .chat-textarea::placeholder {
            color: #94a3b8;
        }

        .upload-preview {
            width: fit-content;
            max-width: 100%;
            margin-bottom: 12px;
            border-radius: 18px;
            border: 1px solid #dbe3ef;
            background: #ffffff;
            padding: 8px;
            box-shadow: 0 12px 28px -18px rgba(15, 23, 42, 0.35);
        }

        .dark .upload-preview {
            background: #0f172a;
            border-color: #334155;
        }

        .upload-preview img {
            display: block;
            width: 170px;
            max-height: 130px;
            object-fit: cover;
            border-radius: 14px;
        }

        .upload-preview-text {
            margin-top: 7px;
            color: #64748b;
            font-size: 12px;
            font-weight: 700;
        }

        .dark .upload-preview-text {
            color: #cbd5e1;
        }

        .attach-button {
            display: inline-flex;
            align-items: center;
            gap: 7px;
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

        .attach-button:hover {
            background: #d1e1f1;
        }

        .attach-button input {
            display: none;
        }

        .upload-loading {
            margin-top: 7px;
            font-size: 12px;
            color: #64748b;
            font-weight: 700;
        }

        .send-button {
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

        .send-button:hover {
            background: #425d8a;
            transform: translateY(-1px);
        }

        .send-button:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
        }

        @media (max-width: 768px) {
            .chat-card {
                height: calc(100vh - 180px);
                min-height: 560px;
                border-radius: 22px;
            }

            .chat-header {
                padding: 20px;
            }

            .chat-title {
                font-size: 20px;
            }

            .chat-body {
                padding: 20px;
            }

            .message-content {
                max-width: 88%;
            }

            .chat-form {
                flex-direction: column;
                align-items: stretch;
            }

            .send-button {
                width: 100%;
            }
        }
    </style>

    <div
        class="chat-wrapper"
        wire:poll.2s="refreshMessages"
        x-data
        x-init="
            const boxId = 'chat-box-tester';
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
        <div class="chat-card">
            <div class="chat-header">
                <div class="chat-header-content">
                    <div class="chat-title-wrap">
                        <div class="chat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0Zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0Zm0 0H12m3.75 0a.375.375 0 11-.75 0 .375.375 0 01.75 0Zm0 0h-.375M21 12c0 4.97-4.03 9-9 9a8.96 8.96 0 01-4.255-1.067L3 20.25l.317-4.128A8.962 8.962 0 013 12c0-4.97 4.03-9 9-9s9 4.03 9 9Z" />
                            </svg>
                        </div>

                        <div>
                            <h2 class="chat-title">Chat dengan Admin</h2>
                            <p class="chat-subtitle">Tanyakan kendala penggunaan TesYuk langsung ke admin.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="chat-box-tester" class="chat-body">
                @forelse ($this->messages as $chat)
                    @php
                        $isMine = $chat->sender_role === 'tester';
                    @endphp

                    <div class="message-row {{ $isMine ? 'mine' : 'admin' }}">
                        <div class="message-content">
                            <div class="message-avatar">
                                {{ $isMine ? 'K' : 'A' }}
                            </div>

                            <div>
                                <div class="message-meta">
                                    {{ $isMine ? 'Kamu' : 'Admin' }}
                                </div>

                                <div class="message-bubble">
                                    @if ($chat->attachment_path)
                                        <a href="{{ asset('storage/' . $chat->attachment_path) }}" target="_blank">
                                            <img
                                                src="{{ asset('storage/' . $chat->attachment_path) }}"
                                                alt="{{ $chat->attachment_original_name ?? 'Lampiran' }}"
                                                class="message-attachment"
                                            >
                                        </a>
                                    @endif

                                    @if (filled($chat->message))
                                        <div style="white-space: pre-line;">
                                            {{ $chat->message }}
                                        </div>
                                    @endif

                                    <div class="message-time">
                                        {{ $chat->created_at->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-box">
                            <h3 style="margin: 0; font-size: 18px; font-weight: 800; color: #1e293b;">
                                Belum ada pesan
                            </h3>
                            <p style="margin: 8px 0 0; color: #64748b; font-size: 14px; line-height: 1.6;">
                                Mulai percakapan dengan admin untuk menanyakan kendala atau bantuan seputar TesYuk.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="chat-footer">
                <form wire:submit.prevent="sendMessage" class="chat-form">
                    <div class="chat-input-box">
                        @if ($attachment_upload)
                            <div class="upload-preview">
                                <img src="{{ $attachment_upload->temporaryUrl() }}" alt="Preview foto">
                                <div class="upload-preview-text">
                                    Foto siap dikirim
                                </div>
                            </div>
                        @endif

                        <textarea
                            wire:model.defer="message"
                            rows="2"
                            placeholder="Tulis pesan kamu..."
                            class="chat-textarea"
                        ></textarea>

                        <label class="attach-button">
                            + Kirim Foto
                            <input type="file" wire:model="attachment_upload" accept="image/*">
                        </label>

                        <div wire:loading wire:target="attachment_upload" class="upload-loading">
                            Mengunggah foto...
                        </div>

                        @error('attachment_upload')
                            <p style="margin-top: 8px; color: #e11d48; font-size: 13px;">{{ $message }}</p>
                        @enderror

                        @error('message')
                            <p style="margin-top: 8px; color: #e11d48; font-size: 13px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="send-button" wire:loading.attr="disabled" wire:target="attachment_upload,sendMessage">
                        Kirim
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-filament-panels::page>