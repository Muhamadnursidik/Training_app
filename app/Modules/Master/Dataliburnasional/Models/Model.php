<?php
namespace App\Modules\Master\Dataliburnasional;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends EloquentModel
{
    use SoftDeletes;

    // Nama tabel yang sesuai
    protected $table = 'dataliburnasional'; // sesuaikan dengan nama tabel kamu

    // Field yang bisa diisi mass assignment
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

    // Dalam model
protected static function boot()
{
    parent::boot();
    
    static::deleting(function ($model) {
        Log::info('Model deleting event triggered:', ['id' => $model->id]);
        // Jika return false, penghapusan akan dibatalkan
        return true;
    });
}
}