<?php
namespace App\Modules\Master\Dataliburnasional;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends EloquentModel
{
    use SoftDeletes;
    protected $date = ['tanggal'];
    protected $table = 'dataliburnasional';

    protected $fillable = [
        'tanggal',
        'keterangan'
    ];

    // Cast tipe data
    protected $casts = [
        'tanggal' => 'date'
    ];

    // Timestamps
    public $timestamps = true;

    // Soft delete field
    protected $dates = ['deleted_at'];

    // Scope untuk query data
    public function scopeData($query)
    {
        return $query->select('id', 'tanggal', 'keterangan', 'created_at', 'updated_at', 'deleted_at');
    }

    public function getTanggalAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('d-m-Y'): null;
    }
    
    protected static function boot()
    {
        parent::boot();
    }
}