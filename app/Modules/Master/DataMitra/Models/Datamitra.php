<?php

namespace App\Modules\Master\DataMitra\Models;

use App\Bases\BaseModel;
use App\Modules\Master\DataProject\Models\DataProject;
use Spatie\Activitylog\LogOptions;

class Datamitra extends BaseModel
{
    protected static $logFillable = true;

    protected $table = 'data_mitra';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'kode_mitra',
        'nama_mitra',
        'alamat',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function projects()
    {
        return $this->hasMany(DataProject::class, 'mitra_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->useLogName('Master Data Mitra');
    }
}
