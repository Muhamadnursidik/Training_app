<?php
namespace App\Modules\Master\Tipeunitkerja;

use App\Bases\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Model extends BaseModel
{
    use SoftDeletes,LogsActivity;

    protected static $logFillable = true;

    protected $table = 'master_tipe_unit_kerja'; // Table name

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'status'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogname('Master Tipe Unit Kerja');
    }

}
