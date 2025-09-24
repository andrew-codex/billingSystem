<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Consumer;
class ConsumerWelcome extends Mailable
{
    use Queueable, SerializesModels;

    public $consumer;
    public $plainPassword;

    /**
     * Create a new message instance.
     */
    public function __construct(Consumer $consumer, string $plainPassword)
    {
        $this->consumer = $consumer;
        $this->plainPassword = $plainPassword;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Consumer Welcome',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.consumer_welcome',
        );
    }

        public function build()
    {
        return $this->subject('Your account details')
                    ->markdown('emails.consumer_welcome'); 
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
