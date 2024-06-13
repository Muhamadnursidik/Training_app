<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Mapping\Notifications\Service;

class SendEmailRisktreatment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-email-risktreatment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to user when tgl target risk treatment not input';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (config('data.send_email') == 'true') {
            return Service::notif_risktreatment([]);
        }
    }
}
