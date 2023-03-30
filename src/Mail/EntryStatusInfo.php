<?php

namespace Partymeister\Competitions\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Partymeister\Competitions\Models\Entry;

class EntryStatusInfo extends Mailable
{
    use Queueable, SerializesModels;

    protected Entry $entry;

    /**
     * Create a new message instance.
     */
    public function __construct(Entry $entry)
    {
        $this->entry = $entry;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(from: new Address(config('partymeister-core-visitor-registration.password_reset_from_email'), config('partymeister-core-visitor-registration.password_reset_from_name')), subject: config('partymeister-core-visitor-registration.password_reset_subject_prefix').'Information about your entry',);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(text: 'partymeister-competitions::emails.entries.status-info', with: [
            'demoparty' => config('motor-cms-frontend.name'),
            'entry'     => $this->entry,
        ],);
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
