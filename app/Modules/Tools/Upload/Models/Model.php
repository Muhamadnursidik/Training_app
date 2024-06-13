<?php
namespace App\Modules\Tools\Upload;

use App\Bases\BaseModel;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Model extends BaseModel
{
    use LogsActivity;

    protected $table = 't_attachment'; // Table name
    protected $keyType = 'string';
    protected static $logFillable = true;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'oriname', 'module', 'source_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogname('Upload Files');
    }
}
