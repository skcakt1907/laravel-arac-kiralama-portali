<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $data) {}

    public function build()
    {
        $name = $this->data['name'] ?? '';

        $mail = $this->subject('Yeni İletişim Mesajı — ' . $name)
            ->view('emails.contact', ['d' => $this->data]);

        // Admin doğrudan "Yanıtla" deyince müşteriye gitsin
        if (! empty($this->data['email'])) {
            $mail->replyTo($this->data['email'], $name ?: null);
        }

        return $mail;
    }
}
