<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreatedMailable extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $password;
    public string $linkToResetPassword;

    public function __construct(string $name, string $password, string $linkToResetPassword)
    {
        $this->name = $name;
        $this->password = $password;
        $this->linkToResetPassword = $linkToResetPassword;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.userCreated');
    }
}
