<?php

namespace App\Filament\Admin\Pages;

use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;

class PesanTester extends Page
{
    use WithFileUploads;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Pesan Tester';

    protected static ?string $title = 'Pesan Tester';

    protected static ?string $navigationGroup = 'Bantuan & Support';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.admin.pages.pesan-tester';

    public ?int $selectedTicketId = null;

    public string $reply = '';

    public $attachment_upload = null;

    public function mount(): void
    {
        $this->selectedTicketId = SupportTicket::query()
            ->latest('last_message_at')
            ->value('id');

        $this->markTesterMessagesAsRead();
    }

    public function getTicketsProperty()
    {
        return SupportTicket::query()
            ->with(['tester', 'latestMessage'])
            ->withCount([
                'messages as unread_count' => fn ($query) => $query
                    ->where('sender_role', 'tester')
                    ->where('is_read', false),
            ])
            ->latest('last_message_at')
            ->get();
    }

    public function getSelectedTicketProperty(): ?SupportTicket
    {
        if (! $this->selectedTicketId) {
            return null;
        }

        return SupportTicket::query()
            ->with(['tester', 'messages.sender'])
            ->find($this->selectedTicketId);
    }

    public function selectTicket(int $ticketId): void
    {
        $this->selectedTicketId = $ticketId;
        $this->reply = '';
        $this->attachment_upload = null;

        $this->markTesterMessagesAsRead();
    }

    public function sendReply(): void
    {
        $this->validate([
            'reply' => ['nullable', 'string', 'max:1000'],
            'attachment_upload' => ['nullable', 'image', 'max:5120'],
        ], [
            'reply.max' => 'Balasan maksimal 1000 karakter.',
            'attachment_upload.image' => 'File harus berupa gambar.',
            'attachment_upload.max' => 'Ukuran gambar maksimal 5 MB.',
        ]);

        $replyText = trim($this->reply);

        if ($replyText === '' && ! $this->attachment_upload) {
            Notification::make()
                ->title('Balasan atau foto wajib diisi.')
                ->danger()
                ->send();

            return;
        }

        if (! $this->selectedTicketId) {
            Notification::make()
                ->title('Pilih percakapan terlebih dahulu.')
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

        DB::transaction(function () use ($replyText, $attachmentPath, $attachmentName, $attachmentMime) {
            $ticket = SupportTicket::query()->findOrFail($this->selectedTicketId);

            SupportMessage::create([
                'support_ticket_id' => $ticket->id,
                'sender_id' => Auth::id(),
                'sender_role' => 'admin',
                'message' => $replyText,
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

        $this->reply = '';
        $this->attachment_upload = null;

        Notification::make()
            ->title('Balasan berhasil dikirim.')
            ->success()
            ->send();
    }

    public function refreshInbox(): void
    {
        $this->markTesterMessagesAsRead();
    }

    private function markTesterMessagesAsRead(): void
    {
        if (! $this->selectedTicketId) {
            return;
        }

        SupportMessage::query()
            ->where('support_ticket_id', $this->selectedTicketId)
            ->where('sender_role', 'tester')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
            ]);
    }
}