<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WarningMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $dataMail;
    public $onBreakMin;
    public $onBreakH;
    public $onBreakCC;

    /**
     * Create a new message instance.
     *
     * @param User $user
     */
    public function __construct(User $user, $dataMail, $onBreakSum)
    {
        //
        $this->user = $user;
        $this->dataMail = $dataMail;

        foreach($dataMail as $data){
            $d = $data->onBreakTimestamp;
            $this->onBreakCC = gmdate("i", $d);
        }



        $this->onBreakMin = gmdate("i", $onBreakSum);
        $this->onBreakH = gmdate("H", $onBreakSum);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($address = 'noreply@noreply.com', $name = 'Kašnjenja')
            ->subject('Kašnjenje')
            ->view('emails.warning');
    }
}
