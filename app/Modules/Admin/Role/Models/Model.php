<?php
namespace App\Modules\Admin\Role;

use App\Bases\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Model extends BaseModel
{
    use SoftDeletes,LogsActivity;

    protected static $logFillable = true;

    protected $table = 'roles'; // Table name
    protected $primaryKey = 'id';
    /*
     * Defining Fillable Attributes On A Model
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'guard_name',
        'status'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogname('Role');
    }

    public function permissions() {
        return $this->belongsToMany('\Spatie\Permission\Models\Permission', 'role_has_permissions', 'role_id', 'permission_id');
    }

    public function menus() {
        return $this->belongsToMany('\App\Modules\Admin\Menu\Model', 'menu_visibilities', 'role_id', 'menu_id');
    }

    public function reviewer(){
       return $this->belongsToMany('\App\Modules\Master\Unitkerja\Model', 'mapping_reviewer', 'role_id',
       'unit_kerja_code')->withPivot('urutan','reviewer_id','operator');
    }

    public function otoritas(){
        return $this->belongsToMany('\App\Modules\Master\Unitkerja\Model', 'mapping_role_jabatan_bagian', 'role_id',
       'unit_kerja_code')->withPivot('jabatan_code','bagian_code');
    }

}
