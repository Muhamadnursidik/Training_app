<?php
namespace App\Modules\Master\Dataliburnasional;

use App\Bases\BaseService;
use App\Modules\Master\Dataliburnasional\Model;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Service extends BaseService
{
    public function __construct()
    {
    }

    public function data(array $data)
    {
        $query = Model::withTrashed()->data();

        return DataTables::of($query)
            ->filter(function ($query) use ($data) {
                if (! empty($data['tanggal'])) {
                    $query->whereYear('tanggal', $data['tanggal']);
                }

                if (! empty($data['keterangan'])) {
                    $query->whereRaw('LOWER(keterangan) LIKE ?', ['%' . strtolower($data['keterangan']) . '%']);
                }
            })

            ->addColumn('id', function ($query) {
                return encrypt($query->id);
            })
            ->make(true)
            ->getData(true);
    }

    public function store(array $data)
    {
        try {
            // Validasi input
            if (empty($data['tanggal']) || empty($data['keterangan'])) {
                throw new \Exception("Tanggal jeung keterangan kudu diisi");
            }

            // Format tanggal dengan validation
            $tanggal = $this->formatDate($data['tanggal']);

            Log::info('Attempting to store data:', [
                'tanggal'    => $tanggal,
                'keterangan' => $data['keterangan'],
            ]);

            return DB::transaction(function () use ($tanggal, $data) {
                $result = Model::create([
                    'tanggal'    => $tanggal,
                    'keterangan' => trim($data['keterangan']),
                ]);

                Log::info('Data stored successfully:', ['id' => $result->id]);
                return $result;
            });

        } catch (\Exception $e) {
            Log::error('Error storing data: ' . $e->getMessage());
            throw $e;
        }
    }

    private function formatDate($dateInput)
    {
        try {
            // Coba beberapa format tanggal yang mungkin
            $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'Y/m/d'];

            foreach ($formats as $format) {
                $date = \DateTime::createFromFormat($format, $dateInput);
                if ($date !== false && $date->format($format) === $dateInput) {
                    return $date->format('Y-m-d');
                }
            }

            // Jika format di atas gagal, coba strtotime
            $timestamp = strtotime($dateInput);
            if ($timestamp !== false) {
                return date('Y-m-d', $timestamp);
            }

            throw new \Exception("Format tanggal tidak valid: {$dateInput}");

        } catch (\Exception $e) {
            throw new \Exception("Error formatting date: " . $e->getMessage());
        }
    }

    public static function get($id)
    {
        try {
            $query = Model::find($id);
            return $query ?: false;
        } catch (\Exception $e) {
            Log::error('Error getting data: ' . $e->getMessage());
            return false;
        }
    }

    public function update(array $data)
    {
        try {
            if (empty($data['id']) || empty($data['tanggal']) || empty($data['keterangan'])) {
                throw new \Exception("ID, tanggal jeung keterangan kudu diisi");
            }

            $tanggal = $this->formatDate($data['tanggal']);

            return DB::transaction(function () use ($data, $tanggal) {
                $model = Model::findOrFail(decrypt($data['id']));

                $result = $model->update([
                    'tanggal'    => $tanggal,
                    'keterangan' => trim($data['keterangan']),
                ]);

                Log::info('Data updated successfully:', ['id' => $model->id]);
                return $result;
            });

        } catch (\Exception $e) {
            Log::error('Error updating data: ' . $e->getMessage());
            throw $e;
        }
    }

    public function destroy(array $data)
    {
        $id    = is_numeric($data['id']) ? $data['id'] : decrypt($data['id']);
        $model = Model::find($id);

        if (! $model) {
            throw new \Exception("Data dengan ID {$id} tidak ditemukan");
        }

        $model->forceDelete();

        return $model;
    }

    // public function destroys(array $data)
    // {
    //     try {
    //         $ids = [];
    //         foreach ($data['id'] as $value) {
    //             $ids[] = decrypt($value);
    //         }

    //         return DB::transaction(function() use ($ids) {
    //             $result = Model::whereIn('id', $ids)->delete();
    //             Log::info('Batch delete completed:', ['count' => $result]);
    //             return $result;
    //         });
    //     } catch (\Exception $e) {
    //         Log::error('Error batch deleting data: ' . $e->getMessage());
    //         throw $e;
    //     }
    // }

    public function restore(array $data)
    {
        $id    = is_numeric($data['id']) ? $data['id'] : decrypt($data['id']);
        $model = Model::withTrashed()->find($id);

        if (! $model) {
            throw new \Exception("Data dengan ID {$id} tidak ditemukan");
        }

        $model->restore();

        return $model;
    }
}
