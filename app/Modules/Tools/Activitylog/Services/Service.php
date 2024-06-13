<?php

namespace App\Modules\Tools\Activitylog;

use App\Bases\BaseService;
use DataTables;
use App\Modules\Tools\Activitylog\Model;
use Carbon\Carbon;
use Spatie\DbDumper\Databases\PostgreSql;
use Illuminate\Support\Facades\DB;

class Service extends BaseService
{

    public function __construct()
    {
    }

    public function data(array $data)
    {
        $query = Model::with(['user'])->data(null, 'created_at', 'desc');

        return DataTables::of($query)
            ->filter(function ($query) use ($data) {

                if ($data['keyword'] != '') {
                    $query->whereLike('description', $data['keyword']);
                }

                if ($data['user'] != '') {
                    $query->where('causer_id', $data['user']);
                }

                if ($data['tanggal'] != '') {
                    $query->whereDate('created_at', date('Y-m-d', strtotime($data['tanggal'])));
                }
            })
            ->addColumn('id', function ($query) {
                return encrypt($query->id);
            })
            ->addColumn('created_at', function ($query) {
                return Carbon::parse($query->created_at)->isoFormat('DD MMMM YYYY HH:m:s');
            })
            ->addColumn('properties.username', function($query) {
                $properties = !empty($query->properties) ? json_decode($query->properties) : null;
                return isset($properties->username) ? $properties->username : 'n/a';
            })
            ->addColumn('properties.ip_address', function($query) {
                $properties = !empty($query->properties) ? json_decode($query->properties) : null;
                return isset($properties->ip_address) ? $properties->ip_address : 'n/a';
            })
            ->addColumn('properties.device', function($query) {
                $properties = !empty($query->properties) ? json_decode($query->properties) : null;
                return isset($properties->device) ? $properties->device : 'n/a';
            })
            ->addColumn('properties.os', function($query) {
                $properties = !empty($query->properties) ? json_decode($query->properties) : null;
                return isset($properties->os) ? $properties->os : 'n/a';
            })
            ->addColumn('properties.browser', function($query) {
                $properties = !empty($query->properties) ? json_decode($query->properties) : null;
                return isset($properties->browser) ? $properties->browser : 'n/a';
            })
            ->addColumn('tabel_terkait', function($query) {
                $tabelTerkait = '';

                if ($query->subject_type!= null) {
                    $tabelTerkait = app($query->subject_type)->getTable();
                }
                return $tabelTerkait;
            })
            ->make(true)
            ->getData(true);
    }

    public static function get($id)
    {
        $query = Model::with(['user'])->find($id);
        // dd($query);
        if ($query) {
            return $query;
        }

        return false;
    }
}
