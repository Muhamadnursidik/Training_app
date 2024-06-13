<?php

namespace App\Console\Commands;

use App\Libraries\AuthAPI;
use Illuminate\Console\Command;
use App\Modules\Master\Unitkerja\Model;
use App\Modules\Master\Tipeunitkerja\Service as TipeunitkerjaService;

class ExtractKantor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:extract-kantor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract kantor from API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $api = new AuthAPI();

        $bagian = $api->getKantor();
        $chunk = array_chunk($bagian, 500);
        
        $time_start = microtime(true); 
        $tipeunit = TipeunitkerjaService::getTipeUnitKerjaId();

        foreach ($chunk as $data) {
            $upsert = [];
           foreach ($data as $key => $value) {
            
                $value->code = $value->nopend;
                $value->name = $value->nama_kantor;
                $value->tipe_unit_kerja_id = isset($tipeunit[$value->kdlevel]) ? $tipeunit[$value->kdlevel] : null;

                $upsert[] = array_intersect_key((array)$value, array_flip(['name', 'code', 'tipe_unit_kerja_id', 'nopend_kc', 'nopend_kcu', 'nopend_regional']));
            }

            $temp = array_unique(array_column($upsert, 'code'));
            $unique_arr = array_intersect_key($upsert, $temp);

            Model::upsert($unique_arr,['code'], ['name', 'code', 'tipe_unit_kerja_id', 'nopend_kc', 'nopend_kcu', 'nopend_regional']);
            unset($upsert);
        }

        $time_end = microtime(true);

        $execution_time = ($time_end - $time_start)/60;

        $this->info('Execute time : '.$execution_time.' Mins');
    }
}
