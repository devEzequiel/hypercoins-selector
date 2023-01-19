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
    public function __construct($cards, $name)
    {
        $this->cards = $cards;
        $this->name = $name; //Auth::user()->name;
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
        $this->to(auth()->user()->email, $this->name);
        return $this->view('cards.email', ['name' => $this->name, 'cards' => $this->cards]);
    }
}
