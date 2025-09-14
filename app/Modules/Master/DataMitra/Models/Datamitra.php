<?php

namespace App\Modules\Master\DataMitra\Models;

use App\Bases\BaseModel;
use Spatie\Activitylog\LogOptions;

class Datamitra extends BaseModel
{

    protected static $logFillable = true;

    protected $table = 'data_mitra';
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kode_mitra',
        'nama_mitra',
        'alamat',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogname('Master Data Mitra');
    }
}
