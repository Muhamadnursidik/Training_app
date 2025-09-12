<?php
namespace App\Modules\Master\Rencanaproject\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends EloquentModel
{
    use SoftDeletes;
    
    protected $table = 'rencana_projects';
    
    protected $fillable = [
        'kode_project',
        'aktivitas', 
        'level',
        'parent_id',
        'bobot',
        'tanggal_mulai',
        'tanggal_akhir',
        'minggu_ke'
    ];

    protected $dates = [
        'tanggal_mulai',
        'tanggal_akhir',
        'deleted_at'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_akhir' => 'date',
        'level' => 'integer',
        'bobot' => 'decimal:2',
        'minggu_ke' => 'integer'
    ];

    // ✅ Tambahin scopeData biar kaya di Dataliburnasional
    public function scopeData($query)
    {
        return $query->select(
            'id',
            'kode_project',
            'aktivitas',
            'level',
            'parent_id',
            'bobot',
            'tanggal_mulai',
            'tanggal_akhir',
            'minggu_ke',
            'created_at',
            'updated_at',
            'deleted_at'
        );
    }

    public function parent()
    {
        return $this->belongsTo(Model::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Model::class, 'parent_id');
    }

    public function scopeByProject($query, $kodeProject)
    {
        return $query->where('kode_project', $kodeProject);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeByWeek($query, $minggu)
    {
        return $query->where('minggu_ke', $minggu);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_mulai', [$startDate, $endDate])
                    ->orWhereBetween('tanggal_akhir', [$startDate, $endDate]);
    }

    public function getNamaAktivitasAttribute()
    {
        $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $this->level - 1);
        return $indent . $this->aktivitas;
    }

    public function setTanggalMulaiAttribute($value)
    {
        $this->attributes['tanggal_mulai'] = $value;
        if ($value && empty($this->attributes['minggu_ke'])) {
            $this->attributes['minggu_ke'] = \Carbon\Carbon::parse($value)->weekOfYear;
        }
    }
}
