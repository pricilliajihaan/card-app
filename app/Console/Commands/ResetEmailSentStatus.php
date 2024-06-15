<?php

namespace App\Console\Commands;

use App\Models\Card;
use Illuminate\Console\Command;

class ResetEmailSentStatus extends Command
{
    protected $signature = 'app:reset-email-sent-status';

    protected $description = 'Command description';

    public function handle()
    {
        Card::query()->update(['email_sent' => false]);
    }
}
