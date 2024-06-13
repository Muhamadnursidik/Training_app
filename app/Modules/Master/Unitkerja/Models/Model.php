<?php
namespace App\Modules\Master\Unitkerja;

use App\Bases\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class Model extends BaseModel
{
    use SoftDeletes;

    protected static $logFillable = true;

    protected $table = 'master_unit_kerja'; // Table name
    protected $keyType = 'string';
    protected $primaryKey = 'code';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'status', 'tipe_unit_kerja_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logFillable()->useLogname('Master Unit Kerja');
    }

    public function risklog(){
        return $this->hasMany('App\Modules\Penilaian\Led\Inputkejadian\Model','unit_kerja_code','code');
    }

    public function tipeunitkerja(){
        return $this->hasOne('\App\Modules\Master\Tipeunitkerja\Model','id','tipe_unit_kerja_id');
    }

    public function katalogrisiko(){
       return $this->belongsToMany('\App\Modules\Master\Katalogrisiko\Model', 'mapping_katalog_risiko_role_unit_kerja',
       'unit_kerja_code', 'katalog_risiko_id');
    }

    public function reviewer(){
       return $this->belongsToMany('\App\Modules\Admin\Role\Model', 'mapping_reviewer',
       'unit_kerja_code', 'role_id')->withPivot('urutan','id','operator','module', 'bagian_code');
    }

    public function konsolidasiunit(){
       return $this->belongsToMany('\App\Modules\Master\Unitkerja\Model', 'mapping_konsolidasi_unit_kerja',
       'parent_unit_kerja_code', 'unit_kerja_code');
    }

    public function konfirmasikejadian(){
        return $this->hasMany('\App\Modules\Penilaian\Led\Konfirmasikejadian\Model','unit_kerja_code','code');
    }
    
    public function dataKuantitatif(){
        return $this->belongsToMany('App\Modules\Mapping\Datakuantitatif\Model', 'mapping_data_kuantitatif_role_unit_kerja', 'unit_kerja_code', 
        'data_kuantitatif_id')->withPivot('role_id','bagian_code');
    }
    
    public function monitorceklisisu(){
        return $this->hasMany('\App\Modules\Penilaian\Led\Monitorceklistisu\Model', 'unit_kerja_code','code');
    }
}
