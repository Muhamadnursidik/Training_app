<?php
namespace App\Bases;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Uuid;

class BaseModel extends Model
{
    public $incrementing = false;

	public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})){
                $model->{$model->getKeyName()} = Uuid::generate()->string;
            }
        });
    }

    public function scopeTransaction($query, $callback)
    {
        DB::beginTransaction();

        $result = $callback();

        if ($result['code'] == 200)
        {
            DB::commit();
        }
        else
        {
            DB::rollback();
        }

        return $result;
    }

    public function scopeData($query, $key = NULL, $orderBy = NULL, $direction = 'asc', $offset = 0, $limit = 0)
    {
        if (is_array($key)) {
          $key_temp = $key;
          // usage ['column'=>['value','value']] convert to whereIn
          foreach($key_temp as $k => $v){
            if(is_array($v)){
              $query->whereIn($k,$v);
              unset($key[$k]);
            }
          }
          //end
          $query->where($key);
        }

        if (!empty($offset) || !empty($limit)) {
            $query->take($limit)->skip($offset);
        }

        if (!empty($orderBy)) {
            $query->orderBy($orderBy, $direction);
        }

        return $query;
    }

    public function scopeWhereLike($query, $name, $value, $status = 'both')
    {
        switch ($status) {
            case 'left':
                $value = $value . '%';
                break;
            case 'right':
                $value = '%' . $value;
                break;
            case 'both':
                $value = '%' . $value . '%';
                break;
        }

        $query->where($name, 'ilike', $value);
        return $query;
    }

    public function scopeCreateOne($query, array $data, $callback = NULL)
    {
        try {
            $event = $query->create($data);

            // if contain callback
            if (is_callable($callback)) {
                $callback($query, $event);
            }

            return [
                'code'    => 200,
                'status'  => 'success',
                'message' => __('Proses simpan berhasil.'),
                'data'    => [
                    '_id' => encrypt($event->id),
                ]
            ];
        } catch (Exception $e) {
            return [
                'code'    => 500,
                'status'  => 'error',
                'message' => __('Proses simpan gagal.'),
                'data'    => $e->getMessage()
            ];
        }
    }

    public function scopeUpdateOne($query, $id, array $data, $callback = NULL)
    {
        try {
            $cursor = $query->find($id);
            if ($cursor) {
                $event = $cursor->update($data);
            
                // if contain callback
                $return = ['_id' => encrypt($id)];

                if (is_callable($callback)) {
                    $return_callback = $callback($query, $event, $cursor);
                    if($return_callback){
                        $return = $return_callback;
                    }
                }

                return  [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => __('Proses edit berhasil.'),
                    'data'    => $return
                ];

            } else {
                return  [
                    'code'    => 400,
                    'status'  => 'error',
                    'message' => __('ID tidak ditemukan.')
                ];
            }
        } catch (Exception $e) {
            return [
                'code'    => 400,
                'status'  => 'error',
                'message' => __('Proses edit gagal.'),
                'data'    => $e->getMessage()
            ];
        }
    }

    public function scopeUpdateOrCreateOne($query, array $key,array $data, $callback = NULL)
    {
        try {
            $event = $query->updateOrCreate($key,$data);

            // if contain callback
            if (is_callable($callback)) {
                $callback($query, $event);
            }

            return [
                'code'    => 200,
                'status'  => 'success',
                'message' => __('Proses simpan berhasil.'),
                'data'    => [
                    '_id' => encrypt($event->id),
                ]
            ];
        } catch (Exception $e) {
            return [
                'code'    => 500,
                'status'  => 'error',
                'message' => __('Proses simpan gagal.'),
                'data'    => $e->getMessage()
            ];
        }
    }

    public function scopeDeleteOne($query, $id, $callback = NULL)
    {
        try {
            $cursor = $query->find($id);
            if ($cursor) {
                $event = $cursor->delete();

                // if contain callback
                if (is_callable($callback)) {
                    $callback($query, $event, $cursor);
                }

                return  [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => __('Proses hapus berhasil.'),
                    'data'    => [
                        '_id' => encrypt($id),
                    ]
                ];
            } else {
                return  [
                    'code'    => 400,
                    'status'  => 'error',
                    'message' => __('ID tidak ditemukan.')
                ];
            }
        } catch (Exception $e) {
            return [
                'code'    => 400,
                'status'  => 'error',
                'message' => __('Proses delele gagal.'),
                'data'    => $e->getMessage()
            ];
        }
    }

    public function scopeDeleteBatch($query, array $id, $callback = NULL, $column = 'id')
    {
        try {
            
            $cursors = $query->whereIn($column, $id)->get();
            if ($cursors) {
                $deleted_id = [];

                foreach ($cursors as $cursor) {
                    $deleted_id[] = encrypt($cursor->id);
                    $event = $cursor->delete();

                    // if contain callback
                    if (is_callable($callback)) {
                        $callback($query, $event, $cursor);
                    }

                }

                return  [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => __('Proses hapus berhasil.'),
                    'data'    => [
                        '_id' => encrypt($id),
                    ]
                ];
            } else {
                return  [
                    'code'    => 400,
                    'status'  => 'error',
                    'message' => __('ID tidak ditemukan.')
                ];
            }
        } catch (Exception $e) {
            return [
                'code'    => 400,
                'status'  => 'error',
                'message' => __('Proses delele gagal.'),
                'data'    => $e->getMessage()
            ];
        }
    }

    
    public function scopeRestoreData($model, $id, $column = 'id', $callback)
    {
        try {
            $model = $model->withTrashed()->where($column, $id)->first();

            if ($model && $model->trashed()) {

                $model->restore();

                if (is_callable($callback)) {
                    $callback($model);
                }

                return [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => __('Proses simpan berhasil.'),
                    'data'    => [
                        '_id' => encrypt($id),
                    ]
                ];
            }
        } catch (Exception $e) {
            return [
                'code'    => 400,
                'status'  => 'error',
                'message' => __('Proses delele gagal.'),
                'data'    => $e->getMessage()
            ];
        }
    }

    public function scopeIsActive($query)
    {
        $query->where('status', 1);
        return $query;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

}
