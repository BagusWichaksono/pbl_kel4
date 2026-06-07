<?php

namespace App\Livewire;

use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Support\AppNotifier;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class SupportChatWidget extends Component
{
    use WithFileUploads;

    public string $role = 'tester';

    public ?SupportTicket $ticket = null;

    public string $message = '';

    public $attachment_upload = null;

    public function mount(string $role = 'tester'): void
    {
        $this->role = in_array($role, ['developer', 'tester'], true) ? $role : 'tester';
        $this->ticket = $this->getOrCreateTicket();
    }

    public function getMessagesProperty()
    {
        $ticket = $this->ticket ?? $this->getOrCreateTicket();

        return $ticket->messages()
            ->with('sender')
            ->oldest()
            ->get();
    }

    public function getUnreadAdminMessagesProperty(): int
    {
        if (! $this->ticket) {
            return 0;
        }

        return SupportMessage::query()
            ->where('support_ticket_id', $this->ticket->id)
            ->where('sender_role', 'admin')
            ->where('is_read', false)
            ->count();
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

        DB::transaction(function () use ($messageText, $attachmentPath, $attachmentName, $attachmentMime): void {
            $ticket = $this->ticket ?? $this->getOrCreateTicket();

            SupportMessage::create([
                'support_ticket_id' => $ticket->id,
                'sender_id' => Auth::id(),
                'sender_role' => $this->role,
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

        AppNotifier::adminsDatabase(
            'Pesan bantuan baru',
            (Auth::user()?->name ?? 'Pengguna') . ' mengirim pesan bantuan dari panel ' . ucfirst($this->role) . '.',
        );

        $this->message = '';
        $this->attachment_upload = null;
        $this->ticket = $this->getOrCreateTicket();

        Notification::make()
            ->title('Pesan berhasil dikirim.')
            ->success()
            ->send();
    }

    public function refreshMessages(): void
    {
        $this->ticket = $this->getOrCreateTicket();
    }

    public function openChat(): void
    {
        $this->ticket = $this->getOrCreateTicket();
        $this->markAdminMessagesAsRead();
    }

    public function render()
    {
        return view('livewire.support-chat-widget', [
            'messages' => $this->messages,
            'unreadAdminMessages' => $this->unreadAdminMessages,
        ]);
    }

    private function getOrCreateTicket(): SupportTicket
    {
        return SupportTicket::query()->firstOrCreate(
            [
                'tester_id' => Auth::id(),
                'status' => 'open',
            ],
            [
                'subject' => $this->role === 'developer' ? 'Bantuan Developer' : 'Bantuan Tester',
                'last_message_at' => now(),
            ]
        );
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
