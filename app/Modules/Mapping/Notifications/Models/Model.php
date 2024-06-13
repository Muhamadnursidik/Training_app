<?php
namespace App\Modules\Mapping\Notifications;

use App\Bases\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends BaseModel
{
    protected $table = 't_notification'; // Table name

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'source_id','url', 'msg','role_id', 'status','unit_kerja_code','user_id','unit_kerja_id', 'unit_kerja_code', 'send_email', 'type', 'bagian_code'
    ];

    public function scopeHasRead($query, $id)
    {
        $query->where('id', $id)->update(['status' => true]);
        return $query;
    }

    public function users(){
        return $this->hasOne('App\Modules\Admin\User\Model','id','user_id');
    }
}
