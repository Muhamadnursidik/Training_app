<?php
namespace App\Modules\Master\Unitkerja;

use App\Bases\BaseService;
use App\Facades\Common;
use DataTables;
use App\Modules\Master\Unitkerja\Model;
use App\Modules\Master\Bagian\Model as BagianModel;
use App\Modules\Master\Tipeunitkerja\Model as TipeUnitKerjaModel;
use App\Modules\Master\Tipeunitkerja\Service as TipeUnitKerjaService;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Libraries\AuthAPI;

class Service extends BaseService
{

    public function __construct()
    {
    }

    public function data(array $data)
    {
        $query = Model::withTrashed()->data();

        return DataTables::of($query)
            ->filter(function ($query) use ($data) {

                if ($data['name'] != '') {
                    $query->where('name', 'ILIKE', '%' . $data['name'] . '%');
                }

                if ($data['status'] != '') {
                    $query->where('status', $data['status']);
                }


                if ($data['tipe_unit_kerja_id'] != '') {
                    $query->whereHas('tipeunitkerja', function ($q) use ($data) {
                        $q->where('id', $data['tipe_unit_kerja_id']);
                    });
                }
            })
            ->addColumn('id', function ($query) {
                return encrypt($query->code);
            })
            ->make(true)
            ->getData(true);
    }

    public function store(array $data)
    {
        return Model::transaction(function () use ($data) {
            return Model::createOne([
                'name' => $data['name'],
                'code' => $data['code'],
                'tipe_unit_kerja_id' => $data['tipe_unit_kerja_id'],
                'status' => $data['status'] ? 1 : 0,
            ], function ($query, $event) use ($data) {
                if (in_array($data['tipe_unit_kerja_code'], ['KC','KON','KCB', 'K'])) {
                    $event->konsolidasiunit()->attach(is_array($data['unit_kerja']) ? $data['unit_kerja'] : []);
                }
            });
        });
    }

    public static function get($id)
    {
        if ($id) {
            $query = Model::find($id);
            if ($query) {
                return $query;
            }
        }

        return false;
    }

    public function update(array $data)
    {
        return Model::transaction(function () use ($data) {
            return Model::updateOne($data['id'], [
                'name' => $data['name'],
                'tipe_unit_kerja_id' => $data['tipe_unit_kerja_id'],
                'status' => $data['status'] ? 1 : 0
            ], function ($query, $event, $cursor) use ($data) {
                if (in_array($data['tipe_unit_kerja_code'], ['KC','KON','KCB', 'K'])) {
                    $cursor->konsolidasiunit()->sync(is_array($data['unit_kerja']) ? $data['unit_kerja'] : []);
                }
            });
        });
    }

    public function destroy(array $data)
    {
        return Model::deleteOne(
            $data['id'],
            function ($query, $event, $cursor) {
                $cursor->update(['status' => false]);
            }
        );
    }

    public function destroys(array $data)
    {
        $id = [];
        foreach ($data['id'] as $value) {
            $id[] = decrypt($value);
        }

        return Model::transaction(function () use ($id) {
            return Model::deleteBatch(
                $id,
                function ($query, $event, $cursor) {
                    $cursor->update(['status' => false]);
                }
            );
        });
    }

    public function restore(array $data)
    {
        return Model::transaction(function () use ($data) {
            return Model::restoreData($data['id'], 'code', function ($query) {
                $query->update(['status' => true]);
            });
        });
    }

    public static function count()
    {
        return Model::isActive()->where(function ($q) {
            $q->whereHas('tipeunitkerja', function ($q) {
                $q->where('code', '!=', 'KON'); }); })->count();
    }

    public static function dropdown($default = '', $code_tipe_unit_kerja = '', $child_unit = true, $without_konsolidasi = false)
    {
        $is_admin = Auth::user()->is_admin;
        $results = [];
        // $code_tipe_unit_kerja = empty($code_tipe_unit_kerja) ? ['0','1','2'] : $code_tipe_unit_kerja;

        if ($is_admin) {

            if ($default) {
                $results[''] = __('Pilih');
            }

            $cursors = Model::isActive()->where(function ($q) use ($code_tipe_unit_kerja, $without_konsolidasi) {

                if ($code_tipe_unit_kerja) {
                    $q->whereHas('tipeunitkerja', function ($q) use ($code_tipe_unit_kerja) {
                        if(is_array($code_tipe_unit_kerja)){
                            $q->whereIn('code', $code_tipe_unit_kerja);
                        }else{
                            $q->where('code', $code_tipe_unit_kerja);
                        }
                    });
                }

                if($without_konsolidasi){
                    $q->whereHas('tipeunitkerja', function ($q) {
                        $q->where('code','!=','KON');
                    });
                }

            })->orderBy('name', 'asc')->get();


        } else {
            $unit_kerja_code = Auth::user()->unit_kerja_code;
            $cursors = Model::isActive()->where('code', $unit_kerja_code)->orderBy('name', 'asc')->get();
        }

        foreach ($cursors as $cursor) {
            $results[$cursor->code] = $cursor->name;
            /* tampilkan cabang/unit kerja dibawahnya */
            // if ($child_unit) {
            //     if (isset($cursor->konsolidasiunit)) {
            //         foreach ($cursor->konsolidasiunit as $children) {
            //             $results[$children->code] = $children->name;
            //         }
            //     }
            // }
        }

        return $results;
    }

    public static function getChildren(array $data)
    {
        $query = Model::with(['konsolidasiunit'])->where('code', $data['unit_kerja_code'])->first();

        return $query->konsolidasiunit->pluck('code')->toArray();
    }

    public function download(array $data)
    {
        $spreadsheet = new Spreadsheet();

        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet()->setTitle('Data Unit Kerja');
        $spreadsheet->createSheet();

        $headers = ['nama_unit_kerja', 'unit_kerja_code', 'tipe'];

        for ($i = 0, $l = sizeof($headers); $i < $l; $i++) {
            $sheet->setCellValueByColumnAndRow($i + 1, 1, $headers[$i]);
        }

        $spreadsheet->setActiveSheetIndex(1);
        $sheet2 = $spreadsheet->getActiveSheet()->setTitle('Referensi Tipe Unit Kerja');
        $spreadsheet->createSheet();

        $headers2 = ['KODE', 'NAMA'];

        for ($i = 0, $l = sizeof($headers2); $i < $l; $i++) {
            $sheet2->setCellValueByColumnAndRow($i + 1, 1, $headers2[$i]);
        }

        $query = TipeUnitKerjaModel::get();

        $rows = 2;
        foreach ($query as $item) {
            $sheet2->setCellValue('A' . $rows, $item->code);
            $sheet2->setCellValue('B' . $rows, $item->name);
            $rows++;
        }

        $spreadsheet->setActiveSheetIndex(0);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'template_import_unit_kerja.xlsx';
        $writer->save(storage_path($filename));

        return response()->download(storage_path($filename))->deleteFileAfterSend();
    }

    public function import(array $data)
    {
        try {
            $file = $data['files']->getRealPath();
            $tipe_unit_kerja = TipeUnitKerjaService::getTipeUnitKerjaId();

            $spreadsheet = IOFactory::load($file);
            $spreadsheet->setActiveSheetIndex(0);
            $sheet = $spreadsheet->getActiveSheet();


            for ($i = 2; $i <= $sheet->getHighestRow(); $i++) {
                $tipe_unit_kerja_id = $tipe_unit_kerja[$sheet->getCell("C$i")->getValue()];

                $input['name'] = $sheet->getCell("A$i")->getValue();
                $input['code'] = $sheet->getCell("B$i")->getValue();
                $input['tipe_unit_kerja_id'] = $tipe_unit_kerja_id;
                $input['status'] = 1;

                $results[] = Model::updateOrCreateOne(['code' => $input['code']], $input);

            }

            return $results;
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'status' => 'error',
                'message' => __('Proses simpan gagal.'),
                'data' => $e->getMessage()
            ];
        }
    }

    public static function byName($string)
    {
        $query = Model::where('name', $string)->first()->code;
        return $query;
    }

    public static function dataKonsolidasi(array $data)
    {
        $query = Model::with(['konsolidasiunit'])
            ->where(function ($query) use ($data) {
                $query->where('code', $data['unit_kerja_code']);

                $query->whereHas('tipeunitkerja', function ($q) use ($data) {
                    $q->whereIn('code', ['KC','KON','KCB', 'K']);
                });

            })->first();

        if (!empty($query)) {
            return $query->konsolidasiunit->pluck('code')->toArray();
        } else {
            return [$data['unit_kerja_code']];
        }
    }

    public static function getCode($code){
        return ModeL::with(['tipeunitkerja'])->find($code)->tipeunitkerja->code;
    }

    public static function getBagian($code)
    {
        $unit_kerja = Model::with('tipeunitkerja')->find($code);
        $tipe_unit_kerja = $unit_kerja->tipeunitkerja->code;
        
        $api = new AuthAPI();

        $bagian = $api->getBagianKantor($code);

        $child = [];
        $parent = [];
        switch ($tipe_unit_kerja) {
            case 0: // PUSAT
                $cond = ['Direktorat','CO'];
                // $filtered = array_values(array_filter($bagian, function ($item) use ($cond) {
                //     return in_array($item->ket_relasi, $cond);
                // }));
                $filtered = $bagian;
                break;
            case 1: // REGIONAL
                // $cond = ['Regional'];
                $cond = ['Deputi', 'Deputi Regional'];
                // $filtered = array_values(array_filter($bagian, function ($item) use ($cond) {
                //     return in_array($item->ket_relasi, $cond);
                // }));
                $filtered = $bagian;
                break;
            
            default:
                $cond = ['Bagian KCU','KCU','Bagian KCP','KCP'];
                // $filtered = array_values(array_filter($bagian, function ($item) use ($cond) {
                //     return in_array($item->ket_relasi, $cond);
                // }));
                $filtered = $bagian;
                break;
        }
        $dataParent = BagianModel::whereIn('name', array_column($bagian, 'parent'))->get();
        // dd($bagian);
        // foreach ($bagian as $value) {
            // $temp = $dataParent->where('name','=',$value->parent)->first();
            // if ($temp != null) {
            //     $child[$temp->code][$value->kodechild] = $value->child;
            //     // $parent[$temp->code] = $temp->name;
            // }
            // if ($temp != null && in_array($temp->jenis_relasi, $cond)) {
            //     $parent[$temp->code] = $temp->name;
            // }
        //     if (in_array($value->ket_relasi, $cond)) {
        //         $parent[$value->kodechild] = $value->child;
        //     }
        // }

        foreach ($filtered as $value) {
            $temp = $dataParent->where('name','=',$value->parent)->first();

            if (in_array($value->ket_relasi, $cond)) {
                if (auth()->user()->view_all_unit) {
                    $parent[''] = [
                        'name' => 'Pilih'
                    ];
                    $parent[$value->kodechild] = [
                        'name' => $value->child
                    ];
                    $session = session('userdata');
                    if ($session['kodebagian'] == $value->kodechild) {
                        $parent[$value->kodechild]['selected'] = true;
                    }
                    if ($session['kode_parent1'] == $value->kodechild) {
                        $parent[$value->kodechild]['selected'] = true;
                    }
                    if ($session['kode_parent2'] == $value->kodechild) {
                        $parent[$value->kodechild]['selected'] = true;
                    }
                    if ($session['kode_parent3'] == $value->kodechild) {
                        $parent[$value->kodechild]['selected'] = true;
                    }
                } else {
                    $session = session('userdata');
                    if ($session['kodebagian'] == $value->kodechild) {
                        $parent[$value->kodechild] = [
                            'name' => $value->child,
                            'selected'    => true,
                        ];
                    }
                    if ($session['kode_parent1'] == $value->kodechild) {
                        $parent[$value->kodechild] = [
                            'name' => $value->child,
                            'selected'    => true,
                        ];
                    }
                    if ($session['kode_parent2'] == $value->kodechild) {
                        $parent[$value->kodechild] = [
                            'name' => $value->child,
                            'selected'    => true,
                        ];
                    }
                    if ($session['kode_parent3'] == $value->kodechild) {
                        $parent[$value->kodechild] = [
                            'name' => $value->child,
                            'selected'    => true,
                        ];
                    }
                }
                
            }
            

            if ($temp != null) {
                if (auth()->user()->view_all_unit) {
                    $child[$temp->code][''] = ['name' => 'Pilih'];
                    $child[$temp->code][$value->kodechild] = ['name' => $value->child];
                    $session = session('userdata');
                    if ($session['kodebagian'] == $value->kodechild) {
                        $child[$temp->code][$value->kodechild]['selected'] = true;
                    }
                    if ($session['kode_parent1'] == $value->kodechild) {
                        $child[$temp->code][$value->kodechild]['selected'] = true;
                    }
                    if ($session['kode_parent2'] == $value->kodechild) {
                        $child[$temp->code][$value->kodechild]['selected'] = true;
                    }
                    if ($session['kode_parent3'] == $value->kodechild) {
                        $child[$temp->code][$value->kodechild]['selected'] = true;
                    }
                } else {
                    $session = session('userdata');
                    if ($session['kodebagian'] == $value->kodechild) {
                        $child[$temp->code][$value->kodechild] = ['name' => $value->child, 'selected' => true];
                    }
                    if ($session['kode_parent1'] == $value->kodechild) {
                        $child[$temp->code][$value->kodechild] = ['name' => $value->child, 'selected' => true];
                    }
                    if ($session['kode_parent2'] == $value->kodechild) {
                        $child[$temp->code][$value->kodechild] = ['name' => $value->child, 'selected' => true];
                    }
                    if ($session['kode_parent3'] == $value->kodechild) {
                        $child[$temp->code][$value->kodechild] = ['name' => $value->child, 'selected' => true];
                    }
                }
            }
        }
        ksort($parent);
        ksort($child);
        return ['parent' => $parent,'child' => $child];
    }
}
