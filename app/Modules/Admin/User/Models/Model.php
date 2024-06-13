<?php
namespace App\Modules\Admin\User;

use App\Bases\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Model extends BaseModel
{
    use SoftDeletes,LogsActivity;

    protected static $logFillable = true;

    protected $table = 'users'; // Table name
    protected $keyType = 'string';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'type', 'active_dashboard', 'status', 'unit_kerja_code', 'view_all_unit',
        'is_admin','is_reviewer','unit_kerja_id','username','password_created','reset_password', 'is_login'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogname('User');
    }

    public function roles() {
        return $this->belongsToMany('\Spatie\Permission\Models\Role', 'user_has_roles', 'user_id', 'role_id')
                    ->withPivot('model_type');
    }

    public function unitkerja(){
        return $this->hasOne('\App\Modules\Master\Unitkerja\Model', 'code', 'unit_kerja_code');
    }

}
