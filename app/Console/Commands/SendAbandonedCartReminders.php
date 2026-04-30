<?php

namespace App\Console\Commands;

use App\Http\Controllers\AbandonedCartController;
use Illuminate\Console\Command;

class SendAbandonedCartReminders extends Command
{
    protected $signature = 'abandoned-cart:send-reminders';
    protected $description = 'Send abandoned cart reminder emails';

    public function handle()
    {
        $controller = new AbandonedCartController();
        $response = $controller->sendReminders();
        
        $this->info($response->getData()->message);
    }
}