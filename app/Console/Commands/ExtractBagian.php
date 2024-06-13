<?php

namespace App\Console\Commands;

use App\Libraries\AuthAPI;
use Illuminate\Console\Command;
use App\Modules\Master\Bagian\Model;

class ExtractBagian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:extract-bagian';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract bagian from API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $api = new AuthAPI();

        $bagian = $api->getBagian();
        $chunk = array_chunk($bagian, 100);
        
        $time_start = microtime(true); 

        foreach ($chunk as $data) {
            $upsert = [];
           foreach ($data as $key => $value) {

                $value->code = $value->kdbagchild;
                $value->name = $value->namabagian;
                $value->parent_code = $value->kdbagparent;
                $value->status = ($value->statusaktif == 'y') ? 1 : 0;

                $upsert[] = array_intersect_key((array)$value, array_flip(['name', 'code', 'parent_code', 'scope', 'jenis_relasi', 'id_scope']));
            }

            Model::upsert($upsert,['code'], ['name', 'code', 'parent_code', 'scope', 'jenis_relasi', 'id_scope','status']);
            unset($upsert);
        }

        $time_end = microtime(true);

        $execution_time = ($time_end - $time_start)/60;

        $this->info('Execute time : '.$execution_time.' Mins');
    }
}

