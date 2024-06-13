<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Master\Katalogrisiko\Model as KatalogrisikoModel;
use App\Modules\Master\Dampaknonkeuangan\Model as DampaknonkeuanganModel;
use App\Modules\Master\Dampaknonkeuangan\ModelDetail as DampaknonkeuanganDetail;
use App\Modules\Penilaian\Led\Inputkejadian\Model as InputkejadianModel;
use App\Modules\Tools\Appconfig\Service as AppconfigService;
use Illuminate\Support\Facades\DB;

class AutomateIntegration extends Command
{
    protected $_master_aktivitas = 'master_aktivitas';
    protected $_temporary        = 'tmp_other_data_app';
    protected $_jenis_kerugian   = ['QACA' => 'potensialloss', 'AUDIT' => 'potensialloss', 'KELUHAN' => 'softloss', 'CBS' => 'nearmiss'];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:automate-integration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatic Integration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $integration = $this->config('INTEGRATION');
        $dampak_non_finansial = $this->setDampakNonFinansial(['peringkat' => 1]);
        
        $data = DB::connection('datasource')->table($this->_temporary, 'a')
            ->where('has_input', false)->get();

        if ($data->count() > 0) {
            foreach ($data as $key => $value) {

                $katalog = KatalogrisikoModel::whereJsonContains('code_reference', [['kode_reference' => $value->kode_reference, 'source' => $value->source]])->first();
                $jenis_kerugian = isset($this->_jenis_kerugian[$value->source]) ? $this->_jenis_kerugian[$value->source] : 'nearmiss';
                

                $input = [
                    'jenis_kerugian'        => $jenis_kerugian,
                    'unit_kerja_code'       => $value->unit_kerja_code,
                    'katalog_risiko_id'     => $katalog->id,
                    'aktifitas_fungsi_id'   => $katalog->aktifitas_fungsi_id,
                    'parameterkri_id'       => $katalog->kri_id,
                    'tgl_kejadian'          => $value->tgl_kejadian,
                    'tgl_diketahui'         => $value->tgl_kejadian,
                    'tgl_diselesaikan'      => $value->tgl_kejadian,
                    'penjelasan_isu'        => $value->deskripsi,
                    'kondisi_seharusnya'    => '-',
                    'jenis_risiko'          => $katalog->jenis_risiko,
                    'penyebab_utama_id'     => $katalog->penyebab_utama_id,
                    'penyebab_utama_detail' => $katalog->penyebab_utama_detail,
                    'jenis_dampak_id'       => $katalog->jenis_dampak_id,
                    'mitigasi_id'           => $katalog->mitigasi_id,
                    'mitigasi_detail'       => $katalog->mitigasi_detail,
                    'jenis_kejadian_risiko_id' => $katalog->jenis_kejadian_risiko_id,
                    'pihak_terlibat_eks_id' => null,
                    'pihak_terlibat_int_id' => null,
                    'dampak_finansial'      => $value->total_kerugian,
                    'peringkat_dampak_finansial' => !empty($value->tingkat_risiko) ? $value->tingkat_risiko : (isset($integration[$value->source]) ? $integration[$value->source] : 1),
                    'kerugian_aktual'       => $value->total_kerugian,
                    'dampak_non_finansial'  => $dampak_non_finansial,
                    'peringkat_dampak_non_finansial' => !empty($value->tingkat_risiko) ? $value->tingkat_risiko : (isset($integration[$value->source]) ? $integration[$value->source] : 1),
                    'peringkat'             => !empty($value->tingkat_risiko) ? $value->tingkat_risiko : (isset($integration[$value->source]) ? $integration[$value->source] : 1),
                    'temuan'                => ($value->source == 'AUDIT') ? true : false,
                    'auditor_id'            => null,
                    'tahun_audit'           => ($value->source == 'AUDIT') ? date('Y', strtotime($value->tgl_kejadian)) : null,
                    'status_penyelesaian'   => true,
                    'produk_jasa_id'        => null,
                    'data_sistem_id'        => null,
                    'status'                => 'SELESAI',
                    'approval_by_name'      => $value->approval_by,
                    'approval_date'         => $value->approval_date
                ];

                if (InputkejadianModel::createOne($input)) {
                    DB::connection('datasource')->table($this->_temporary)->where('id', $value->id)->update(['has_input' => true]);
                    echo 'Input kejadian by system telah disimpan' . PHP_EOL;
                }
            }
        }
    }

    private function config($code)
    {
        $config = AppconfigService::getbycode($code);
        $output = [];
        foreach (json_decode($config->config, true) as $key => $value) {
            $output[$value['key']] = (int)$value['value'];
        }
        return $output;
    }

    private function setDampakNonFinansial(array $data)
    {
        $query = DB::table((new DampaknonkeuanganModel)->getTable(),'a')
        ->select(['b.id'])
        ->join((new DampaknonkeuanganDetail)->getTable().' as b', function($query)use($data){
            $query->on('b.dampak_non_keuangan_id','=','a.id');
            $query->where('b.peringkat', $data['peringkat']);
        })->get()->pluck('id');

        return !empty($query) ? $query : [];
    }
}
