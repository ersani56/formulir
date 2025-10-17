<?php

namespace App\Mail;

use App\Models\ArsipPeg;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentUploadNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $nip;
    public $email;
    public $uploadedFiles;
    public $isUpdate;
    public $arsipPeg;
    public $uploadTime;

    /**
     * Create a new message instance.
     */
    public function __construct($nip, $email, $uploadedFiles, $isUpdate, $arsipPeg)
    {
        $this->nip = $nip;
        $this->email = $email;
        $this->uploadedFiles = $uploadedFiles;
        $this->isUpdate = $isUpdate;
        $this->arsipPeg = $arsipPeg;
        $this->uploadTime = now()->format('d/m/Y H:i:s');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $action = $this->isUpdate ? 'Diperbarui' : 'Diupload';
        return new Envelope(
            subject: "📄 Dokumen Pegawai {$action} - NIP: {$this->nip}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.document-upload-notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
