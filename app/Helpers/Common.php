<?php

namespace App\Helpers;

use Symfony\Component\Console\Helper\FormatterHelper;
use App\Modules\Riskpro\Penilaian\Service;
use App\Modules\Riskpro\Laporan\Service as ProfilrisikoService;
use App\Modules\Master\Predikat\Service as PredikatService;
use App\Modules\Master\Parameter\Service as ParameterService;
use App\Modules\Master\Datakuantitatif\Service as DatakuantitatifService;
use App\Modules\Tools\Upload\Service as UploadService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Modules\Master\Dampakkeuangan\Service as DampakfinansialService;
use App\Modules\Master\Frekuensi\Service as FrekuensiService;
use App\Modules\Master\Matrikrisiko\Service as MatrikrisikoService;
use App\Modules\Penilaian\Rcsa\Kriteriarisiko\Service as KriteriaMatrikRisikoService;
use App\Modules\Penilaian\Rcsa\Mastermonitoring\Service as MastermonitoringService;
use App\Modules\Mapping\Approval\ProcessReview;
use App\Modules\Master\Signature\Service as SignatureService;
use App\Modules\Master\Mastertemplate\Service as MastertemplateService;
use Carbon\Carbon;

class Common
{
    public static function operatorReplace($kode, $_to = 'code')
    {
        if ($_to == 'code') {
            $operatorList = ['1' => '<', '2' => '>', '3' => '<=', '4' => '>=', '5' => '='];
            return isset($operatorList[$kode]) ? $operatorList[$kode] : '';
        } elseif ($_to == 'numeric') {
            $operatorList = ['<' => '1', '>' => '2', '<=' => '3', '>=' => '4', '=' => '5'];
            return isset($operatorList[$kode]) ? $operatorList[$kode] : '';
        }
    }

    public static function changeLabelJeniskejadian($code)
    {
        $list_code = ['nearmiss' => 'Near Miss', 'potensialloss' => 'Potential Loss', 'softloss' => 'Soft Loss', 'lossevent' => 'Loss Event'];
        return $list_code[$code];
    }

    public static function comparitionOperator($list = false, $kode = '', $switchLabel = '')
    {
        $operatorList = ['1' => '<', '2' => '>', '3' => '<=', '4' => '>=', '5' => '='];

        if ($switchLabel) {
            $operatorList = ['1' => '>', '2' => '<', '3' => '>=', '4' => '<=', '5' => '='];
        }

        if (!$list) {
            return isset($operatorList[$kode]) ? $operatorList[$kode] : '';
        } else {
            return $operatorList;
        }
    }

    public static function jenisOutputCodeList()
    {
        return [
            'RASIO' => 'RASIO',
            'FREKUENSI' => 'FREKUENSI',
            'JUMLAH' => 'JUMLAH',
            'NOMINAL' => 'NOMINAL',
            'WAKTUJAM' => 'WAKTU JAM',
            'WAKTUMENIT' => 'WAKTU MENIT',
            'RATING' => 'RATING',
            'TAHUN' => 'TAHUN',
            'MINGGU' => 'MINGGU',
            'BULAN' => 'BULAN',
            'HARI' => 'HARI',
        ];
    }
    public static function jenisOutputLabel($jenisoutput)
    {
        $output = [
            'RASIO' => '%',
            'FREKUENSI' => 'Kali',
            'JUMLAH' => '',
            'NOMINAL' => 'Rp.',
            'WAKTUJAM' => 'jam',
            'WAKTUMENIT' => 'menit',
            'RATING' => '',
            'TAHUN' => 'tahun',
            'MINGGU' => 'Minggu',
            'BULAN' => 'Bulan',
            'HARI' => 'Hari',
        ];

        return isset($output[$jenisoutput]) ? $output[$jenisoutput] : '';
    }

    /** 
     * $every int : in a month integer
     */
    public static function current_report($every = 3)
    {
        $curr_month = date('n');
        $curr_year = date('n');

        switch ($every) {
            case 3:
                // triwulanan
                if ($curr_month <= 3) {
                    return 'Mar-' . date('Y');
                } elseif ($curr_month > 3 && $curr_month <= 6) {
                    return 'Jun-' . date('Y');
                } elseif ($curr_month > 6 && $curr_month <= 9) {
                    return 'Sep-' . date('Y');
                } elseif ($curr_month > 9) {
                    return 'Dec-' . date('Y');
                }
                break;
            case 6:
                // triwulanan
                if ($curr_month < 6) {
                    return 'Dec-' . date('Y', strtotime(date('Y') . " -1 year"));
                } elseif ($curr_month > 6) {
                    return 'Jun-' . date('Y');
                } elseif ($curr_month == 6) {
                    return 'Jun-' . date('Y');
                }

                break;
            default:
                return date('F-Y');
                break;
        }
    }

    public static function formatNumber($number, $prefix = 'Rp. ', $commas = 2)
    {   
        
        if(empty($number)){
            return '-';
        }

        return $prefix . number_format($number, $commas);
    }

    public static function labelTindaklanjut($code = '', $list = false)
    {
        $list_code = ['BTL' => 'Belum ditindaklanjuti', 'DTL' => 'Dalam Proses Tindaklanjut', 'STL' => 'Sudah ditindaklanjuti', 'TDTL' => 'Tidak dapat ditindaklanjuti'];
        if (!$list) {
            return isset($list_code[$code]) ? $list_code[$code] : $list_code['BTL'];
        } else {
            $list_code[''] = 'Semua';
            ksort($list_code);
            return $list_code;
        }
    }

    public static function labelPerubahanIkhtisar($code = '', $list = false)
    {
        $list_code = [
            'PPR' => 'Perubahan Profil Risiko',
            'PTIR' => 'Penambahan Item Risiko',
            'PKIR' => 'Pengurangan Item Risiko',
            'RSR' => 'Perubahan Strategi Risiko',
        ];
        if (!$list) {
            return isset($list_code[$code]) ? $list_code[$code] : $list_code['PPR'];
        } else {
            $list_code[''] = 'Semua';
            ksort($list_code);
            return $list_code;
        }
    }

    public static function labelStatusInternalControlTest($code = '', $list = false)
    {
        $list_code = [
            'S' => 'Sesuai',
            'BS' => 'Belum Sesuai',
            'BTL' => 'Belum Ditindaklanjuti',
            'TDTL' => 'Tidak Dapat Ditindaklanjuti',
        ];
        if (!$list) {
            return isset($list_code[$code]) ? $list_code[$code] : $list_code['BTL'];
        } else {
            $list_code[''] = 'Semua';
            ksort($list_code);
            return $list_code;
        }
    }

    public function list_operator()
    {
        $operator = [
            'min' => 'Min',
            'max' => 'Max',
            '+' => '+',
            '-' => '-',
            '/' => '/',
            '*' => 'x',
        ];

        return $operator;
    }

    public static function list_jenis_output()
    {
        $operator = [
            'RASIO' => 'RASIO',
            'FREKUENSI' => 'FREKUENSI',
            'NOMINAL' => 'NOMINAL',
        ];

        return $operator;
    }

    public function replace_operator($code)
    {
        $operator = [
            'min' => 'Min',
            'max' => 'Max',
            '+' => '+',
            '-' => '-',
            '/' => '<hr>',
            '*' => 'x',
        ];

        return isset($operator[$code]) ? $operator[$code] : '';
    }

    public static function jenisOutput($jenis_output, $data)
    {
        /* 'RASIO','FREKUENSI','NOMINAL' */
        switch ($jenis_output) {
            case 'RASIO':
                return is_null($data) ? '-' : self::formatNumber($data, '') . '%';
                break;
            case 'FREKUENSI':
                return is_null($data) ? '-' : $data . ' Kali';
                break;
            case 'NOMINAL':
                return is_null($data) ? '-' : self::formatNumber($data);
                break;
            case 'WAKTUMENIT':
                return is_null($data) ? '-' : ($data) . ' Menit';
                break;
            case 'WAKTUJAM':
                return is_null($data) ? '-' : ($data) . ' Jam';
                break;
            case 'DAMPAK':
                return is_null($data) ? '-' : self::formatNumber($data, 'Rp. ');
                break;
            default:
                return $data;
                break;
        }
    }

    public static function outputFormula($jenis_output, $data, $format = true)
    {
        if ($jenis_output == 'RASIO') {
            $data = ($data * 100);
            return ($format) ? self::jenisOutput($jenis_output, $data) : $data;
        }
        return ($format) ? self::jenisOutput($jenis_output, $data) : $data;
    }

    public static function labelOutputFrekuensiDampak($data, $jenis_output = 'frekuensi', $switchLabel = '')
    {
        if ($jenis_output == 'frekuensi') {
            $min_range = self::jenisOutput(strtoupper($jenis_output), $data['min_range']['probabilitas']['kemungkinan']);
            $max_range = self::jenisOutput(strtoupper($jenis_output), $data['max_range']['probabilitas']['kemungkinan']);
            $min_range_opr = self::comparitionOperator(false, $data['min_range_opr']['probabilitas']['kemungkinan']);
            $max_range_opr = self::comparitionOperator(false, $data['max_range_opr']['probabilitas']['kemungkinan']);


            if (strcmp($data['max_range']['probabilitas']['kemungkinan'], $data['min_range']['probabilitas']['kemungkinan']) == 0 && strcmp($data['max_range_opr']['probabilitas']['kemungkinan'], $data['min_range_opr']['probabilitas']['kemungkinan']) == 0) {
                $label = implode(' ', [$jenis_output, $max_range_opr, $max_range]);
            } else {
                $min_range_opr = self::comparitionOperator(false, $data['min_range_opr']['probabilitas']['kemungkinan'], $switchLabel);
                $label = implode(' ', [$min_range, $min_range_opr, $jenis_output, $max_range_opr, $max_range]);
            }
        } else {
            $min_range = self::jenisOutput(strtoupper($jenis_output), $data['min_range'][$jenis_output]['nominal']);
            $max_range = self::jenisOutput(strtoupper($jenis_output), $data['max_range'][$jenis_output]['nominal']);
            $min_range_opr = self::comparitionOperator(false, $data['min_range_opr'][$jenis_output]);
            $max_range_opr = self::comparitionOperator(false, $data['max_range_opr'][$jenis_output]);

            if (strcmp($data['max_range'][$jenis_output]['nominal'], $data['min_range'][$jenis_output]['nominal']) == 0 && strcmp($data['max_range_opr'][$jenis_output], $data['min_range_opr'][$jenis_output]) == 0) {
                $label = implode(' ', [$jenis_output, $max_range_opr, $max_range]);
            } else {
                $min_range_opr = self::comparitionOperator(false, $data['min_range_opr'][$jenis_output], $switchLabel);
                $label = implode(' ', [$min_range, $min_range_opr, $jenis_output, $max_range_opr, $max_range]);
            }
        }

        return $label;
    }

    public static function comparasiOutput($data, $peringkat, $jenis_output, $switchLabel = '')
    {
        $results = json_decode($data);

        $_temporary = [];
        if (is_array($results)) {
            foreach ($results as $key => $value) {
                $_temporary[$value->peringkat] = [
                    'min_range' => $value->min_range,
                    'max_range' => $value->max_range,
                    'min_range_opr' => $value->min_range_opr,
                    'max_range_opr' => $value->max_range_opr,
                    'or_min_range' => $value->or_min_range,
                    'or_max_range' => $value->or_max_range,
                    'or_min_range_opr' => isset($value->or_min_range_opr) ? $value->or_min_range_opr : '',
                    'or_max_range_opr' => isset($value->or_max_range_opr) ? $value->or_max_range_opr : ''
                ];
            }
        }

        if (isset($_temporary[$peringkat])) {
            $peringkatData = $_temporary[$peringkat];

            $min_range = self::jenisOutput($jenis_output, $peringkatData['min_range']);
            $max_range = self::jenisOutput($jenis_output, $peringkatData['max_range']);
            $min_range_opr = self::comparitionOperator(false, $peringkatData['min_range_opr']);
            $max_range_opr = self::comparitionOperator(false, $peringkatData['max_range_opr']);

            if (strcmp($peringkatData['max_range'], $peringkatData['min_range']) == 0 && strcmp($peringkatData['max_range_opr'], $peringkatData['min_range_opr']) == 0) {
                $label = implode(' ', [$jenis_output, $max_range_opr, $max_range]);
            } else {
                $min_range_opr = self::comparitionOperator(false, $peringkatData['min_range_opr'], $switchLabel);
                $label = implode(' ', [$min_range, $min_range_opr, $jenis_output, $max_range_opr, $max_range]);
            }

            if ($peringkatData['or_min_range'] && $peringkatData['or_max_range']) {

                $or_min_range = self::jenisOutput($jenis_output, $peringkatData['or_min_range']);
                $or_max_range = self::jenisOutput($jenis_output, $peringkatData['or_max_range']);
                $or_min_range_opr = self::comparitionOperator(false, $peringkatData['or_min_range_opr'], $switchLabel);
                $or_max_range_opr = self::comparitionOperator(false, $peringkatData['or_max_range_opr'], $switchLabel);

                $label .= '&nbsp;&nbsp;  <b> OR </b>  &nbsp;&nbsp;';

                if (strcmp($peringkatData['or_min_range'], $peringkatData['or_max_range']) == 0 && strcmp($peringkatData['or_min_range_opr'], $peringkatData['or_max_range_opr']) == 0) {
                    $label .= implode(' ', [$jenis_output, $or_max_range_opr, $or_max_range]);
                } else {
                    $label .= implode(' ', [$or_min_range, $or_min_range_opr, $jenis_output, $or_max_range_opr, $or_max_range]);
                }
            }

            return $label;
        }

        return '-';
    }

    public static function comparasiOutputFrekuensiDampak($data, $output, $jenis = 'probabilitas')
    {

        $kriteria = json_decode($data, true);

        $__temporary = [];
        foreach ($kriteria as $key => $value) {
            $__temporary[$value['peringkat']] = [
                'peringkat' => $value['peringkat'],
                'keterangan' => $value['keterangan']
            ];

            if ($jenis == 'probabilitas') {

                $compare_min = Common::compareNumeric($output, $value['min_range']['probabilitas']['persentase'], Common::comparitionOperator(false, $value['min_range_opr']['probabilitas']['persentase']));
                $compare_max = Common::compareNumeric($output, $value['max_range']['probabilitas']['persentase'], Common::comparitionOperator(false, $value['max_range_opr']['probabilitas']['persentase']));

                if ($compare_min && $compare_max) {
                    return $__temporary[$value['peringkat']];
                    break;
                }
            } elseif ($jenis == 'dampak') {

                $compare_min = Common::compareNumeric($output, $value['min_range']['dampak']['nominal'], Common::comparitionOperator(false, $value['min_range_opr']['dampak']));
                $compare_max = Common::compareNumeric($output, $value['max_range']['dampak']['nominal'], Common::comparitionOperator(false, $value['max_range_opr']['dampak']));

                if ($compare_min && $compare_max) {
                    return $__temporary[$value['peringkat']];
                    break;
                }
            }
        }
    }

    public static function comparasiOutputFrekuensiDampakKualitatif($data, $output, $jenis = 'dampak')
    {

        $kriteria = json_decode($data, true);

        $__temporary = [];
        foreach ($kriteria as $key => $value) {
            $__temporary[$value['peringkat']] = [
                'peringkat' => $value['peringkat'],
                'keterangan' => $value['keterangan']
            ];

            if ($jenis == 'dampak') {

                $compare_min = Common::compareNumeric($output, $value['min_range']['dampak']['persentase'], Common::comparitionOperator(false, $value['min_range_opr']['dampak']));
                $compare_max = Common::compareNumeric($output, $value['max_range']['dampak']['persentase'], Common::comparitionOperator(false, $value['max_range_opr']['dampak']));

                if ($compare_min && $compare_max) {
                    return $__temporary[$value['peringkat']];
                    break;
                }
            }
        }
    }

    public static function compareNumeric($number1, $number2, $operator = null)
    {
        switch ($operator) {
            case "=":
                return $number1 == $number2;
            case "!=":
                return $number1 != $number2;
            case ">=":
                return $number1 >= $number2;
            case "<=":
                return $number1 <= $number2;
            case ">":
                return $number1 > $number2;
            case "<":
                return $number1 < $number2;
            default:
                return false;
        }
    }

    public static function InfoStatusPenilaianLabel($data)
    {
        switch ($data['status']) {
            case 'REVIEW':
                return '[REVIEW] <p>' . $data['role_name'] . '</p>';
                break;
            default:
                return $data['status'];
                break;
        }
    }

    public static function calculateParameter(array $data)
    {
        $service = new Service();
        return $service->calculateDataParent($data);
    }

    public static function checkRangePeringkat(array $data)
    {
        $service = new Service();
        return $service->checkRangePeringkat($data);
    }

    public static function generate_parameter(array $data)
    {
        $service = new ProfilrisikoService();
        return $service->getParameter($data);
    }

    public static function peringkat_label()
    {
        return PredikatService::getPredikatLabel();
    }

    public static function createFormulaReport(array $data)
    {
        return ParameterService::createFormulaReport($data);
    }

    public static function createGapReport(array $data)
    {
        return ParameterService::createGapReport($data);
    }

    public static function permission()
    {
        $permission = isset(Auth::user()->roles[0]->actions_permission) ? Auth::user()->roles[0]->actions_permission : [];
        return $permission;
    }

    public static function attachment_list(array $data)
    {
        return UploadService::attachment_list($data);
    }

    public static function getQueries($query)
    {
        return Str::replaceArray('?', $query->getBindings(), $query->toSql());
    }

    public static function dropdown_parentcode($code = '')
    {

        $list = array('' => 'PILIH', 'TKB' => 'TKB', 'RP' => 'PROFIL RISIKO', 'TK' => 'TATA KELOLA', 'CP' => 'PERMODALAN', 'ER' => 'RENTABILITAS');

        if (isset($list[$code]) && $code != '') {
            return $list[$code];
        }

        return $list;
    }

    public static function jenis_koreksi($code = '', $header = false)
    {

        $list = ['' => 'PILIH', 'R' => 'RUTIN', 'K' => 'KOREKSI'];

        if ($header) {
            $list_header = ['R' => 0, 'K' => 1];
            if (isset($list_header[$code]) && $code != '') {
                return $list_header[$code];
            }
        } else {
            if (isset($list[$code]) && $code != '') {
                return $list[$code];
            }
        }
        return $list;
    }

    public static function jenis_laporan($code = '')
    {
        $list = ['' => 'PILIH', 'A' => 'Laporan Self Assessment', 'I' => 'Pengkinian Laporan Self Assessment', 'S' => 'Semesteran'];
        if (isset($list[$code]) && $code != '') {
            return $list[$code];
        }
        return $list;
    }

    public static function get_column_data(array $data)
    {
        $output[''] = 'PILIH';
        $column = DB::getSchemaBuilder()->getColumnListing($data['table']);

        if (isset($data['allowed'])) {
            $column = array_intersect($column, $data['allowed']);
        }

        foreach ($column as $value) {
            $output[$value] = $value;
        }

        return $output;
    }

    public static function checkLock(array $data)
    {
        $status = true;
        $complete_data = [];
        if (isset($data['formula']) && $data['formula'] != '') {

            foreach (json_decode($data['formula'], true) as $formula) {

                if ($data['existing']) {
                    $formulaExtract = explode(',', $formula['formula']);

                    foreach ($formulaExtract as $fml) {
                        if (str_contains($fml, '#')) {

                            $explode = explode('#', $fml);
                            $formulaCode = str_replace('@', '', $explode[0]);

                            if (isset($explode[0]) && str_contains($explode[0], '@')) {

                                if (isset($data['existing'][$formulaCode]) && $data['existing'][$formulaCode] == false) {
                                    $status = false;
                                    $complete_data[] = $formulaCode;
                                }

                                if (!isset($data['existing'][$formulaCode])) {
                                    $status = false;
                                    $complete_data[] = $formulaCode;
                                }
                            }
                        } else if (str_contains($fml, '@')) {


                            $formulaCode = str_replace('@', '', $fml);

                            if (isset($data['existing'][$formulaCode]) && $data['existing'][$formulaCode] == false) {
                                $status = false;
                                $complete_data[] = $formulaCode;
                            }

                            if (!isset($data['existing'][$formulaCode])) {

                                $status = false;
                                $complete_data[] = $formulaCode;
                            }
                        }
                    }
                } else {

                    if (str_contains($formula['formula'], '#')) {
                        $explode = explode('#', $formula['formula']);
                        $formulaCode = str_replace('@', '', $explode[0]);
                        $status = false;
                        $complete_data[] = $formulaCode;
                    } else {
                        $formulaCode = $formula['formula'];
                        $status = false;
                        $complete_data[] = $formulaCode;
                    }
                }
            }
        }

        return ['status' => $status, 'data' => DatakuantitatifService::getDataByCode($complete_data)];
    }

    public static function remove_tags_html($html, $allowed = '<ul><li><ol><p><table>')
    {
        $html = html_entity_decode(strip_tags($html, $allowed));
        $html = preg_replace("/<p[^>]*><\\/p[^>]*>/", '', $html);
        $html = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $html);

        return $html;
    }

    public static function ToRomawi($angka)
    {
        $angka = intval($angka);
        $result = '';

        $array = array(
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        );

        foreach ($array as $roman => $value) {
            $matches = intval($angka / $value);

            $result .= str_repeat($roman, $matches);

            $angka = $angka % $value;
        }

        return $result;
    }

    public static function dampakfinansial($total_kerugian, $periode)
    {
        $dampak = new KriteriaMatrikRisikoService();
        return $dampak->dampakfinansial(['dampak_finansial' => $total_kerugian, 'tgl_kejadian' => ($periode['enddate'])]);
    }

    public static function frekuensi($total_frekuensi, $periode)
    {
        $frekuensi = new KriteriaMatrikRisikoService();
        return $frekuensi->frekuensi(['frekuensi' => $total_frekuensi, 'tgl_kejadian' => ($periode['enddate'])]);
    }

    public static function matrik(array $data, $frekuensi, $impact)
    {
        $matrik = new KriteriaMatrikRisikoService();
        return $matrik->matrik($data, $frekuensi, $impact);
    }

    public static function peringkatKRI()
    {

        return PredikatService::allPredikat(true);
    }

    public static function trendStatus($nilai1, $nilai2)
    {
        if (!isset($nilai) && !isset($nilai2)) {
            return '-';
        } elseif (isset($nilai1) && isset($nilai2)) {
            if ($nilai1 == $nilai2) {
                return 'Stabil <i class="fas fa-arrow-right-long text-info"></i>';
            } elseif ($nilai1 > $nilai2) {
                return 'Naik <i class="fas fa-arrow-trend-up text-danger"></i>';
            } elseif ($nilai1 < $nilai2) {
                return 'Turun <i class="fas fa-arrow-trend-down text-success"></i>';
            }
        } else {
            return 'Stabil <i class="fas fa-arrow-right-long text-info"></i>';
        }
    }

    public static function posisi($type = 'triwulan')
    {
        $month = date('n');
        switch ($type) {
            case 'semester':
                # code...
                break;

            default:
                return self::setTriwulan($month);
                break;
        }
    }

    private static function setTriwulan($m)
    {
        if ($m < 3) {
            return 'Dec ' . date('Y', strtotime(now() . ' -1 years'));
        } elseif ($m > 3 && $m < 6) {
            return 'Mar ' . date('Y');
        } elseif ($m > 6 && $m < 9) {
            return 'Jun ' . date('Y');
        } elseif ($m > 9) {
            return 'Jun ' . date('Y');
        } elseif (in_array($m, [3, 6, 9, 12])) {
            return date('M Y');
        }
    }

    public static function triwulanSebelumnya($this_month)
    {
        $curr_month = $this_month - 1;
        if ($curr_month <= 3) {
            return MastermonitoringService::getIdByPeriodeTo('March');
        } elseif ($curr_month > 3 && $curr_month <= 6) {
            return MastermonitoringService::getIdByPeriodeTo('June');
        } elseif ($curr_month > 6 && $curr_month <= 9) {
            return MastermonitoringService::getIdByPeriodeTo('September');
        } elseif ($curr_month > 9) {
            return MastermonitoringService::getIdByPeriodeTo('December');
        }
    }

    public static function triwulanSekarang($this_month)
    {
        if ($this_month <= 3) {
            return MastermonitoringService::getIdByPeriodeTo('March');
        } elseif ($this_month > 3 && $this_month <= 6) {
            return MastermonitoringService::getIdByPeriodeTo('June');
        } elseif ($this_month > 6 && $this_month <= 9) {
            return MastermonitoringService::getIdByPeriodeTo('September');
        } elseif ($this_month > 9) {
            return MastermonitoringService::getIdByPeriodeTo('December');
        }
    }

    public static function efektifitas($p1, $p2)
    {
        if ($p1 <= $p2) {
            return 'EFEKTIF';
        } else {
            return 'TIDAK EFEKTIF';
        }
    }

    public static function status_risktreatment($status)
    {
        $list = ['SELESAI' => 'SELESAI DILAKSANAKAN', 'TIDAKDILAKSANAKAN' => 'TIDAK DILAKSANAKAN', 'SEBAGIAN' => 'DILAKSANAKAN SEBAGIAN'];
        return isset($list[$status]) ? $list[$status] : '-';
    }

    public static function historyReview(array $data)
    {
        $query = ProcessReview::where('source_id', $data['source_id'])->first();
        return $query;
    }

    public static function signature(array $data)
    {
        return SignatureService::render($data);
    }

    public static function percentData(array $data)
    {
        $nilai1 = $data['nilai1'];
        $nilai2 = $data['nilai2'];

        if (!empty($nilai2)) {
            return number_format(($nilai1 / $nilai2) * 100, 2) . '%';
        }

        return '0%';
    }

    public static function priority_status($priority = '')
    {
        $list = [3 => 'HIGH', 2 => 'MEDIUM', 1 => 'LOW'];
        if ($priority != '') {
            return isset($list[$priority]) ? $list[$priority] : '';
        }

        return $list;
    }

    public static function priority_color($data)
    {
        $label = [3 => 'danger', 2 => 'warning', 1 => 'bg-info'];
        return isset($label[$data]) ? $label[$data] : 'bg-info';
    }

    public static function status_label($data)
    {
        $label = ['OPEN' => 'OPEN', 'CLOSE' => 'CLOSE', 'DRAFT' => 'DRAFT'];
        return isset($label[$data]) ? $label[$data] : '';
    }

    public static function status_color($data)
    {
        $label = ['OPEN' => 'success', 'CLOSE' => 'danger', 'DRAFT' => 'secondary'];
        return isset($label[$data]) ? $label[$data] : '';
    }

    public static function get_first_letter($string)
    {
        $result = preg_match_all('/\b\w/', $string, $match);
        return ($result) ? implode('', $match[0]) : '';
    }

    public static function dropdownScope()
    {
        return ['' => 'Pilih', 'PUSAT' => 'PUSAT', 'REGIONAL' => 'REGIONAL', 'UPT' => 'UPT'];
    }

    public static function dropdownControl()
    {
        return ['C' => 'Controllable', 'UC' => 'Uncontrollable'];
    }

    public static function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public static function options_status($status){
        $list = ['DRAFT' => 'DRAFT', 'REVIEW' => 'REVIEW'];
        
        if(!empty($status)){
            $list[$status] = $status;
        }

        return $list;
    }
    
    public static function generateTemplate($data_permohonan, string $code_template)
    {
        $template = MastertemplateService::getByKode($code_template);
        preg_match_all("/@\w+/", $template, $data_keyword);
        foreach ($data_keyword as $key => $rows) {
            foreach ($rows as $item) {
                $change = '';
                if ($item == '@tabel_fasilitas') {
                    if (sizeof($data_permohonan->detailpermohonan) > 0) {
                        $change = view('analisakredit' 
                                    . DIRECTORY_SEPARATOR . 
                                    'kreditreview::templating' 
                                    . DIRECTORY_SEPARATOR . 
                                    'tujuan', [
                                        'detailpermohonan' => $data_permohonan->detailpermohonan
                                    ])->render();
                    }
                }
            }
            $template = str_replace($item, $change, $template);
        }

        return $template;
    }

    public static function replaceSUMfunction($code, $alfa, $jumlahbulan){
        $sum = [];
        for ($i=$alfa; $i < $alfa + $jumlahbulan; $i++) { 
            $sum[] = chr($i).$code;
        }

        return implode('+', $sum);
    }
    
    public static function options_jenis_bunga($code){
        $list = [
            'C571' => 'FLAT', 
            'A571' => 'ANUITAS BULANAN',
            'F571' => 'SLIDING HARIAN TERJADWAL / EFEKTIF',
        ];
        
        if(!empty($code)){
            return $list[$code];
        }

        return $list;
    }
}
