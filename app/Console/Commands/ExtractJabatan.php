<?php

namespace App\Console\Commands;

use App\Libraries\AuthAPI;
use Illuminate\Console\Command;
use App\Modules\Master\Jabatan\Model;

class ExtractJabatan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:extract-jabatan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract jabatan from API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $api = new AuthAPI();

        $jabatan = $api->getJabatan();
        $time_start = microtime(true);

        $upsert = [];
        foreach ($jabatan as $key => $value) {

            $value->code = $value->Jabatan;
            $value->name = $value->DescJabatan;
            $value->status = ($value->statusaktif == 'y') ? 1 : 0;

            $upsert[] = array_intersect_key((array) $value, array_flip(['name', 'code']));
        }

        Model::upsert($upsert, ['code'], ['name', 'code']);
        unset($upsert);


        $time_end = microtime(true);

        $execution_time = ($time_end - $time_start) / 60;

        $this->info('Execute time : ' . $execution_time . ' Mins');
    }
}
