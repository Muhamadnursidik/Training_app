<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Uuid;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'type', 'active_dashboard', 'status', 'unit_kerja_id', 'unit_kerja_code',
        'is_admin','is_reviewer','unit_kerja_code','username','password_created','reset_password','is_lock',
        'bagian_code', 'jabatan_code'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'attributes' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Uuid::generate()->string;
            }
        });
    }

    public function unitkerja(){
        return $this->hasOne('\App\Modules\Master\Unitkerja\Model', 'code', 'unit_kerja_code');
    }
}
