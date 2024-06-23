<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OTPMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    // public function build()
    // {
    //     return $this->subject('OTP for Registration')->view('emails.otp')->with(['otp' => $this->otp]);
    // }
    public function build()
    {
        return $this->subject('SugarBloom Bakery - One-Time Password for Account Registration')
                    ->view('emails.otp')
                    ->with(['otp' => $this->otp]);
    }

}
