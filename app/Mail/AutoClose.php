<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AutoClose extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($usersAutoClose)
    {
        //
        $this->user = User::whereIn('id', $usersAutoClose)->get();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($address = 'noreply@noreply.com', $name = 'Kašnjenja')
            ->subject('Automatsko zatvaranje')
            ->view('emails.autoClose');
    }
}
