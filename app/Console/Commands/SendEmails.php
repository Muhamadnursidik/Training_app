<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Mapping\Notifications\Service;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email notification to pic';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if(config('data.send_email') == 'true'){
            return Service::sendMail([]);
        }
    }
}
