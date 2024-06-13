<?php
namespace App\Modules\Tools\Backup;

use App\Bases\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Model extends BaseModel
{
    use SoftDeletes,LogsActivity;

    protected $table = 'b_history_backup'; // Table name
    protected $keyType = 'string';
    protected static $logFillable = true;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
