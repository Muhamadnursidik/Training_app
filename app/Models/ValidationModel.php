<?php
namespace App\Models;

use App\Bases\BaseModel;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ValidationModel extends BaseModel
{
    use LogsActivity;
    
    protected $table = 't_validasi_laporan'; // Table name
    protected $keyType = 'string';
    protected static $logFillable = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unit_kerja_code', 'status','validasi_data','posisi', 'parameter_id'
    ];
    
    protected $casts = [
        'validasi_data' => 'array'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
