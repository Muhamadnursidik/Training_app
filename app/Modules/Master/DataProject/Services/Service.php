<?php
namespace App\Modules\Master\DataProject\Services;

use App\Bases\BaseService;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\Master\DataProject\Models\DataProject;


class Service extends BaseService
{
    public function __construct()
    {
    }

    public function data(array $data)
    {
        $query = DataProject::with('mitra');

        return DataTables::of($query)
            ->filter(function ($query) use ($data) {
                if (!empty($data['nama_project'])) {
                    $query->where('nama_project', 'ILIKE', '%' . $data['nama_project'] . '%');
                }

                if (!empty($data['kode_project'])) {
                    $query->where('kode_project', 'ILIKE', '%' . $data['kode_project'] . '%');
                }

                if (!empty($data['kode_mitra'])) {
                    $query->whereHas('mitra', function($q) use ($data) {
                        $q->where('kode_mitra', 'ILIKE', '%' . $data['kode_mitra'] . '%');
                    });
                }

                if (!empty($data['tanggal_mulai'])) {
                    $query->whereDate('tanggal_mulai', '>=', $data['tanggal_mulai']);
                }

                if (!empty($data['tanggal_akhir'])) {
                    $query->whereDate('tanggal_selesai', '<=', $data['tanggal_akhir']);
                }
            })
            ->addColumn('id', function ($query) {
                return encrypt($query->id);
            })
            ->addColumn('kode_mitra_display', function ($query) {
                return $query->mitra ? $query->mitra->kode_mitra : '-';
            })
            ->addColumn('tanggal_mulai', function ($query) {
                return $query->tanggal_mulai ? $query->tanggal_mulai->format('d/m/Y') : '-';
            })
            ->addColumn('tanggal_akhir', function ($query) {
                return $query->tanggal_selesai ? $query->tanggal_selesai->format('d/m/Y') : '-';
            })
            ->make(true)
            ->getData(true);
    }

    public function store(array $data)
    {
        return DataProject::transaction(function () use ($data) {
            return DataProject::createOne([
                'kode_project'    => $data['kode_project'],
                'nama_project'    => $data['nama_project'],
                'mitra_id'        => $data['mitra_id'],
                'tanggal_mulai'   => $data['tanggal_mulai'],
                'tanggal_selesai' => $data['tanggal_akhir'], // Form menggunakan tanggal_akhir, tapi model menggunakan tanggal_selesai
            ]);
        });
    }

    public static function get($id)
    {
        if ($id) {
            return DataProject::find($id);
        }
        return false;
    }

    public function update(array $data)
    {
        return DataProject::transaction(function () use ($data) {
            return DataProject::updateOne($data['id'], [
                'kode_project'    => $data['kode_project'],
                'nama_project'    => $data['nama_project'],
                'deskripsi'       => $data['deskripsi'] ?? null,
                'mitra_id'        => $data['mitra_id'],
                'tanggal_mulai'   => $data['tanggal_mulai'],
                'tanggal_selesai' => $data['tanggal_akhir'],
                'status'          => $data['status'] ?? 1,
            ]);
        });
    }

    public function destroy(array $data)
    {
        return DataProject::deleteOne(
            $data['id'],
            function ($query, $event, $cursor) {
                $cursor->update(['status' => false]);
            }
        );
    }

    public function destroys(array $data)
    {
        $id = [];
        foreach ($data['id'] as $value) {
            $id[] = decrypt($value);
        }

        return DataProject::transaction(function () use ($id) {
            return DataProject::deleteBatch(
                $id,
                function ($query, $event, $cursor) {
                    $cursor->update(['status' => false]);
                }
            );
        });
    }

    public function restore(array $data)
    {
        return DataProject::transaction(function () use ($data) {
            return DataProject::restoreData($data['id'], 'id', function ($query) {
                $query->update(['status' => true]);
            });
        });
    }
}
