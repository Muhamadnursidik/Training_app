<?php

namespace App\Modules\Master\DataProject\Models;

use App\Bases\BaseModel;
use App\Modules\Master\DataMitra\Models\Datamitra;
use Spatie\Activitylog\LogOptions;
use Carbon\Carbon;

class DataProject extends BaseModel
{
    protected static $logFillable = true;

    protected $table = 'data_project';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'kode_project',
        'mitra_id',
        'nama_project', 
        'tanggal_mulai',
        'tanggal_akhir',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_akhir'   => 'date',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    public function mitra()
    {
        return $this->belongsTo(Datamitra::class, 'mitra_id', 'id');
    }

    public function getTanggalMulaiAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function getTanggalAkhirAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->useLogName('Master Data Project');
    }
}
