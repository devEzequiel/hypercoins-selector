<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class SendCards extends Mailable
{
    use Queueable, SerializesModels;

    public $cards;
    public $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($cards)
    {
        $this->cards = $cards;
        $this->name = 'Ezequiel'; //Auth::user()->name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Você resgatou os seguintes cards');
        //$this->to(Auth::user()->email, Auth::user()->name);
        $this->to('ezeqcoder@gmail.com', 'Ezequiel');
        return $this->view('cards.email', ['name' => $this->name, 'cards' => $this->cards]);
    }
}
