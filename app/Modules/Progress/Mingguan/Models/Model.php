<?php
namespace App\Modules\Progress\Mingguan\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Model extends EloquentModel
{
    use SoftDeletes;

    protected $table = 'realisasi_rencana_mingguan';

    protected $fillable = [
        'kode_project',
        'aktivitas',
        'minggu_ke',
        'realisasi',
        'tanggal_realisasi',
    ];

    protected $dates = [
        'tanggal_realisasi',
        'deleted_at',
    ];

    protected $casts = [
        'minggu_ke'        => 'integer',
        'realisasi'        => 'decimal:2',
        'tanggal_realisasi'=> 'date',
    ];

    public function scopeData($query)
    {
        return $query->select(
            'id',
            'kode_project',
            'aktivitas',
            'minggu_ke',
            'realisasi',
            'tanggal_realisasi',
            'created_at',
            'updated_at',
            'deleted_at'
        );
    }

    /**
     * Scope filter by project
     */
    public function scopeByProject($query, $kodeProject)
    {
        return $query->where('kode_project', $kodeProject);
    }

    /**
     * Scope filter by minggu
     */
    public function scopeByWeek($query, $minggu)
    {
        return $query->where('minggu_ke', $minggu);
    }

    /**
     * Scope filter by tanggal range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_realisasi', [$startDate, $endDate]);
    }

    /**
     * Accessor nama aktivitas + minggu ke
     */
    public function getLabelAktivitasAttribute()
    {
        return $this->aktivitas . ' (Minggu ke-' . $this->minggu_ke . ')';
    }

    /**
     * Mutator otomatis set minggu_ke dari tanggal_realisasi
     */
    public function setTanggalRealisasiAttribute($value)
    {
        $this->attributes['tanggal_realisasi'] = $value;
        if ($value && empty($this->attributes['minggu_ke'])) {
            $this->attributes['minggu_ke'] = Carbon::parse($value)->weekOfYear;
        }
    }
}
