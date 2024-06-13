<?php
namespace App\Modules\Tools\Pengaturan;

use App\Bases\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Model extends BaseModel
{
    use LogsActivity;

    protected static $logFillable = true;

    protected $table = 'master_config_app'; // Table name
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code','config', 'status'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogname('Konfigurasi Aplikasi');
    }
}
