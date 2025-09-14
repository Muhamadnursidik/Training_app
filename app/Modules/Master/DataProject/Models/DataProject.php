<?php

namespace App\Modules\Master\DataProject\Models;

use App\Bases\BaseModel;
use App\Modules\Master\DataMitra\Models\Datamitra;
use Spatie\Activitylog\LogOptions;

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
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    public function mitra()
    {
        return $this->belongsTo(Datamitra::class, 'mitra_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->useLogName('Master Data Project');
    }
}
