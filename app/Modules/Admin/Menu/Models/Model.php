<?php
namespace App\Modules\Admin\Menu;

use App\Bases\BaseModel;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Model extends BaseModel
{
    use LogsActivity;

    protected static $logFillable = true;

    protected $table = 'menus'; // Table name
    /*
     * Defining Fillable Attributes On A Model
     */
    protected $fillable = [
        'name',
        'custom_url',
        'url',
        'icon',
        'description',
        'category',
        'parent_id',
        'sequence',
        'data_authority',
        'actions_permission'
    ];

    protected $casts = [
        'actions_permission' => 'array'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogname('Menu');
    }

    public function permissions() {
        return $this->belongsToMany('\App\Modules\Admin\Role\Permission', 'menu_has_permissions', 'menu_id', 'permission_id')
                    ->withPivot('name', 'sequence');
    }

    public function roles() {
        return $this->belongsToMany('\App\Modules\Admin\Role\Model', 'menu_visibilities', 'menu_id', 'role_id');
    }

}
