<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class DemoEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $content;
    public $fromEmail;

    public function __construct($subject, $content, $fromEmail)
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->fromEmail = $fromEmail;
    }

    public function build()
    {
        return $this->view('emails.demo')
                    ->subject($this->subject)
                    ->with([
                        'subject' => $this->subject,
                        'content' => $this->content,
                        'fromEmail' => $this->fromEmail
                    ]);
    }
}
