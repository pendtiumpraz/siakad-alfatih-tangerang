<?php

namespace App\Mail;

use App\Models\DaftarUlang;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MahasiswaAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $daftarUlang;
    public $username;
    public $password;
    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(DaftarUlang $daftarUlang, string $username, string $password)
    {
        $this->daftarUlang = $daftarUlang;
        $this->username = $username;
        $this->password = $password;
        $this->loginUrl = route('login');
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Akun Mahasiswa STAI AL-FATIH Telah Dibuat')
                    ->view('emails.mahasiswa-account-created');
    }
}
