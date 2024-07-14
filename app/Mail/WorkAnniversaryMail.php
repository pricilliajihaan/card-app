<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Card;

class WorkAnniversaryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $base64;

    /**
     * Create a new message instance.
     *
     * @return void
     */
   
    public function __construct(Card $user, $base64)
    {
        $this->user = $user;
        $this->base64 = $base64;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->attachData(base64_decode($this->base64), 'work_anniversary.jpg', [
            'mime' => 'image/jpeg',
            'as' => 'work_anniversary.jpg', // Nama file yang digunakan di dalam email
        ]);
    
        return $this->subject('Happy Work Anniversary!')
                    ->view('emails.work_anniversary')
                    ->with([
                        'user' => $this->user,
                        'image' => 'cid:work_anniversary.jpg',
                    ]);
    }
}
