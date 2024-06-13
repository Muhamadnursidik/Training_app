<?php
namespace App\Modules\Tools\Activitylog;

use App\Bases\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Model extends BaseModel
{
    use LogsActivity;

    protected $table = 't_activity_log'; // Table name
    protected static $logFillable = true;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function user(){
        return $this->hasOne('App\Modules\Admin\User\Model','id','causer_id');
    }

}
