<?php

namespace App\Filament\Tester\Pages;

use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;

class HubungiAdmin extends Page
{
    use WithFileUploads;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Hubungi Admin';

    protected static ?string $title = 'Hubungi Admin';

    protected static ?string $navigationGroup = 'Akun & Bantuan';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.tester.pages.hubungi-admin';

    public ?SupportTicket $ticket = null;

    public string $message = '';

    public $attachment_upload = null;

    public function mount(): void
    {
        $this->ticket = $this->getOrCreateTicket();
        $this->markAdminMessagesAsRead();
    }

    private function getOrCreateTicket(): SupportTicket
    {
        return SupportTicket::query()->firstOrCreate(
            [
                'tester_id' => Auth::id(),
                'status' => 'open',
            ],
            [
                'subject' => 'Bantuan Tester',
                'last_message_at' => now(),
            ]
        );
    }

    public function getMessagesProperty()
    {
        $ticket = $this->ticket ?? $this->getOrCreateTicket();

        return $ticket->messages()
            ->with('sender')
            ->oldest()
            ->get();
    }

    public function sendMessage(): void
    {
        $this->validate([
            'message' => ['nullable', 'string', 'max:1000'],
            'attachment_upload' => ['nullable', 'image', 'max:5120'],
        ], [
            'message.max' => 'Pesan maksimal 1000 karakter.',
            'attachment_upload.image' => 'File harus berupa gambar.',
            'attachment_upload.max' => 'Ukuran gambar maksimal 5 MB.',
        ]);

        $messageText = trim($this->message);

        if ($messageText === '' && ! $this->attachment_upload) {
            Notification::make()
                ->title('Pesan atau foto wajib diisi.')
                ->danger()
                ->send();

            return;
        }

        $attachmentPath = null;
        $attachmentName = null;
        $attachmentMime = null;

        if ($this->attachment_upload) {
            $attachmentName = $this->attachment_upload->getClientOriginalName();
            $attachmentMime = $this->attachment_upload->getMimeType();
            $attachmentPath = $this->attachment_upload->store('support-attachments', 'public');
        }

        DB::transaction(function () use ($messageText, $attachmentPath, $attachmentName, $attachmentMime) {
            $ticket = $this->ticket ?? $this->getOrCreateTicket();

            SupportMessage::create([
                'support_ticket_id' => $ticket->id,
                'sender_id' => Auth::id(),
                'sender_role' => 'tester',
                'message' => $messageText,
                'attachment_path' => $attachmentPath,
                'attachment_original_name' => $attachmentName,
                'attachment_mime_type' => $attachmentMime,
                'is_read' => false,
            ]);

            $ticket->update([
                'status' => 'open',
                'last_message_at' => now(),
            ]);
        });

        $this->message = '';
        $this->attachment_upload = null;

        Notification::make()
            ->title('Pesan berhasil dikirim.')
            ->success()
            ->send();
    }

    public function refreshMessages(): void
    {
        $this->markAdminMessagesAsRead();
    }

    private function markAdminMessagesAsRead(): void
    {
        if (! $this->ticket) {
            return;
        }

        SupportMessage::query()
            ->where('support_ticket_id', $this->ticket->id)
            ->where('sender_role', 'admin')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
            ]);
    }
}