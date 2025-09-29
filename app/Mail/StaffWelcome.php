<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StaffWelcome extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $plainPassword;

    public function __construct(User $user, $plainPassword)
    {
        $this->user = $user;
        $this->plainPassword = $plainPassword;
    }

    public function build()
    {
        return $this->subject('Welcome to Billing System - Staff Account')
                    ->markdown('emails.staff.welcome')
                    ->with([
                        'user' => $this->user,
                        'plainPassword' => $this->plainPassword,
                    ]);
    }
}
