<?php
namespace App\Modules\Admin\User;

use App\Bases\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Blocklogin extends BaseModel
{
    protected static $logFillable = true;

    protected $table = 't_block_login'; // Table name
    protected $keyType = 'string';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key','ip_address','blokir'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

    public function scopeBlock($query){
        return $query->where('blokir',true);
    }

}
