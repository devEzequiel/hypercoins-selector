<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CardsSent extends Mailable
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
        $this->name = 'Ezequiell';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Um cliente acaba de resgatar cards em seu site');
        //$this->to(Auth::user()->email, Auth::user()->name);
        $this->to('ezeqcoder@gmail.com', 'Marcos');
        return $this->view('reports.email', ['name' => 'Ezequiel', 'cards' => $this->cards]);
    }
}
